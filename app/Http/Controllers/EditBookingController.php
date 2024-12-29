<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class EditBookingController extends Controller
{
    public function getForm(Request $request)
    {
        $id = $request->query('id');
        $additionalData = $request->except('id');
        $booking = \DB::table('bookings')
            ->select(
                'id',
                'date_from',
                'date_to',
                'room_id',
                'staffname',
                'contact_phone',
            )
            ->where('id',$id)
            ->first();
        if ($booking) {
            $booking->date_from = Carbon::parse($booking->date_from)->format('d.m.Y');
            $booking->date_to = Carbon::parse($booking->date_to)->format('d.m.Y');
            foreach ($additionalData as $key => $value) {
                $booking->{$key} = $value; // Ob'ektga yangi xususiyatlar qo'shish
            }
        }
        return view('booking.edit-form',compact('booking'));
    }
}
