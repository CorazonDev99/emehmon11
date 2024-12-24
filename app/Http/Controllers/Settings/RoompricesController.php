<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\PriceList\RoompricesStoreRequest;
use App\Http\Requests\PriceList\RoompricesUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class RoompricesController extends Controller
{
    public function index()
    {
        $regions = DB::table('tb_region')->select(['id', 'name'])->get();
        $hotels = DB::table('tb_hotels')->select(['id', 'name', 'id_region'])->get();
        $roomTypes = DB::table('tb_room_types')->select(['id', 'en'])->get();
        return view('roomprices.index', ['hotels' => $hotels, 'regions' => $regions, 'roomTypes' => $roomTypes]);
    }

    public function store(RoompricesStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $id_hotel = auth()->user()->id_hotel;

            $data = DB::table('tb_room_prices')->insert([
                'entry_by' => auth()->user()->id,
                'id_hotel' => $id_hotel,
                'id_type' => $request->id_type,
                'dt' => Carbon::parse($request->dt)->format('Y-m-d'),
                'beds' => $request->beds,
                'capacity' => $request->capacity,
                'uzs' => $request->uzs,
                'usd' => $request->usd,
                'breakfast' => $request->breakfast,
                'created_at' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Успешно создано',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::info($e);

            return response()->json([
                'message' => 'Ошибка: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }


    public function show($id)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $userId = auth()->user()->id;

        $data = DB::table('tb_room_prices')
            ->join('tb_hotels', 'tb_room_prices.id_hotel', '=', 'tb_hotels.id')
            ->where('tb_room_prices.id', $id)
            ->where('tb_room_prices.entry_by', $userId)
            ->first();
        if (!$data) {
            return null;
        }
        $roomType = DB::table('tb_room_types')->where('id', $data->id_type)->first();
        $user = DB::table('tb_users')->where('id', $data->entry_by)->first();
        return view('roomprices.show', [
            'data' => $data,
            'room_type' => $roomType,
            'created_by' => $user ? $user->username : 'Unknown',
        ]);
    }

    public function edit($id)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $userId = auth()->user()->id;

        $data = DB::table('tb_room_prices')
            ->join('tb_hotels', 'tb_room_prices.id_hotel', '=', 'tb_hotels.id')
            ->where('tb_room_prices.id', $id)
            ->where('tb_room_prices.entry_by', $userId)
            ->select('tb_room_prices.*', 'tb_hotels.name as hotel_name')
            ->first();
        $roomTypes = DB::table('tb_room_types')->get();
        return view('roomprices.edit', ['data' => $data, 'roomTypes' => $roomTypes]);
    }
    public function update(RoompricesUpdateRequest $request, $id)
    {
        $roomPrice = DB::table('tb_room_prices')->where('id', $id)->where('entry_by', auth()->user()->id)->first();

        if ($roomPrice) {
            try {
                DB::table('tb_room_prices')->where('id', $id)->update([
                    'dt' => Carbon::parse($request->date)->format('Y-m-d'),
                    'id_type' => $request->id_type,
                    'beds' => $request->beds,
                    'capacity' => $request->capacity,
                    'uzs' => $request->uzs,
                    'usd' => $request->usd,
                    'breakfast' => $request->breakfast,
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Ваша запись была успешно обновлена',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e,
                ], 400);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Запись не найдена',
        ], 404);
    }


    public function getData(Request $request)
    {
        $validated = $request->validate([
            'region' => 'nullable|integer',
            'hotel' => 'nullable|integer',
            'tip' => 'nullable|integer',
        ]);

        // Base query for room prices
        $rooms = DB::table('tb_room_prices')
            ->where('tb_room_prices.entry_by', auth()->user()->id)
            ->join('tb_room_types', 'tb_room_prices.id_type', '=', 'tb_room_types.id')
            ->join('tb_hotels', 'tb_room_prices.id_hotel', '=', 'tb_hotels.id')
            ->join('tb_region', 'tb_hotels.id_region', '=', 'tb_region.id')
            ->select('tb_room_prices.*', 'tb_room_types.en as room_type', 'tb_hotels.name as hotel_name', 'tb_hotels.id_region');

        // Apply filters if present
        if (isset($validated['region']) && $validated['region'] !== null) {
            $rooms->where('id_region', $validated['region']);
        }

        if (isset($validated['hotel']) && $validated['hotel']) {
            $rooms->where('id_hotel', $validated['hotel']);
        }

        if (isset($validated['tip']) && $validated['tip']) {
            $rooms->where('id_type', $validated['tip']);
        }

        // Generate DataTables response
        return DataTables::of($rooms)
            ->addColumn('room_type', function ($room) {
                return $room->room_type;
            })
            ->addColumn('hotel_name', function ($room) {
                return $room->hotel_name;
            })
            ->make(true);
    }

    public function getForm()
    {
        $id_hotel = auth()->user()->id_hotel;
        $hotel = DB::table('tb_hotels')->where('id', $id_hotel)->first();
        $hotelName = $hotel->name;
        $roomsTypes = DB::table('tb_room_types')->get();
        return view('roomprices.form', ['hotelName' => $hotelName, 'roomTypes' => $roomsTypes]);
    }
}
