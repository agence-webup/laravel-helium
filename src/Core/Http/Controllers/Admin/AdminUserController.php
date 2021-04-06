<?php

namespace Webup\LaravelHelium\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Http\Request;
use Webup\LaravelHelium\Core\Entities\AdminUser;
use Webup\LaravelHelium\Core\Facades\HeliumFlash;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('helium::admin_user.index');
    }

    public function datatable(Request $request)
    {
        $admins = AdminUser::select("id", "email");

        return Datatables::of($admins)
            ->rawColumns(['actions'])
            ->addColumn('actions', function ($admin) {
                return view('helium::admin_user.datatable-actions', ['admin' => $admin])->render();
            })
            ->make(true);
    }

    public function create()
    {
        return view('helium::admin_user.create', [
            'admin' => new AdminUser(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'email' => 'required|unique:admin_users,email',
            'password' => 'required|confirmed',
        ]);

        $admin = new AdminUser();
        $admin->email = $data['email'];
        $admin->password = bcrypt($data['password']);
        $admin->save();

        HeliumFlash::success("L'admin a été modifié avec succès.");

        return redirect()->route('admin.admin_user.index');
    }

    public function edit(Request $request, $id)
    {
        $admin = AdminUser::findOrFail($id);

        return view('helium::admin_user.edit', [
            'admin' => $admin,
        ]);
    }

    public function update(Request $request, $id)
    {
        $admin = AdminUser::findOrFail($id);

        $data = $this->validate($request, [
            'email' => 'required|unique:admin_users,email,' . $id,
            'password' => 'sometimes|confirmed',
        ]);

        $admin->email = $data['email'];
        if ($data['password']) {
            $admin->password = bcrypt($data['password']);
        }
        $admin->save();

        HeliumFlash::success("L'admin a été modifié avec succès.");

        return redirect()->route('admin.admin_user.index');
    }

    public function destroy(Request $request, $id)
    {
        AdminUser::destroy($id);

        HeliumFlash::success("L'admin a été supprimé avec succès.");

        return redirect()->route('admin.admin_user.index');
    }
}
