<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditEvent
{
    public static function add($event_type, $entity_id, $entity_type, $changes = [])
    {
        $clickhouse = app(ClickHouseService::class)->getClient();

        $authUser = \Auth::user();

        $uid = \Session::get('uid', $authUser ? $authUser->id : 0);
        $hid = \Session::get('hid', $authUser ? $authUser->id_hotel : 0);
        $fid = \Session::get('fid', $authUser ? $authUser->first_name . ' ' . $authUser->last_name : 'Unknown User');
        $htlname = \Session::get('htlname');
        if (!$htlname && $authUser) {
            $hotel = \DB::table('tb_hotels')->where('id', $authUser->id_hotel)->first();
            $htlname = $hotel ? $hotel->name : 'Unknown Hotel';
        }

        $audit = [
            'event_type' => $event_type,
            'hotel_id' => (int)$hid,
            'hotel_name' => $htlname,
            'user_id' => (int)$uid,
            'user_name' => $fid,
            'entity_id' => $entity_id,
            'entity_type' => $entity_type,
            'event_time' => now()->format('Y-m-d H:i:s'),
            'ip_address' => Request::ip(),
            'changes' => json_encode($changes, JSON_UNESCAPED_UNICODE),
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
}
