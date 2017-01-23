<?php

namespace Webup\LaravelHelium\Core\Console;

use Illuminate\Console\Command;
use Webup\LaravelHelium\Core\Entities\AdminUser;

class AdminCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment("Create an admin user");

        $email = $this->ask('email');
        $password = $this->ask('password');

        $admin = new AdminUser();
        $admin->email = $email;
        $admin->password = bcrypt($password);
        $admin->save();

        $this->comment("Admin user was created");
    }
}
