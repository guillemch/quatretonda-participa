<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Edition;
use Carbon\Carbon;

class NewEdition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edition:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $edition = new Edition();

        $edition->name = $this->ask('Name');
        $edition->description = $this->ask('Description');

        $suggestedStartDate = Carbon::today()->addWeeks(1);
        $startDay = $this->anticipate('Start date (YYYY-MM-DD 00:00)', [$suggestedStartDate]);
        $startDay = Carbon::parse($startDay);

        $suggestedEndDateOneWeeks = $startDay->addWeeks(1);
        $suggestedEndDateTwoWeeks = $startDay->addWeeks(1);
        $endDay = $this->anticipate('End date', [$suggestedEndDateOneWeeks, $suggestedEndDateTwoWeeks]);
        $endDay = Carbon::parse($endDay);

        $suggestedResultsDayImmidate = $endDay; // Publish immediately
        $suggestedResultsDayNextDay = $endDay->addDays(1);
        $resultsDay = $this->anticipate('Publish results', ['Immediately', $suggestedResultsDayNextDay]);
        $resultsDay = ($resultsDay == 'Immediately') ? $endDay : $resultsDay;
        $resultsDay = Carbon::parse($resultsDay);

        $edition->start_date = $startDay;
        $edition->end_date = $endDay;
        $edition->publish_results = $resultsDay;

        $publish = $this->choice('Publish now?', ['Yes', 'No'], 0);
        $edition->published = ($publish === 'Yes') ? 1 : 0;

        $edition->save();
        $this->line('Edition created successfully.');

        $addQuestions = $this->choice('Add questions and options?', ['Yes', 'No'], 0);

        if ($addQuestions == 'Yes') {
            $this->call('edition:questions', ['--edition' => $edition->id]);
        }
    }
}
