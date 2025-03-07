<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class ReportAllController extends Controller
{

    public function index(Request $request)
    {

        $hotels = DB::table('tb_hotels')->select(['id', 'name', 'id_region', 'hotel_type_id'])->get();
        $regions = DB::table('tb_region')->select(['id', 'name'])->get();

        return view('reportall.index', compact('hotels', 'regions'));
    }

    public function getReportAll(Request $request) {
        \Log::info('Получен запрос на формирование отчёта:', $request->all());
        if (!$request->ajax()) {
            \Log::error('Некорректный запрос: не AJAX');
            return response()->json(['error' => 'Некорректный запрос'], 400);
        }

        $reports_id = $request->input('id_reports');
        $dateFrom = \DateTime::createFromFormat('d.m.Y', $request->input('dateFrom'));
        $dateTo = \DateTime::createFromFormat('d.m.Y', $request->input('dateTo'));
        switch ($reports_id) {
            case '777':
                $data = [];
                $result = $this->report_777($data);
                break;
            case '950':
                $data = [
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_950($data);
                break;

            case '210':
                $data = [
                    'year_num' => $request->input('year_num'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_210($data);
                break;

            case '801':
                $data = [
                    'id_region' => $request->input('id_region')
                ];
                $result = $this->report_801($data);
                break;

            case '200':
                $data = [
                    'year_num' => $request->input('year_num'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_200($data);
                break;

            case '205':
                $data = [
                    'year_num' => $request->input('year_num'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_205($data);
                break;

            case '10':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_01($data, \Session::get('gid') * 1);
                break;
            case '20':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_02($data, \Session::get('gid'));
                break;

            case '30':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_03($data);
                break;

            case '40':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_04($data);
                break;

            case '50':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_05($data);
                break;

            case '60':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_06($data);
                break;


            case '70':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_08($data);
                break;

            case '80':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel'),
                    'year_num' => $request->input('year_num'),
                    'month_num' => $request->input('month_num')
                ];
                $result = $this->report_09($data);
                break;

            case '90':
                $data = [
                    'year_num' => $request->input('year_num'),
                    'month_num' => $request->input('month_num')
                ];
                $result = $this->report_10($data);
                break;

            case '100':
                $data = [
                    'year_num' => $request->input('year_num')
                ];
                $result = $this->report_11($data);
                break;

            case '110':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_12($data);
                break;

            case '111':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_12_foreighn_only($data);
                break;

            case '120':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_13_with_statuses($data);
                break;

            case '130':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_14($data);
                break;

            case '140':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_15($data);
                break;

            case '150':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_16($data);
                break;


            case '800':
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_800($data);
                break;


            case '5010':

                if (!$dateFrom || !$dateTo) {
                    \Log::error('Неверный формат даты');
                    return response()->json(['error' => 'Неверный формат даты'], 400);
                }
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_visa5010($data);
                break;

            case '5015':
                $dateFrom = \DateTime::createFromFormat('d.m.Y', $request->input('dateFrom'));
                $dateTo = \DateTime::createFromFormat('d.m.Y', $request->input('dateTo'));
                if (!$dateFrom || !$dateTo) {
                    \Log::error('Неверный формат даты');
                    return response()->json(['error' => 'Неверный формат даты'], 400);
                }
                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y H:i:s'),
                    'dateTo' => $dateTo->format('d-m-Y H:i:s'),
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel')
                ];
                $result = $this->report_visa5015($data);
                break;

            case '5000': // Отчёт 5000
                if (!$request->has(['dateFrom', 'dateTo'])) {
                    \Log::error('Недостаточно данных для формирования отчёта 5000');
                    return response()->json(['error' => 'Недостаточно данных для формирования отчёта'], 400);
                }

                $dateFrom = \DateTime::createFromFormat('d.m.Y', $request->input('dateFrom'));
                $dateTo = \DateTime::createFromFormat('d.m.Y', $request->input('dateTo'));

                if (!$dateFrom || !$dateTo) {
                    \Log::error('Неверный формат даты');
                    return response()->json(['error' => 'Неверный формат даты'], 400);
                }

                $data = [
                    'dateFrom' => $dateFrom->format('d-m-Y'),
                    'dateTo' => $dateTo->format('d-m-Y')
                ];
                $result = $this->report_5000($data);
                break;


            case '6103':

                $data = [
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel'),
                    'year_num' => $request->input('year_num')
                ];
                $result = $this->report_6103($data);
                break;

            case '2000': // Отчёт 2000
                $data = [
                    'id_region' => $request->input('id_region', ''), // Если id_region не передан, используем пустую строку
                    'id_hotel' => $request->input('id_hotel', '')    // Если id_hotel не передан, используем пустую строку
                ];
                $result = $this->report_2000($data);
                break;


            case '1000': // Отчёт 1000
                if (!$request->has(['id_region', 'dateFrom', 'dateTo'])) {
                    \Log::error('Недостаточно данных для формирования отчёта 1000');
                    return response()->json(['error' => 'Недостаточно данных для формирования отчёта'], 400);
                }

                $dateFrom = \DateTime::createFromFormat('d.m.Y', $request->input('dateFrom'));
                $dateTo = \DateTime::createFromFormat('d.m.Y', $request->input('dateTo'));

                if (!$dateFrom || !$dateTo) {
                    \Log::error('Неверный формат даты');
                    return response()->json(['error' => 'Неверный формат даты'], 400);
                }

                $data = [
                    'id_region' => $request->input('id_region'),
                    'dateFrom' => $dateFrom->format('Y-m-d'),
                    'dateTo' => $dateTo->format('Y-m-d')
                ];
                $result = $this->report_1000($data);
                break;

            case '900':


                $data = [
                    'id_region' => $request->input('id_region'),
                    'id_hotel' => $request->input('id_hotel'),
                    'month_num' => $request->input('month_num'),
                    'year_num' => $request->input('year_num')
                ];
                $result = $this->report_900($data); // Пример для отчёта 900
                break;


            default:
                \Log::error('Неизвестный тип отчёта: ' . $reports_id);
                return response()->json(['error' => 'Неизвестный тип отчёта'], 400);
        }

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }


        return response()->json($result);
    }



    private function report_01($data, $gid) {
        try {
            $id_region = !empty($data['id_region']) ? "R.id=" . $data['id_region'] : "R.id is not null";
            $id_hotel = !empty($data['id_hotel']) ? "H.id in (" . $data['id_hotel'] . ")" : "H.id is not null";
            if ($gid == 13) {
                $sql = " select z.* from (select R.name as `Название региона:`,sum(case when L.id_citizen not in (173,231,245) then 1 else 0 end) as `Всего иностранцев`,sum(case when L.id_citizen not in (173,231,245) and L.sex = 'M' then 1 else 0 end) as `Мужчин (иностр)`,sum(case when L.id_citizen not in (173,231,245) and L.sex = 'W' then 1 else 0 end) as `Женщин (иностр)`,sum(case when L.id_citizen in (173,231,245) then 1 else 0 end) as `Всего местных`,sum(case when L.id_citizen in (173,231,245) and L.sex='M' then 1 else 0 end) as `Мужчин (мест)`,sum(case when L.id_citizen in (173,231,245) and L.sex='W' then 1 else 0 end) as `Женщин (мест)`,H.id as `hotel_id` from tb_listok L left join tb_users U on L.entry_by = U.id left join tb_hotels H on U.id_hotel=H.id and H.noshow=0 left join tb_region R on R.id = H.id_region where H.noshow=0 and H.hotel_status_id=1 and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s') and $id_region group by R.name) z order by z.`Название региона:`";
            }
            else {
                $sql = " select z.* from (select R.name as `Название региона:`, H.Name as `Гостиница`,sum(case when L.id_citizen not in (173,231,245) then 1 else 0 end) as `Всего иностранцев`,sum(case when L.id_citizen not in (173,231,245) and L.sex = 'M' then 1 else 0 end) as `Мужчин (иностр.)`,sum(case when L.id_citizen not in (173,231,245) and L.sex = 'W' then 1 else 0 end) as `Женщин (иностр.)`,sum(case when L.id_citizen in (173,231,245) then 1 else 0 end) as `Всего местных`,sum(case when L.id_citizen in (173,231,245) and L.sex='M' then 1 else 0 end) as `Мужчин (мест)`,sum(case when L.id_citizen in (173,231,245) and L.sex='W' then 1 else 0 end) as `Женщин (мест)`,H.id as `hotel_id` from tb_listok L left join tb_users U on L.entry_by = U.id left join tb_hotels H on U.id_hotel=H.id and H.noshow=0 left join tb_region R on R.id = H.id_region where H.noshow=0 and H.hotel_status_id=1 and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s') and $id_region and $id_hotel group by R.name, H.name) z order by z.`Название региона:`, z.`Гостиница`";

                if (empty($data['id_hotel'])) {
                    $sql = " select R.name as `Название региона:`, ifnull(H.hotelname,'TOTAL:')  as `Гостиница`,if(H.id=9000000 and isnull(z.`Всего иностранцев`),0, z.`Всего иностранцев`) as `Всего иностранцев`,if(H.id=9000000 and isnull(z.`Мужчин (иностр.)`),0, z.`Мужчин (иностр.)`) as `Мужчин (иностр)`,if(H.id=9000000 and isnull(z.`Женщин (иностр.)`),0, z.`Женщин (иностр.)`) as `Женщин (иностр)`,if(H.id=9000000 and isnull(z.`Всего местных`),0, z.`Всего местных`) as `Всего местных`,if(H.id=9000000 and isnull(z.`Мужчин (мест.)`),0, z.`Мужчин (мест.)`) as `Мужчин (мест)`,if(H.id=9000000 and isnull(z.`Женщин (мест.)`),0, z.`Женщин (мест.)`) as `Женщин (мест)`,H.id as `hotel_id` from tb_region R left join (select id, id_region, `name` as hotelname from tb_hotels where tb_hotels.noshow=0  AND tb_hotels.hotel_status_id=1 union select distinct 9000000, id_region, 'TOTAL:' from tb_hotels) H on R.id = H.id_region left join (select R.id as id_region, H.id as id_hotel, sum(case when L.id_citizen not in (173,231,245) then 1 else 0 end) as `Всего иностранцев`,sum(case when L.id_citizen not in (173,231,245) and L.sex = 'M' then 1 else 0 end) as `Мужчин (иностр.)`,sum(case when L.id_citizen not in (173,231,245) and L.sex = 'W' then 1 else 0 end) as `Женщин (иностр.)`, sum(case when L.id_citizen in (173,231,245) then 1 else 0 end) as `Всего местных`,sum(case when L.id_citizen in (173,231,245) and L.sex='M' then 1 else 0 end) as `Мужчин (мест.)`,sum(case when L.id_citizen in (173,231,245) and L.sex='W' then 1 else 0 end) as `Женщин (мест.)` from tb_listok L left join tb_users U on L.entry_by = U.id left join tb_hotels H on U.id_hotel = H.id and H.noshow=0 left join tb_region R on R.id = H.id_region where H.noshow=0 and H.hotel_status_id=1 and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s') and $id_region and $id_hotel group by R.id, H.id union ALL select R.id, 9000000, sum(case when L.id_citizen not in (173,231,245) then 1 else 0 end), sum(case when L.id_citizen not in (173,231,245) and L.sex = 'M' then 1 else 0 end),sum(case when L.id_citizen not in (173,231,245) and L.sex = 'W' then 1 else 0 end), sum(case when L.id_citizen in (173,231,245) then 1 else 0 end),sum(case when L.id_citizen in (173,231,245) and L.sex='M' then 1 else 0 end), sum(case when L.id_citizen in (173,231,245) and L.sex='W' then 1 else 0 end) from tb_listok L left join tb_users U on L.entry_by = U.id left join tb_hotels H on U.id_hotel = H.id and H.noshow=0 left join tb_region R on R.id = H.id_region where H.noshow=0 and H.hotel_status_id=1 and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s') and $id_region and $id_hotel group by R.id) z on z.id_region = R.id and z.id_hotel = H.id where $id_region and $id_hotel order by R.name, H.id, H.hotelname";
                }
            }
            \Log::info('Выполняемый SQL-запрос для report_01: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_01');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_01: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_777($data) {
        $sql['hotels'] = DB::select("select * from vw_hotels_list");
        $sql['local'] = DB::select("select * from vw_tourpayment_local");
        $sql['non_local'] = DB::select("select * from vw_tourpayment_non_local");
        $sql['st'] = DB::select("select * from vw_tourpayment_selftourists");

        return $sql;
    }


    private function report_02($data, $gid) {
        try {
            $id_region = !empty($data['id_region']) ? " and H.id_region=" . $data['id_region'] : "";
            $id_hotel = !empty($data['id_hotel']) ? " and H.id in (" . $data['id_hotel'] . ")" : "";

            if ($gid == 13) {
                $sql = "select replace(U.`№`,'90000000', '') as `№`, U.`СТРАНА`, U.`A1`, U.`A2`, U.`B1`, U.`B2`, U.`C1`, U.`C2`, U.`D1`, U.`D2`, U.`E`, U.`J1`, U.`J2`, U.`PV1`, U.`PV2`, U.`S1`, U.`S2`, U.`S3`, U.`T`, U.`TG`, U.`EX`, U.`ТРАНЗИТ`, U.`БЕЗ_ВИЗЫ`, U.`ИТОГО:`
                    from (select IF(x.`СТРАНА` = 'TOTAL:',90000000,@rows:=@rows+1) as `№`, x.`СТРАНА`, x.`A1`, x.`A2`, x.`B1`, x.`B2`, x.`C1`, x.`C2`, x.`D1`, x.`D2`, x.`E`, x.`J1`, x.`J2`, x.`PV1`, x.`PV2`, x.`S1`, x.`S2`, x.`S3`, x.`T`, x.`TG`, x.`EX`, x.`ТРАНЗИТ`, x.`БЕЗ_ВИЗЫ`, x.`ИТОГО:`
                          from (select C.SP_NAME01 as `СТРАНА`,
                                sum(case when L.id_visa = 17 then 1 else 0 end) as `A1`,
                                sum(case when L.id_visa = 18 then 1 else 0 end) as `A2`,
                                sum(case when L.id_visa = 8 then 1 else 0 end) as `B1`,
                                sum(case when L.id_visa = 9 then 1 else 0 end) as `B2`,
                                sum(case when L.id_visa = 19 then 1 else 0 end) as `C1`,
                                sum(case when L.id_visa = 20 then 1 else 0 end) as `C2`,
                                sum(case when L.id_visa = 1 then 1 else 0 end) as `D1`,
                                sum(case when L.id_visa = 2 then 1 else 0 end) as `D2`,
                                sum(case when L.id_visa = 12 then 1 else 0 end) as `E`,
                                sum(case when L.id_visa = 13 then 1 else 0 end) as `J1`,
                                sum(case when L.id_visa = 14 then 1 else 0 end) as `J2`,
                                sum(case when L.id_visa = 15 then 1 else 0 end) as `PV1`,
                                sum(case when L.id_visa = 16 then 1 else 0 end) as `PV2`,
                                sum(case when L.id_visa = 4 then 1 else 0 end) as `S1`,
                                sum(case when L.id_visa = 5 then 1 else 0 end) as `S2`,
                                sum(case when L.id_visa = 6 then 1 else 0 end) as `S3`,
                                sum(case when L.id_visa = 10 then 1 else 0 end) as `T`,
                                sum(case when L.id_visa = 11 then 1 else 0 end) as `TG`,
                                sum(case when L.id_visa = 22 then 1 else 0 end) as `EX`,
                                sum(case when L.id_visa=21 then 1 else 0 end) as `ТРАНЗИТ`,
                                sum(case when L.id_visa=0 or ISNULL(L.id_visa) then 1 else 0 end) as `БЕЗ_ВИЗЫ`,
                                count(L.id) as `ИТОГО:`
                                from tb_listok L
                                left join tb_citizens C on L.id_citizen = C.id
                                left join tb_users U on U.id = L.entry_by
                                left join tb_hotels H on H.id = U.id_hotel
                                where H.noshow=0 and H.hotel_status_id=1 and L.entry_by is not null
                                and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
                                $id_region
                                group by C.SP_NAME01
                                union all
                                select 'TOTAL:', sum(case when L.id_visa = 17 then 1 else 0 end),
                                sum(case when L.id_visa = 18 then 1 else 0 end),
                                sum(case when L.id_visa = 8 then 1 else 0 end),
                                sum(case when L.id_visa = 9 then 1 else 0 end),
                                sum(case when L.id_visa = 19 then 1 else 0 end),
                                sum(case when L.id_visa = 20 then 1 else 0 end),
                                sum(case when L.id_visa = 1 then 1 else 0 end),
                                sum(case when L.id_visa = 2 then 1 else 0 end),
                                sum(case when L.id_visa = 12 then 1 else 0 end),
                                sum(case when L.id_visa = 13 then 1 else 0 end),
                                sum(case when L.id_visa = 14 then 1 else 0 end),
                                sum(case when L.id_visa = 15 then 1 else 0 end),
                                sum(case when L.id_visa = 16 then 1 else 0 end),
                                sum(case when L.id_visa = 4 then 1 else 0 end),
                                sum(case when L.id_visa = 5 then 1 else 0 end),
                                sum(case when L.id_visa = 6 then 1 else 0 end),
                                sum(case when L.id_visa = 10 then 1 else 0 end),
                                sum(case when L.id_visa = 11 then 1 else 0 end),
                                sum(case when L.id_visa=22 then 1 else 0 end),
                                sum(case when L.id_visa=21 then 1 else 0 end),
                                sum(case when L.id_visa=0 or ISNULL(L.id_visa) then 1 else 0 end),
                                count(L.id)
                                from tb_listok L
                                left join tb_users U on U.id = L.entry_by
                                left join tb_hotels H on H.id = U.id_hotel
                                where H.noshow=0 and H.hotel_status_id=1 and L.entry_by is not null
                                and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
                                $id_region) x
                          join (select @rows:=0) w
                          order by x.`СТРАНА`) U
                    where U.`СТРАНА` > ''
                    order by U.`№`";
            } else {
                $sql = "select replace(U.`№`,'90000000', '') as `№`, U.`СТРАНА`, U.`A1`, U.`A2`, U.`B1`, U.`B2`, U.`C1`, U.`C2`, U.`D1`, U.`D2`, U.`E`, U.`J1`, U.`J2`, U.`PV1`, U.`PV2`, U.`S1`, U.`S2`, U.`S3`, U.`T`, U.`TG`, U.`EX`, U.`ТРАНЗИТ`, U.`БЕЗ_ВИЗЫ`, U.`ИТОГО:`
                    from (select IF(x.`СТРАНА` = 'TOTAL:',90000000,@rows:=@rows+1) as `№`, x.`СТРАНА`, x.`A1`, x.`A2`, x.`B1`, x.`B2`, x.`C1`, x.`C2`, x.`D1`, x.`D2`, x.`E`, x.`J1`, x.`J2`, x.`PV1`, x.`PV2`, x.`S1`, x.`S2`, x.`S3`, x.`T`, x.`TG`, x.`EX`, x.`ТРАНЗИТ`, x.`БЕЗ_ВИЗЫ`, x.`ИТОГО:`
                          from (select C.SP_NAME01 as `СТРАНА`,
                                sum(case when L.id_visa = 17 then 1 else 0 end) as `A1`,
                                sum(case when L.id_visa = 18 then 1 else 0 end) as `A2`,
                                sum(case when L.id_visa = 8 then 1 else 0 end) as `B1`,
                                sum(case when L.id_visa = 9 then 1 else 0 end) as `B2`,
                                sum(case when L.id_visa = 19 then 1 else 0 end) as `C1`,
                                sum(case when L.id_visa = 20 then 1 else 0 end) as `C2`,
                                sum(case when L.id_visa = 1 then 1 else 0 end) as `D1`,
                                sum(case when L.id_visa = 2 then 1 else 0 end) as `D2`,
                                sum(case when L.id_visa = 12 then 1 else 0 end) as `E`,
                                sum(case when L.id_visa = 13 then 1 else 0 end) as `J1`,
                                sum(case when L.id_visa = 14 then 1 else 0 end) as `J2`,
                                sum(case when L.id_visa = 15 then 1 else 0 end) as `PV1`,
                                sum(case when L.id_visa = 16 then 1 else 0 end) as `PV2`,
                                sum(case when L.id_visa = 4 then 1 else 0 end) as `S1`,
                                sum(case when L.id_visa = 5 then 1 else 0 end) as `S2`,
                                sum(case when L.id_visa = 6 then 1 else 0 end) as `S3`,
                                sum(case when L.id_visa = 10 then 1 else 0 end) as `T`,
                                sum(case when L.id_visa = 11 then 1 else 0 end) as `TG`,
                                sum(case when L.id_visa = 22 then 1 else 0 end) as `EX`,
                                sum(case when L.id_visa=21 then 1 else 0 end) as `ТРАНЗИТ`,
                                sum(case when L.id_visa=0 or ISNULL(L.id_visa) then 1 else 0 end) as `БЕЗ_ВИЗЫ`,
                                count(L.id) as `ИТОГО:`
                                from tb_listok L
                                left join tb_citizens C on L.id_citizen = C.id
                                left join tb_users U on U.id = L.entry_by
                                left join tb_hotels H on H.id = U.id_hotel
                                where H.noshow=0 and H.hotel_status_id=1 and L.entry_by is not null
                                and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
                                $id_region $id_hotel
                                group by C.SP_NAME01
                                union all
                                select 'TOTAL:', sum(case when L.id_visa = 17 then 1 else 0 end),
                                sum(case when L.id_visa = 18 then 1 else 0 end),
                                sum(case when L.id_visa = 8 then 1 else 0 end),
                                sum(case when L.id_visa = 9 then 1 else 0 end),
                                sum(case when L.id_visa = 19 then 1 else 0 end),
                                sum(case when L.id_visa = 20 then 1 else 0 end),
                                sum(case when L.id_visa = 1 then 1 else 0 end),
                                sum(case when L.id_visa = 2 then 1 else 0 end),
                                sum(case when L.id_visa = 12 then 1 else 0 end),
                                sum(case when L.id_visa = 13 then 1 else 0 end),
                                sum(case when L.id_visa = 14 then 1 else 0 end),
                                sum(case when L.id_visa = 15 then 1 else 0 end),
                                sum(case when L.id_visa = 16 then 1 else 0 end),
                                sum(case when L.id_visa = 4 then 1 else 0 end),
                                sum(case when L.id_visa = 5 then 1 else 0 end),
                                sum(case when L.id_visa = 6 then 1 else 0 end),
                                sum(case when L.id_visa = 10 then 1 else 0 end),
                                sum(case when L.id_visa = 11 then 1 else 0 end),
                                sum(case when L.id_visa=22 then 1 else 0 end),
                                sum(case when L.id_visa=21 then 1 else 0 end),
                                sum(case when L.id_visa=0 or ISNULL(L.id_visa) then 1 else 0 end),
                                count(L.id)
                                from tb_listok L
                                left join tb_users U on U.id = L.entry_by
                                left join tb_hotels H on H.id = U.id_hotel
                                where H.noshow=0 and H.hotel_status_id=1 and L.entry_by is not null
                                and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
                                $id_region $id_hotel) x
                          join (select @rows:=0) w
                          order by x.`СТРАНА`) U
                    where U.`СТРАНА` > ''
                    order by U.`№`";
            }

            \Log::info('Выполняемый SQL-запрос для report_02: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_02');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_02: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_03($data) {
        try {
            $id_region = !empty($data['id_region']) ? "R.id=" . $data['id_region'] : "R.id is not null";
            $id_hotel = !empty($data['id_hotel']) ? "H.id in (" . $data['id_hotel'] . ")" : "H.id is not null";
            $dtfrom = \DateTime::createFromFormat('d-m-Y H:i:s', $data['dateFrom']);
            $dtto = \DateTime::createFromFormat('d-m-Y H:i:s', $data['dateTo']);

            if (!$dtfrom || !$dtto) {
                \Log::error('Неверный формат даты: dateFrom=' . $data['dateFrom'] . ', dateTo=' . $data['dateTo']);
                return ['error' => 'Неверный формат даты'];
            }

            $dtfrom = $dtfrom->format('Y-m-d 00:00:00');
            $dtto = $dtto->format('Y-m-d 23:59:59');

            $sql = "select x.rgn as `Название региона`, x.dst as `Район`, x.hotel as `Гостиница`,
                x.hoteltype as `Тип сред разм`, x.hotel_rooms_fund as `Н/Ф`, x.hotel_rooms_beds as `Кол-во коек`,
                SUM(IF(x.ctz in (173,231,245),1,0)) as `Всего местных`,
                SUM(IF(x.ctz in (173,231,245), x.lived,0)) as `Местных (прожито дней)`,
                SUM(IF(x.ctz not in (173,231,245), 1,0)) as `Всего иностранцев`,
                SUM(IF(x.ctz not in (173,231,245), x.lived,0)) as `Иностранцы (прожито дней)`,
                hotel_id
                from (select z.region as `rgn`, z.dst, z.hotel, z.hoteltype, z.hotel_rooms_fund, z.hotel_rooms_beds,
                      z.rdt_in, z.rdt_out, z.ctz,
                      CASE WHEN (TIMESTAMPDIFF(HOUR,z.rdt_in,z.rdt_out)/24)+0.35<1 THEN 1 ELSE ROUND((TIMESTAMPDIFF(HOUR,z.rdt_in,z.rdt_out)/24)+0.35) END as lived,
                      z.hotel_id
                      from (select R.`name` as `region`, d.name as dst, H.`name` as `hotel`,
                            htp.`name` as `hoteltype`, H.hotel_rooms_fund, H.hotel_rooms_beds, c.id as `ctz`,
                            COALESCE(if(L.dateVisitOff<='$dtto', L.dateVisitOff, '$dtto'), '$dtto') AS rdt_out,
                            if(L.dateVisitOn>='$dtfrom', L.dateVisitOn, '$dtfrom') as rdt_in, H.id as hotel_id
                            from tb_listok L
                            join tb_hotels H on L.id_hotel=H.id
                            join tb_hoteltype htp on htp.id=H.hotel_type_id
                            join tb_region R on R.id=H.id_region
                            join tb_citizens c on c.id=L.id_citizen
                            join tb_districts d on d.id=H.id_district
                            where H.noshow=0 and $id_region and $id_hotel
                            and ((L.dateVisitOn < '$dtfrom' and (L.dateVisitOff is null or L.dateVisitOff between '$dtfrom' and '$dtto'))
                            or L.dateVisitOn between '$dtfrom' and '$dtto')) AS z) AS x
                group by x.`rgn`, x.dst, x.hotel_id, x.hotel, x.hoteltype, x.hotel_rooms_fund, x.hotel_rooms_beds";

            \Log::info('Выполняемый SQL-запрос для report_03: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_03');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_03: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_04($data) {
        try {
            $id_region = !empty($data['id_region']) ? " and H.id_region=" . $data['id_region'] : "";
            $id_hotel = !empty($data['id_hotel']) ? " and H.id in (" . $data['id_hotel'] . ")" : "";

            $sql = "select replace(U.`Num`,'90000000', '') as `№`, U.Citizen as `ГРАЖДАНСТВО`,
                U.`Pensioner` as `Пенсионер`, U.`Student` as `Учащийся`, U.`Dependent` as `Иждивенец`,
                U.`Other` as `Другие`, U.`Totaly` as `Итого`
                from (select IF(x.`Citizen` = 'TOTAL:',90000000,@rows:=@rows+1) as `Num`, x.Citizen,
                      x.`Pensioner`, x.`Student`, x.`Dependent`, x.`Other`, x.`Totaly`
                      from (select C.SP_NAME01 as `Citizen`,
                            sum(case when L.id_guest = 1 then 1 else 0 end) as `Pensioner`,
                            sum(case when L.id_guest = 2 then 1 else 0 end) as `Student`,
                            sum(case when L.id_guest = 3 then 1 else 0 end) as `Dependent`,
                            sum(case when L.id_guest = 4 or ISNULL(L.id_guest) then 1 else 0 end) as `Other`,
                            count(L.id) as Totaly
                            from tb_listok L
                            left join tb_citizens C on L.id_citizen = C.id
                            left join tb_users U on U.id = L.entry_by
                            left join tb_hotels H on H.id = U.id_hotel
                            where L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
                            $id_region $id_hotel
                            group by C.SP_NAME01
                            union all
                            select 'TOTAL:', sum(case when L.id_guest=1 then 1 else 0 end),
                            sum(case when L.id_guest = 2 then 1 else 0 end),
                            sum(case when L.id_guest = 3 then 1 else 0 end),
                            sum(case when L.id_guest = 4 or ISNULL(L.id_guest) then 1 else 0 end),
                            count(L.id)
                            from tb_listok L
                            left join tb_users U on U.id = L.entry_by
                            left join tb_hotels H on H.id = U.id_hotel
                            left join tb_region R on R.id = H.id_region
                            where L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
                            $id_region $id_hotel) x
                      join (select @rows:=0) w
                      order by x.`Citizen`) U
                order by U.`Num`";

            \Log::info('Выполняемый SQL-запрос для report_04: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_04');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_04: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_06($data) {
        try {
            $id_region = !empty($data['id_region']) ? " and H.id_region=" . $data['id_region'] : "";
            $id_hotel = !empty($data['id_hotel']) ? " and H.id in (" . $data['id_hotel'] . ")" : "";

            $sql = "select region as `Регион`, hotel as `Гостиница`,
                case when lastactivity=0 then 'никогда' else date_format(FROM_UNIXTIME(lastactivity), '%d-%m-%Y %H:%i') end as `Дата последней активности`,
                case when lastactivity=0 then '' else round(((unix_timestamp(now())-lastactivity)/60/60/24),1) end as `Прошло дней`,
                hotel_id
                from (select R.`name` as region, H.`name` as hotel, H.id as hotel_id,
                      (select max(ifnull(U.last_activity,0)) from tb_users as U where U.id_hotel = H.id) as lastactivity
                      from tb_hotels H
                      left join tb_region R on R.id=H.id_region
                      where H.noshow=0 and H.hotel_status_id=1 and R.id $id_region $id_hotel
                      group by R.name, H.name, H.id) x
                order by region, hotel;";

            \Log::info('Выполняемый SQL-запрос для report_06: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_06');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_06: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_05($data) {
        try {
            $id_region = !empty($data['id_region']) ? " and H.id_region=" . $data['id_region'] : "";
            $id_hotel = !empty($data['id_hotel']) ? " and H.id in (" . $data['id_hotel'] . ")" : "";

            $sql = "select R.name as `Регион`, H.name as `Гостиница`,
                concat(IFNULL(U.first_name,''),' ',IFNULL(U.last_name,'XXX')) as `Администратор`,
                sum(case when L.id_citizen in (173,231,245) then 1 else 0 end) as `Местные`,
                sum(case when L.id_citizen not in (173,231,245) then 1 else 0 end) as `Иностранцы`,
                case when U.last_activity=0 then 'никогда' else date_format(FROM_UNIXTIME(U.last_activity), '%d-%m-%Y %H:%i') end as `Дата последней активности`,
                case when U.last_activity=0 then '' else round(((unix_timestamp(now())-U.last_activity)/60/60/24),1) end as `Прошло дней`,
                H.id as `hotel_id`
            from tb_listok L
            left join tb_users U on L.entry_by=U.id
            left join tb_hotels H on U.id_hotel=H.id
            left join tb_region R on R.id=H.id_region
            where H.noshow=0 and H.hotel_status_id=1 and R.id $id_region and U.id_hotel $id_hotel
                and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
            group by R.name, H.name, H.id,
                concat(IFNULL(U.first_name,''),' ',IFNULL(U.last_name,'XXX')),
                case when U.last_activity=0 then 'никогда' else date_format(FROM_UNIXTIME(U.last_activity), '%d-%m-%Y %H:%i') end,
                case when U.last_activity=0 then '' else round(((unix_timestamp(now())-U.last_activity)/60/60/24),1) end
            order by R.name, H.id;";


            \Log::info('Выполняемый SQL-запрос для report_05: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_05');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_05: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_08($data) {
        try {
            $id_region = !empty($data['id_region']) ? " and H.id_region=" . $data['id_region'] : "";
            $id_hotel = !empty($data['id_hotel']) ? " and H.id in (" . $data['id_hotel'] . ")" : "";

            $sql = "select C.SP_NAME01 as `Гражданство`, L.firstname as `Имя`, L.surname as `Фамилия`,
                L.lastname as `Отчество`, if(L.sex = 'W','Ж','М') as `Пол`,
                concat(ifnull(L.passportSerial,''),' ',L.passportNumber) as `Паспорт`,
                L.datePassport as `Дата_выдачи`, L.PassportIssuedBy as `Выдан`, V.`name` as `Виза`,
                count(*) as `Кол_визитов`
                from tb_listok L
                left join tb_citizens C on L.id_citizen = C.id
                left join tb_users U on U.id = L.entry_by
                left join tb_hotels H on H.id = U.id_hotel
                left join tb_visa V on L.id_visa = V.id
                where H.noshow=0 and H.hotel_status_id=1 and L.id_citizen not in (173,231,245)
                and L.dateVisitOn between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
                $id_region $id_hotel
                group by C.SP_NAME01, concat(ifnull(L.passportSerial,''),' ',L.passportNumber),
                L.firstname, L.surname, L.lastname, if(L.sex = 'W','Ж','М'),
                L.datePassport, L.PassportIssuedBy, V.`name`";

            \Log::info('Выполняемый SQL-запрос для report_08: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_08');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_08: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_11($data) {
        try {
            $year = !empty($data['year_num']) ? $data['year_num'] : 0;

            $sql = "select C.SP_NAME03 as 'COUNTRY',
                sum(case when month(L.dateVisitOn)=1 then 1 else 0 end) as `JAN`,
                sum(case when month(L.dateVisitOn)=2 then 1 else 0 end) as `FEB`,
                sum(case when month(L.dateVisitOn)=3 then 1 else 0 end) as `MAR`,
                sum(case when month(L.dateVisitOn)=4 then 1 else 0 end) as `APR`,
                sum(case when month(L.dateVisitOn)=5 then 1 else 0 end) as `MAY`,
                sum(case when month(L.dateVisitOn)=6 then 1 else 0 end) as `JUN`,
                sum(case when month(L.dateVisitOn)=7 then 1 else 0 end) as `JUL`,
                sum(case when month(L.dateVisitOn)=8 then 1 else 0 end) as `AUG`,
                sum(case when month(L.dateVisitOn)=9 then 1 else 0 end) as `SEP`,
                sum(case when month(L.dateVisitOn)=10 then 1 else 0 end) as `OCT`,
                sum(case when month(L.dateVisitOn)=11 then 1 else 0 end) as `NOV`,
                sum(case when month(L.dateVisitOn)=12 then 1 else 0 end) as `DEC`,
                count(*) as `{$year}`
                from tb_listok L
                inner join tb_citizens C on C.id = L.id_citizen
                where year(L.dateVisitOn)={$year} and L.id_citizen not in (173,231,245)
                group by C.SP_NAME03";

            \Log::info('Выполняемый SQL-запрос для report_11: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_11');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_11: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_10($data) {
        try {
            $year = !empty($data['year_num']) ? $data['year_num'] : 0;
            $year_prev = ($year * 1) - 1;
            $month = !empty($data['month_num']) ? $data['month_num'] : 0;
            $month_num_opt = [
                '0' => NULL, '1' => 'Январь', '2' => 'Февраль', '3' => 'Март',
                '4' => 'Апрель', '5' => 'Май', '6' => 'Июнь', '7' => 'Июль',
                '8' => 'Август', '9' => 'Сентябрь', '10' => 'Октябрь',
                '11' => 'Ноябрь', '12' => 'Декабрь'
            ];
            $month_name = $month_num_opt[$month];

            $sql = "select C.SP_NAME03 as `Страна`,
                        Y.XXX as `Кол-во гостей`,
                        X.`dec` as `Сравнение декабрь кол-во`,
                        if(X.`dec`>0, round(Y.XXX/X.`dec`*100,2), NULL) as `Сравнение декабрь %`,
                        X.XXX as `Прошлый год кол-во`,
                        if(X.XXX>0, round(Y.XXX/X.XXX*100.00,2), NULL) as `Прошлый год %`
                from (select L.id_countryFrom,
                             sum(case when month(L.dateVisitOn)={$month} then 1 else 0 end) as XXX,
                             sum(case when month(L.dateVisitOn)=12 then 1 else 0 end) as `dec`
                      from tb_listok L
                      where year(L.dateVisitOn)={$year_prev} and L.id_countryFrom not in (173,231,245)
                      group by L.id_countryFrom) X
                inner join (select L.id_countryFrom,
                                    sum(case when month(L.dateVisitOn)={$month} then 1 else 0 end) as XXX,
                                    sum(case when month(L.dateVisitOn)=12 then 1 else 0 end) as `dec`
                           from tb_listok L
                           where year(L.dateVisitOn)={$year} and L.id_countryFrom not in (173,231,245)
                           group by L.id_countryFrom) Y
                on X.id_countryFrom=Y.id_countryFrom
                inner join tb_citizens C on C.id=X.id_countryFrom";

            \Log::info('Выполняемый SQL-запрос для report_10: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_10');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_10: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }



    private function report_09($data) {
        try {
            $year = !empty($data['year_num']) ? $data['year_num'] : 0;
            $year_prev = ($year * 1) - 1;
            $month = !empty($data['month_num']) ? $data['month_num'] : 0;
            $month_num_opt = [
                '0' => NULL, '1' => 'Январь', '2' => 'Февраль', '3' => 'Март',
                '4' => 'Апрель', '5' => 'Май', '6' => 'Июнь', '7' => 'Июль',
                '8' => 'Август', '9' => 'Сентябрь', '10' => 'Октябрь',
                '11' => 'Ноябрь', '12' => 'Декабрь'
            ];
            $month_name = $month_num_opt[$month];
            $month = intval($month);
            $year = intval($year);
            $year_prev = intval($year_prev);

            $sql = "select
            C.SP_NAME03 as `Страна`,
            Y.XXX as `Кол-во гостей`,
            X.`dec` as `Сравнение декабрь кол-во`,
            if(X.`dec`>0, round(Y.XXX/X.`dec`*100,2), NULL) as `Сравнение декабрь %`,
            X.XXX as `Прошлый год кол-во`,
            if(X.XXX>0, round(Y.XXX/X.XXX*100.00,2), NULL) as `Прошлый год %`
        from (
            select L.id_citizen,
                   sum(case when month(L.dateVisitOn)={$month} then 1 else 0 end) as XXX,
                   sum(case when month(L.dateVisitOn)=12 then 1 else 0 end) as `dec`
            from tb_listok L
            where year(L.dateVisitOn)={$year_prev} and L.id_citizen not in (173,231,245)
            group by L.id_citizen
        ) X
        inner join (
            select L.id_citizen,
                   sum(case when month(L.dateVisitOn)={$month} then 1 else 0 end) as XXX,
                   sum(case when month(L.dateVisitOn)=12 then 1 else 0 end) as `dec`
            from tb_listok L
            where year(L.dateVisitOn)={$year} and L.id_citizen not in (173,231,245)
            group by L.id_citizen
        ) Y on X.id_citizen=Y.id_citizen
        inner join tb_citizens C on C.id=X.id_citizen";



            \Log::info('Выполняемый SQL-запрос для report_09: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_09');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_09: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_12_foreighn_only($data) {
        try {
            $id_region = !empty($data['id_region']) ? " and H.id_region=" . $data['id_region'] : "";
            $id_hotel = !empty($data['id_hotel']) ? " and H.id in (" . $data['id_hotel'] . ")" : "";

            $sql = "select D.shortname as `НАЗВАНИЕ РАЙОНА`, Z.Total as `ВСЕГО`, Z.Works as `РАБОТА`,
                Z.Students as `УЧЁБА`, Z.Tourists as `ТУРИСТ`, Z.Private as `ЧАСТНЫЙ`, Z.Others as `ДРУГОЕ`,
                (select count(*) from tb_hotels HH where HH.id_district = D.id) as `КОЛ-ВО ГОСТИНИЦ`
                from tb_districts D
                left join (select H.id_district, count(*) as Total,
                          sum(case when L.id_visitType=1 then 1 else 0 end) as Works,
                          sum(case when L.id_visitType=2 then 1 else 0 end) as Students,
                          sum(case when L.id_visitType=3 then 1 else 0 end) as Tourists,
                          sum(case when L.id_visitType = 4 then 1 else 0 end) as `Private`,
                          sum(case when L.id_visitType = 4 then 1 else 0 end) as `Others`
                          from tb_listok L
                          inner join tb_users U on L.entry_by = U.id
                          inner join tb_hotels H on U.id_hotel = H.id
                          where H.noshow=0 and H.hotel_status_id=1
                          and L.dateVisitOn BETWEEN STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
                          and L.id_citizen not in (173,231,245)
                          $id_region $id_hotel
                          group by H.id_district) Z
                on Z.id_district = D.id
                where D.id_region" . (!empty($data['id_region']) ? '=' . $data['id_region'] : '!=0');

            \Log::info('Выполняемый SQL-запрос для report_12_foreighn_only: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_12_foreighn_only');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_12_foreighn_only: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_12($data) {
        try {
            $id_region = !empty($data['id_region']) ? " and H.id_region=" . $data['id_region'] : "";
            $id_hotel = !empty($data['id_hotel']) ? " and H.id in (" . $data['id_hotel'] . ")" : "";

            $sql = "select D.shortname as `НАЗВАНИЕ РАЙОНА`, Z.Total as `ВСЕГО`, Z.Works as `РАБОТА`,
                Z.Students as `УЧЁБА`, Z.Tourists as `ТУРИСТ`, Z.Private as `ЧАСТНЫЙ`, Z.Others as `ДРУГОЕ`,
                (select count(*) from tb_hotels HH where HH.id_district = D.id) as `КОЛ-ВО ГОСТИНИЦ`
                from tb_districts D
                left join (select H.id_district, count(*) as Total,
                          sum(case when L.id_visitType=1 then 1 else 0 end) as Works,
                          sum(case when L.id_visitType=2 then 1 else 0 end) as Students,
                          sum(case when L.id_visitType=3 then 1 else 0 end) as Tourists,
                          sum(case when L.id_visitType = 4 then 1 else 0 end) as `Private`,
                          sum(case when L.id_visitType = 4 then 1 else 0 end) as `Others`
                          from tb_listok L
                          inner join tb_users U on L.entry_by = U.id
                          inner join tb_hotels H on U.id_hotel = H.id
                          where H.noshow=0 and H.hotel_status_id=1
                          and L.dateVisitOn BETWEEN STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s')
                          $id_region $id_hotel
                          group by H.id_district) Z
                on Z.id_district = D.id
                where D.id_region" . (!empty($data['id_region']) ? '=' . $data['id_region'] : '!=0');

            \Log::info('Выполняемый SQL-запрос для report_12: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_12');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_12: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_15($data) {
        try {
            $id_hotel = !empty($data['id_hotel']) ? "tb_loading.id_hotel in (" . $data['id_hotel'] . ")" : "tb_loading.id_hotel=0";

            $sql = "select tb_loading.dateLoad as `Дата: `, tb_hotels.`name` as ` Гостиница:`,
                if(tb_loading.rooms is null or tb_loading.rooms = 0,'15*',tb_loading.rooms) as `Кол-во номеров`,
                if(tb_loading.beds is null or tb_loading.beds = 0,'30*',tb_loading.beds) as `Кол-во коек`,
                tb_loading.loaded as `Кол-во гостей`, tb_loading.f_loaded as `Из них иностранцы`,
                round(tb_loading.per_rooms,2) as `Средняя загруж к номерам (%)`,
                round(tb_loading.per_beds,2) as `Средняя загруж к койкам (%)`
                from tb_loading
                inner join tb_hotels on tb_hotels.id=tb_loading.id_hotel
                where $id_hotel
                and tb_loading.dateLoad between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y')
                union ALL
                select 'За " . substr($data['dateFrom'],0,10) . " / " . substr($data['dateTo'],0,10) . ": ', null, null, null,
                sum(tb_loading.loaded + tb_loading.f_loaded), sum(tb_loading.f_loaded),
                round(avg(tb_loading.per_rooms),2), round(avg(tb_loading.per_beds),2)
                from tb_loading
                where $id_hotel
                and tb_loading.dateLoad between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y')";

            \Log::info('Выполняемый SQL-запрос для report_15: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_15');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_15: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_14($data) {
        try {
            $id_region = !empty($data['id_region']) ? "tb_hotels.id_region=" . $data['id_region'] : "tb_hotels.id_region is not null";
            $id_hotel = !empty($data['id_hotel']) ? "tb_loading.id_hotel in (" . $data['id_hotel'] . ")" : "tb_loading.id_hotel is not null";

            $sql = "select STRAIGHT_JOIN tb_region.`name` as `Название региона:`, tb_hotels.`name` as `Гостиница`,
                tb_hoteltype.`name` as `Тип сред.разм.`,
                if(tb_loading.rooms is null or tb_loading.rooms = 0,'15*',tb_loading.rooms) as `Кол-во номеров`,
                if(tb_loading.beds is null or tb_loading.beds = 0,'30*',tb_loading.beds) as `Кол-во коек`,
                sum(tb_loading.loaded) as `Кол-во гостей`, sum(tb_loading.f_loaded) as `Из них иностранцы`,
                round(avg(tb_loading.per_rooms),2) as `Средняя загруж. к номерам (%)`,
                round(avg(tb_loading.per_beds),2) as `Средняя загруж. к койкам (%)`,
                tb_loading.id_hotel as hotel_id
                from tb_loading
                inner join tb_hotels on tb_hotels.id = tb_loading.id_hotel
                inner join tb_hoteltype on tb_hotels.hotel_type_id = tb_hoteltype.id
                inner join tb_region on tb_hotels.id_region = tb_region.id
                where $id_hotel and $id_region
                and tb_loading.dateLoad between STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y')
                group by tb_region.`name`, tb_hotels.`name`, tb_hoteltype.`name`, tb_loading.id_hotel, tb_loading.rooms, tb_loading.beds";

            \Log::info('Выполняемый SQL-запрос для report_14: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_14');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_14: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }




    private function report_13_with_statuses($data) {
        try {
            $id_region = !empty($data['id_region']) ? " and H.id_region=" . $data['id_region'] : "";
            $sql = "select z.* from (select COALESCE(R.name, ' ******* : ') as `Название региона:`,
                sum(case when H.hotel_type_id = 1 then 1 else 0 end) as `Гостиницы`,
                sum(case when H.hotel_type_id = 1 and H.hotel_status_id=1 then 1 else 0 end) as `on_1`,
                sum(case when H.hotel_type_id = 2 then 1 else 0 end) as `Апартотели`,
                sum(case when H.hotel_type_id = 2 and H.hotel_status_id=1 then 1 else 0 end) as `on_2`,
                sum(case when H.hotel_type_id = 3 then 1 else 0 end) as `Резиденции`,
                sum(case when H.hotel_type_id = 3 and H.hotel_status_id=1 then 1 else 0 end) as `on_3`,
                sum(case when H.hotel_type_id = 4 then 1 else 0 end) as `SPA отели`,
                sum(case when H.hotel_type_id = 4 and H.hotel_status_id=1 then 1 else 0 end) as `on_4`,
                sum(case when H.hotel_type_id = 5 then 1 else 0 end) as `Бутик отели`,
                sum(case when H.hotel_type_id = 5 and H.hotel_status_id=1 then 1 else 0 end) as `on_5`,
                sum(case when H.hotel_type_id = 6 then 1 else 0 end) as `Мотели`,
                sum(case when H.hotel_type_id = 6 and H.hotel_status_id=1 then 1 else 0 end) as `on_6`,
                sum(case when H.hotel_type_id = 7 then 1 else 0 end) as `Санатории`,
                sum(case when H.hotel_type_id = 7 and H.hotel_status_id=1 then 1 else 0 end) as `on_t`,
                sum(case when H.hotel_type_id = 8 then 1 else 0 end) as `Пансионаты`,
                sum(case when H.hotel_type_id = 8 and H.hotel_status_id=1 then 1 else 0 end) as `on_8`,
                sum(case when H.hotel_type_id = 9 then 1 else 0 end) as `Лечебницы`,
                sum(case when H.hotel_type_id = 9 and H.hotel_status_id=1 then 1 else 0 end) as `on_9`,
                sum(case when H.hotel_type_id = 10 then 1 else 0 end) as `Зона отдыха`,
                sum(case when H.hotel_type_id = 10 and H.hotel_status_id=1 then 1 else 0 end) as `on_10`,
                sum(case when H.hotel_type_id = 11 then 1 else 0 end) as `Спорт базы`,
                sum(case when H.hotel_type_id = 11 and H.hotel_status_id=1 then 1 else 0 end) as `on_11`,
                sum(case when H.hotel_type_id = 12 then 1 else 0 end) as `Дом охотничий`,
                sum(case when H.hotel_type_id = 12 and H.hotel_status_id=1 then 1 else 0 end) as `on_12`,
                sum(case when H.hotel_type_id = 13 then 1 else 0 end) as `Дом рыбака`,
                sum(case when H.hotel_type_id = 13 and H.hotel_status_id=1 then 1 else 0 end) as `on_13`,
                sum(case when H.hotel_type_id = 14 then 1 else 0 end) as `Лагерь детский`,
                sum(case when H.hotel_type_id = 14 and H.hotel_status_id=1 then 1 else 0 end) as `on_14`,
                sum(case when H.hotel_type_id = 15 then 1 else 0 end) as `Лагерь туристский`,
                sum(case when H.hotel_type_id = 15 and H.hotel_status_id=1 then 1 else 0 end) as `on_15`,
                sum(case when H.hotel_type_id = 16 then 1 else 0 end) as `Лагерь палаточный`,
                sum(case when H.hotel_type_id = 16 and H.hotel_status_id=1 then 1 else 0 end) as `on_16`,
                sum(case when H.hotel_type_id = 17 then 1 else 0 end) as `Лагерь юртовый`,
                sum(case when H.hotel_type_id = 17 and H.hotel_status_id=1 then 1 else 0 end) as `on_17`,
                sum(case when H.hotel_type_id = 18 then 1 else 0 end) as `Кемпинги`,
                sum(case when H.hotel_type_id = 18 and H.hotel_status_id=1 then 1 else 0 end) as `on_18`,
                sum(case when H.hotel_type_id = 19 then 1 else 0 end) as `Дом загородный`,
                sum(case when H.hotel_type_id = 19 and H.hotel_status_id=1 then 1 else 0 end) as `on_19`,
                sum(case when H.hotel_type_id = 20 then 1 else 0 end) as `Дом частный`,
                sum(case when H.hotel_type_id = 20 and H.hotel_status_id=1 then 1 else 0 end) as `on_20`,
                sum(case when H.hotel_type_id = 21 then 1 else 0 end) as `Дом фермерский`,
                sum(case when H.hotel_type_id = 21 and H.hotel_status_id=1 then 1 else 0 end) as `on_21`,
                sum(case when H.hotel_type_id = 22 then 1 else 0 end) as `Дом гостевой`,
                sum(case when H.hotel_type_id = 22 and H.hotel_status_id=1 then 1 else 0 end) as `on_22`,
                sum(case when H.hotel_type_id = 23 then 1 else 0 end) as `Дом сельский`,
                sum(case when H.hotel_type_id = 23 and H.hotel_status_id=1 then 1 else 0 end) as `on_23`,
                sum(case when H.hotel_type_id = 24 then 1 else 0 end) as `Юрта`,
                sum(case when H.hotel_type_id = 24 and H.hotel_status_id=1 then 1 else 0 end) as `on_24`,
                sum(case when H.hotel_type_id = 25 then 1 else 0 end) as `Шале`,
                sum(case when H.hotel_type_id = 25 and H.hotel_status_id=1 then 1 else 0 end) as `on_25`,
                sum(case when H.hotel_type_id = 26 then 1 else 0 end) as `Бунгало`,
                sum(case when H.hotel_type_id = 26 and H.hotel_status_id=1 then 1 else 0 end) as `on_26`,
                sum(case when H.hotel_type_id = 27 then 1 else 0 end) as `Квартира`,
                sum(case when H.hotel_type_id = 27 and H.hotel_status_id=1 then 1 else 0 end) as `on_27`,
                sum(case when H.hotel_type_id = 28 then 1 else 0 end) as `Апартамент`,
                sum(case when H.hotel_type_id = 28 and H.hotel_status_id=1 then 1 else 0 end) as `on_28`,
                sum(case when H.hotel_type_id = 29 then 1 else 0 end) as `Хостел`,
                sum(case when H.hotel_type_id = 29 and H.hotel_status_id=1 then 1 else 0 end) as `on_29`,
                sum(case when H.hotel_type_id = 30 then 1 else 0 end) as `Туроператор`,
                sum(case when H.hotel_type_id = 30 and H.hotel_status_id=1 then 1 else 0 end) as `on_30`,
                sum(case when H.hotel_type_id = 32 then 1 else 0 end) as `Туристская база`,
                sum(case when H.hotel_type_id = 32 and H.hotel_status_id=1 then 1 else 0 end) as `on_32`,
                sum(case when H.hotel_type_id IS NULL then 1 else 0 end) as `Без категории`,
                sum(case when H.hotel_type_id IS NULL and H.hotel_status_id=1 then 1 else 0 end) as `on_33`
                from tb_hotels H
                left join tb_region R on R.id = H.id_region
                where H.noshow=0 " . $id_region . "
                group by R.name WITH ROLLUP) z";

            \Log::info('Выполняемый SQL-запрос для report_13_with_statuses: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_13_with_statuses');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_13_with_statuses: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }



    private function report_16($data) {
        try {
            $id_region = !empty($data['id_region']) ? "H.id_region=" . $data['id_region'] : "H.id_region is not null";
            $id_hotel = !empty($data['id_hotel']) ? "H.id in (" . $data['id_hotel'] . ")" : "H.id is not null";

            $sql = "SELECT RE.name AS `РЕГИОН`, H.name AS `ГОСТИНИЦА`, HC.stars AS `ЗВЕЗДЫ`, HC.rooms AS `СЕРТНФ`,
                HC.beds AS `СЕРТКОЙКИ`, RT.en AS `ТИП НОМЕРА`, R.room_floor AS `ЭТАЖ`, R.room_numb AS `№КОМНАТЫ`,
                R.beds AS `КОЛ-ВО КОЕК В НОМЕРЕ`, COUNT(LR.id_reg) AS `ПРОЖИВАЮТ`,
                SUM(CASE WHEN L.sex='W' THEN 1 ELSE 0 END) AS `ИЗ НИХ ЖЕН`,
                SUM(CASE WHEN L.sex='M' THEN 1 ELSE 0 END) AS `ИЗ НИХ МУЖ`
                FROM tb_rooms AS R
                JOIN tb_room_types AS RT ON RT.id = R.id_room_type
                JOIN tb_hotels AS H ON H.id = R.id_hotel
                JOIN tb_region AS RE ON H.id_region = RE.id
                LEFT JOIN tb_hotel_certs AS HC ON H.id_cert = HC.id
                LEFT JOIN tb_listok_rooms LR ON LR.id_room = R.id
                LEFT JOIN tb_listok L ON L.id = LR.id_reg
                where $id_region and $id_hotel
                GROUP BY RE.name, H.name, HC.stars, HC.rooms, HC.beds, R.room_floor, R.room_numb, RT.en, R.beds, R.active
                ORDER BY RE.name, H.name, R.room_floor, R.room_numb*1";

            \Log::info('Выполняемый SQL-запрос для report_16: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_16');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_16: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_950($data) {
        try {
            $id_region = !empty($data['id_region']) ? "h.id_region=" . $data['id_region'] : "h.id_region is not null";
            $id_hotel = !empty($data['id_hotel']) ? "h.id in (" . $data['id_hotel'] . ")" : "h.id is not null";

            $sql = "select re.name02 as rgn, ds.t_name_ru as  dst, h.name as hotel, tp.name as htl_tp, h.id, h.inn, h.hotel_rooms_fund as rooms,h.hotel_rooms_beds as beds from tb_hotels as h join tb_region as re on re.id = h.id_region join tb_districts_coato as ds on ds.id = h.id_district join tb_hoteltype as tp on tp.id = h.hotel_type_id where h.hotel_type_id not in (30,31) AND $id_region AND $id_hotel";


            \Log::info('Выполняемый SQL-запрос для report_950: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_950');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_950: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_210($data) {
        try {
            $yy = $data['year_num'] * 1;
            $id_region = !empty($data['id_region']) ? "id_region=" . $data['id_region'] : "id_region is not null";
            $id_hotel = !empty($data['id_hotel']) ? "id_hotel in (" . $data['id_hotel'] . ")" : "id_hotel is not null";

            $sql = "SELECT H.`name` AS `СРЕДСТВА РАЗМЕЩЕНИЯ`, T.`name` AS `ТИП`,
                CASE WHEN Z.ctz='uz' THEN 'Узбекистан:'
                     WHEN Z.ctz='sng' THEN 'Страны СНГ:'
                     ELSE 'Дальный зарубеж:' END as `КАТЕГОРИИ`,
                SUM(Z.d_all) AS `ВСЕГО:`, SUM(Z.d_1_3) AS `НОЧИ 1-3`, SUM(Z.d_4_7) AS `НОЧИ 4-7`,
                SUM(Z.d_8_28) AS `НОЧИ 8-28`, SUM(Z.d_29_91) AS `НОЧИ 29-91`, SUM(Z.d_92_182) AS `НОЧИ 92-182`,
                SUM(Z.d_365) AS `НОЧИ 183 >`
                FROM (SELECT Y.id_region, Y.id_hotel, Y.ctz, COUNT(DISTINCT Y.psp) as guests_qty,
                             SUM(CASE WHEN Y.nights>=0 THEN 1 ELSE 0 END) as d_all,
                             SUM(CASE WHEN Y.nights BETWEEN 0 AND 3 THEN 1 ELSE 0 END) as d_1_3,
                             SUM(CASE WHEN Y.nights BETWEEN 4 AND 7 THEN 1 ELSE 0 END) as d_4_7,
                             SUM(CASE WHEN Y.nights BETWEEN 8 AND 28 THEN 1 ELSE 0 END) as d_8_28,
                             SUM(CASE WHEN Y.nights BETWEEN 29 AND 91 THEN 1 ELSE 0 END) as d_29_91,
                             SUM(CASE WHEN Y.nights BETWEEN 92 AND 182 THEN 1 ELSE 0 END) as d_92_182,
                             SUM(CASE WHEN Y.nights>182 THEN 1 ELSE 0 END) as d_365
                      FROM (SELECT X.*, CASE WHEN ROUND(TIMESTAMPDIFF(HOUR,X.dtIn,X.dtOut))<7 THEN 0 ELSE ROUND(TIMESTAMPDIFF(HOUR,X.dtIn,X.dtOut)/24) END AS nights
                            FROM (SELECT id_hotel, id_region, CONCAT(IFNULL(passportSerial,''), passportNumber,'_',id_citizen) as psp,
                                         CASE WHEN id_citizen IN (173,231,245) THEN 'uz'
                                              WHEN id_citizen IN (206,3,113,114,137,225,152,180,170,205) THEN 'sng'
                                              ELSE 'mir' END ctz,
                                         CASE WHEN YEAR(dateVisitOn) < $yy AND YEAR(dateVisitOff)=$yy THEN '$yy-01-01 00:00:00' ELSE dateVisitOn END AS dtIn,
                                         CASE WHEN (YEAR(dateVisitOff) > $yy AND YEAR(dateVisitOn)=$yy) OR dateVisitOff IS NULL THEN '$yy-12-31 23:59:59' ELSE dateVisitOff END AS dtOut
                                  FROM tb_listok
                                  WHERE $id_region AND $id_hotel
                                  AND ((YEAR(dateVisitOn)=$yy AND YEAR(dateVisitOff)=$yy) OR (YEAR(dateVisitOn)<$yy AND (YEAR(dateVisitOff)=$yy OR dateVisitOff IS NULL)))) AS X) AS Y
                      GROUP BY Y.id_region, Y.id_hotel, Y.ctz) AS Z
                JOIN tb_hotels as H on H.id = Z.id_hotel
                JOIN tb_hoteltype as T on T.id = H.hotel_type_id
                GROUP BY H.name, T.name, CASE WHEN Z.ctz='uz' THEN 'Узбекистан:' WHEN Z.ctz='sng' THEN 'Страны СНГ:' ELSE 'Дальный зарубеж:' END";

            \Log::info('Выполняемый SQL-запрос для report_210: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_210');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_210: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_visa5010($data) {
        try {
            // Преобразуем даты в нужный формат
            $dFrom = \DateTime::createFromFormat('d-m-Y H:i:s', $data['dateFrom']);
            $dTo = \DateTime::createFromFormat('d-m-Y H:i:s', $data['dateTo']);
            $dFrom = $dFrom->format('Y-m-d');
            $dTo = $dTo->format('Y-m-d');

            // Формируем условия для региона и отеля
            $id_region = !empty($data['id_region']) ? "v.id_region=" . $data['id_region'] : "v.id_region is not null";
            $id_hotel = !empty($data['id_hotel']) ? "v.id_hotel in (" . $data['id_hotel'] . ")" : "v.id_hotel is not null";

            // Формируем SQL-запрос
            $sql = "SELECT * FROM (
                    SELECT 1 AS ord, c.SP_NAME03 as `DAVLATLAR`,
                        SUM(IF(visa=1, qty, NULL)) as `D-1`,
                        SUM(IF(visa=2, qty, NULL)) as `D-2`,
                        SUM(IF(visa=3, qty, NULL)) as `DT`,
                        SUM(IF(visa=4, qty, NULL)) as `S-1`,
                        SUM(IF(visa=5, qty, NULL)) as `S-2`,
                        SUM(IF(visa=6, qty, NULL)) as `S-3`,
                        SUM(IF(visa=7, qty, NULL)) as `O`,
                        SUM(IF(visa=8, qty, NULL)) as `B-1`,
                        SUM(IF(visa=9, qty, NULL)) as `B-2`,
                        SUM(IF(visa=10, qty, NULL)) as `T`,
                        SUM(IF(visa=11, qty, NULL)) as `TG`,
                        SUM(IF(visa=12, qty, NULL)) as `E`,
                        SUM(IF(visa=13, qty, NULL)) as `J-1`,
                        SUM(IF(visa=14, qty, NULL)) as `J-2`,
                        SUM(IF(visa=15, qty, NULL)) as `PV-1`,
                        SUM(IF(visa=16, qty, NULL)) as `PV-2`,
                        SUM(IF(visa=17, qty, NULL)) as `A-1`,
                        SUM(IF(visa=18, qty, NULL)) as `A-2`,
                        SUM(IF(visa=19, qty, NULL)) as `C-1`,
                        SUM(IF(visa=20, qty, NULL)) as `C-2`,
                        SUM(IF(visa=21, qty, NULL)) as `TRAN`,
                        SUM(IF(visa=22, qty, NULL)) as `EXIT`,
                        SUM(IF(visa=23, qty, NULL)) as `MED`,
                        SUM(IF(visa=24, qty, NULL)) as `INV`,
                        SUM(IF(visa=25, qty, NULL)) as `VTD`,
                        SUM(IF(visa=26, qty, NULL)) as `A-3`,
                        SUM(IF(visa=27, qty, NULL)) as `PLG`,
                        SUM(IF(visa=28, qty, NULL)) as `EV`,
                        SUM(IF(visa=29, qty, NULL)) as `STD`,
                        SUM(IF(visa=30, qty, NULL)) as `PZ`,
                        SUM(qty) as `Jami:`
                    FROM tb_citizens as c
                    LEFT JOIN (
                        SELECT v.person_country as ctz, v.i_type_visa as visa, COUNT(*) as qty
                        FROM visa_req as v
                        JOIN tb_hotels as h ON h.id = v.id_hotel
                        WHERE v.i_give_date BETWEEN '$dFrom' AND '$dTo' AND $id_hotel AND $id_region
                        GROUP BY v.i_type_visa, v.person_country
                    ) as cte ON cte.ctz = c.id
                    WHERE c.id NOT IN (173, 245, 231)
                    GROUP BY c.SP_NAME03
                ) B
                ORDER BY `DAVLATLAR`";

            \Log::info('Выполняемый SQL-запрос для report_visa5010: ' . $sql);
            \Log::info('Данные для report_visa5010:', $data);

            // Выполняем запрос к базе данных
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_visa5010');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;

        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_visa5010: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_visa5015($data) {
        try {
            $dFrom = \DateTime::createFromFormat('d-m-Y H:i:s', $data['dateFrom']);
            $dTo = \DateTime::createFromFormat('d-m-Y H:i:s', $data['dateTo']);
            $dFrom = $dFrom->format('Y-m-d');
            $dTo = $dTo->format('Y-m-d');

            $rgn = !empty($data['id_region']) ? "h.id_region = " . $data['id_region'] : "h.id_region IS NOT NULL";
            $htl = !empty($data['id_hotel']) ? "h.id = " . $data['id_hotel'] : "h.id IS NOT NULL";
            $dt = "v.i_give_date BETWEEN '$dFrom' AND '$dTo'";

            $sql = "SELECT c.SP_NAME03 as `DAVLATLAR`,
                       SUM(IF(cte.rgn = '99', cte.qty, NULL)) as `Andijon`,
                       SUM(IF(cte.rgn = '40', cte.qty, NULL)) as `Fargona`,
                       SUM(IF(cte.rgn = '98', cte.qty, NULL)) as `Namangan`,
                       SUM(IF(cte.rgn = '11', cte.qty, NULL)) as `Toshkent viloyati`,
                       SUM(IF(cte.rgn = '10', cte.qty, NULL)) as `Bekabad`,
                       SUM(IF(cte.rgn = '01', cte.qty, NULL)) as `Toshkent shahri`,
                       SUM(IF(cte.rgn = '77', cte.qty, NULL)) as `OVViOG-aeroport`,
                       SUM(IF(cte.rgn = '74', cte.qty, NULL)) as `IIV MvaFRBB`,
                       SUM(IF(cte.rgn = '96', cte.qty, NULL)) as `Gulistan`,
                       SUM(IF(cte.rgn = '97', cte.qty, NULL)) as `Jizzax`,
                       SUM(IF(cte.rgn = '30', cte.qty, NULL)) as `Samarqand`,
                       SUM(IF(cte.rgn = '80', cte.qty, NULL)) as `Qarshi`,
                       SUM(IF(cte.rgn = '75', cte.qty, NULL)) as `Termez`,
                       SUM(IF(cte.rgn = '76', cte.qty, NULL)) as `Navoi`,
                       SUM(IF(cte.rgn = '81', cte.qty, NULL)) as `Buxoro`,
                       SUM(IF(cte.rgn = '90', cte.qty, NULL)) as `Urgench`,
                       SUM(IF(cte.rgn = '95', cte.qty, NULL)) as `Nukus`,
                       SUM(cte.qty) as `Jami:`
                FROM tb_citizens as c
                LEFT JOIN (
                    SELECT o.sticker_code as rgn, v.person_country as ctz, COUNT(*) as qty
                    FROM visa_req as v
                    JOIN tb_users as u ON u.id = v.i_issued_by
                    JOIN tb_hotels as h ON h.id = u.id_hotel
                    LEFT JOIN visa_organs as o ON o.sticker_code = u.mrz_code
                    WHERE $dt AND $rgn AND $htl
                    GROUP BY o.sticker_code, v.person_country
                ) as cte ON cte.ctz = c.id
                WHERE c.id NOT IN (173, 245, 231)
                GROUP BY c.SP_NAME03
                ORDER BY `DAVLATLAR`";

            \Log::info('Выполняемый SQL-запрос для report_visa5015: ' . $sql);
            \Log::info('Данные для report_visa5015:', $data);

            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_visa5015');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;

        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_visa5015: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }



    private function report_5000($data) {
        try {
            $dt = " STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y') ";
            $dt2 = " STR_TO_DATE('{$data['dateFrom']}','%d-%m-%Y %H:%i:%s') and STR_TO_DATE('{$data['dateTo']}','%d-%m-%Y %H:%i:%s') ";

            $sql = "select c.SP_NAME01 as `ГРАЖДАНСТВО`, SUM(IFNULL(x.gmdc_qty,0)) as `ГУМиОГ Времення прописка`,SUM(IFNULL(x.hotels_qty,0)) as `Гостиницы`,SUM(IFNULL(x.selftputists_qty,0)) as `Самостоятельный туристы`,SUM(IFNULL(x.phys_qty,0)) as `Физ лица` from tb_citizens as c left join (select o.id_citizen, count(*) as gmdc_qty,0 as hotels_qty,0 as selftputists_qty,0 as phys_qty from tb_ovir_lists as o join tb_ovir_registers as r on o.id_register=r.id where r.created_at between $dt2 group by o.id_citizen union all select id_citizen, 0,count(*),0,0 from tb_srv where dtIn between $dt group by id_citizen union all select id_citizen, 0,0,count(*),0 from tb_self_listok where dateVisitOn
		    between $dt2 group by id_citizen union all select id_citizen, 0,0,0,count(*) from uzbektourism.tb_listok where created_at between $dt2 and payStatus=2 group by id_citizen) as x on x.id_citizen = c.id group by c.SP_NAME01";


            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_5000');
                return ['error' => 'Нет данных для отображения'];
            }
            return $results;
        }
        catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }

    }



    private function report_6103($data) {
        try {
            $yy = $data['year_num'] ? $data['year_num'] : date('Y');
            $rgn = !empty($data['id_region']) ? "h.id_region = " . $data['id_region'] : "h.id_region IS NOT NULL";
            $htl = !empty($data['id_hotel']) ? "h.id = " . $data['id_hotel'] : "h.id IS NOT NULL";

            $sql = "SELECT r.name as `РЕГИОН`, h.name as `ГОСТИНИЦА`, t.name as `СТАТУС`,
                       h.license as `Дог №`, h.inn as `ИНН`,
                       x.jan as `ЯНВ`, x.feb as `ФЕВ`, x.mar as `МАР`, x.apr as `АПР`,
                       x.may as `МАЙ`, x.jun as `ИЮН`, x.jul as `ИЮЛ`, x.aug as `АВГ`,
                       x.sep as `СЕН`, x.oct as `ОКТ`, x.nov as `НОЯ`, x.dec as `ДЕК`,
                       x.yy as `ВСЕГО ЗА $yy`
                FROM tb_hotels as h
                JOIN tb_region as r ON r.id = h.id_region
                JOIN tb_hotelstatus as t ON t.id = h.hotel_status_id
                LEFT JOIN (
                    SELECT h.id,
                           SUM(IF(MONTH(s.dtIn) = 1, 1, 0)) as `jan`,
                           SUM(IF(MONTH(s.dtIn) = 2, 1, 0)) as `feb`,
                           SUM(IF(MONTH(s.dtIn) = 3, 1, 0)) as `mar`,
                           SUM(IF(MONTH(s.dtIn) = 4, 1, 0)) as `apr`,
                           SUM(IF(MONTH(s.dtIn) = 5, 1, 0)) as `may`,
                           SUM(IF(MONTH(s.dtIn) = 6, 1, 0)) as `jun`,
                           SUM(IF(MONTH(s.dtIn) = 7, 1, 0)) as `jul`,
                           SUM(IF(MONTH(s.dtIn) = 8, 1, 0)) as `aug`,
                           SUM(IF(MONTH(s.dtIn) = 9, 1, 0)) as `sep`,
                           SUM(IF(MONTH(s.dtIn) = 10, 1, 0)) as `oct`,
                           SUM(IF(MONTH(s.dtIn) = 11, 1, 0)) as `nov`,
                           SUM(IF(MONTH(s.dtIn) = 12, 1, 0)) as `dec`,
                           COUNT(h.id) as `yy`
                    FROM tb_srv as s
                    JOIN tb_hotels as h ON h.id = s.id_hotel
                    WHERE YEAR(s.dtIn) = $yy AND $rgn AND $htl
                    GROUP BY h.id
                ) as x ON x.id = h.id
                WHERE $rgn AND $htl
                ORDER BY r.name, h.name";

            \Log::info('Выполняемый SQL-запрос для report_6103: ' . $sql);
            \Log::info('Данные для report_6103:', $data);

            $results = DB::select($sql);
            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_6103');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_6103: ' . $e->getMessage()); // Логируем ошибку
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_1000($data) {
        try {
            $id_region = !empty($data['id_region']) ? "h.id_region=" . $data['id_region'] : "h.id_region is not null";
            $dt = "STR_TO_DATE('{$data['dateFrom']}', '%Y-%m-%d') AND STR_TO_DATE('{$data['dateTo']}', '%Y-%m-%d')";

            $sql = "SELECT r.id as rid, h.id as did, r.name02 as region, h.shortname as district, x.loreg, x.foreg, NULL as gmdc, NULL as phys
                FROM tb_districts as h
                JOIN tb_region as r ON h.id_region = r.id
                LEFT JOIN (
                    SELECT h.id_district,
                           SUM(IF(s.id_citizen NOT IN (173, 231, 245), 1, 0)) as foreg,
                           SUM(IF(s.id_citizen IN (173, 231, 245), 1, 0)) as loreg
                    FROM tb_srv as s
                    JOIN tb_hotels as h ON h.id = s.id_hotel
                    WHERE s.dtIn BETWEEN $dt
                    GROUP BY h.id_district
                ) x ON h.id = x.id_district
                WHERE $id_region
                ORDER BY r.name02, h.shortname";

            $results = DB::select($sql);

            if (empty($results)) {
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_800($data) {
        try {
            $sql = "select r.name as `НАЗВАНИЕ РЕГИОНА`, count(s.id) as `КОЛ-ВО ИНОСТРАНЦЕВ` from tb_srv as s join tb_region as r on s.id_region = r.id where s.dtOut is null and s.id_citizen not in (173,231,245) group by r.name order by r.name";
            \Log::info('Выполняемый SQL-запрос для report_800: ' . $sql);
            $results = DB::select($sql);
            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_800: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_801($data) {
        try {
            $id_region = !empty($data['id_region']) ? "s.id_region=" . $data['id_region'] : "s.id_region is not null";
            $sql = "select c.SP_NAME03 as `CITIZENS`, c.SP_NAME01 as `ГРАЖДАНСТВО`, count(s.id) as `КОЛ-ВО`
                from tb_srv as s
                inner join tb_citizens as c on s.id_citizen = c.id
                where s.dtOut is null
                and s.id_citizen not in (173,231,245)
                and $id_region
                group by c.SP_NAME03, c.SP_NAME01
                order by c.SP_NAME03";

            \Log::info('Выполняемый SQL-запрос для report_801: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_801');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_801: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }


    private function report_200($data) {
        try {
            $yy = $data['year_num'];
            $id_region = !empty($data['id_region']) ? "id_region=" . $data['id_region'] : "id_region is not null";
            $id_hotel = !empty($data['id_hotel']) ? "id_hotel in (" . $data['id_hotel'] . ")" : "id_hotel is not null";

            $sql = "SELECT H.`name` AS `НАИМЕНОВАНИЕ`, T.`name` AS `ТИП СРЕДСТВО`, H.hotel_rooms_fund as `НФ`, H.hotel_rooms_beds as `КОЙКИ`,
                SUM(Z.guests_qty) AS `ВСЕГО ГОСТЕЙ`, SUM(Z.nights) AS `ВСЕГО НОЧЕЙ`,
                SUM(CASE WHEN Z.ctz='uz' THEN Z.guests_qty ELSE 0 END) AS `ГОСТИ УЗБ`,
                SUM(CASE WHEN Z.ctz='uz' THEN Z.nights ELSE 0 END) AS `НОЧИ УЗБ`,
                SUM(CASE WHEN Z.ctz='sng' THEN Z.guests_qty ELSE 0 END) AS `ГОСТИ СНГ`,
                SUM(CASE WHEN Z.ctz='sng' THEN Z.nights ELSE 0 END) AS `НОЧИ СНГ`,
                SUM(CASE WHEN Z.ctz='mir' THEN Z.guests_qty ELSE 0 END) AS `ГОСТИ ЗАРУБ`,
                SUM(CASE WHEN Z.ctz='mir' THEN Z.nights ELSE 0 END) AS `НОЧИ ЗАРУБ`
                FROM (SELECT Y.id_region, Y.id_hotel, Y.ctz, COUNT(Y.psp) as guests_qty, SUM(Y.nights) as nights
                      FROM (SELECT X.*, CASE WHEN ROUND(TIMESTAMPDIFF(HOUR,X.dtIn,X.dtOut))<7 THEN 0 ELSE ROUND(TIMESTAMPDIFF(HOUR,X.dtIn,X.dtOut)/24) END AS nights
                            FROM (SELECT id_hotel, id_region, CONCAT(IFNULL(passportSerial,''), passportNumber,'_',id_citizen) as psp,
                                  CASE WHEN id_citizen IN (173,231,245) THEN 'uz'
                                       WHEN id_citizen IN (206,3,113,114,137,225,152,180,170,205) THEN 'sng'
                                       ELSE 'mir' END ctz,
                                  CASE WHEN YEAR(dateVisitOn) < $yy AND YEAR(dateVisitOff)=$yy THEN '$yy-01-01 00:00:00' ELSE dateVisitOn END AS dtIn,
                                  CASE WHEN (YEAR(dateVisitOff)>$yy AND YEAR(dateVisitOn)=$yy) OR dateVisitOff IS NULL THEN '$yy-12-31 23:59:59' ELSE dateVisitOff END AS dtOut
                                  FROM tb_listok
                                  WHERE ((YEAR(dateVisitOn)=$yy AND YEAR(dateVisitOff)=$yy) OR (YEAR(dateVisitOn)<$yy AND (YEAR(dateVisitOff)=$yy OR dateVisitOff IS NULL)))
                                  AND $id_region AND $id_hotel) AS X) AS Y
                      GROUP BY Y.id_region, Y.id_hotel, Y.ctz) AS Z
                JOIN tb_hotels as H on H.id = Z.id_hotel
                JOIN tb_hoteltype as T on T.id = H.hotel_type_id
                GROUP BY Z.id_region, Z.id_hotel, H.id, T.id";

            \Log::info('Выполняемый SQL-запрос для report_200: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_200');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_200: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_205($data) {
        try {
            $yy = $data['year_num'] * 1;
            $id_region = !empty($data['id_region']) ? "id_region=" . $data['id_region'] : "id_region is not null";
            $id_hotel = !empty($data['id_hotel']) ? "id_hotel in (" . $data['id_hotel'] . ")" : "id_hotel is not null";

            $sql = "SELECT H.`name` AS `СРЕДСТВА РАЗМЕЩЕНИЯ`,
                CASE WHEN Z.ctz='uz' THEN 'Узбекистан:'
                     WHEN Z.ctz='sng' THEN 'Страны СНГ:'
                     ELSE 'Дальный зарубеж:' END as `КАТЕГОРИИ`,
                SUM(Z.c_all) AS `ВСЕГО`, SUM(Z.c_1) AS `ТУРИСТ`, SUM(Z.c_2) AS `ПОСЕЩ РОДСТВ`,
                SUM(Z.c_3) AS `УЧЁБА`, SUM(Z.c_4) AS `ЛЕЧЕБ`, SUM(Z.c_5) AS `ПАЛОМНИЧ`,
                SUM(Z.c_6) AS `ШОПИНГ`, SUM(Z.c_7) AS `ТРАНЗИТ`, SUM(Z.c_8) AS `ПРОЧИЕ`,
                SUM(Z.c_9) AS `РАБОТА`, SUM(Z.c_10) AS `ЧАСТНЫЙ`, SUM(Z.c_11) AS `СПОРТ И КУЛЬТУРА`,
                SUM(Z.c_12) AS `НА ОТДЫХ`, SUM(Z.c_13) AS `ГОСТЕВАЯ`, SUM(Z.c_14) AS `СЛУЖЕБНАЯ`,
                SUM(Z.c_15) AS `СООТЕЧЕСТВЕННИК`, SUM(Z.c_16) AS `ПОЧЁТНЫЙ`, SUM(Z.c_17) AS `ИНВЕРСТОР`
                FROM (SELECT X.id_region, X.id_hotel, X.ctz, X.psp, count(X.vt) as c_all,
                             SUM(CASE WHEN X.vt =3 THEN 1 ELSE 0 END) as c_1,
                             SUM(CASE WHEN X.vt =6 THEN 1 ELSE 0 END) as c_2,
                             SUM(CASE WHEN X.vt =2 THEN 1 ELSE 0 END) as c_3,
                             SUM(CASE WHEN X.vt =7 THEN 1 ELSE 0 END) as c_4,
                             SUM(CASE WHEN X.vt =8 THEN 1 ELSE 0 END) as c_5,
                             SUM(CASE WHEN X.vt =9 THEN 1 ELSE 0 END) as c_6,
                             SUM(CASE WHEN X.vt =10 THEN 1 ELSE 0 END) as c_7,
                             SUM(CASE WHEN X.vt =5 OR X.vt IS NULL THEN 1 ELSE 0 END) as c_8,
                             SUM(CASE WHEN X.vt=1 THEN 1 ELSE 0 END) as c_9,
                             SUM(CASE WHEN X.vt=4 THEN 1 ELSE 0 END) as c_10,
                             SUM(CASE WHEN X.vt=11 THEN 1 ELSE 0 END) as c_11,
                             SUM(CASE WHEN X.vt=12 THEN 1 ELSE 0 END) as c_12,
                             SUM(CASE WHEN X.vt=13 THEN 1 ELSE 0 END) as c_13,
                             SUM(CASE WHEN X.vt=14 THEN 1 ELSE 0 END) as c_14,
                             SUM(CASE WHEN X.vt=15 THEN 1 ELSE 0 END) as c_15,
                             SUM(CASE WHEN X.vt=16 THEN 1 ELSE 0 END) as c_16,
                             SUM(CASE WHEN X.vt=17 THEN 1 ELSE 0 END) as c_17
                      FROM (SELECT id_hotel, id_region, CONCAT(IFNULL(passportSerial,''), passportNumber,'_',id_citizen) as psp,
                                   id_visitType as vt,
                                   CASE WHEN id_citizen IN (173,231,245) THEN 'uz'
                                        WHEN id_citizen IN (206,3,113,114,137,225,152,180,170,205) THEN 'sng'
                                        ELSE 'mir' END ctz,
                                   CASE WHEN YEAR(dateVisitOn)<$yy AND YEAR(dateVisitOff)=$yy THEN '$yy-01-01 00:00:00' ELSE dateVisitOn END AS dtIn,
                                   CASE WHEN (YEAR(dateVisitOff)>$yy AND YEAR(dateVisitOn)=$yy) OR dateVisitOff IS NULL THEN '$yy-12-31 23:59:59' ELSE dateVisitOff END AS dtOut
                            FROM tb_listok
                            WHERE $id_hotel AND $id_region
                            AND ((YEAR(dateVisitOn)=$yy AND YEAR(dateVisitOff)=$yy) OR (YEAR(dateVisitOn)<$yy AND (YEAR(dateVisitOff)=$yy OR dateVisitOff IS NULL)))) AS X
                      GROUP BY X.id_region, X.id_hotel, X.ctz, X.psp) AS Z
                JOIN tb_hotels as H on H.id = Z.id_hotel
                GROUP BY H.name, CASE WHEN Z.ctz='uz' THEN 'Узбекистан:' WHEN Z.ctz='sng' THEN 'Страны СНГ:' ELSE 'Дальный зарубеж:' END";

            \Log::info('Выполняемый SQL-запрос для report_205: ' . $sql);
            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_205');
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_205: ' . $e->getMessage());
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }



    private function report_2000($data) {
        try {
            $id_region = !empty($data['id_region']) ? "h.id_region = " . $data['id_region'] : "h.id_region IS NOT NULL";
            $id_hotel = !empty($data['id_hotel']) ? "h.id = " . $data['id_hotel'] : "h.id IS NOT NULL";

            $sql = "SELECT x.rgn as region, x.name as hotel_name, x.inn as inn, x.yy as year,
                       IFNULL(x.accured, 0) as accrued, IFNULL(x.paid, 0) as paid,
                       IFNULL(x.paid, 0) - IFNULL(x.accured, 0) as balance
                FROM (
                    SELECT r.name as rgn, h.name, h.inn, YEAR(c.created_at) as yy, SUM(c.total) as accured,
                           (SELECT SUM(d.summa)
                            FROM tb_debet as d
                            WHERE d.id_hotel = h.id AND YEAR(d.dtOper) = YEAR(c.created_at)) as paid
                    FROM tb_hotels as h
                    JOIN tb_region as r ON r.id = h.id_region
                    JOIN tb_credit as c ON c.id_hotel = h.id
                    WHERE $id_region AND $id_hotel
                    GROUP BY r.name, h.name, h.inn, YEAR(c.created_at), h.id, c.created_at -- Добавлен c.created_at
                ) x
                ORDER BY x.rgn, x.name, x.inn, x.yy";

            \Log::info('Выполняемый SQL-запрос для report_2000: ' . $sql); // Логируем SQL-запрос
            \Log::info('Данные для report_2000:', $data); // Логируем входные данные

            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения в report_2000'); // Логируем отсутствие данных
                return ['error' => 'Нет данных для отображения'];
            }

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса в report_2000: ' . $e->getMessage()); // Логируем ошибку
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

    private function report_900($data) {
        try {
            $id_region = !empty($data['id_region']) ? "h.id_region=" . $data['id_region'] : "h.id_region is not null";
            $id_hotel = !empty($data['id_hotel']) ? "h.id in (" . $data['id_hotel'] . ")" : "h.id is not null";
            $mm = $data['month_num'];
            $yy = $data['year_num'];
            $mmx = str_pad($mm, 2, '0', STR_PAD_LEFT);

            $sql = "select r.name02 as region_name, ht.name as hotel_type, h.name as hotel_name, h.hotel_rooms_fund as room_fund, h.hotel_rooms_beds as beds_count, '$mmx/$yy' as period, (SELECT IFNULL(SUM(IFNULL(li.amount, 0)), 0) FROM tb_listok as li WHERE h.id = li.id_hotel and YEAR(li.dateVisitOff) = $yy and month(li.dateVisitOff) = $mm) as total_amount, h.id as hotel_id from tb_hotels as h join tb_hoteltype as ht on ht.id = h.hotel_type_id join tb_region as r on r.id = h.id_region where h.noshow = 0 AND $id_region AND $id_hotel ORDER BY h.name";

            \Log::info('Выполняемый SQL-запрос: ' . $sql); // Логируем SQL-запрос

            $results = DB::select($sql);

            if (empty($results)) {
                \Log::warning('Нет данных для отображения'); // Логируем отсутствие данных
                return ['error' => 'Нет данных для отображения'];
            }

            // Логируем данные перед возвратом
            \Log::info('Данные отчёта:', $results);

            return $results;
        } catch (\Exception $e) {
            \Log::error('Ошибка при выполнении SQL-запроса: ' . $e->getMessage()); // Логируем ошибку
            return ['error' => 'Ошибка при формировании отчёта'];
        }
    }

}
