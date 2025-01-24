<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{

    public function index()
    {
        $permissions = DB::table('permissions')->get();

        return view('core.permissions.index', [
            'permissions' => $permissions
        ]);
    }


    public function create()
    {
        return view('core.permissions.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        DB::table('permissions')->insert([
            'name' => $request->input('name'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('permissions.index')
            ->withSuccess(__('Permission created successfully.'));
    }


    public function edit($id)
    {
        $permission = DB::table('permissions')->find($id);

        return view('core.permissions.edit', [
            'permission' => $permission
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$id
        ]);

        DB::table('permissions')->where('id', $id)->update([
            'name' => $request->input('name'),
            'updated_at' => now(),
        ]);

        return redirect()->route('permissions.index')
            ->withSuccess(__('Permission updated successfully.'));
    }


    public function destroy($id)
    {
        DB::table('permissions')->where('id', $id)->delete();
        DB::table('role_has_permissions')->where('permission_id', $id)->delete();
        DB::table('model_has_permissions')->where('permission_id', $id)->delete();

        return redirect()->route('permissions.index')
            ->withSuccess(__('Permission deleted successfully.'));
    }
}
