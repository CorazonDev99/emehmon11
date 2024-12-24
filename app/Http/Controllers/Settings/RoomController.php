<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoomController extends Controller
{
    private $data = [];
    public function index()
    {
        return view('room.index');
    }

    public function getData(Request $request)
    {
        $lang = session()->get('locale', 'ru');
        //if(!$request->ajax()) return abort(404,'Page not found!');

        $where = '1=2';
        if (\Auth::user()->can('own_room')) $where = 'ru.hotel_id=' . \Auth::user()->id_hotel;
        if (\Auth::user()->can('all_room')) $where = '1=1';

        $d = \DB::table('rooms as ru')
            ->join('hotels as h', 'h.id', '=', 'ru.hotel_id')
            ->join('room_types as rt', 'ru.room_type_id', '=', 'rt.id')
            ->selectRaw("ru.id, h.name as hotel,ru.room_floor,ru.room_numb,ru.beds,rt.$lang as tp,ru.tag,ru.wifi,ru.tvset,ru.aircond,ru.freezer,ru.created_at,ru.active,ru.hotel_id")
            //->selectRaw("ru.id, ru.room_floor,ru.room_numb,ru.beds,rt.$lang as tp,ru.tag,ru.wifi,ru.tvset,ru.aircond,ru.freezer,ru.created_at,ru.active,ru.hotel_id")
            ->whereRaw($where);

        if ($region_id = $request->get('region_id')) $d->where('h.id_region', '=', $region_id);
        if ($hotel_id = $request->get('hotel_id')) $d->where('h.id', '=', $hotel_id);
        if ($floor = $request->get('floor')) $d->where('ru.room_floor', '=', $floor);
        if ($room_type = $request->get('room_type')) $d->where('ru.room_type_id', '=', $room_type);
        if ($tag = $request->get('room_tag')) $d->where('ru.tag', 'like', "%$tag%");
        if ($room_cap = $request->get('room_cap')) {
            switch ($room_cap * 1) {
                case 1:
                case 2:
                case 3:
                    $d->where("ru.beds", $room_cap);
                    break;
                case 99:
                    $d->where("ru.beds", ">", 3);
                    break;
            }
        }

        return DataTables::of($d)
            ->editColumn('wifi', function ($row) {
                return  !empty($row->wifi) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            })
            ->editColumn('tvset', function ($row) {
                return  !empty($row->tvset) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            })
            ->editColumn('aircond', function ($row) {
                return  !empty($row->aircond) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            })
            ->editColumn('freezer', function ($row) {
                return  !empty($row->freezer) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? date('d.m.Y H:i', strtotime($row->created_at)) : '';
            })
            ->editColumn('active', function ($row) {
                return $row->active * 1 == 1 ?
                    '<span class="badge bg-soft-success text-success font-size-12">ACTIVE</span>' :
                    '<span class="badge bg-soft-danger text-danger font-size-12">NOT ACTIVE</span>';
            })
            ->setRowData(['data-id' => function ($row) {
                return cryptId($row->id);
            }])
            ->addColumn('action', function ($row) {
                $id = cryptId($row->id);
                $url = asset('settings/rooms/show/' . $id);
                $urlEdit = asset('settings/rooms/update/' . $id);
                $r = "'" . str_replace(["'", '"'], "`", $row->room_numb) . "'";
                if ($row->hotel_id = \Session::get('hid', \Auth::user()->hotel_id) || \Auth::user()->can('allowedit')) {
                    $urlEdit = '&nbsp;<a href="' .  $urlEdit . '" class="btn btn-xs btn-default" title="Редактировать" onclick="editRoom(event,this.href,' . $r . '); return false;"><i class="fa fa-edit"></i></a>';
                } else
                    $urlEdit = '';
                return '<a href="' . $url . '" class="btn btn-xs btn-default" onclick="viewRoom(event,this.href,' . $r . '); return false;" title="Просмотр"><i class="fa fa-eye"></i></a>' . $urlEdit;
            })
            ->rawColumns(['action', 'wifi', 'tvset', 'aircond', 'freezer', 'active'])
            ->make(true);
    }

    function getForm(Request $request, $id = null)
    {
        if (empty($id))
            if (\Auth::user()->can('add_room')) return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');
            else
            if (\Auth::user()->can('edit_room')) return redirect('dashboard')->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus', 'error');

        $this->data['pageModule'] = 'rooms';

        $lang = \Session::get('lang', 'en');
        $this->data['types'] = \DB::table('room_types')->selectRaw("id,$lang as name")->get();

        if ($id) $id = cryptId($id, 'dec');

        $where = '1=2';
        if (\Auth::user()->can('own_room')) $where = 'rooms.hotel_id=' . \Auth::user()->hotel_id;
        if (\Auth::user()->can('all_room')) $where = '1=1';

        $this->data['row'] = \DB::table('rooms')->where('id', $id)->whereRaw($where)->first();
        if (empty($row))
            $this->data['hotel'] = \DB::table('hotels')->select('id', 'name')->where('id', \Session::get('hid', \Auth::user()->id_hotel))->first();
        else
            $this->data['hotel'] = \DB::table('hotels')->select('id', 'name')->where('id', $this->data['row']->hotel_id)->first();

        return view('room.form', $this->data);
    }
}
