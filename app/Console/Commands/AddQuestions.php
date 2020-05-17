<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Question;
use App\Edition;

class AddQuestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edition:questions {--edition=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a question to an edition';

    /**
     * The edition
     *
     * @var object
     */
    protected $edition;

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
        $this->setEdition();

        $question = new Question();

        $question->edition_id = $this->edition->id;
        $question->question = $this->ask('Question');
        $question->description = '';
        $question->template = $this->choice('Template', ['2column', 'Simple'], 0);
        $question->min_options = $this->anticipate('Minimum options to select', ['0','1']);
        $question->max_options = $this->anticipate('Maxiumum options to select', range(0, 10));
        $displayCost = $this->choice('Display cost of each option?', ['Yes', 'No'], 0);
        $question->display_cost = ($displayCost == 'Yes') ? 1 : 0;
        $randomOrder = $this->choice('Randomize option order on ballot?', ['Yes', 'No'], 0);
        $question->random_order = ($randomOrder == 'Yes') ? 1 : 0;
        $question->results_to_highlight = $this->anticipate('Top options to highlight on results page', range(1, 4));
        $question->save();

        $this->createOptions($question->id);

        $addQuestion = $this->choice('Add another question?', ['Yes', 'No'], 0);
        if ($addQuestion == 'Yes') $this->handle();
    }

    private function setEdition()
    {
        $editionId = $this->option('edition');
        $this->edition = ($editionId) ? Edition::where('id', $editionId)->first() : Edition::current();

        if (!$this->edition) {
            $this->error('Edition not found.');

            $proceed = $this->choice('Create new one?', ['Yes', 'No'], 0);

            if ($proceed == 'Yes') {
                $this->call('edition:new');
                $this->handle();
            } else {
                return;
            }
        }

        $this->info('Adding question to ' . $this->edition->name);
    }

    private function createOptions($questionId)
    {
        $this->call('edition:options', ['--question' => $questionId]);
    }
}
