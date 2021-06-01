<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Webup\LaravelHelium\Core\Entities\AdminUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeedAdminUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::create([
            'name' => 'admin_users.read',
            'guard_name' => 'admin',
            'title' => 'Voir les administrateurs'
        ]);
        Permission::create([
            'name' => 'admin_users.write',
            'guard_name' => 'admin',
            'title' => 'Editer les administrateurs'
        ]);
        Permission::create([
            'name' => 'roles.read',
            'guard_name' => 'admin',
            'title' => 'Voirs les rôles'
        ]);
        Permission::create([
            'name' => 'roles.write',
            'guard_name' => 'admin',
            'title' => 'Editer les rôles'
        ]);

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if (!$superAdminRole) {
            $superAdminRole = Role::create(['guard_name' => 'admin', 'name' => 'Super Admin']);
        }

        $admin = AdminUser::where('email', 'admin')->first();
        
        if (!$admin) {
            $admin = new AdminUser();
            $admin->email = 'admin';
            $admin->password = bcrypt('changeme');
            $admin->save();
        }

        $admin->assignRole($superAdminRole);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
