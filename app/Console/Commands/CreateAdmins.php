<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class CreateAdmins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admins:create {users*} {--superadmin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin users';

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
        $users = $this->argument('users');
        $superadmin = $this->option('superadmin');
        $table = [];

        foreach ($users as $user){

            // Check if user exists
            if (User::where('username', $user)->count()) {
                $this->error('User \'' . $user . '\' could not be created: Username duplicate.');
                continue;
            }

            $randomPassword = str_random(12);
            $isSuperadmin = ($superadmin) ? 1 : 0;
            $table[] = [$user, $randomPassword, $isSuperadmin];

            User::create([
                'name' => $user,
                'username' => $user,
                'password' => bcrypt($randomPassword),
                'is_superadmin' => $isSuperadmin
            ]);
        }

        $this->line('The following user(s) were created:');

        $this->table(['Username', 'Password', 'Superadmin'], $table);

    }
}
