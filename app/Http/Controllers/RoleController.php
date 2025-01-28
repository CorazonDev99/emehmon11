<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $roles = DB::table('roles')->orderBy('id', 'ASC')->get();
        return view('core.roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $modules = DB::table('tb_module')->where('activate', 1)->get();
        $permissions = DB::table('permissions')->get();
        return view('core.roles.create', compact('modules', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $isAdmin = $request->has('is_admin') ? true : false;
        $roleId = DB::table('roles')->insertGetId([
            'name' => $request->input('name'),
            'is_admin'=> $isAdmin,
            'guard_name' => 'web',
            'created_at' => now()
        ]);

        foreach ($request->input('permission') as $permissionId) {
            $existing = DB::table('role_has_permissions')
                ->where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->exists();

            if (!$existing) {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }


        $permissionsData = $request->input('permissions');

        if (!is_array($permissionsData)) {
            $permissionsData = [];
        }

        if (empty($permissionsData)) {
            DB::table('cms_privileges_roles')->insert([
                'id_cms_privileges' => $roleId,
                'id_cms_moduls' => null,
                'is_visible' => 0,
                'is_create' => 0,
                'is_read' => 0,
                'is_edit' => 0,
                'is_delete' => 0,
                'created_at' => now(),
            ]);
        } else {
            foreach ($permissionsData as $moduleId => $permissions) {
                DB::table('cms_privileges_roles')->insert([
                    'id_cms_privileges' => $roleId,
                    'id_cms_moduls' => $moduleId,
                    'is_visible' => isset($permissions['view']) ? 1 : 0,
                    'is_create' => isset($permissions['create']) ? 1 : 0,
                    'is_read' => isset($permissions['read']) ? 1 : 0,
                    'is_edit' => isset($permissions['edit']) ? 1 : 0,
                    'is_delete' => isset($permissions['delete']) ? 1 : 0,
                    'created_at' => now(),
                ]);
            }
        }


        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }


    public function show($id)
    {

        $role = DB::table('roles')->where('id', $id)->first();
        $allPermissions = DB::table('permissions')->get();
        $rolePermissions = DB::table('permissions')
            ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->where('role_has_permissions.role_id', $id)
            ->get();

        $modules = DB::table('tb_module')
            ->leftJoin('cms_privileges_roles', 'tb_module.module_id',  '=', 'cms_privileges_roles.id_cms_moduls')
            ->select('tb_module.module_id', 'tb_module.module_title', 'cms_privileges_roles.*')
            ->where('cms_privileges_roles.id_cms_privileges', $id)->get();


        return view('core.roles.show', compact('role', 'rolePermissions', 'modules', 'allPermissions'));
    }

    public function edit($id)
    {
        $role = DB::table('roles')->where('id', $id)->first();
        if (!$role) {
            abort(404, 'Role not found');
        }
        $permissions = DB::table('permissions')->get();
        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $id)
            ->pluck('permission_id')
            ->toArray();

        $allmodules = DB::table('tb_module')->where('activate', 1)->get();


        $modules = DB::table('tb_module')
            ->join('cms_privileges_roles', 'tb_module.module_id',  '=', 'cms_privileges_roles.id_cms_moduls')
            ->select('tb_module.module_id', 'tb_module.module_title', 'cms_privileges_roles.*')
            ->where('cms_privileges_roles.id_cms_privileges', $id)->get();
        $modules = $modules->keyBy('module_id');

        return view('core.roles.edit', compact('role', 'permissions', 'rolePermissions', 'modules', 'allmodules'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'permission' => 'required|array',
        ]);

        $isAdmin = $request->has('is_admin') ? true : false;

        DB::transaction(function () use ($request, $id, $isAdmin) {
            $role = DB::table('roles')->where('id', $id)->first();
            if (!$role) {
                abort(404, 'Role not found');
            }

            DB::table('roles')->where('id', $id)->update([
                'name' => $request->input('name'),
                'is_admin' => $isAdmin,
                'guard_name' => 'web',
            ]);

            DB::table('role_has_permissions')->where('role_id', $id)->delete();
            foreach ($request->input('permission') as $permissionId) {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $id,
                    'permission_id' => $permissionId,
                ]);
            }

            DB::table('cms_privileges_roles')
                ->where('id_cms_privileges', $id)
                ->update([
                    'is_visible' => 0,
                    'is_create' => 0,
                    'is_read' => 0,
                    'is_edit' => 0,
                    'is_delete' => 0,
                ]);

            $permissionsData = $request->input('permissions', []);
            foreach ($permissionsData as $moduleId => $permissions) {
                $existing = DB::table('cms_privileges_roles')
                    ->where('id_cms_privileges', $id)
                    ->where('id_cms_moduls', $moduleId)
                    ->first();

                if ($existing) {
                    DB::table('cms_privileges_roles')
                        ->where('id_cms_privileges', $id)
                        ->where('id_cms_moduls', $moduleId)
                        ->update([
                            'is_visible' => isset($permissions['view']) ? 1 : 0,
                            'is_create' => isset($permissions['create']) ? 1 : 0,
                            'is_read' => isset($permissions['read']) ? 1 : 0,
                            'is_edit' => isset($permissions['edit']) ? 1 : 0,
                            'is_delete' => isset($permissions['delete']) ? 1 : 0,
                        ]);
                } else {
                    DB::table('cms_privileges_roles')->insert([
                        'id_cms_privileges' => $id,
                        'id_cms_moduls' => $moduleId,
                        'is_visible' => isset($permissions['view']) ? 1 : 0,
                        'is_create' => isset($permissions['create']) ? 1 : 0,
                        'is_read' => isset($permissions['read']) ? 1 : 0,
                        'is_edit' => isset($permissions['edit']) ? 1 : 0,
                        'is_delete' => isset($permissions['delete']) ? 1 : 0,
                    ]);
                }
            }
        });

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }


    public function destroy($id)
    {
        DB::table('roles')->where('id', $id)->delete();
        DB::table('role_has_permissions')->where('role_id', $id)->delete();
        DB::table('model_has_roles')->where('role_id', $id)->delete();
        DB::table('cms_privileges_roles')->where('id_cms_privileges', $id)->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
