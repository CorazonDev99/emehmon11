<style>
    .ui-resizable-e, .ui-resizable-w {
        width: 0;
        height: 0;
        border-style: solid;
        position: absolute;
        cursor: ew-resize;
        z-index: 0;
    }

    .ui-resizable-e {
        border-width: 13px 0 13px 7px;
        right: -24px;
        top: 50%;
        transform: translateY(-50%);
    }

    .ui-resizable-w {
        border-width: 13px 7px 13px 0;
        left: -24px;
        top: 50%;
        transform: translateY(-50%);
    }

    td.room-header,
    th.room-cell {
        position: sticky;
        left: 0;
        z-index: 100;
        background-color: #f4f4f4;
        border: 1px solid black;
        text-align: center;
    }
</style>
<table id="swipeTable" class="table table-sm table-bordered table-nowrap align-middle">
    <td colspan="1" style="font-weight: bold" class="room-header">
        <select name="" id="roomTypeFilter" style="margin-left: 2px; width: 100px;">
            <option value="0">All rooms</option>
            <option value="1">20 rooms</option>
            <option value="1">30 rooms</option>
            <option value="1">50 rooms</option>
        </select>
    </td>
    @foreach($month as $k=>$m)
        <td colspan="{{ $m }}" style="font-weight: bold; text-align: center">{{ \Carbon\Carbon::createFromFormat('m-Y', $k)->format('M, Y') }}</td>
    @endforeach
    <tr style="text-align: center" id="days">
        <td class="room-header">Days</td>
        @foreach($dates as $date)
            <td style="{{ \Carbon\Carbon::parse($date)->isWeekend() ? 'background-color:ghostwhite;color: red;' : 'background-color:#dff0ff;' }} {{ \Carbon\Carbon::parse($date)->isCurrentDay() ? 'background-color:#f9c769;' : '' }} ">{{ \Carbon\Carbon::parse($date)->format('d.m') }}
                <br><span>{{ strtolower(\Carbon\Carbon::parse($date)->format('D')) }}</span></td>
        @endforeach
    </tr>
    @foreach($room_types as $room_type)
        <tr>
            <td style="width: 150px; font-weight: bold" id="roomType" class="room-header">
                    {{ $room_type->en }}
            </td>
            @foreach($dates as $date)
                @php
                    $totalRooms = $room_type->rooms->count();
                    $bookedRooms = $bookings->filter(function ($booking) use ($room_type, $date) {
                        return $room_type->rooms->pluck('room_numb')->contains($booking->room_id)
                            && $date > $booking->date_from
                            && $date <= $booking->date_to;
                    })->pluck('room_id')->unique()->count();
                    $availableRooms = $totalRooms - $bookedRooms;
                @endphp
                <td style="background: lightgreen; color: #0b0b0b;font-weight: bold;text-align: center;
                {{ \Carbon\Carbon::parse($date)->isWeekend() ? 'background: ghostwhite':'' }};
                {{ \Carbon\Carbon::parse($date)->isCurrentDay() ? 'background: #f9c769':'' }}">
                    @if(!\Carbon\Carbon::parse($date)->isYesterday())
                        {{ $availableRooms }}
                    @endif
                </td>
            @endforeach
        </tr>
        @foreach($room_type->rooms as $d)
            <tr class="swipe">
                <th class="room-cell">{{ $d->room_numb }}</th>
                @php
                    $merge = false;
                    $mergeDates = [];
                @endphp
                @foreach($dates as $date)
                    @php
                        $date = \Carbon\Carbon::parse($date);
                        $isTrue = false;
                    @endphp
                    @foreach($bookings as $booking)
                        @if($booking->date_to == null)
                            @php $booking->date_to = now(); @endphp
                        @endif
                        @php
                            $booking->date_from = \Carbon\Carbon::parse($booking->date_from);
                            $booking->date_to = \Carbon\Carbon::parse($booking->date_to);
                        @endphp
                        @if($booking->room_id == $d->room_numb && ($booking->date_from < $date && ($booking->date_to > $date || $booking->date_to == null)))
                            @php
                                $isTrue = true;
                                $listokData = [
                                    'listok_id' => $booking->id,
                                    'date_start' => $booking->date_from,
                                    'date_end' => $booking->date_to,
                                    'status' => $booking->status,
                                    'adults' => $booking->adults + $booking->children,
                                    'name'=> $booking->staffname,
                                    'Phone'=> $booking->contact_phone,
                                ];

                            @endphp
                            @break
                        @endif
                    @endforeach

                    @if($isTrue)
                        @php
                            $merge = true;
                            $mergeDates[] = [
                                'date' => $date,
                                'data' => $listokData,
                            ];
                        @endphp
                    @else
                        @if($merge)
                            <td colspan="{{ count($mergeDates) }}"
                                data-merge-dates="{{ json_encode(array_column($mergeDates, 'date')) }}"
                                class="listok ui-widget-content draggable "
                                data-listok = "true"
                                data-listok-id="{{ $mergeDates[0]['data']['listok_id'] }}"
                                data-date-start="{{ $mergeDates[0]['data']['date_start'] }}"
                                data-date-end="{{ $mergeDates[count($mergeDates) - 1]['data']['date_end'] }}"
                                data-status="{{ $mergeDates[0]['data']['status'] }}"
                                data-name="{{ $mergeDates[0]['data']['name'] }}"
                                data-phone="{{ $mergeDates[0]['data']['Phone'] }}"
                                style="position: relative; width: {{ count($mergeDates)*50 }}px;height: 35px;">
                                <div class="inner-cell" style="position: absolute">
                                    <strong>{{ $d->beds }}/{{ $mergeDates[0]['data']['adults'] }}</strong>
                                </div>
                            </td>
                            @php
                                $merge = false;
                                $mergeDates = [];
                            @endphp
                        @endif

                        {{-- False bo'lgan har bir elementni alohida ko'rsatish --}}
                        <td data-day="{{ $date->format('d') }}"
                            data-month="{{ $date->format('m') }}"
                            data-year="{{ $date->format('Y') }}"
                            data-type="{{ $room_type->id }}"
                            data-type_name="{{ $room_type->en }}"
                            data-room="{{ $d->id }}"
                            data-room_name="{{ $d->room_numb }}"
                            data-date="{{ $date->format('Y-m-d') }}"
                            style="width: 50px; height: 35px">

                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    @endforeach

</table>
<script src="{{ asset('assets/js/mouse.js') }}"></script>
