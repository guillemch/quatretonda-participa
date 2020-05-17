<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Edition;

class PublishEdition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'edition:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish an edition';

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
        $edition = Edition::where('published', 0)->first();

        if (!$edition) {
            $this->error('No unpublished editions were found');
            return;
        }

        $edition->published = 1;
        $edition->save();

        $this->line($edition->name . ' has been made public.');
    }
}
