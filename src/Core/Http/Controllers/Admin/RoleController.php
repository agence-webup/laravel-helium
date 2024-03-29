<?php

namespace Webup\LaravelHelium\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webup\LaravelHelium\Core\Facades\HeliumFlash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Arr;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        return view('helium::role.index');
    }

    public function datatable(Request $request)
    {
        $roles = Role::select("id", "name");

        return DataTables::of($roles)
            ->rawColumns(['actions'])
            ->addColumn('actions', function ($role) {
                return view('helium::role.datatable-actions', ['role' => $role])->render();
            })
            ->make(true);
    }

    public function create(Request $request)
    {
        $permissions = Permission::where('guard_name', 'admin')->pluck('title', 'name')->all();

        return view('helium::role.create', [
            'role' => new Role(),
            'rolePermissions' => "",
            'permissions' => $permissions,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = new Role();
        $role->name = $data['name'];
        $role->guard_name = 'admin';
        $role->save();

        $role->syncPermissions(Arr::get($data, 'permissions', []));

        HeliumFlash::success("Le rôle a été modifié avec succès.");

        return redirect()->route(helium_route_name('role.index'));
    }

    public function edit(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = $role->getPermissionNames()->join(',');
        $permissions = Permission::where('guard_name', 'admin')->pluck('title', 'name')->all();

        return view('helium::role.edit', [
            'role' => $role,
            'rolePermissions' => $rolePermissions,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $data = $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $id,
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->name = $data['name'];
        $role->save();

        $role->syncPermissions(Arr::get($data, 'permissions', []));


        HeliumFlash::success("Le rôle a été modifié avec succès.");

        return redirect()->route(helium_route_name('role.index'));
    }

    public function destroy(Request $request, $id)
    {
        Role::destroy($id);

        HeliumFlash::success("Le rôle a été supprimé avec succès.");

        return redirect()->route(helium_route_name('role.index'));
    }
}
