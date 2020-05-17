<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Edition;
use App\Voter;

class ImportCensus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'census:import {file=import.csv} {--edition=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load census from file onto database';

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
        $edition = ($editionId) ? Edition::where('id', $editionId)->first() : Edition::current();

        if (!$edition) {
            $this->error('You must first create an edition.');

            $proceed = $this->choice('Create new one?', ['Yes', 'No'], 0);

            if ($proceed == 'Yes') {
                $this->call('edition:new');
                $this->handle();
            } else {
                return;
            }
        }

        $this->info('Importing census onto edition ' . $edition->name . ' ...');

        $filename = $this->argument('file');
        $contents = Storage::get('census/' . $filename);

        $lines = explode("\n", $contents);

        foreach ($lines as $SID) {
            if (empty($SID)) continue;
            $voter = new Voter;
            $voter->SID = $this->cleanSID($SID);
            $voter->edition_id = $edition->id;
            $voter->save();
        }

        $this->line('Census imported successfully.');
    }

    private function cleanSID($SID)
    {
        $SID = trim($SID);
        $SID = str_replace(" ", "", $SID);
        $SID = str_replace(".", "", $SID);
        $SID = str_replace("-", "", $SID);

        return $SID;
    }
}
