<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Edition;
use App\Voter;
use App\Result;

class CacheResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'results:cache {--edition=} {--no-save}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate and cache results';

    /**
     * The edition
     *
     * @var object
     */
    protected $edition;

    /**
     * Errors
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Integrity
     *
     * @var boolean
     */
    protected $integrity = true;

    /**
     * Result tabulation
     *
     * @var array
     */
    protected $tab = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $editionId = $this->option('edition');
        $dontSave = $this->option('no-save');
        $this->edition = ($editionId) ? Edition::where('id', $editionId)->first() : Edition::current();

        if (!$this->edition) {
            $this->error('No active edition was found');
            return;
        }

        $ballots = $this->edition->ballots()->get();
        $this->validate($ballots);

        $this->line('');
        $this->line('');

        if (!$dontSave) $this->save();

        if (!$this->errors) {
            $this->info('Ballot check finished without errors.');
            if (!$dontSave) $this->line('Results cached successfully.');
        } else {
            $this->error('Ballot check finisheded with errors. The following ballots are invalid!');

            $this->table(['Cast at', 'Ballot ref.'], $this->errors);
        }

        if (!$dontSave){
            Cache::forever('last_tally_finished' . $this->edition->id, time());
            Cache::forever('last_tally_integrity' . $this->edition->id, $this->integrity);

            $publishDate = strtotime($this->edition->publish_results);
            if (time() > $publishDate) {
                Cache::forever('final_tally_finished_' . $this->edition->id, 'true');
            }
        }
    }

    /**
     * Validate ballots
     *
     * @return mixed
     */
    private function validate($ballots)
    {
        $validBallots = 0;
        $bar = $this->output->createProgressBar(count($ballots));
        foreach ($ballots as $ballot) {
            if (!$ballot->check() || !$decodedBallot = $ballot->decrypt()) {
                $this->errors[] = [$ballot->cast_at, $ballot->ref];
                continue;
            }

            foreach ($decodedBallot as $question => $options) {
                foreach ($options as $option => $points) {
                    $this->tab[$question][$option] = (isset($this->tab[$question][$option])) ? $this->tab[$question][$option] + $points : $points;
                }
            }

            $validBallots++;
            $bar->advance();
        }

        $bar->finish();

        /* Check totals against voter marked list */
        $voters = Voter::where('edition_id', $this->edition->id)->where('ballot_cast', 1)->count();

        $this->line('');
        $this->line('');
        $this->info('----------------------------');
        $this->info('Turnout: ' . $voters);
        $this->info('Ballots: ' . $validBallots);
        $this->info('----------------------------');

        if ($validBallots !== $voters) {
            $this->integrity = false;
            $this->error('@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@');
            $this->error('@@         Result integrity check failed        @@');
            $this->error('@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@');
        }
    }

    /**
     * Save results to database
     *
     * @return mixed
     */
    private function save()
    {
        foreach ($this->tab as $question => $options) {
            foreach ($options as $option => $votes) {
                Result::updateOrCreate(
                    ['edition_id' => $this->edition->id, 'question_id' => $question, 'option_id' => $option],
                    ['points' => number_format($votes, 3)]
                );
            }
        }
    }
}
