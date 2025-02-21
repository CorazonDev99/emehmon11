<?php

namespace App\Http\Controllers;

use App\Services\ClickHouseService;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $clickhouse = app(ClickHouseService::class);
        $hotels = $clickhouse->select('
            SELECT DISTINCT hotel_id,
                hotel_name

            FROM
                emehmon.audit_events
        ');

        return view('core.audit.index',  compact('hotels'));
    }

    public function getData(Request $request)
    {
        $clickhouse = app(ClickHouseService::class);

        // Base query
        $query = '
        SELECT
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
        FROM
            emehmon.audit_events
    ';

        $whereConditions = [];

        if ($request->has('user_name') && $request->user_name) {
            $whereConditions[] = "user_name = '{$request->user_name}'";
        }

        if ($request->has('hotel_id') && $request->hotel_id) {
            $whereConditions[] = "hotel_id = '{$request->hotel_id}'";
        }

        if ($request->has('ip_address') && $request->ip_address) {
            $ipAddress = $request->ip_address;
            $whereConditions[] = "ip_address = toIPv4('{$ipAddress}')";
        }


        if ($request->has('entity_type') && $request->entity_type) {
            $whereConditions[] = "entity_type = '{$request->entity_type}'";
        }

        if ($request->has('event_time_from') && $request->event_time_from) {
            $whereConditions[] = "event_time >= parseDateTimeBestEffort('{$request->event_time_from}')";
        }

        if ($request->has('event_time_to') && $request->event_time_to) {
            $whereConditions[] = "event_time <= parseDateTimeBestEffort('{$request->event_time_to}')";
        }



        if (!empty($whereConditions)) {
            $query .= ' WHERE ' . implode(' AND ', $whereConditions);
        }

        $query .= ' ORDER BY event_time DESC';

        $data = $clickhouse->select($query);

        return DataTables::of($data)
            ->editColumn('event_time', function ($row) {
                return Carbon::parse($row['event_time'])->format('d.m.Y H:i:s');
            })
            ->make(true);
    }

}
