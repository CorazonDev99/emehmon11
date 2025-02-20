<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AuditEvent
{
    public static function add($event_type, $entity_id, $entity_type, $changes = [])
    {

        $clickhouse = app(ClickHouseService::class)->getClient();
        $user = self::getUserData();
        $hotel = self::getHotelData($user['id_hotel'] ?? null);

        $processedChanges = self::processChanges($changes);

        $audit = [
            'event_type' => $event_type,
            'hotel_id' => (int)($hotel['id'] ?? 0),
            'hotel_name' => $hotel['name'] ?? '',
            'user_id' => (int)($user['id'] ?? 0),
            'user_name' => $user['full_name'] ?? '',
            'entity_id' => $entity_id,
            'entity_type' => $entity_type,
            'event_time' => now()->format('Y-m-d H:i:s'),
            'ip_address' => Request::ip(),
            'changes' => json_encode($processedChanges, JSON_UNESCAPED_UNICODE),
        ];

        try {
            $query = "
                INSERT INTO audit_events (
                    event_type,
                    hotel_id,
                    hotel_name,
                    user_id,
                    user_name,
                    entity_id,
                    entity_type,
                    event_time,
                    ip_address,
                    changes
                ) VALUES (
                    :event_type,
                    :hotel_id,
                    :hotel_name,
                    :user_id,
                    :user_name,
                    :entity_id,
                    :entity_type,
                    :event_time,
                    toIPv4(:ip_address),
                    :changes
                )
            ";

            $clickhouse->write($query, [
                'event_type' => $audit['event_type'],
                'hotel_id' => $audit['hotel_id'],
                'hotel_name' => $audit['hotel_name'],
                'user_id' => $audit['user_id'],
                'user_name' => $audit['user_name'],
                'entity_id' => $audit['entity_id'],
                'entity_type' => $audit['entity_type'],
                'event_time' => $audit['event_time'],
                'ip_address' => $audit['ip_address'],
                'changes' => $audit['changes'],
            ]);

        } catch (\Exception $e) {
            Log::error('ClickHouse Insert Error: ' . $e->getMessage(), ['audit' => $audit]);
            throw $e;
        }
    }

    /**
     * Получаем данные пользователя из сессии или Auth.
     */
    private static function getUserData(): array
    {
        $authUser = Auth::user();

        return [
            'id' => Session::get('uid', $authUser->id ?? 0),
            'id_hotel' => Session::get('hid', $authUser->id_hotel ?? 0),
            'full_name' => Session::get('fid', $authUser ? $authUser->first_name . ' ' . $authUser->last_name : ''),
        ];
    }

    /**
     * Получаем данные отеля по его ID.
     */
    private static function getHotelData($hotelId): array
    {
        if (!$hotelId) {
            return [];
        }

        $hotel = DB::table('tb_hotels')->where('id', $hotelId)->first();
        return [
            'id' => $hotel->id ?? 0,
            'name' => $hotel->name ?? '',
        ];
    }

    /**
     * Обрабатываем изменения (old и new).
     */
    private static function processChanges(array $changes): array
    {
        if (isset($changes['old']) && isset($changes['new'])) {
            return $changes;
        }

        $old = [];
        $new = [];

        foreach ($changes as $key => $value) {
            if (strpos($key, 'old_') === 0) {
                $newKey = substr($key, 4);
                $old[$newKey] = $value;
            } elseif (strpos($key, 'new_') === 0) {
                $newKey = substr($key, 4);
                $new[$newKey] = $value;
            }
        }

        return ['old' => $old, 'new' => $new];
    }

}
