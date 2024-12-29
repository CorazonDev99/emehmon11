<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function getForm(Request $request)
    {
        $data = [
            'room_type' => $request->query('type_name'),
            'room_name' => $request->query('room_name'),
            'minDate' => $request->query('minDate'),
            'maxDate' => $request->query('maxDate')
        ];
        return view('booking.form', $data);
    }
    public function getBookTable(Request $request)
    {
        $data['start'] = $start = $request->input('start');
        $data['end'] = $end = $request->input('end');

        $data['dates'] = [];
        $data['month'] = [];

        for ($date = Carbon::parse($start); $date->lte(Carbon::parse($end)); $date->addDay()) {
            $data['dates'][] = $date->format('Y-m-d');
            if (!isset($data['month'][$date->format('m-Y')])){
                $data['month'][$date->format('m-Y')] = 1;
            } else {
                $data['month'][$date->format('m-Y')]++;
            }
        }

        $data['room_types'] = collect(\DB::table('tb_room_types')->get());

        foreach ($data['room_types'] as $room_type)
            $room_type->rooms = collect(\DB::table('tb_rooms')
                ->select('id', 'room_numb', 'beds')
                ->where('id_room_type', $room_type->id)
                ->where('id_hotel',session('hid', /*auth()->user()->id_hotel*/12))
                ->orderBy('room_numb','asc')
                ->get());

        $data['room_types'] = $data['room_types']->filter(function ($room_type) {
            return $room_type->rooms->isNotEmpty();
        })->values();
        $data['bookings']= \DB::table('bookings')
            ->where('hotel_id',session('hid', auth()->user()->id_hotel))
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('date_from', [$start, $end])
                    ->orWhereBetween('date_to', [$start, $end])
                    ->orWhere('date_from', null);
            })->select(
                'id',
                'date_from',
                'date_to',
                'room_id',
                'staffname',
                'status',
                'adults',
                'children',
                'contact_phone',
            )->get();
        \Log::info($data);
        return view('booking.book-table',$data);
    }
    public function bookGuest(Request $request)
    {
        $validated = $request->validate([
            'staffname' => 'required|string|max:50',
            'guest' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'minDate' => 'required|date',
            'maxDate' => 'required|date|after_or_equal:minDate',
            'datebirth' => 'nullable|date',
            'room' => 'required|integer',
            'passport' => 'nullable|string|max:50',
            'pinfl' => 'nullable|string|max:14',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:50',
            'comments' => 'nullable|string|max:255',
        ]);
        $data = [
            'hotel_id' => session('hid', auth()->user()->id_hotel),
            'date_from' => Carbon::parse($validated['minDate'])->format('Y-m-d H:i:s'),
            'date_to' => Carbon::parse($validated['maxDate'])->format('Y-m-d H:i:s'),
            'customer_id' => auth()->user()->id,
            'status' => 'new',
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'rooms_qty' => 1,
            'adults' => $validated['guest'],
            'children' => $validated['children'],
            'comments' => $validated['comments'],
            'doc_number' => $validated['passport'],
            'pinfl' => $validated['pinfl'],
            'dtb' => $validated['datebirth'],
            'staffname' => $validated['staffname'],
            'room_id' => $validated['room'],
            'org_name'=>'OOO "Emehmon"',
            'org_inn'=>'1234567890',
        ];
        try {
            $listokId = \DB::table('bookings')->insertGetId($data);
            return response()->json([
                'status' => 'success',
                'message' => 'Booking muvaffaqiyatli amalga oshirildi!',
                'regnum' => $listokId,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking amalga oshmadi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateBooking(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer|exists:bookings,id',
                'new_room' => 'required|string|max:255',
                'new_minDate' => 'required|date',
                'new_maxDate' => 'required|date|after_or_equal:start',
            ]);

            $formattedMinDate = Carbon::createFromFormat('d.m.Y', $validatedData['new_minDate'])->format('Y-m-d');
            $formattedMaxDate = Carbon::createFromFormat('d.m.Y', $validatedData['new_maxDate'])->format('Y-m-d');
            \DB::table('bookings')
                ->where('id', $validatedData['id'])
                ->update([
                    'room_id' => $validatedData['new_room'],
                    'date_from' => $formattedMinDate,
                    'date_to' => $formattedMaxDate,
                ]);

            return response()->json(['message' => 'Ma\'lumotlar muvaffaqiyatli yangilandi.'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Xatolik yuz berdi: ' . $e->getMessage()], 500);
        }
    }
}
