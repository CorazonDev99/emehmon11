<?php

namespace App\Http\Controllers;

use App\Repository\PersonInfo;
use App\Services\AuditEvent;
use ClickHouseDB\Client;
use App\Services\ClickhouseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ListokController extends Controller
{

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'host' => env('CLICKHOUSE_HOST'),
            'port' => env('CLICKHOUSE_PORT'),
            'username' => env('CLICKHOUSE_USERNAME'),
            'password' => env('CLICKHOUSE_PASSWORD'),
        ]);
        $this->client->database(env('CLICKHOUSE_DATABASE'));
    }

    private $data = [];
    public function index()
    {
        $grp_id = \Session::get('rid', \Auth::user()->roles->pluck('id')->first());
        $this->data['showHotel'] = $grp_id == 6 || $grp_id == 8;

        $childrens = \DB::table('tb_children')
            ->join('tb_listok', 'tb_listok.id', '=', 'tb_children.id_listok')
            ->select(
                'tb_children.id_listok as id',
                'tb_children.name as child_name',
                'tb_children.gender as child_gender',
                'tb_children.dateBirth as child_dateBirth'
            )
            ->get();

        $rooms = \DB::table('tb_rooms')
            ->leftjoin('tb_listok_rooms', 'tb_listok_rooms.id_room', '=', 'tb_rooms.id')
            ->join('tb_room_types', 'tb_room_types.id', '=', 'tb_rooms.id_room_type')
            ->where('tb_rooms.active', "1")
            ->where('tb_rooms.id_hotel', session('hid', auth()->user()->id_hotel))
            ->groupBy('tb_listok_rooms.id_room', 'tb_rooms.id', 'tb_rooms.room_numb', 'tb_room_types.ru', 'tb_rooms.room_floor')
            ->select('tb_rooms.id as room_id', 'tb_rooms.room_numb as room_number', 'tb_room_types.ru as room_type', 'tb_rooms.room_floor', 'tb_rooms.beds', 'tb_rooms.tag',  \DB::raw('count(tb_listok_rooms.id_reg) as living_room'))
            ->get();

        $hotels = \DB::table('tb_hotels')->select(['id', 'name', 'id_region'])->get();
        $regions = \DB::table('tb_region')->select(['id', 'name'])->get();
        $ctzns = \DB::table('tb_citizens')->select(['id', 'SP_NAME04 as name'])->get();

        $feedbacks = \DB::table('tb_listok')
            ->leftJoin('tb_feedbacks', 'tb_feedbacks.pspNumber', '=', 'tb_listok.passportNumber')
            ->leftJoin('tb_hotels', 'tb_listok.id_hotel', '=', 'tb_hotels.id')
            ->leftJoin('tb_users', 'tb_listok.entry_by', '=', 'tb_users.id')
            ->select(
                \DB::raw('DATE_FORMAT(tb_feedbacks.created_at, "%d.%m.%Y %H:%i") AS created_at'),
                'tb_feedbacks.pspNumber',
                'tb_feedbacks.text',
                'tb_feedbacks.inBlack',
                'tb_hotels.name as htl',
                \DB::raw("CONCAT(UPPER(LEFT(tb_users.first_name, 1)), '. ', tb_users.last_name) as adm")
            )->distinct()
            ->whereNull('tb_listok.dateVisitOff')
            ->get();

        $room_history = \DB::table('tb_listok')
            ->Join('tb_room_history', 'tb_room_history.id_reg', '=', 'tb_listok.id')
            ->Join('tb_hotels', 'tb_room_history.id_hotel', '=', 'tb_hotels.id')
            ->Join('tb_users', 'tb_room_history.entry_by', '=', 'tb_users.id')
            ->select(
                'tb_room_history.id_reg',
                \DB::raw('DATE_FORMAT(tb_room_history.created_at, "%d.%m.%Y %H:%i") AS created_at'),
                'tb_room_history.events',
                'tb_hotels.name as htl',
                \DB::raw("CONCAT(UPPER(LEFT(tb_users.first_name, 1)), '. ', tb_users.last_name) as adm")
            )
            ->whereNull('tb_listok.dateVisitOff')
            ->get();


        $bron_room = \DB::table('tb_listok')
            ->leftJoin('bookings', 'bookings.id', '=', 'tb_listok.book_id')
            ->Join('tb_hotels', 'bookings.hotel_id', '=', 'tb_hotels.id')
            ->select(
                'bookings.id',
                \DB::raw("CONCAT(DATE_FORMAT(bookings.date_from, '%d.%m.%Y'), ' - ', DATE_FORMAT(bookings.date_to, '%d.%m.%Y')) AS bron_date"),
                'bookings.contact_phone AS phone_number',
                'bookings.org_name',
                'tb_hotels.name as htl',
            )->get();


        return view('listok.index', $this->data, compact('childrens','rooms', 'hotels', 'regions', 'ctzns', 'feedbacks', 'room_history', 'bron_room'));
    }


    public function getCheckout()
    {
        $grp_id = \Session::get('rid', \Auth::user()->roles->pluck('id')->first()) * 1;
        $this->data['showHotel'] = $grp_id == 6 || $grp_id == 8;
        return view('listok.checkout', $this->data);
    }


    public function create()
    {
        $rooms = \DB::table('tb_rooms')
            ->leftjoin('tb_listok_rooms', 'tb_listok_rooms.id_room', '=', 'tb_rooms.id')
            ->join('tb_room_types', 'tb_room_types.id', '=', 'tb_rooms.id_room_type')
            ->where('tb_rooms.active', "1")
            ->where('tb_rooms.id_hotel', session('hid', auth()->user()->id_hotel))
            ->groupBy('tb_listok_rooms.id_room', 'tb_rooms.id', 'tb_rooms.room_numb', 'tb_room_types.ru', 'tb_rooms.room_floor')
            ->select('tb_rooms.id as room_id', 'tb_rooms.room_numb as room_number', 'tb_room_types.ru as room_type', 'tb_rooms.beds', 'tb_rooms.room_floor', 'tb_rooms.tag',  \DB::raw('count(tb_listok_rooms.id_reg) as living_room'))
            ->get();
        return view('listok.form', compact('rooms'));
    }


    public function show(Request $request)
    {
        $book_id = $request->query('id');
        $rooms = \DB::table('tb_rooms')
            ->leftjoin('tb_listok_rooms', 'tb_listok_rooms.id_room', '=', 'tb_rooms.id')
            ->join('tb_room_types', 'tb_room_types.id', '=', 'tb_rooms.id_room_type')
            ->where('tb_rooms.active', "1")
            ->where('tb_rooms.id_hotel', session('hid', auth()->user()->id_hotel))
            ->groupBy('tb_listok_rooms.id_room', 'tb_rooms.id', 'tb_rooms.room_numb', 'tb_room_types.ru', 'tb_rooms.room_floor')
            ->select('tb_rooms.id as room_id', 'tb_rooms.room_numb as room_number', 'tb_room_types.ru as room_type', 'tb_rooms.beds', 'tb_rooms.room_floor', 'tb_rooms.tag',  \DB::raw('count(tb_listok_rooms.id_reg) as living_room'))
            ->get();

        return view('listok.show', compact('rooms', 'book_id'));
    }


    public function datarow(Request $request)
    {
        $rowData = $request->input('rowData');

        session(['editRowData' => $rowData]);

        return response()->json(['success' => true]);
    }



    public function getData(Request $request)
    {
        $regNum = $request->get('regNum', '');
        $room = $request->get('room', '');
        $tag = $request->get('tag', '');

        $d = \DB::table('tb_listok')
            ->leftJoin('tb_passporttype', 'tb_listok.id_passporttype', '=', 'tb_passporttype.id')
            ->leftJoin('tb_guests', 'tb_listok.id_guest', '=', 'tb_guests.id')
            ->leftJoin('tb_listok_hash', 'tb_listok_hash.id_reg', '=', 'tb_listok.id')
            ->leftJoin('tb_visittype', 'tb_listok.id_visitType', '=', 'tb_visittype.id')
            ->leftJoin('tb_users', 'tb_listok.entry_by', '=', 'tb_users.id')
            ->leftJoin('tb_citizens', 'tb_listok.id_citizen', '=', 'tb_citizens.id')
            ->leftJoin('tb_visa', 'tb_visa.id', '=', 'tb_listok.id_visa')
            ->leftJoin('tb_hotels', 'tb_users.id_hotel', '=', 'tb_hotels.id')
            ->leftJoin('tb_region', 'tb_region.id', '=', 'tb_hotels.id_region')
            ->selectRaw(
                "DISTINCT
                '' AS `empty`,
                tb_listok.id,
                tb_listok.regNum,
                tb_listok.propiska AS room,
                tb_listok.id_citizen AS ctz,
                tb_citizens.SP_NAME03 AS ctzn,
                tb_listok.wdays,
                tb_listok.payed,
                tb_listok.paytp,
                tb_listok.tag,
                tb_listok_hash.rowhash,
                tb_listok.id_hotel,
                tb_listok.surname,
                tb_listok.firstname,
                tb_listok.lastname,
                tb_users.last_name,
                tb_hotels.name AS htl,
                tb_hotels.address AS htl_address,
                tb_region.name AS region,
                DATE_FORMAT(tb_listok.dateVisitOn, '%d.%m.%Y %H:%i') AS dt,
                tb_passporttype.name AS passportType,
                tb_guests.guesttype,
                tb_visittype.name AS visittype,
                CONCAT(tb_listok.surname, ' ', tb_listok.firstname, ' ', tb_listok.lastname) AS guest,
                CONCAT(UPPER(LEFT(tb_users.first_name, 1)), '. ', tb_users.last_name) AS adm,
                DATE_FORMAT(tb_listok.datebirth, '%d.%m.%Y') AS datebirth,
                tb_listok.amount,
                tb_visa.name AS tb_visa,
                tb_listok.visaNumber AS tb_visanm,
                tb_listok.PassportIssuedBy,
                DATE_FORMAT(tb_listok.datePassport, '%d.%m.%Y') AS datePassport,
                CONCAT(tb_listok.passportSerial, tb_listok.passportNumber) AS passport_full,
                DATE_FORMAT(tb_listok.dateVisaOn, '%d.%m.%Y') AS tb_visafrom,
                DATE_FORMAT(tb_listok.dateVisaOff, '%d.%m.%Y') AS tb_visato,
                tb_listok.kppNumber,
                DATE_FORMAT(tb_listok.dateKPP, '%d.%m.%Y') AS dateKPP,
                tb_listok.id_citizen,
                tb_listok.passportSerial,
                tb_listok.passportNumber,
                tb_listok.entry_by,
                tb_listok.sex,
                tb_listok.book_id,
                tb_listok.updated_at,
                tb_listok.pinfl"

            )
            ->whereNull('tb_listok.dateVisitOff');


        #Глобальный поиск
        if ($request->filled('surname')) {
            $d->where('tb_listok.surname', 'like', '%' . $request->surname . '%');
        }
        if ($request->filled('firstname')) {
            $d->where('tb_listok.firstname', 'like', '%' . $request->firstname . '%');
        }
        if ($request->filled('lastname')) {
            $d->where('tb_listok.lastname', 'like', '%' . $request->lastname . '%');
        }
        if ($request->filled('datebirth')) {
            $dateBirth = \Carbon\Carbon::createFromFormat('d.m.Y', $request->datebirth)->format('Y-m-d');

            $d->whereDate('tb_listok.datebirth', '=', $dateBirth);
        }
        if ($request->filled('citizenship')) {
            $d->where('tb_listok.id_citizen', '=', $request->citizenship);
        }
        if ($request->filled('arrival_from') && $request->filled('arrival_to')) {
            $arrivalTo = \Carbon\Carbon::createFromFormat('d.m.Y', $request->arrival_to)->format('Y-m-d');
            $arrivalFrom = \Carbon\Carbon::createFromFormat('d.m.Y', $request->arrival_from)->format('Y-m-d');
            $d->whereBetween('tb_listok.dateVisitOn', [$arrivalFrom, $arrivalTo])
                  ->orWhereBetween('tb_listok.dateVisitOff', [$arrivalFrom, $arrivalTo]);
        }

        elseif ($request->filled('arrival_from')) {
            $arrivalFrom = \Carbon\Carbon::createFromFormat('d.m.Y', $request->arrival_from)->format('Y-m-d');
            $d->where('tb_listok.dateVisitOn', '>=', $arrivalFrom)
                  ->orWhere('tb_listok.dateVisitOff', '>=', $arrivalFrom);
        }
        if ($request->filled('passport_number')) {
            $d->whereRaw("CONCAT(tb_listok.passportSerial, tb_listok.passportNumber) = ?", [$request->passport_number]);
        }

        if ($request->filled('region')) {
            $d->where('tb_hotels.id_region', '=', $request->region);
        }
        if ($request->filled('hotel')) {
            $d->where('tb_listok.id_hotel', '=', $request->hotel);
        }


        #Быстрый поиск
        if ($regNum) {
            $d->where('tb_listok.regNum', '=', $regNum);
        }

        if ($room) {
            $d->where('tb_listok.propiska', '=', "$room");
        }

        if ($tag) {
            $d->where('tb_listok.tag', 'LIKE', "$tag%");
        }

        return DataTables::of($d)
            ->editColumn('ctz', function ($row) {
                return '<img src="' . asset('uploads/flags/' . $row->ctz . '.png') . '"
                        title="' . $row->ctzn . '"
                        width="40px" height="24px"
                        style="text-shadow: 1px 1px; border:1px solid #777;" />
                        <span style="color:transparent;font-size:1px">' . $row->ctzn . '</span>';
            })
            ->editColumn('amount', function ($row) {
                return number_format($row->amount, 2, '.', ' ');
            })
            ->rawColumns(['ctz'])
            ->make(true);
    }



    public function getDataCheckout(Request $request)
    {
        $rememberCriteria = [];

        $query = "
                    SELECT
                    '' AS empty,
                    tb_listok_checkout.id as id,
                    tb_listok_checkout.regNum,
                    tb_listok_checkout.propiska AS room,
                    tb_listok_checkout.id_citizen AS ctz,
                    tb_listok_checkout.SP_NAME03 AS ctzn,
                    tb_listok_checkout.wdays,
                    tb_listok_checkout.payed,
                    tb_listok_checkout.tag,
                    tb_listok_checkout.hotel_name AS htl,
                    formatDateTime(tb_listok_checkout.dateVisitOn, '%d.%m.%Y %H:%i') AS dt,
                    CONCAT(tb_listok_checkout.surname, ' ', tb_listok_checkout.firstname, ' ', tb_listok_checkout.lastname) AS guest,
                    CONCAT(upper(substring(tb_listok_checkout.first_name_user, 1, 1)), '. ', tb_listok_checkout.last_name_user) AS adm,
                    formatDateTime(tb_listok_checkout.datebirth, '%d.%m.%Y') AS datebirth,
                    tb_listok_checkout.amount,
                    tb_listok_checkout.visa_name AS tb_visa,
                    tb_listok_checkout.visaNumber AS tb_visanm,
                    tb_listok_checkout.dateVisaOn AS tb_visafrom,
                    tb_listok_checkout.dateVisaOff AS tb_visato,
                    tb_listok_checkout.kppNumber as kppnumber,
                    tb_listok_checkout.dateKPP as datekpp,
                    dateDiff('day', tb_listok_checkout.dateVisaOff, now()) AS expired
                    FROM tb_listok_checkout";

        if ($pn = $request->get('pn')) {
            $query .= " AND tb_listok_checkout.passportNumber = '$pn'";
            $rememberCriteria['pn'] = $pn;
        }

        if ($fname = $request->get('fName')) {
            $query .= " AND tb_listok_checkout.firstname LIKE '$fname%'";
            $rememberCriteria['fname'] = $fname;
        }

        if ($sname = $request->get('sName')) {
            $query .= " AND tb_listok_checkout.surname LIKE '$sname%'";
            $rememberCriteria['sname'] = $sname;
        }

        if ($lname = $request->get('lName')) {
            $query .= " AND tb_listok_checkout.lastname LIKE '$lname%'";
            $rememberCriteria['lname'] = $lname;
        }

        if ($ctz = $request->get('ctz')) {
            $query .= " AND tb_listok_checkout.id_citizen = $ctz";
            $rememberCriteria['ctz'] = $ctz;
        }

        if ($from = $request->get('from')) {
            $from = date('Y-m-d', strtotime(str_replace('-', '/', $from)));
            $query .= " AND tb_listok_checkout.datevisiton >= '$from 00:00:00'";
            $rememberCriteria['from'] = $from;
        }

        if ($till = $request->get('till')) {
            $till = date('Y-m-d', strtotime(str_replace('-', '/', $till)));
            $query .= " AND tb_listok_checkout.datevisiton <= '$till 23:59:59'";
            $rememberCriteria['till'] = $till;
        }

        session(['searchListovka' => $rememberCriteria]);

        $results = $this->client->select($query)->rows();

        return DataTables::of($results)
            ->editColumn('ctz', function ($row) {
                return '<img src="' . asset('uploads/flags/' . $row['ctz'] . '.png') . '" title="' . $row['ctzn'] . '" width="40px" height="24px" style="text-shadow: 1px 1px; border: 1px solid #777;" /><span style="color: transparent; font-size: 1px">' . $row['ctzn'] . '</span>';
            })
            ->editColumn('amount', function ($row) {
                return number_format($row['amount'], 2, ',', ' ');
            })
            ->setRowClass(function ($row) {
                if (!$row['tb_visato'] || $row['expired'] > 2) {
                    return '';
                } elseif ($row['expired'] > 0) {
                    return 'tb_visa-twodays';
                } elseif ($row['expired'] == 0) {
                    return 'tb_visa-lastday';
                }
                return 'tb_visa-expired';
            })
            ->rawColumns(['ctz'])
            ->make(true);
    }


    public function getForm()
    {
        return view('tb_listok.form');
    }

    public function postSave(Request $request)
    {
        $data = $request->except(['_token', 'room']);
        $data['entry_by'] = auth()->user()->id;
        $data['datevisiton'] = Carbon::parse($data['datevisiton'])->format('Y-m-d H:i:s');
        $data['datevisitoff'] = Carbon::parse($data['datevisitoff'])->format('Y-m-d H:i:s');
        $data['id_hotel'] = session('hid', auth()->user()->id_hotel);
        $data['id_region'] = session('rid', 11);

        $tb_listok = \DB::table('tb_listok')->insertGetId($data);

        $data['regnum'] = $tb_listok . '-' . $data['id_hotel'] . '-' . date('Y');
        \DB::table('tb_listok')->where('id', $tb_listok)->update(['regnum' => $data['regnum'], 'updated_at' => now()]);
        if ($tb_listok) {
            $room = $request->input('room');
            \DB::table('tb_listok_rooms')->insert([
                'reg_id' => $tb_listok,
                'room_id' => $room,
                'hotel_id' => $data['id_hotel'],
            ]);
            return response()->json(['status' => 'success', 'message' => 'tb_listok created successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'tb_listok not created']);
    }

    public function getComboSelect(Request $request)
    {

        $params = $request->input('filter');
        $params = explode(':', $params);


        $limit = $parent = [0];

        if (count($limit) >= 3) {
            $table = strtolower($params[0]);
            $condition = $limit[0] . " `" . $limit[1] . "` " . $limit[2] . " " . $limit[3] . " ";
            if (count($parent) >= 2) {
                $row =  \DB::select("SELECT * FROM " . $table . " " . $condition . " AND " . $parent[0] . " = '" . $parent[1] . "'"  . ($table == 'tb_hotels' ? ' order by name' : ''));
            } else {
                $row =  \DB::select("SELECT * FROM " . $table . " " . $condition . ($table == 'tb_hotels' ? ' order by name' : ''));
            }
        } else {

            $table = $params[0];
            if (count($parent) >= 2) {
                if ($table == 'tb_hotels')
                    $row = \DB::table($table)->where($parent[0], $parent[1])
                        ->select($params[1], $params[2])
                        ->orderby('name')->get();
                else
                    $row =  \DB::table($table)->select($params[1], $params[2])->where($parent[0], $parent[1])->get();
            } else {

                if ($table == 'tb_hotels')
                    $row = \DB::table($table)->select($params[1], $params[2])->orderby('name')->get();
                else
                    $row =  \DB::table($table)->select($params[1], $params[2])->get();
            }
        }
        $i = 0;
        $data = [];
        foreach ($row as $key => $value) {
            $k = 0;
            foreach ($value as $v) {
                $data[$i][$k] = $v;
                $k++;
            }
            $i++;
        }


        return $data;
    }

    public function postCheckout(Request $request)
    {
        $ids = $request->input('ids', []);
        $payment = $request->input('payment');
        $paytype = $request->input('paytype');
        $comment = $request->input('comment');
        $inblack = $request->input('inblack');
        if (empty($ids)) {
            return response()->json(['status' => 'error', 'message' => 'Нет выбранных элементов для Checkout.'], 400);
        }
        if ($paytype === null || $payment === null) {
            return response()->json(['status' => 'error', 'message' => 'Необходимо написать сумму!']);
        }


        \DB::table('tb_listok')
            ->whereIn('tb_listok.id', $ids)
            ->update([
                'paytp' => $paytype,
                'amount' => $payment,
                'updated_at' => now(),
            ]);


//        \DB::table('tb_feedbacks')
//            ->insert(['tb_feedbacks.text' => $comment, 'tb_feedbacks.inblack' => $inblack]);



        foreach ($ids as $id) {
            \DB::table('tb_listok')->where('id', $id)->update([
                'datevisitoff' => now()->subDay()->format('Y-m-d H:i:s'),
                'updated_at' => now()
            ]);


            $tb_listok = \DB::table('tb_listok')
                ->where('tb_listok.id', $id)
                ->join('tb_users', 'tb_listok.entry_by', '=', 'tb_users.id')
                ->join('tb_citizens', 'tb_listok.id_citizen', '=', 'tb_citizens.id')
                ->leftJoin('tb_visa', 'tb_visa.id', '=', 'tb_listok.id_visa')
                ->join('tb_hotels', 'tb_users.id_hotel', '=', 'tb_hotels.id')
                ->select(
                    'tb_listok.*',
                    'tb_users.first_name as first_name_user',
                    'tb_users.last_name as last_name_user',
                    'tb_citizens.SP_NAME03 as SP_NAME03',
                    'tb_visa.name as visa_name',
                    'tb_hotels.name as hotel_name'
                )
                ->first();

            try {
                if ($tb_listok) {
                    $checkoutData = [
                        'id' => $tb_listok->id,
                        'regNum' => $tb_listok->regNum,
                        'surname' => $tb_listok->surname,
                        'firstname' => $tb_listok->firstname,
                        'lastname' => $tb_listok->lastname ?? 'XXX',
                        'datebirth' => $tb_listok->datebirth,
                        'id_country' => $tb_listok->id_country,
                        'id_citizen' => $tb_listok->id_citizen,
                        'SP_NAME03' => $tb_listok->SP_NAME03,
                        'id_countryFrom' => $tb_listok->id_countryFrom,
                        'propiska' => $tb_listok->propiska ?? '',
                        'sex' => $tb_listok->sex ?? 'M',
                        'dateVisitOn' => $tb_listok->dateVisitOn,
                        'id_visitType' => $tb_listok->id_visitType ?? 1,
                        'id_passporttype' => $tb_listok->id_passporttype ?? 1,
                        'dateVisitOff' => $tb_listok->dateVisitOff,
                        'passportSerial' => $tb_listok->passportSerial ?? '',
                        'passportNumber' => $tb_listok->passportNumber,
                        'datePassport' => $tb_listok->datePassport,
                        'PassportIssuedBy' => $tb_listok->PassportIssuedBy,
                        'id_visa' => $tb_listok->id_visa,
                        'visa_name' => $tb_listok->visa_name ?? '',
                        'visaNumber' => $tb_listok->visaNumber,
                        'dateVisaOn' => $tb_listok->dateVisaOn,
                        'dateVisaOff' => $tb_listok->dateVisaOff,
                        'visaIssuedBy' => $tb_listok->visaIssuedBy,
                        'kppNumber' => $tb_listok->kppNumber ?? '',
                        'dateKPP' => $tb_listok->dateKPP,
                        'id_guest' => $tb_listok->id_guest ?? 1,
                        'amount' => $tb_listok->amount ?? 0.0,
                        'entry_by' => $tb_listok->entry_by,
                        'last_name_user' => $tb_listok->last_name_user ?? '',
                        'first_name_user' => $tb_listok->first_name_user ?? '',
                        'created_date' => now()->subDay()->format('Y-m-d'),
                        'created_time' => now()->subDay()->format('H:i:s'),
                        'updated_at' => $tb_listok->updated_at,
                        'wdays' => $tb_listok->wdays ?? 0,
                        'lived_days' => $tb_listok->lived_days ?? 0,
                        'out_by' => $tb_listok->out_by,
                        'payed' => $tb_listok->payed ?? 0,
                        'id_hotel' => $tb_listok->id_hotel,
                        'hotel_name' => $tb_listok->hotel_name ?? '',
                        'id_region' => $tb_listok->id_region,
                        'tag' => $tb_listok->tag ?? '',
                        'paytp' => $tb_listok->paytp ?? '1',
                        'id_person' => $tb_listok->id_person,
                        'pinfl' => $tb_listok->pinfl
                    ];

//                    $this->client->insert('tb_listok_checkout', [$checkoutData]);

                    \DB::table('tb_listok_rooms')
                        ->where('id_reg', $tb_listok->id)
                        ->where('id_hotel', $tb_listok->id_hotel)
                        ->delete();

                    \DB::table('tb_listok')->where('id', $id)->delete();
                }
            } catch (\Exception $e) {
                $failedData = [
                    'error_message' => $e->getMessage(),
                    'failed_data' => json_encode($checkoutData),
                    'created_at' => now(),
                ];

                \DB::table('clickhouse_failed')->insert([$failedData]);
            }
        }
        return response()->json(['status' => 'success', 'message' => 'Checkout успешно выполнен для выбранных элементов.']);
    }

    public function check(Request $request)
    {
        $data = $request->all();
        if (isset($data['citezenID'])) $data['country'] = $data['citezenID'];

        if (isset($data['passportNumber'])) {
            $data['passport'] = $data['passportNumber'];
            $data['psp'] = $data['passportNumber'];
            $data['dtb'] = $data['dtb'];
        }

        $PersonID_SGB = PersonInfo::gotPersonID_SGB_REMOTE($data);

        if (!$PersonID_SGB)  return response()->json(['message' => 'Данный гость по Вашему запросу не найден! Просим Вас сделать корректировку.', 'status' => 'error', 'person' => ['checking' => 0]]);
        if (!is_array($PersonID_SGB)) $PersonID_SGB = json_decode($PersonID_SGB, true);
        if (!(isset($PersonID_SGB['person_id']) && $PersonID_SGB['person_id'])) return response()->json(['message' => 'Данный гость по Вашему запросу не найден! Просим Вас сделать корректировку.', 'status' => 'error', 'person' => ['checking' => 0]]);

        $p_name = explode(" ", $PersonID_SGB['name']);
        $PersonID_SGB['surname'] = $p_name[0];
        $PersonID_SGB['firstname'] = isset($p_name[1]) ? $p_name[1] : 'XXX';
        $PersonID_SGB['lastname'] = implode(" ", array_slice($p_name, 2, 2));

        $sgbArr = [
            'person_id' => base64_encode($PersonID_SGB['person_id']),
            'checking' => 1,
            'firstname' => $PersonID_SGB['firstname'],
            'surname' => $PersonID_SGB['surname'],
            'lastname' => $PersonID_SGB['lastname'],
            'birth_date' => $PersonID_SGB['birth_date'],
            'visaNumber' => $PersonID_SGB['visaNumber'],
            'visa_id' => $PersonID_SGB['visa_id'],
            'dateKPP' => $PersonID_SGB['dateKPP'],
            'kppNumber' => $PersonID_SGB['kppNumber'],
            'dateVisaOn' => $PersonID_SGB['dateVisaOn'],
            'dateVisaOff' => $PersonID_SGB['dateVisaOff'],
            'visaIssuedBy' => $PersonID_SGB['visaIssuedBy'],
        ];

        $gotKOGG = PersonInfo::gotLAST_KOGG_REMOTE($PersonID_SGB['person_id']);

        if (!$gotKOGG) {
            return response()->json(['message' => 'Данный гость по Вашему запросу не найден в базе Погран.службы! Просим Вас сделать корректировку.', 'status' => 'error', 'person' => ['checking' => 0]]);
        }


        return response()->json(['message' => 'Гость найден!', 'status' => 'success', 'person' => array_merge($sgbArr, $gotKOGG)]);
    }


    public function savedata(Request $request)
    {
        $id_region = \DB::table('tb_hotels')->select('id_region')->where('id', auth()->user()->id_hotel)->first();

        $data = [
            'pinfl' => $request->get('pinfl'),
            'passportIssuedBy' => $request->get('passportissuedby'),
            'surname' => $request->get('surname'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'id_country' => $request->get('id_country'),
            'id_countryFrom' => $request->get('id_countryfrom'),
            'id_passporttype' => $request->get('id_passporttype'),
            'dateVisitOn' => $request->get('datevisiton'),
            'datebirth' => $request->get('datebirth'),
            'id_citizen' => $request->get('id_countryfrom'),
            'propiska' => $request->get('propiska'),
            'sex' => $request->get('sex'),
            'id_visitType' => $request->get('id_visitType'),
            'dateVisitOff' => null,
            'passportSerial' => $request->get('passportSerial'),
            'passportNumber' => $request->get('passportNumber'),
            'datePassport' => $request->get('datePassport'),
            'id_visa' => $request->get('id_visa'),
            'visaNumber' => $request->get('visaNumber'),
            'dateVisaOn' => $request->get('dateVisaOn'),
            'dateVisaOff' => $request->get('dateVisaOff'),
            'visaIssuedBy' => $request->get('visaIssuedBy'),
            'kppNumber' => $request->get('kppNumber'),
            'dateKPP' => $request->get('dateKPP'),
            'id_guest' => $request->get('id_guest'),
            'amount' => $request->get('amount'),
            'entry_by' => auth()->user()->id,
            'created_at' => now(),
            'wdays' => $request->get('wdays'),
            'lived_days' => $request->get('lived_days'),
            'payed' => $request->get('payed'),
            'id_hotel' => auth()->user()->id_hotel,
            'id_region' => $id_region->id_region,
            'id_person' => $request->get('id_person'),
            'book_id' => $request->get('book_id'),
        ];

        $id_listok = \DB::table('tb_listok')->insertGetId($data);

        if ($id_listok) {
            $rowhash = sha1($id_listok . $request->get('pinfl') . now());

            \DB::table('tb_listok_hash')->insert([
                'rowhash' => $rowhash,
                'id_reg' => $id_listok,
                'pinfl' => $request->get('pinfl'),
                'viewed_qty' => 0,
            ]);

            $children = $request->get('childrens', []);
            if ($children) {
                foreach ($children as $child) {
                    if (!empty($child['name']) || !empty($child['gender']) || !empty($child['birthday'])) {
                        $formattedBirthday = null;
                        if (!empty($child['birthday'])) {
                            $formattedBirthday = \DateTime::createFromFormat('d/m/Y', $child['birthday'])->format('Y-m-d');
                        }

                        $child_data = [
                            'name' => $child['name'],
                            'gender' => $child['gender'],
                            'dateBirth' => $formattedBirthday,
                            'id_listok' => $id_listok,
                        ];

                        \DB::table('tb_children')->insert($child_data);
                    }
                }
            }

            session()->flash('success', 'Данные успешно сохранены');
            return response()->json(['status' => 'success', 'rowhash' => $rowhash]);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return response()->json(['error' => 'Не выбраны элементы для удаления.'], 400);
        }

        try {

            \DB::table('tb_listok')->whereIn('id', $ids)->delete();

            return response()->json(['success' => 'Элементы успешно удалены.']);
        } catch (\Exception $e) {
            \Log::error("Ошибка при удалении элементов: " . $e->getMessage());

            return response()->json(['error' => 'Ошибка при удалении элементов.'], 500);
        }
    }


    public function moveToRoom(Request $request)
    {
        $guestId = $request->input('guest_id');
        $hotelId = $request->input('hotel_id');
        $entryBy = $request->input('entry_by');
        $oldRoom = $request->input('old_room_number');
        $newRoom = $request->input('room_number');
        $guest = $request->input('guest');

        if (!$guestId || !$newRoom) {
            return response()->json([
                'status' => 'error',
                'message' => 'Необходимо выбрать гостя и номер комнаты.'
            ]);
        }

        \DB::table('tb_listok')
            ->where('id', $guestId)
            ->update(['propiska' => $newRoom, 'updated_at' => now()]);

        $roomHistoryData = [
            'id_reg' => $guestId,
            'id_hotel' => $hotelId,
            'entry_by' => $entryBy,
            'events' => sprintf(
                "%s changed room number from %s to %s",
                $guest ?? 'Guest',
                $oldRoom ?? 'Unknown',
                $newRoom
            ),
            'created_at' => now(),
        ];

        \DB::table('tb_room_history')->insert($roomHistoryData);

        return response()->json([
            'status' => 'success',
            'message' => 'Гость успешно перемещен.'
        ]);
    }



    public function statusPayment(Request $request)
    {
        $guestIds = $request->input('guest_ids');
        $newPaymentStatus = $request->input('paymentStatus');
        $newPayment = $request->input('payment');

        if (empty($guestIds) || $newPayment === null || empty($newPaymentStatus)) {
            return response()->json(['status' => 'error', 'message' => 'Необходимо написать сумму!']);
        }

        try {
            $currentPayments = \DB::table('tb_listok')
                ->whereIn('id', $guestIds)
                ->select('id', 'payed', 'amount')
                ->get()
                ->keyBy('id');

            \DB::table('tb_listok')
                ->whereIn('id', $guestIds)
                ->update([
                    'payed' => $newPaymentStatus,
                    'amount' => $newPayment,
                    'updated_at' => now(),
                ]);

            foreach ($guestIds as $guestId) {
                if (isset($currentPayments[$guestId])) {
                    $currentData = $currentPayments[$guestId];

                    AuditEvent::add(
                        'Обновление платежа',
                        $guestId,
                        'tb_listok',
                        [
                            'old_payed' => $currentData->payed,
                            'new_payed' => $newPaymentStatus,
                            'old_amount' => $currentData->amount,
                            'new_amount' => $newPayment,
                        ]
                    );
                }
            }

            return response()->json(['status' => 'success', 'message' => 'Успешно обновлено!.']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении amount: ' . $e->getMessage(),
            ]);
        }
    }


    public function updateTag(Request $request)
    {
        $guestIds = $request->input('guest_ids');
        $tag = $request->input('tag');

        if (empty($guestIds) || !$tag) {
            return response()->json([
                'success' => false,
                'message' => 'Необходимо выбрать гостей и указать тег.'
            ]);
        }

        try {
            \DB::table('tb_listok')
                ->whereIn('id', $guestIds)
                ->update(['tag' => $tag]);

            foreach ($guestIds as $guestId) {
                AuditEvent::add(
                    'Присвоил тег',
                    $guestId,
                    'tb_listok',
                    ['tag' => $tag]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Тег успешно обновлен.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении тега: ' . $e->getMessage()
            ]);
        }
    }


    public function deleteTag(Request $request)
    {
        $guestIds = $request->input('guest_ids');

        if (empty($guestIds)) {
            return response()->json(['success' => false, 'message' => 'Не выбраны гости для удаления тега.']);
        }
        foreach ($guestIds as $guestId) {
            $currentTag = \DB::table('tb_listok')
                ->where('id', $guestId)
                ->value('tag');

            \DB::table('tb_listok')
                ->where('id', $guestId)
                ->update(['tag' => '', 'updated_at' => now()]);

            AuditEvent::add(
                'Удалить тег',
                $guestId,
                'tb_listok',
                ['tag' => $currentTag]
            );
        }

        return response()->json(['success' => true, 'message' => 'Теги успешно удалены.']);
    }


    public function extendVisa(Request $request)
    {
        $guestId = $request->input('guest_id');

        $request->validate([
            'dateVisaOn' => 'required|date',
            'dateVisaOff' => 'required|date|after_or_equal:dateVisaOn',
        ]);

        $currentVisaDates = \DB::table('tb_listok')
            ->where('id', $guestId)
            ->select('dateVisaOn', 'dateVisaOff')
            ->first();

        try {
            $dateVisaOn = \Carbon\Carbon::createFromFormat('d.m.Y', $request->dateVisaOn)->format('Y-m-d');
            $dateVisaOff = \Carbon\Carbon::createFromFormat('d.m.Y', $request->dateVisaOff)->format('Y-m-d');

            \DB::table('tb_listok')
                ->where('id', $guestId)
                ->update([
                    'dateVisaOn' => $dateVisaOn,
                    'dateVisaOff' => $dateVisaOff,
                    'updated_at' => now(),
                ]);

            AuditEvent::add(
                'Продление визы',
                $guestId,
                'tb_listok',
                [
                    'old_dateVisaOn' => $currentVisaDates->dateVisaOn ?? null,
                    'old_dateVisaOff' => $currentVisaDates->dateVisaOff ?? null,
                    'new_dateVisaOn' => $dateVisaOn,
                    'new_dateVisaOff' => $dateVisaOff,
                ]
            );

            return response()->json(['success' => true, 'message' => 'Виза успешно продлена.']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при продлении визы: ' . $e->getMessage(),
            ]);
        }
    }



    public function feedBack(Request $request)
    {
        $data = [
            'id_citizen' => $request->id_citizen,
            'pspSerial' => $request->passportSerial,
            'pspNumber' => $request->passportNumber,
            'text' => $request->feedback,
            'inBlack' => ($request->blacklist === 'yes') ? 1 : 0,
            'entry_by' => $request->entry_by,
            'person_id' => $request->person_id,
            'created_at' => now(),
        ];

        \DB::table('tb_feedbacks')->insert($data);

        return response()->json([
            'success' => true,
            'message' => 'Отзыв успешно добавлен!',
            'data' => [
                'created_at' => $data['created_at'],
                'feedback' => $data['text'],
            ],
        ]);
    }


    public function getIdentifyQr($hash)
    {
        $hashRecord = \DB::table('tb_listok_hash')
            ->where('rowhash', $hash)
            ->first();

        if (!$hashRecord) {
            return abort(404, 'Hash not found!');
        }

        $id_reg = $hashRecord->id_reg;

        $row = \DB::table('tb_listok as s')
            ->join('tb_users as u', 's.entry_by', '=', 'u.id')
            ->join('tb_citizens as bc', 'bc.id', '=', 's.id_country')
            ->join('tb_citizens as ct', 'ct.id', '=', 's.id_citizen')
            ->join('tb_citizens as cf', 'cf.id', '=', 's.id_countryFrom')
            ->join('tb_hotels as h', 'h.id', '=', 's.id_hotel')
            ->join('tb_region as r', 'r.id', '=', 's.id_region')
            ->leftJoin('tb_visittype as visit', 'visit.id', '=', 's.id_visitType')
            ->leftJoin('tb_guests as guest', 'guest.id', '=', 's.id_guest')
            ->leftJoin('tb_passporttype as passport', 'passport.id', '=', 's.id_passportType')
            ->selectRaw(\DB::raw("
            s.*,
            ct.SP_NAME03 as ctzn,
            bc.SP_NAME03 as born_country,
            cf.SP_NAME03 as from_country,
            h.name as hotel_name,
            r.name as region,
            CONCAT(UCASE(SUBSTRING(u.first_name, 1, 1)), '. ', u.last_name) as administrator,
            visit.name as visit_type,
            guest.guesttype as guest_type,
            passport.name as passport_type
        "))
            ->where('s.id', $id_reg)
            ->first();

        if ($row) {
            $datachildren = \DB::table("tb_children")->where('id_listok', $row->id)->get();
            return view('listok.qrlistok', ['data' => $row, 'datachildren' => $datachildren]);
        }

        return abort(404, 'Data not found!');
    }


    public function getAuditLogs(Request $request)
    {
        $entity_id = $request->input('entity_id');
        if (!$entity_id) {
            return response()->json([
                'success' => false,
                'message' => 'Entity ID is required.',
            ], 400);
        }

        $clickhouse = app(ClickhouseService::class);

        $query = "
        SELECT
            event_type,
            hotel_name,
            user_name,
            entity_id,
            entity_type,
            event_time,
            changes
        FROM emehmon.audit_events
        WHERE entity_id = :entity_id
        ORDER BY event_time DESC
    ";

        try {
            $auditLogs = $clickhouse->select($query, ['entity_id' => $entity_id]);

            $formattedLogs = array_map(function ($log) {
                $log['changes'] = $this->formatChanges($log['changes'], $log['event_type']);
                $log['event_time'] = Carbon::parse($log['event_time'])->format('d.m.Y H:i');
                return $log;
            }, $auditLogs);

            return response()->json([
                'success' => true,
                'data' => $formattedLogs,
                'count' => count($formattedLogs),
            ]);
        } catch (\Exception $e) {
            \Log::error('ClickHouse query error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при выполнении запроса к ClickHouse.',
            ], 500);
        }
    }

    private function formatChanges(string $changes, string $event_type): string
    {
        try {
            $changesObj = json_decode($changes, true);
            $message = '';

            $paymentStatuses = [
                1 => 'Оплачен частично',
                2 => 'Оплачен полностью',
                3 => 'Не оплачен',
            ];

            if ($event_type === 'Присвоил тег') {
                $message = "Присвоил тег {$changesObj['tag']}";
            }


            if ($event_type === 'Удалить тег') {
                $message = "Удалено тег {$changesObj['tag']}";
            }

            if ($event_type === 'Продление визы') {
                $newDateVisaOn = Carbon::parse($changesObj['new_dateVisaOn'])->format('d.m.Y H:i');
                $newDateVisaOff = Carbon::parse($changesObj['new_dateVisaOff'])->format('d.m.Y H:i');
                $message = "Продлено виза с {$newDateVisaOn} до {$newDateVisaOff}";
            }

            if ($event_type === 'Обновление платежа') {
                $oldPayed = $paymentStatuses[$changesObj['old_payed']] ?? 'Не оплачено';
                $newPayed = $paymentStatuses[$changesObj['new_payed']] ?? 'Не оплачено';
                $oldAmount = number_format($changesObj['old_amount'], 2, ',', ' ');
                $newAmount = number_format($changesObj['new_amount'], 2, ',', ' ');

                $message = "Платежный статус: {$oldPayed} → {$newPayed}, Сумма: {$oldAmount} → {$newAmount}";
            }

            return $message ?: 'Изменения не указаны';
        } catch (\Exception $e) {
            return 'Ошибка при обработке изменений';
        }

    }

}



