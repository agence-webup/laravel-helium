<?php

namespace Webup\LaravelHelium\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Http\Request;
use Webup\LaravelHelium\Core\Entities\AdminUser;
use Webup\LaravelHelium\Core\Facades\HeliumFlash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        abort_if($request->user('admin')->cannot('admin_users.read'), 403);

        return view('helium::admin_user.index');
    }

    public function datatable(Request $request)
    {
        abort_if($request->user('admin')->cannot('admin_users.read'), 403);

        $admins = AdminUser::select("id", "email");

        return Datatables::of($admins)
            ->rawColumns(['actions'])
            ->addColumn('actions', function ($admin) {
                return view('helium::admin_user.datatable-actions', ['admin' => $admin])->render();
            })
            ->make(true);
    }

    public function create(Request $request)
    {
        abort_if($request->user('admin')->cannot('admin_users.write'), 403);

        $roles = Role::where('guard_name', 'admin')->pluck('name', 'name')->all();

        return view('helium::admin_user.create', [
            'admin' => new AdminUser(),
            'adminRoles' => "",
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        abort_if($request->user('admin')->cannot('admin_users.write'), 403);

        $data = $this->validate($request, [
            'email' => 'required|unique:admin_users,email',
            'password' => 'required|confirmed',
            'roles.*' => 'exists:roles,name',
        ]);

        $admin = new AdminUser();
        $admin->email = $data['email'];
        $admin->password = bcrypt($data['password']);
        $admin->save();

        $admin->syncRoles(Arr::get($data, 'roles', []));

        HeliumFlash::success("L'admin a été modifié avec succès.");

        return redirect()->route('admin.admin_user.index');
    }

    public function edit(Request $request, $id)
    {
        abort_if($request->user('admin')->cannot('admin_users.write'), 403);

        $admin = AdminUser::findOrFail($id);
        $adminRoles = $admin->getRoleNames()->join(',');
        $roles = Role::where('guard_name', 'admin')->pluck('name', 'name')->all();

        return view('helium::admin_user.edit', [
            'admin' => $admin,
            'adminRoles' => $adminRoles,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, $id)
    {
        abort_if($request->user('admin')->cannot('admin_users.write'), 403);

        $admin = AdminUser::findOrFail($id);

        $data = $this->validate($request, [
            'email' => 'required|unique:admin_users,email,' . $id,
            'password' => 'sometimes|confirmed',
            'roles.*' => 'exists:roles,name',
        ]);

        $admin->email = $data['email'];
        if ($data['password']) {
            $admin->password = bcrypt($data['password']);
        }
        $admin->save();

        $admin->syncRoles(Arr::get($data, 'roles', []));


        HeliumFlash::success("L'admin a été modifié avec succès.");

        return redirect()->route('admin.admin_user.index');
    }

    public function destroy(Request $request, $id)
    {
        abort_if($request->user('admin')->cannot('admin_users.write'), 403);

        AdminUser::destroy($id);

        HeliumFlash::success("L'admin a été supprimé avec succès.");

        return redirect()->route('admin.admin_user.index');
    }
}
