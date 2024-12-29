<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
class InfoBookingController extends Controller
{
    public function getForm(Request $request)
    {
        $id = $request->query('id');
        $booking = \DB::table('bookings')
            ->select(
                'id',
                'date_from',
                'date_to',
                'room_id',
                'staffname',
                'contact_phone',
                'rooms_qty',
                'adults',
                'children',
                'created_at',
                'contact_email',
                'doc_number',
                'pinfl',
                'dtb',
            )
            ->where('id',$id)
            ->first();
        if ($booking) {
            $booking->date_from = Carbon::parse($booking->date_from)->format('d.m.Y');
            $booking->date_to = Carbon::parse($booking->date_to)->format('d.m.Y');
            $booking->created_at = Carbon::parse($booking->created_at)->format('d.m.Y');
            $booking->dtb = $booking->dtb ? Carbon::parse($booking->dtb)->format('d.m.Y') : $booking->dtb;
        }
        return view('booking.info-form',compact('booking'));
    }
    public function updateBooking(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer|exists:bookings,id',
                'new_children' => 'nullable|integer|min:0',
                'new_adults' => 'required|integer|min:1',
                'new_staffname' => 'required|string|max:50',
                'new_contact_phone' => 'nullable|string|max:50',
                'new_contact_email' => 'nullable|email|max:50',
                'new_datebirth' => 'nullable|date',
                'new_passport' => 'nullable|string|max:50',
                'new_pinfl' => 'nullable|string|max:14',
                'new_minDate' => 'required|date',
                'new_maxDate' => 'required|date|after_or_equal:start',
            ]);

            $formattedMinDate = Carbon::createFromFormat('d.m.Y', $validatedData['new_minDate'])->format('Y-m-d');
            $formattedMaxDate = Carbon::createFromFormat('d.m.Y', $validatedData['new_maxDate'])->format('Y-m-d');
            \DB::table('bookings')
                ->where('id', $validatedData['id'])
                ->update([
                    'date_from' => $formattedMinDate,
                    'date_to' => $formattedMaxDate,
                    'adults' => $validatedData['new_adults'],
                    'children' => $validatedData['new_children'],
                    'staffname' => $validatedData['new_staffname'],
                    'contact_phone' => $validatedData['new_contact_phone'],
                    'contact_email' => $validatedData['new_contact_email'],
                    'doc_number' => $validatedData['new_passport'],
                    'pinfl' => $validatedData['new_pinfl'],
                    'dtb' => $validatedData['new_datebirth'],
                ]);

            return response()->json(['message' => 'Ma\'lumotlar muvaffaqiyatli yangilandi.'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Xatolik yuz berdi: ' . $e->getMessage()], 500);
        }
    }
}
