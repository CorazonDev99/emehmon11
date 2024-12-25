<?php

namespace App\Http\Controllers;

use App\Jobs\LogUserActionJob;
use App\Repository\PersonInfo;
use ClickHouseDB\Client;

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
        $grp_id = \Session::get('rid', \Auth::user()->roles->pluck('id')->first()) * 1;
        $this->data['showHotel'] = $grp_id == 6 || $grp_id == 8;

        $children = \DB::table('tb_children')
            ->join('tb_listok', 'tb_listok.id', '=', 'tb_children.id_listok')
            ->select(
                'tb_children.id_listok as id',
                'tb_children.name as child_name',
                'tb_children.gender as child_gender',
                'tb_children.dateBirth as child_dateBirth'
            )
            ->get();

        $this->data['children'] = $children;
        $rooms = \DB::table('tb_listok')
            ->join('tb_listok_rooms', 'tb_listok.id', '=', 'tb_listok_rooms.id_reg')
            ->join('tb_rooms', 'tb_listok_rooms.id_room', '=', 'tb_rooms.id')
            ->join('tb_room_types', 'tb_room_types.id', '=', 'tb_rooms.id_room_type')
            ->where('tb_rooms.active', "1")
            ->where('tb_rooms.id_hotel', 12)
            ->whereRaw('tb_listok.id_hotel = tb_listok_rooms.id_hotel')
            ->distinct()
            ->select('tb_rooms.id as room_id', 'tb_rooms.room_numb as room_number', 'tb_room_types.ru as room_type')
            ->get();

        $hotels = \DB::table('tb_hotels')->select(['id', 'name', 'id_region'])->get();


        return view('listok.index', $this->data, compact('rooms', 'hotels'));
    }


    public function getCheckout()
    {
        $grp_id = \Session::get('rid', \Auth::user()->roles->pluck('id')->first()) * 1;
        $this->data['showHotel'] = $grp_id == 6 || $grp_id == 8;
        return view('listok.checkout', $this->data);
    }


    public function create()
    {
        $rooms = \DB::table('tb_listok')
            ->join('tb_listok_rooms', 'tb_listok.id', '=', 'tb_listok_rooms.id_reg')
            ->join('tb_rooms', 'tb_listok_rooms.id_room', '=', 'tb_rooms.id')
            ->join('tb_room_types', 'tb_room_types.id', '=', 'tb_rooms.id_room_type')
            ->where('tb_rooms.active', "0")
            ->where('tb_rooms.id_hotel', 12)
            ->whereRaw('tb_listok.id_hotel = tb_listok_rooms.id_hotel')
            ->distinct()
            ->select('tb_rooms.id as room_id', 'tb_rooms.room_numb as room_number', 'tb_room_types.ru as room_type')
            ->get();
        return view('listok.form', compact('rooms'));
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
            ->leftjoin('tb_feedbacks', 'tb_feedbacks.pspNumber', '=', 'tb_listok.passportNumber')
            ->join('tb_passporttype', 'tb_listok.id_passporttype', '=', 'tb_passporttype.id')
            ->join('tb_guests', 'tb_listok.id_guest', '=', 'tb_guests.id')
            ->join('tb_visittype', 'tb_listok.id_visitType', '=', 'tb_visittype.id')
            ->join('tb_users', 'tb_listok.entry_by', '=', 'tb_users.id')
            ->join('tb_citizens', 'tb_listok.id_citizen', '=', 'tb_citizens.id')
            ->leftJoin('tb_visa', 'tb_visa.id', '=', 'tb_listok.id_visa')
            ->join('tb_hotels', 'tb_users.id_hotel', '=', 'tb_hotels.id')
            ->join('tb_region as rr', 'rr.id', '=', 'tb_hotels.id_region')
            ->selectRaw(
                "'' AS `empty`,
                tb_listok.id,
                tb_listok.regNum,
                tb_listok.propiska AS room,
                tb_listok.id_citizen AS ctz,
                tb_citizens.SP_NAME03 AS ctzn,
                tb_listok.wdays,
                tb_listok.payed,
                tb_listok.tag,
                tb_hotels.name AS htl,
                rr.name AS region,
                DATE_FORMAT(tb_listok.dateVisitOn, '%d-%m-%Y %H:%i') AS dt,
                tb_passporttype.name AS passportType,
                tb_guests.guesttype,
                tb_visittype.name AS visittype,
                CONCAT(tb_listok.surname, ' ', tb_listok.firstname, ' ', tb_listok.lastname) AS guest,
                CONCAT(UPPER(LEFT(tb_users.first_name, 1)), '. ', tb_users.last_name) AS adm,
                DATE_FORMAT(tb_listok.datebirth, '%d-%m-%Y') AS datebirth,
                tb_listok.amount,
                tb_visa.name AS tb_visa,
                tb_listok.visanumber AS tb_visanm,
                tb_listok.PassportIssuedBy,
                tb_listok.datePassport,
                CONCAT(tb_listok.passportSerial, tb_listok.passportNumber) AS passport_full,
                tb_listok.datevisaon AS tb_visafrom,
                tb_listok.datevisaoff AS tb_visato,
                tb_listok.kppnumber,
                tb_listok.datekpp,
                tb_feedbacks.text,
                tb_listok.id_citizen,
                tb_listok.passportSerial,
                tb_listok.passportNumber,
                tb_listok.entry_by,
                DATEDIFF(NOW(), tb_listok.datevisaoff) AS expired"
            )
            ->where(function($query) {
                $query->whereNull('tb_listok.datevisitoff')
                      ->orWhereRaw('DATEDIFF(NOW(), tb_listok.datevisitoff) >= 0');
            })
            ->orderBy("tb_listok.id");


        if ($regNum) {
            $d->where('tb_listok.regNum', 'LIKE', "$regNum%");
        }

        if ($room) {
            $d->where('tb_listok.propiska', 'LIKE', "$room%");
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
                return number_format($row->amount, 2, ',', ' ');
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
        \DB::table('tb_listok')->where('id', $tb_listok)->update(['regnum' => $data['regnum']]);
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

        if (empty($ids)) {
            return response()->json(['status' => 'error', 'message' => 'Нет выбранных элементов для Checkout.'], 400);
        }


        foreach ($ids as $id) {
            \DB::table('tb_listok')->where('id', $id)->update([
                'datevisitoff' => now()->subDay()->format('Y-m-d H:i:s'),
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



                    $this->client->insert('tb_listok_checkout', [$checkoutData]);
                    $logData = [
                        'id' => (string) \Str::uuid(),
                        'user_id' => auth()->id(),
                        'user_name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                        'hotel_id' => $tb_listok->id_hotel,
                        'hotel_name' => $tb_listok->hotel_name ?? '',
                        'data' => json_encode($checkoutData),
                        'modul_id' => 2,
                        'modul' => 'Checkout',
                        'event' => 'User checked out',
                        'ip_address' => request()->ip(),
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'dt' => now()->subDay()->format('Y-m-d'),
                    ];

                    \Log::info('LogData для LogUserActionJob', $logData);
                    LogUserActionJob::dispatch($logData);

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

        $data = [
            'pinfl' => $request->get('pinfl'),
            'passportIssuedBy' => $request->get('passportissuedby'),
            'surname' => $request->get('surname'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'id_country' => $request->get('id_country'),
            'id_countryFrom' => 1,
            'id_passporttype' => 1,
            'dateVisitOn' => $request->get('datevisiton'),
            'regNum' => '000000',
            'datebirth' => $request->get('datebirth'),
            'id_citizen' => 1,
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
            'entry_by' => 1,
            'created_at' => now(),
            'wdays' => $request->get('wdays'),
            'lived_days' => 2,
            'out_by' => 1,
            'payed' => $request->get('payed'),
            'id_hotel' => 1,
            'id_region' => 1,
            'tag' => '',
            'paytp' => '1',
            'id_person' => 1,
        ];

        if ($data) {
            \DB::table('tb_listok')->insert($data);
            session()->flash('success', 'Данные успешно сохранены');
            return response()->json(['status' => 'success']);
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


    public function update(Request $request)
    {
        $data = $request->all();
        if (empty($data['id'])) {
            return response()->json(['error' => 'Не передан идентификатор регистрации.'], 400);
        }

        $affected = \DB::table('tb_listok')
            ->where('id', $data['id'])
            ->update([
                'pinfl' => $data['pinfl'],
                'datePassport' => $data['datePassport'],
                'PassportIssuedBy' => $data['passportissuedby'],
                'surname' => $data['surname'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'id_citizen' => $data['id_citizen'],
                'sex' => $data['sex'],
                'dateVisitOn' => $data['datevisiton'],
                'propiska' => $data['room_number'],
                'wdays' => $data['stay_days'],
                'id_visitType' => $data['id_visitType'],
                'id_visa' => $data['id_visa'],
                'kppNumber' => $data['kpp_number'],
                'dateKPP' => $data['kpp_date'],
                'paytp' => $data['payment_status'],
                'amount' => $data['payment_amount'],
                'id_guest' => $data['id_guest']
            ]);

        if ($affected) {
            $updatedRecord = \DB::table('tb_listok')->where('id', $data['id'])->first();

            session()->flash('success', 'Данные успешно обновлены');
            return response()->json(['status' => 'success', 'updated_data' => $updatedRecord]);
        } else {
            return response()->json(['error' => 'Запись не найдена'], 400);
        }
    }

    public function moveToRoom(Request $request)
    {
        $guestIds = $request->input('guest_ids');
        $newRoom = $request->input('room_number');

        if (empty($guestIds) || !$newRoom) {
            return response()->json([
                'status' => 'error',
                'message' => 'Необходимо выбрать гостей и номер комнаты.'
            ]);
        }

        \DB::table('tb_listok')
            ->whereIn('id', $guestIds)
            ->update(['propiska' => $newRoom]);

        return response()->json([
            'status' => 'success',
            'message' => 'Гости успешно перемещены.'
        ]);
    }

    public function statusPayment(Request $request)
    {
        $guestId = $request->input('guest_id');
        $newpayment = $request->input('payment');

        if (empty($guestId) || !$newpayment) {
            return response()->json(['status' => 'error', 'message' => 'Необходимо написать сумму!']);
        }

        \DB::table('tb_listok')
            ->where('id', $guestId)
            ->update(['amount' => $newpayment]);

        return response()->json(['status' => 'success', 'message' => 'Успешно обновлено!.']);
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

        \DB::table('tb_listok')
            ->whereIn('id', $guestIds)
            ->update(['tag' => '']);

        return response()->json(['success' => true, 'message' => 'Теги успешно удалены.']);
    }


    public function extendVisa(Request $request)
    {
        $guestId = $request->input('guest_id');

        $request->validate([
            'dateVisaOn' => 'required|date',
            'dateVisaOff' => 'required|date|after_or_equal:dateVisaOn',
        ]);

        \DB::table('tb_listok')
            ->where('id', $guestId)
            ->update([
                'dateVisaOn' => $request->dateVisaOn,
                'dateVisaOff' => $request->dateVisaOff,
            ]);

        return response()->json(['success' => true]);
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
    
        $exists = \DB::table('tb_feedbacks')
            ->where('id_citizen', $request->id_citizen)
            ->where('pspSerial', $request->passportSerial)
            ->where('pspNumber', $request->passportNumber)
            ->exists();
    
        if ($exists) {
            \DB::table('tb_feedbacks')
                ->where('id_citizen', $request->id_citizen)
                ->where('pspSerial', $request->passportSerial)
                ->where('pspNumber', $request->passportNumber)
                ->update($data);
            
            return response()->json(['success' => true, 'message' => 'Отзыв успешно обновлён!']);
        } else {
            \DB::table('tb_feedbacks')->insert($data);
    
            return response()->json(['success' => true, 'message' => 'Отзыв успешно добавлен!']);
        }
    }
    
    

    
}



