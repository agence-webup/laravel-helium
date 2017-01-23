<?php

namespace Webup\LaravelHelium\Core\Console;

use Illuminate\Console\Command;
use Webup\LaravelHelium\Core\Entities\AdminUser;

class AdminUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:update {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update an admin user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment("Update an admin user");

        $admin = AdminUser::find($this->argument('id'));
        if (!$admin) {
            $this->comment("Admin user not found.");
            return;
        }

        $admin->email = $this->ask('email', $admin->email);
        $password = $this->ask('password', false);
        if ($password) {
            $admin->password = bcrypt($password);
        }

        $admin->save();

        $this->comment("Admin user was updated");
    }
}
