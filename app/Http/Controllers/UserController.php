<?php

namespace App\Http\Controllers;
use App\Services\ClickHouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditEvent;
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

        $hotels = DB::table('tb_hotels')->select(['id', 'name', 'id_region', 'hotel_type_id'])->get();
        $regions = DB::table('tb_region')->select(['id', 'name'])->get();
        $mvd = DB::table('visa_organs')->get();

        return view('core.users.index', compact('mvd','roles', 'permissions', 'hotels', 'regions', 'userPermissions', 'rolesEdit'));
    }


    public function getData(Request $request)
    {
        $query = DB::table('tb_users')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'tb_users.id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->leftJoin('tb_hotels', 'tb_hotels.id', '=', 'tb_users.id_hotel')
            ->leftJoin('visa_organs', 'visa_organs.sticker_code', '=', 'tb_users.mrz_code')
            ->leftJoin('tb_region', 'tb_hotels.id_region', '=', 'tb_region.id')
            ->leftJoin('tb_users as created_by_user', 'tb_users.created_by', '=', 'created_by_user.id')
            ->select([
                'tb_users.id as user_id',
                'roles.id AS role_id',
                'roles.name AS role_name',
                'tb_users.group_id',
                'tb_users.id_hotel',
                'tb_hotels.id_region',
                'tb_users.username',
                'tb_users.email',
                'visa_organs.code as code',
                'visa_organs.sticker_name as sticker_name',
                'visa_organs.mvd_name as mvd_name',
                'tb_users.mrz_code',
                'tb_users.first_name as name',
                'tb_users.last_name as last_name',
                'tb_users.avatar',
                'tb_users.active',
                'tb_users.deleted_at',
                'tb_hotels.name as hotel_name',
                'tb_region.name as region_name',
                DB::raw("DATE_FORMAT(tb_users.last_login, '%d.%m.%Y %H:%i') AS last_login"),
                DB::raw("DATE_FORMAT(tb_users.created_at, '%d.%m.%Y %H:%i') AS created_at"),
                DB::raw("DATE_FORMAT(tb_users.updated_at, '%d.%m.%Y %H:%i') AS updated_at"),
                'created_by_user.first_name as created_by_first_name',
                'created_by_user.last_name as created_by_last_name' ,
            ]);

        if ($request->filled('name')) {
            $query->where('tb_users.first_name', 'like', $request->input('name') . '%');
        }
        if ($request->filled('username')) {
            $query->where('tb_users.username', 'like', $request->input('username') . '%');
        }
        if ($request->filled('email')) {
            $query->where('tb_users.email', 'like', $request->input('email') . '%');
        }
        if ($request->filled('hotel_name')) {
            $query->where('tb_hotels.id', '=', $request->input('hotel_name'));
        }
        if ($request->filled('region_name')) {
            $query->where('tb_region.id', '=', $request->input('region_name'));
        }
        if ($request->filled('status')) {
            if ($request->input('status') == "deleted") {
                $query->whereNotNull('tb_users.deleted_at');
            } else {
                $query->where('tb_users.active', $request->input('status'))
                    ->whereNull('tb_users.deleted_at');
            }
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
                    style="border-radius: 10%; text-shadow: 1px 1px; border:1px solid #777;" />
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
        $rules = [
            'username' => 'required|string|max:255|unique:tb_users,username',
            'email' => 'required|email|max:255|unique:tb_users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
        ];

        $messages = [
            'username.unique' => 'Пользователь с таким username уже существует.',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
            'role_id.required' => 'Необходимо выбрать роль.',
            'role_id.exists' => 'Выбранная роль не существует.',
        ];

        if ($request->has('permissions')) {
            $permissions = json_decode($request->permissions, true);

            if (is_array($permissions)) {
                $visaPrintAccessPermission = DB::table('permissions')
                    ->where('name', 'VISA_PRINT_ACCESS')
                    ->first();

                if ($visaPrintAccessPermission && in_array($visaPrintAccessPermission->id, $permissions)) {
                    $rules['mrz_code'] = 'required|string|max:50';
                    $messages['mrz_code.required'] = 'Необходимо выбрать MVD_VISA_CODE.';
                }
            }
        }

        $request->validate($rules, $messages);

        $hotelId = $request->id_hotel;
        $roleId = $request->role_id;
        $mrzCode = $request->mrz_code;

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarDirectory = 'assets/images/users/';
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatar->move(public_path($avatarDirectory), $avatarName);
            $avatarPath = $avatarName;
        }

        $hashedPassword = Hash::make($request->password);

        $userData = [
            'id_hotel' => $hotelId,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $hashedPassword,
            'avatar' => $avatarPath,
            'mrz_code' => $mrzCode,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $userId = DB::table('tb_users')->insertGetId($userData);

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

        // Запись аудита о создании пользователя
        AuditEvent::add(
            'Создание пользователя',
            $userId,
            'tb_users',
            [
                'old' => [],
                'new' => array_merge($userData, ['id' => $userId]),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Пользователь успешно создан',
        ]);
    }





    public function deleteUser(Request $request)
    {
        $id = $request->get("id");

        try {
            $user = DB::table('tb_users')->where('id', $id)->first();

            if ($user) {
                $updateData = [
                    'deleted_at' => Carbon::now(),
                    'active' => 0
                ];

                // Формируем массив изменений в формате 'old' и 'new'
                $changes = ['old' => [], 'new' => []];

                foreach ($updateData as $key => $newValue) {
                    $oldValue = $user->$key ?? null;
                    if ($oldValue != $newValue) {
                        $changes['old'][$key] = $oldValue;
                        $changes['new'][$key] = $newValue;
                    }
                }

                // Добавляем доп. данные в "old"
                $changes['old'] += [
                    'username'   => $user->username,
                    'email'      => $user->email,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'id_hotel'   => $user->id_hotel,
                ];

                if (!empty($changes['new'])) {
                    DB::table('tb_users')->where('id', $id)->update($updateData);

                    // Логируем аудит
                    AuditEvent::add('Удаление пользователя', $id, 'tb_users', $changes);
                }

                // Удаляем записи из связанных таблиц
                DB::table('model_has_roles')->where('model_id', $id)->delete();
                DB::table('model_has_permissions')->where('model_id', $id)->delete();

                return response()->json([
                    'success' => true,
                    'message' => __('User deleted successfully.')
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('User not found.')
            ], 404);
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
            $hotelId = $request->input('id_hotel');
            $mrzCode = $request->input('mrz_code');
            $createdBy = auth()->user()->id;
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
                ], 422);
            }

            if ($password && $password !== $confirmPassword) {
                return response()->json([
                    'success' => false,
                    'message' => 'Пароль и подтверждение пароля не совпадают.'
                ], 422);
            }

            $oldUser = DB::table('tb_users')->where('id', $userId)->first();
            if (!$oldUser) {
                return response()->json(['success' => false, 'message' => 'Пользователь не найден.'], 404);
            }

            $avatarPath = $oldUser->avatar;
            if ($avatar) {
                $avatarDirectory = 'assets/images/users/';
                $avatarName = time() . '_' . $avatar->getClientOriginalName();
                $avatar->move(public_path($avatarDirectory), $avatarName);
                $avatarPath = $avatarName;
            }

            $updateData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
                'email' => $email,
                'created_by' => $createdBy,
                'id_hotel' => $hotelId,
                'mrz_code' => $mrzCode,
                'avatar' => $avatarPath,
                'password' => $password ? bcrypt($password) : $oldUser->password,
            ];

            $changes = [];
            foreach ($updateData as $key => $newValue) {
                $oldValue = $oldUser->$key ?? null;
                if ($oldValue != $newValue) {
                    $changes["old_$key"] = $oldValue;
                    $changes["new_$key"] = $newValue;
                }
            }

            if (!empty($changes)) {
                DB::table('tb_users')->where('id', $userId)->update($updateData);
                AuditEvent::add('update_user', $userId, 'user', $changes);
            }

            // 🔹 Оптимизированная проверка роли
            $oldRole = DB::table('model_has_roles')->where('model_id', $userId)->first();
            if (!$oldRole || $oldRole->role_id != $roleId) {
                AuditEvent::add('update_user_role', $userId, 'user', [
                    'old_role_id' => $oldRole->role_id ?? null,
                    'new_role_id' => $roleId,
                ]);

                DB::table('model_has_roles')
                    ->updateOrInsert(['model_id' => $userId], [
                        'role_id' => $roleId,
                        'model_type' => 'App\Models\User',
                    ]);
            }

            // 🔹 Оптимизированная проверка разрешений
            $oldPermissions = DB::table('model_has_permissions')
                ->where('model_id', $userId)
                ->pluck('permission_id')
                ->toArray();

            if (is_array($permissions)) {
                $newPermissions = array_map('intval', $permissions);
                if ($oldPermissions !== array_reverse($newPermissions)) {
                    AuditEvent::add('update_user_permissions', $userId, 'user', [
                        'old_permissions' => $oldPermissions,
                        'new_permissions' => $newPermissions,
                    ]);

                    DB::table('model_has_permissions')->where('model_id', $userId)->delete();
                    $insertPermissions = [];
                    foreach ($newPermissions as $permissionId) {
                        if ($permissionId > 0) {
                            $insertPermissions[] = [
                                'permission_id' => $permissionId,
                                'model_type' => 'App\Models\User',
                                'model_id' => $userId,
                            ];
                        }
                    }
                    if (!empty($insertPermissions)) {
                        DB::table('model_has_permissions')->insert($insertPermissions);
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
            ], 500);
        }
    }


    public function getHotelsByRegion(Request $request)
    {
        $regionId = $request->input('region_id');
        $hotels = DB::table('tb_hotels')
            ->where('id_region', $regionId)
            ->select(['id', 'name'])
            ->get();

        return response()->json($hotels);
    }

    public function getAuditLogs(Request $request)
    {
        $entity_id = $request->input('entity_id');
        if (!$entity_id) {
            return response()->json([
                'success' => false,
                'message' => 'Entity ID is required.',
            ], 400);
        }

        $clickhouse = app(ClickhouseService::class);

        $query = "
        SELECT
            event_type,
            hotel_name,
            user_name,
            entity_id,
            entity_type,
            event_time,
            changes
        FROM emehmon.audit_events
        WHERE entity_id = :entity_id
        ORDER BY event_time DESC
    ";

        try {
            $auditLogs = $clickhouse->select($query, ['entity_id' => $entity_id]);

            $formattedLogs = array_map(function ($log) {
                $log['changes'] = $this->formatChanges($log['changes'], $log['event_type']);
                $log['event_time'] = Carbon::parse($log['event_time'])->format('d.m.Y H:i');
                return $log;
            }, $auditLogs);

            return response()->json([
                'success' => true,
                'data' => $formattedLogs,
                'count' => count($formattedLogs),
            ]);
        } catch (\Exception $e) {
            \Log::error('ClickHouse query error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при выполнении запроса к ClickHouse.',
            ], 500);
        }
    }



    private function formatChanges(string $changes, string $event_type): string
    {
        try {
            $changesObj = json_decode($changes, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                \Log::error('Ошибка декодирования JSON', [
                    'changes' => $changes,
                    'error' => json_last_error_msg()
                ]);
                return 'Ошибка при обработке изменений';
            }

            if (!is_array($changesObj) || empty($changesObj)) {
                return 'Изменения не указаны';
            }

            $messages = [];

            if ($event_type === 'Удаление пользователя') {
                $username = $changesObj['old']['username'] ?? 'Неизвестный пользователь';
                $email = $changesObj['old']['email'] ?? 'Нет email';

                $messages[] = "Пользователь **{$username}** ({$email}) был удалён.";

                foreach ($changesObj['old'] as $field => $oldValue) {
                    if (!in_array($field, ['username', 'email'])) {
                        $messages[] = "Поле **$field**: '$oldValue'";
                    }
                }
            } elseif ($event_type === 'Создание пользователя') {
                $username = $changesObj['new']['username'] ?? 'Неизвестный пользователь';
                $email = $changesObj['new']['email'] ?? 'Нет email';

                $messages[] = "Создан новый пользователь **{$username}** ({$email}).";

                foreach ($changesObj['new'] as $field => $newValue) {
                    if (!in_array($field, ['username', 'email', 'password'])) {
                        $messages[] = "Поле **$field**: '$newValue'";
                    }
                }
            } else {
                foreach ($changesObj['old'] as $field => $oldValue) {
                    $newValue = $changesObj['new'][$field] ?? null;

                    if (is_array($oldValue) && is_array($newValue)) {
                        $removed = array_diff($oldValue, $newValue);
                        $added = array_diff($newValue, $oldValue);

                        if (!empty($removed) || !empty($added)) {
                            $removedText = !empty($removed) ? "Удалены: " . implode(", ", $removed) : "";
                            $addedText = !empty($added) ? "Добавлены: " . implode(", ", $added) : "";
                            $messages[] = "Поле **$field** изменилось: $removedText $addedText";
                        }
                    } else {
                        if ($oldValue != $newValue) {
                            $messages[] = "Поле **$field** изменилось: '$oldValue' → '$newValue'";
                        }
                    }
                }
            }

            return !empty($messages) ? implode("\n", $messages) : 'Изменения не указаны';
        } catch (\Exception $e) {
            \Log::error('Ошибка при обработке изменений: ' . $e->getMessage(), [
                'changes' => $changes,
                'event_type' => $event_type,
            ]);
            return 'Ошибка при обработке изменений';
        }
    }


}
