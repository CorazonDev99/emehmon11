<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;


class UserController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $userRole = $user->roles->first();
        $roles = DB::table('roles')
            ->where('level', '>', $userRole->level)
            ->orderBy('id')
            ->get();

        $rolesEdit = DB::table('roles')
            ->where('level', '>=', $userRole->level)
            ->orderBy('id')
            ->get();

        if ($user->roles->first()->is_admin == 1) {
            $permissions = DB::table('permissions')->get();
        } else {
            $permissions = [];
        }

        $userPermissions = DB::table('permissions')
            ->join('model_has_permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->select('permissions.*', 'model_has_permissions.model_id')
            ->get();

        $hotels = DB::table('tb_hotels')->select(['id', 'name', 'id_region'])->get();
        $regions = DB::table('tb_region')->select(['id', 'name'])->get();

        return view('core.users.index', compact('roles', 'permissions', 'hotels', 'regions', 'userPermissions', 'rolesEdit'));
    }


    public function getData(Request $request)
    {
        $query = DB::table('tb_users')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'tb_users.id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftJoin('tb_hotels', 'tb_hotels.id', '=', 'tb_users.id_hotel')
            ->leftJoin('tb_region', 'tb_hotels.id_region', '=', 'tb_region.id')
            ->select([
                'tb_users.id as user_id',
                'roles.id AS role_id',
                'roles.name AS role_name',
                'group_id',
                'id_hotel',
                'username',
                'email',
                'first_name as name',
                'last_name as last_name',
                'avatar',
                'tb_hotels.name as hotel_name',
                'tb_region.name as region_name',
            ])
            ->whereNull('deleted_at');

        if ($request->filled('name')) {
            $query->where('first_name', 'like', $request->input('name') . '%');
        }
        if ($request->filled('username')) {
            $query->where('username', 'like', $request->input('username') . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', $request->input('email') . '%');
        }
        if ($request->filled('hotel_name')) {
            $query->where('tb_hotels.id', '=', $request->input('hotel_name'));
        }
        if ($request->filled('region_name')) {
            $query->where('tb_region.id', '=', $request->input('region_name'));
        }


        return DataTables::of($query)
            ->editColumn('avatar', function ($row) {
                if (is_null($row->avatar) || empty($row->avatar)) {
                    return '<i class="fas fa-user" style="font-size: 50px; color:#777;"></i>';
                }
                $avatarPath = public_path('assets/images/users/' . $row->avatar);
                if (file_exists($avatarPath)) {
                    $avatarSrc = asset('assets/images/users/' . $row->avatar);
                    return '<img src="' . $avatarSrc . '"
                    title="' . $row->avatar . '"
                    width="50px" height="50px"
                    style="text-shadow: 1px 1px; border:1px solid #777;" />
                <span style="color:transparent;font-size:1px">' . $row->avatar . '</span>';
                } else {
                    return '<i class="fas fa-user" style="font-size: 50px; color:#777;"></i>';
                }
            })
            ->rawColumns(['avatar'])
            ->make(true);
    }




    public function createUser(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:tb_users,username',
            'email' => 'required|email|max:255|unique:tb_users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
        ], [
            'username.unique' => 'Пользователь с таким username уже существует.',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
            'role_id.required' => 'Необходимо выбрать роль.',
            'role_id.exists' => 'Выбранная роль не существует.',
        ]);

        $authUser = Auth::user();
        $groupId = $authUser->group_id;
        $hotelId = $authUser->id_hotel;
        $roleId = $request->role_id;

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarDirectory = 'assets/images/users/';
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatar->move(public_path($avatarDirectory), $avatarName);
            $avatarPath = $avatarName;
        }

        $hashedPassword = Hash::make($request->password);

        $userId = DB::table('tb_users')->insertGetId([
            'group_id' => $groupId,
            'id_hotel' => $hotelId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $hashedPassword,
            'avatar' => $avatarPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => $roleId,
            'model_type' => 'App\Models\User',
            'model_id' => $userId,
        ]);

        if ($request->has('permissions')) {
            $permissions = json_decode($request->permissions, true);
            if (is_array($permissions)) {
                foreach ($permissions as $permissionId) {
                    DB::table('model_has_permissions')->insert([
                        'permission_id' => $permissionId,
                        'model_type' => 'App\Models\User',
                        'model_id' => $userId,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Пользователь успешно создан',
        ]);
    }




    public function deleteUser(Request $request)
    {
        $id = $request->get("id");

        try {
            $deleted = DB::table('tb_users')
                ->where('id', $id)
                ->update(['deleted_at' => Carbon::now()]);

            if ($deleted) {
                DB::table('model_has_roles')->where('model_id', $id)->delete();
                DB::table('model_has_permissions')->where('model_id', $id)->delete();

                return response()->json([
                    'success' => true,
                    'message' => __('User deleted successfully.')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('User not found.')
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while deleting the User.'),
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function editUser(Request $request)
    {

        try {


            $userId = $request->input('user_id');
            $firstName = $request->input('first_name');
            $lastName = $request->input('last_name');
            $username = $request->input('username');
            $email = $request->input('email');
            $password = $request->input('password');
            $confirmPassword = $request->input('password_confirmation');
            $roleId = $request->input('role_id');
            $avatar = $request->file('avatar');
            $permissions = json_decode($request->input('permissions'), true);

            if ($password && strlen($password) < 8) {
                return response()->json([
                    'success' => false,
                    'message' => 'Пароль должен содержать минимум 8 символов',
                ], 422);
            }
            if (empty($userId) || empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($roleId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Все обязательные поля должны быть заполнены.'
                ]);
            }

            if ($password && $password !== $confirmPassword) {
                return response()->json([
                    'success' => false,
                    'message' => 'Пароль и подтверждение пароля не совпадают.'
                ]);
            }

            $avatarPath = null;
            if ($avatar) {
                $avatar = $request->file('avatar');
                $avatarDirectory = 'assets/images/users/';
                $avatarName = time() . '_' . $avatar->getClientOriginalName();
                $avatar->move(public_path($avatarDirectory), $avatarName);
                $avatarPath = $avatarName;
            }

            DB::table('tb_users')
                ->where('id', $userId)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'username' => $username,
                    'email' => $email,
                    'avatar' => $avatarPath ? $avatarPath : DB::raw('avatar'),
                    'password' => $password ? bcrypt($password) : DB::raw('password'),
                ]);

            $exists_role = DB::table('model_has_roles')
                ->where('model_id', '=', $userId)
                ->exists();

            if ($exists_role) {
                DB::table('model_has_roles')
                    ->where('model_id', '=', $userId)
                    ->update(['role_id' => $roleId]);
            } else {
                DB::table('model_has_roles')
                    ->insert([
                        'role_id' => $roleId,
                        'model_type' => 'App\Models\User',
                        'model_id' => $userId
                    ]);
            }

            if (is_array($permissions)) {
                DB::table('model_has_permissions')->where('model_id', $userId)->delete();

                foreach ($permissions as $permissionId) {
                    $permissionId = (int)$permissionId;

                    if ($permissionId > 0) {
                        DB::table('model_has_permissions')->insert([
                            'permission_id' => $permissionId,
                            'model_type' => 'App\Models\User',
                            'model_id' => $userId,
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Данные пользователя успешно обновлены!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при обновлении данных: ' . $e->getMessage()
            ]);
        }
    }



}
