<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Option;

class AddOptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edition:options {--question=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add options to a question';

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
        $questionId = $this->option('question');

        if (!$questionId) {
            $createQuestion = $this->choice('The question ID was not specified. Create one?', ['Yes', 'No'], 0);

            if ($createQuestion == 'Yes') {
                $this->call('edition:questions');
                $this->handle();
            } else {
                return;
            }
        }

        $option = new Option();

        $option->question_id = $questionId;
        $option->option = $this->ask('Option');
        $option->salt = Str::random(12);
        $option->save();

        $addOption = $this->choice('Add another option?', ['Yes', 'No'], 0);
        if ($addOption === 'Yes') $this->handle();
    }
}
