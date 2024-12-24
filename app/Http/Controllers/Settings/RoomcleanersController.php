<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoomcleanersController extends Controller
{
    public function getIndex()
    {
        return view('roomcleaners.index');
    }
    public function getForm()
    {
        return view('roomcleaners.form');
    }
    public function getData(Request $request)
    {
        $user = auth()->user();
        $d = \DB::table('tb_room_cleaners as tb_c')
            ->where('tb_c.id_hotel', $user->id_hotel)
            ->join('tb_hotels as h', 'tb_c.id_hotel', '=', 'h.id')
            ->join('tb_users as u', 'tb_c.entry_by', '=', 'u.id')
            ->select([
                'tb_c.*',
                'h.name as hotel_name',
                \DB::raw('CONCAT(u.first_name, " ", u.last_name) as entry_by')
            ]);
        return DataTables::of($d)
            ->editColumn('work_stop', function ($row) {
                if ($row->work_stop === "0000-00-00") {
                    return ' ';
                }
                return $row->work_stop;
            })
            ->make(true);
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'cleaner' => 'required|string|max:255',
            'active' => 'required|boolean',
            'work_start' => 'required|date_format:Y-m-d',
            'work_stop' => 'nullable|date_format:Y-m-d',
        ]);

        try {
            \DB::beginTransaction();

            $cleaningRecord = \DB::table('tb_room_cleaners')->insert([
                'id_hotel' => $user->id_hotel,
                'entry_by' => $user->id,
                'cleaner' => $validated['cleaner'],
                'active' => $validated['active'],
                'work_start' => $validated['work_start'],
                'work_stop' => $validated['work_stop'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Cleaning staff record created successfully!',
                'data' => $validated
            ], 201);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'message' => 'Failed to create cleaning staff record.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($id)
    {
        $user = auth()->user();
        $data = \DB::table('tb_room_cleaners as tb_c')
            ->where('tb_c.id', $id)
            ->where('tb_c.id_hotel', $user->id_hotel)
            ->join('tb_users as u', 'tb_c.entry_by', '=', 'u.id')
            ->join('tb_hotels as h', 'tb_c.id_hotel', '=', 'h.id')
            ->select([
                'tb_c.*',
                'h.name as hotel_name',
                \DB::raw('CONCAT(u.first_name, " ", u.last_name) as entry_by')
            ])->first();
        return view('roomcleaners.show', ['data' => $data]);
    }

    public function edit($id)
    {
        $user = auth()->user();
        $data = \DB::table('tb_room_cleaners as tb_c')
            ->where('tb_c.id', $id)
            ->where('tb_c.id_hotel', $user->id_hotel)
            ->join('tb_users as u', 'tb_c.entry_by', '=', 'u.id')
            ->join('tb_hotels as h', 'tb_c.id_hotel', '=', 'h.id')
            ->select([
                'tb_c.*',
                'h.name as hotel_name',
                \DB::raw('CONCAT(u.first_name, " ", u.last_name) as entry_by')
            ])->first();
        return view('roomcleaners.form', ['data' => $data]);
    }


    // update record
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'cleaner' => 'required|string|max:255',
            'active' => 'required|boolean',
            'work_start' => 'required|date_format:Y-m-d',
            'work_stop' => 'nullable|date_format:Y-m-d',
        ]);
        $cleaningRecord = \DB::table('tb_room_cleaners')->where('id', $id);
        try {
            \DB::beginTransaction();

            $cleaningRecord->update([
                'cleaner' => $validated['cleaner'],
                'active' => $validated['active'],
                'work_start' => $validated['work_start'],
                'work_stop' => $validated['work_stop'] ?? null,
                'updated_at' => now(),
            ]);

            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Record updated!',
                'data' => $validated
            ], 201);
        } catch (\Exception $e) {
            \DB::rollBack();

            return response()->json([
                'message' => 'Failed to create cleaning staff record.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            return response()->json(['error' => 'Invalid ID provided'], 400);
        }

        $delete = \DB::table('tb_room_cleaners')->where('id', $id)->delete();
        if ($delete === 0) {
            return response()->json(['error' => 'No data to delete or smth went wrong!!!'], 404);
        }
        return response()->json(['success' => 'Record deleted'], 200);
    }
}
