<?php

namespace App\Http\Controllers;

use App\Repository\PersonInfo;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SelfListokController extends Controller
{
    public function index()
    {
        return view('selflistok.index');
    }

    public function edit()
    {

        $id = session('editRowData')['id'];
        $rowdata = \DB::table('tb_self_listok')
            ->join('countries', 'tb_self_listok.id_country', '=', 'countries.country_id')
            ->join('tb_citizens', 'tb_self_listok.id_countryFrom', '=', 'tb_citizens.id')
            ->leftJoin('tb_self_children', 'tb_self_children.id_listok', '=', 'tb_self_listok.id')
            ->join('tb_visittype', 'tb_self_listok.id_visitType', '=', 'tb_visittype.id')
            ->join('tb_guests', 'tb_self_listok.id_guest', '=', 'tb_guests.id')
            ->leftJoin('tb_visa', 'tb_self_listok.id_visa', '=', 'tb_visa.id')
            ->where('tb_self_listok.id', $id)
            ->select('tb_self_listok.*', 'countries.country_name', 'tb_citizens.sp_name04', "tb_visittype.name_uz", 'tb_visa.name as visa_name', 'tb_guests.guesttype_uz as guesttype_name', "tb_self_children.name as children_name", "tb_self_children.gender as gender", "tb_self_children.dateBirth as children_dateBirth")
            ->first();

        return view('selflistok.edit', compact('rowdata'));
    }


    public function getData(Request $request)
    {
        if (!$request->ajax()) {
            return abort(404, 'Данную ссылку невозможно открыть в новом окне!');
        }

        // Build the base query
        $query = DB::table('tb_self_listok')
            ->join('tb_hotels', 'tb_self_listok.id_hotel', '=', 'tb_hotels.id')
            ->join('tb_citizens', 'tb_self_listok.id_citizen', '=', 'tb_citizens.id')
            ->select([
                'tb_self_listok.id',
                'tb_self_listok.regNum as reg_num',
                'tb_self_listok.summa',
                'tb_self_listok.dateVisitOn as visit_date',
                'tb_self_listok.wdays as stay_days',
                'tb_hotels.name as hotel_name',
                'tb_hotels.address as hotel_address',
                'tb_citizens.id as ctz',
                'tb_citizens.SP_NAME03 as ctzn',
                'tb_self_listok.surname',
                'tb_self_listok.firstname',
                'tb_self_listok.lastname',
                'tb_self_listok.passportSerial',
                'tb_self_listok.passportNumber',
                // Add the virtual columns for sorting
                \DB::raw('CONCAT(tb_self_listok.surname, " ", tb_self_listok.firstname, " ", tb_self_listok.lastname) as full_name'),
                \DB::raw('CONCAT(tb_self_listok.passportSerial, " ", tb_self_listok.passportNumber) as pass_sn')
            ]);

        // Return DataTables response
        return DataTables::of($query)
            ->addColumn('reg_num', function ($data) {
                return $data->reg_num;
            })
            ->addColumn('hotel_name', function ($data) {
                return $data->hotel_name;
            })
            ->addColumn('summa', function ($data) {
                return $data->summa;
            })
            ->addColumn('visit_date', function ($data) {
                return $data->visit_date;
            })
            ->addColumn('stay_days', function ($data) {
                return $data->stay_days;
            })
            ->addColumn('hotel_address', function ($data) {
                return $data->hotel_address;
            })
            ->addColumn('ctzn', function ($data) {
                return $data->ctzn;
            })
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->whereRaw('CONCAT(tb_self_listok.surname, " ", tb_self_listok.firstname, " ", tb_self_listok.lastname) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('pass_sn', function ($query, $keyword) {
                $query->whereRaw('CONCAT(tb_self_listok.passportSerial, " ", tb_self_listok.passportNumber) LIKE ?', ["%{$keyword}%"]);
            })
            ->editColumn('ctz', function ($row) {
                return '<img src="' . asset('uploads/flags/' . e($row->ctz) . '.png') . '" title="' . e($row->ctzn) . '" width="40px" height="24px" style="text-shadow: 1px 1px;border:1px solid #777;" />
            <span style="color:transparent;font-size:1px">' . e($row->ctzn) . '</span>';
            })
            ->rawColumns(['ctz'])
            ->make(true);
    }

    // check guest is exist in the border registration
    public function check(Request $request)
    {

        $data = $request->all();
        if (isset($data['id_citizen'])) $data['country'] = $data['id_citizen'];

        if (isset($data['passportNumber'])) {
            $data['passport'] = $data['passportNumber'];
            $data['psp'] = $data['passportNumber'];
            $data['dtb'] = $data['datebirth'];
        }

        $PersonID_SGB = PersonInfo::gotPersonID_SGB_REMOTE($data);

        if (!$PersonID_SGB)  return response()->json(['message' => 'Данный гость по Вашему запросу не найден! Просим вас сделать корректировку.', 'status' => 'error', 'person' => ['checking' => 0]]);
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
            'sex' => $PersonID_SGB['sex']
        ];

        $gotKOGG = PersonInfo::gotLAST_KOGG_REMOTE($PersonID_SGB['person_id']);

        if (!$gotKOGG) {
            return response()->json(['message' => 'Данный гость по Вашему запросу не найден в базе Погран.службы! Просим Вас сделать корректировку.', 'status' => 'error', 'person' => ['checking' => 0]]);
        }


        return response()->json(['message' => 'Гость найден!', 'status' => 'success', 'person' => array_merge($sgbArr, $gotKOGG)]);
    }


    public function getForm()
    {
        $userID = auth()->user()->id_hotel;

        $countries = \DB::table('tb_citizens')
            ->select(['id', 'SP_NAME03'])
            ->orderBy('SP_NAME03', 'asc')
            ->where('SP_ACTIVE', 1)->get();
        $guesttypes = \DB::table('tb_guests')->get();
        $visittype = \DB::table('tb_visittype')->get();
        $visatype = \DB::table('sp_visa_types')->where('sp_active', 1)->get();
        $hotel = \DB::table('tb_hotels')->where('id', $userID)->select(['id', 'address'])->first();

        return view('selflistok.form', ['countries' => $countries, 'visittype' => $visittype, 'visatype' => $visatype, 'guesttypes' => $guesttypes, 'hotel' => $hotel]);
    }

    // storing the data to tb_self_listok TABLE
    public function postStore(Request $request)
    {
        \Log::info($request);
        $user = auth()->user();
        \Log::info($user->id_hotel);
        if (!$user) return redirect()->guest('user/login');

        $rules = [
            'id_citizen' => 'required|integer',
            'id_passporttype' => 'required|integer',
            'datebirth' => [
                'required',
                'date_format:Y-m-d',
                'before:today',
                function ($attribute, $value, $fail) {
                    $age = Carbon::parse($value)->age;
                    if ($age <= 16) {
                        $fail('The age must be greater than 16 years old.');
                    }
                },
            ],
            'passportNumber' => 'required|string|max:50',
            'date_of_issue' => 'required|date_format:Y-m-d',
            'pass_given_by' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'id_born' => 'required|integer',
            'id_from_c' => 'required|integer',
            'sex' => 'required|in:1,2',
            'visit_time' => 'required|date_format:Y-m-d\TH:i',
            'id_visittype' => 'required|integer',
            'id_visatype' => 'required|integer',
            'visa_number' => 'required|string|max:50',
            'visa_by' => 'required|string|max:255',
            'visa_start' => 'required|date_format:Y-m-d',
            'visa_end' => 'required|date_format:Y-m-d|after:visa_start',  // Visa end must be after visa start date
            'kppNumber' => 'required|string|max:50',
            'date_kpp' => 'required|date_format:Y-m-d',
            'id_gesttype' => 'required|integer',
            'direction' => 'required|string|max:255',
            'children' => 'required|array|min:1',
            'children.*.full_name' => 'required|string|max:255',
            'children.*.birth_day' => 'required|date_format:Y-m-d|before:today',
            'children.*.gen' => 'required|in:M,W',
        ];

        $validator = Validator::make($request->all(), $rules);
        \DB::beginTransaction();

        try {
            // Save the main citizen data and get the inserted ID
            $selfListok = \DB::table('tb_self_listok')->insertGetId([
                'id_hotel' => $user->id_hotel,
                'entry_by' => $user->id,
                'id_region' => $user->id,
                'surname' => $request->surname,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'datebirth' => $request->datebirth,
                'id_country' => $request->id_born,
                'id_citizen' => $request->id_citizen,
                'id_countryFrom' => $request->id_from_c,
                'sex' => $request->sex,
                'dateVisitOn' => $request->visit_time,
                'id_visitType' => $request->id_visittype,
                'id_passporttype' => $request->id_passporttype,
                'passportNumber' => $request->passportNumber,
                'datePassport' => $request->date_of_issue,
                'visaNumber' => $request->visa_number,
                'dateVisaOn' => $request->visa_start,
                'dateVisaOff' => $request->visa_end,
                'a_route' => $request->direction,
                'mzrp' => 12.3,
                'prc' => 12.3,
                'PassportIssuedBy' => $request->pass_given_by,
                'visaIssuedBy' => $request->visa_by,
            ]);

            // Save children data
            foreach ($request->children as $childData) {
                \DB::table('tb_children')->insert([
                    'id_listok' => $selfListok,
                    'entry_by' => $user->id,
                    'name' => $childData['full_name'],
                    'dateBirth' => $childData['birth_day'],
                    'gender' => $childData['gen'],
                ]);
            }

            // Commit the transaction if everything is successful
            \DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Успешно сохранено ',
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            \DB::rollback();
            // Return the error response
            return response()->json([
                'error' => 'Something went wrong! Please try again later.',
                'message' => $e->getMessage(),
                \Log::info($e->getMessage())
            ], 500);
        }
    }

    public function show($regnum)
    {
        $regnum = base64_decode($regnum);
        return view('selflistok.show');
    }




    // public function datarow(Request $request)
    // {
    //     $rowData = $request->input('rowData');

    //     session(['editRowData' => $rowData]);

    //     return response()->json(['success' => true]);
    // }



    // public function getBookTable(Request $request)
    // {
    //     $data['start'] = $start = $request->input('start');
    //     $data['end'] = $end = $request->input('end');

    //     $data['dates'] = [];
    //     $data['month'] = [];

    //     for ($date = Carbon::parse($start); $date->lte(Carbon::parse($end)); $date->addDay()) {
    //         $data['dates'][] = $date->format('Y-m-d');
    //         if (!isset($data['month'][$date->format('m-Y')])) {
    //             $data['month'][$date->format('m-Y')] = 1;
    //         } else {
    //             $data['month'][$date->format('m-Y')]++;
    //         }
    //     }

    //     $data['room_types'] = collect(\DB::table('room_types')->get());

    //     foreach ($data['room_types'] as $room_type)
    //         $room_type->rooms = collect(\DB::table('rooms')->where('room_type_id', $room_type->id)->where('hotel_id', session('hid', auth()->user()->id_hotel))->orderBy('room_numb', 'asc')->get());

    //     // get tb_self_listok where datevisitoff and datevisiton between start and end or datevisitoff is null
    //     $data['tb_self_listoks'] = \DB::table('tb_self_listok')->where('id_hotel', session('hid', auth()->user()->id_hotel))
    //         ->join('tb_self_listok_rooms', 'tb_self_listok.id', '=', 'tb_self_listok_rooms.reg_id')
    //         ->where(function ($query) use ($start, $end) {
    //             $query->whereBetween('datevisitoff', [$start, $end])
    //                 ->orWhereBetween('datevisiton', [$start, $end])
    //                 ->orWhere('datevisitoff', null);
    //         })->select('tb_self_listok.regnum as tb_self_listok_id', 'tb_self_listok_rooms.room_id', 'datevisiton', 'datevisitoff')->get();

    //     return view('tb_self_listok.book-table', $data);
    // }





    public function postSave(Request $request)
    {
        $data = $request->except(['_token', 'room']);
        $data['entry_by'] = auth()->user()->id;
        $data['datevisiton'] = Carbon::parse($data['datevisiton'])->format('Y-m-d H:i:s');
        $data['datevisitoff'] = Carbon::parse($data['datevisitoff'])->format('Y-m-d H:i:s');
        $data['id_hotel'] = session('hid', auth()->user()->id_hotel);
        $data['id_region'] = session('rid', 11);
        //        $data['id_citizen'] = $data['id_citizen'];
        //        $data['entry_by'] = auth()->user()->id;

        $tb_self_listok = \DB::table('tb_self_listok')->insertGetId($data);

        $data['regnum'] = $tb_self_listok . '-' . $data['id_hotel'] . '-' . date('Y');
        \DB::table('tb_self_listok')->where('id', $tb_self_listok)->update(['regnum' => $data['regnum']]);
        if ($tb_self_listok) {
            $room = $request->input('room');
            \DB::table('tb_self_listok_rooms')->insert([
                'reg_id' => $tb_self_listok,
                'room_id' => $room,
                'hotel_id' => $data['id_hotel'],
            ]);
            return response()->json(['status' => 'success', 'message' => 'tb_self_listok created successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'tb_self_listok not created']);
    }

    public function getComboSelect(Request $request)
    {

        $params = $request->input('filter');
        $params = explode(':', $params);
        /*        $limit = explode(':',$limit);
                $parent = explode(':',$parent);*/

        $limit = $parent = [0];

        if (count($limit) >= 3) {
            $table = strtolower($params[0]);
            $condition = $limit[0] . " `" . $limit[1] . "` " . $limit[2] . " " . $limit[3] . " ";
            if (count($parent) >= 2) {
                //$row =  \DB::table($table)->where($parent[0],$parent[1])->get();
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
        // $row change keys to index massive numbers
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

    // public function postCheckout($regnum)
    // {
    //     $regnum = base64_decode($regnum);
    //     \DB::table('tb_self_listok')->where('regnum', $regnum)->update([
    //         // date minus one day
    //         'datevisitoff' => Carbon::parse(now())->subDay()->format('Y-m-d H:i:s'),
    //     ]);
    //     $tb_self_listok = \DB::table('tb_self_listok')->where('regnum', $regnum)->first();
    //     \DB::table('tb_self_listok_rooms')->where('reg_id', $tb_self_listok->id)->where('hotel_id', $tb_self_listok->id_hotel)->delete();
    //     return response()->json(['status' => 'success', 'message' => 'Checkout success Regnum: ' . $regnum]);
    // }




    public function savedata(Request $request)
    {
        $data = [
            'PassportIssuedBy' => $request->get('passportissuedby'),
            'surname' => $request->get('surname'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'id_country' => 1,
            'id_countryFrom' => 1,
            'id_passporttype' => 1,
            'dateVisitOn' => $request->get('datevisiton'),

            'regNum' => '000000',
            'datebirth' => '1900-01-01',
            'id_citizen' => 1,
            'sex' => 'M',
            'id_visitType' => 1,
            'dateVisitOff' => '1900-01-01',
            'passportSerial' => '',
            'passportNumber' => '000000',
            'datePassport' => '1900-01-01',
            'id_visa' => 1,
            'visaNumber' => '',
            'dateVisaOn' => '1900-01-01',
            'dateVisaOff' => '1900-01-01',
            'visaIssuedBy' => '',
            'kppNumber' => '',
            'dateKPP' => '1900-01-01',
            'id_guest' => 1,
            'amount' => 0.00,
            'entry_by' => 1,
            'a_id_region' => null,
            'a_id_district' => null,
            'a_address' => '',
            'a_route' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'wdays' => 0,
            'id_hotel' => 1,
            'id_region' => 1,
            'summa' => 0.00,
            'canceled' => null,
            'person_id' => null,
            'hsh' => null,
        ];

        if (\DB::table('tb_self_listok')->insert($data)) {
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

            \DB::table('tb_self_listok')->whereIn('id', $ids)->delete();

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
        $currentRecord = \DB::table('tb_self_listok')->where('id', $data['id'])->first();
        if ($currentRecord) {
            $isUpdated = false;
            foreach ($data as $key => $value) {
                if (isset($currentRecord->$key) && $currentRecord->$key != $value) {
                    $isUpdated = true;
                    break;
                }
            }
            if (!$isUpdated) {
                return response()->json(['status' => 'success', 'message' => 'Данные не были изменены']);
            }
        }

        $affected = \DB::table('tb_self_listok')
            ->where('id', $data['id'])
            ->update([
                'datePassport' => $data['passportissuedby'],
                'surname' => $data['surname'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'id_country' => $data['id_country'],
                'id_countryFrom' => $data['id_countryfrom'],
                'sex' => $data['sex'],
                'dateVisitOn' => $data['datevisiton'],
                'wdays' => $data['stay_days'],
                'id_visitType' => $data['id_visitType'],
                'id_visa' => $data['id_visa'],
                'kppNumber' => $data['kpp_number'],
                'dateKPP' => $data['kpp_date'],
                'summa' => $data['payment_amount'],
                'id_guest' => $data['id_guest']
            ]);

        if ($affected) {
            $updatedRecord = \DB::table('tb_self_listok')->where('id', $data['id'])->first();
            session()->flash('success', 'Данные успешно обновлены');
            return response()->json(['status' => 'success', 'updated_data' => $updatedRecord]);
        } else {
            return response()->json(['error' => 'Запись не найдена'], 400);
        }
    }


    // calculate tursbor
    public function getCalcpayment(Request $request)
    {
        $days = 0;
        if (! Auth::check()) return redirect()->guest('user/login');
        if (!$request->ajax())
            return abort(404, 'Данную ссылку невозможно открыть в новом окне!');
        if ($request->has('daysLive'))  $days = $request->input('daysLive') * 1.00;
        if ($request->has('dateVisitOn'))  $dt = $request->input('dateVisitOn');

        try {
            if ($dt) {

                $dt = date_format(DateTime::createFromFormat('Y-m-d\TH:i', $dt), 'Y-m-d');
                if ($dt >= '2021-09-01' && $dt < '2023-01-01') return response()->json(array('status' => 'success', 'summa' => 0));
                $ms = \DB::table('tb_minsalary')->select('summa')->whereRaw('dtFixed <= CURDATE()')->orderBy('dtFixed', 'desc')->first();
                $ms = $ms->summa * 1.00;
                $percent = 0.05;
                return response()->json(['status' => 'success', 'summa' => ($days * ($ms * $percent))]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'ДАТА ПРИБЫТИЯ: *  требуется'], 400);
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'summa' => 0], 400);
        }
    }
}
