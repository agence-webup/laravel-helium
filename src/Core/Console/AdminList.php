<?php

namespace Webup\LaravelHelium\Core\Console;

use Illuminate\Console\Command;
use Webup\LaravelHelium\Core\Entities\AdminUser;

class AdminList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all admin users';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment("List all admin users");

        $admins = AdminUser::all();

        $headers = ['id', 'email'];
        $rows = [];
        foreach ($admins as $admin) {
            $rows[] = [
                $admin->id,
                $admin->email,
            ];
        }

        $this->table($headers, $rows);
    }
}
