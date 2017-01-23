<?php

namespace Webup\LaravelHelium\Core\Console;

use Illuminate\Console\Command;
use Webup\LaravelHelium\Core\Entities\AdminUser;

class AdminDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:delete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an admin user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment("Delete an admin user.");

        if ($this->confirm('Are you sure to delete this user?')) {
            $admin = AdminUser::find($this->argument('id'));
            if (!$admin) {
                $this->comment("Admin user not found.");
                return;
            }
            $admin->delete();
            $this->comment("Admin user was deleted.");
        }
    }
}
