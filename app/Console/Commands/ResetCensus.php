<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Edition;

class ResetCensus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'census:reset {--edition=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset census voting information after testing';

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

        $this->info('Reseting census on edition ' . $edition->name . ' ...');

        foreach ($edition->voters()->get() as $voter) {
            $voter->SMS_phone = '';
            $voter->SMS_token = '';
            $voter->SMS_attempts = 0;
            $voter->SMS_verified = 0;
            $voter->SMS_time = null;
            $voter->ballot_cast = 0;
            $voter->ballot_time = null;
            $voter->signature = '';
            $voter->by_user_id = null;
            $voter->ip_address = '';
            $voter->user_agent = '';

            $voter->save();
        }

        $this->info('Census reset successfully.');
    }
}
