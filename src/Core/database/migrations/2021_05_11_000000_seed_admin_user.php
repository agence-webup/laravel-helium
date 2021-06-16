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
            'name' => 'admin_users.create',
            'guard_name' => 'admin',
            'title' => 'Créer des administrateurs'
        ]);
        Permission::create([
            'name' => 'admin_users.update',
            'guard_name' => 'admin',
            'title' => 'Mettre à jour les administrateurs'
        ]);
        Permission::create([
            'name' => 'admin_users.delete',
            'guard_name' => 'admin',
            'title' => 'Supprimer les administrateurs'
        ]);

        Permission::create([
            'name' => 'roles.read',
            'guard_name' => 'admin',
            'title' => 'Voir les rôles'
        ]);
        Permission::create([
            'name' => 'roles.create',
            'guard_name' => 'admin',
            'title' => 'Créer des rôles'
        ]);
        Permission::create([
            'name' => 'roles.update',
            'guard_name' => 'admin',
            'title' => 'Mettre à jour les rôles'
        ]);
        Permission::create([
            'name' => 'roles.delete',
            'guard_name' => 'admin',
            'title' => 'Supprimer les rôles'
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
