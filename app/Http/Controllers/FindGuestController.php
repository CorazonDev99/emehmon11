<?php

namespace App\Http\Controllers;
use App\Http\Helper\AuditHelper;
use App\Services\AuditEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;


class FindGuestController extends Controller
{

    public function index(Request $request)
    {
        $citizens = DB::table('tb_citizens')->select(['id', 'SP_NAME03 as name'])->get();
        $regions = DB::table('tb_region')->select(['id', 'name'])->get();

        return view('findguest.index', compact('regions', 'citizens'));
    }

    public function searchGuest(Request $request)
    {
        $filters = $request->only(['last_name', 'first_name', 'birth_date', 'citizenship', 'gender', 'region', 'passport', 'start_date', 'end_date']);

        if (!empty($filters['birth_date'])) {
            $filters['birth_date'] = \Carbon\Carbon::createFromFormat('d.m.Y', $filters['birth_date'])->format('Y-m-d');
        }
        if (!empty($filters['start_date'])) {
            $filters['start_date'] = \Carbon\Carbon::createFromFormat('d.m.Y', $filters['start_date'])->format('Y-m-d');
        }
        if (!empty($filters['end_date'])) {
            $filters['end_date'] = \Carbon\Carbon::createFromFormat('d.m.Y', $filters['end_date'])->format('Y-m-d');
        }

        $listokQuery = DB::table('tb_listok')
            ->leftjoin('tb_citizens', 'tb_listok.id_citizen', '=', 'tb_citizens.id')
            ->when($filters['last_name'] ?? null, function ($q) use ($filters) {
                $q->where('tb_listok.surname', 'like', '%' . $filters['last_name'] . '%');
            })
            ->when($filters['first_name'] ?? null, function ($q) use ($filters) {
                $q->where('tb_listok.firstname', 'like', '%' . $filters['first_name'] . '%');
            })
            ->when($filters['birth_date'] ?? null, function ($q) use ($filters) {
                $q->whereDate('tb_listok.datebirth', $filters['birth_date']);
            })
            ->when($filters['passport'] ?? null, function ($q) use ($filters) {
                $q->where('tb_listok.passport', 'like', '%' . $filters['passport'] . '%');
            })
            ->when($filters['citizenship'] ?? null, function ($q) use ($filters) {
                $q->where('tb_listok.id_citizen', '=', $filters['citizenship']);
            })
            ->when($filters['region'] ?? null, function ($q) use ($filters) {
                $q->where('tb_listok.id_region', '=', $filters['region']);
            })
            ->when($filters['gender'] ?? null, function ($q) use ($filters) {
                $q->where('tb_listok.sex', '=', $filters['gender']);
            })
            ->when($filters['start_date'] && $filters['end_date'], function ($q) use ($filters) {
                $q->whereBetween('tb_listok.dateVisitOn', [$filters['start_date'] . ' 00:00:00', $filters['end_date'] . ' 23:59:59']);
            })
            ->select(
                'tb_listok.regNum',
                'tb_listok.surname',
                'tb_listok.lastname',
                'tb_listok.firstname',
                DB::raw("DATE_FORMAT(tb_listok.dateVisitOn, '%d.%m.%Y %H:%i') AS dateVisitOn"),
                DB::raw("DATE_FORMAT(tb_listok.dateVisitOff, '%d.%m.%Y %H:%i') AS dateVisitOff"),
                'tb_listok.id_citizen AS ctz',
                'tb_citizens.SP_NAME03 AS ctzn'
            );

        $selfListokQuery = DB::table('tb_self_listok')
            ->leftjoin('tb_citizens', 'tb_self_listok.id_citizen', '=', 'tb_citizens.id')

            ->when($filters['last_name'] ?? null, function ($q) use ($filters) {
                $q->where('tb_self_listok.surname', 'like', '%' . $filters['last_name'] . '%');
            })
            ->when($filters['first_name'] ?? null, function ($q) use ($filters) {
                $q->where('tb_self_listok.firstname', 'like', '%' . $filters['first_name'] . '%');
            })
            ->when($filters['birth_date'] ?? null, function ($q) use ($filters) {
                $q->whereDate('tb_self_listok.datebirth', $filters['birth_date']);
            })
            ->when($filters['passport'] ?? null, function ($q) use ($filters) {
                $q->where('tb_self_listok.passport', 'like', '%' . $filters['passport'] . '%');
            })
            ->when($filters['citizenship'] ?? null, function ($q) use ($filters) {
                $q->where('tb_self_listok.id_citizen', '=', $filters['citizenship']);
            })
            ->when($filters['region'] ?? null, function ($q) use ($filters) {
                $q->where('tb_self_listok.id_region', '=', $filters['region']);
            })
            ->when($filters['gender'] ?? null, function ($q) use ($filters) {
                $q->where('tb_self_listok.sex', '=', $filters['gender']);
            })
            ->when($filters['start_date'] && $filters['end_date'], function ($q) use ($filters) {
                $q->whereBetween('tb_self_listok.dateVisitOn', [$filters['start_date'] . ' 00:00:00', $filters['end_date'] . ' 23:59:59']);
            })
            ->select(
                'tb_self_listok.regNum',
                'tb_self_listok.surname',
                'tb_self_listok.lastname',
                'tb_self_listok.firstname',
                DB::raw("DATE_FORMAT(tb_self_listok.dateVisitOn, '%d.%m.%Y %H:%i') AS dateVisitOn"),
                DB::raw("DATE_FORMAT(tb_self_listok.dateVisitOff, '%d.%m.%Y %H:%i') AS dateVisitOff"),
                'tb_self_listok.id_citizen AS ctz',
                'tb_citizens.SP_NAME03 AS ctzn'
            );

        $ovirListsQuery = DB::table('tb_ovir_lists')
            ->leftjoin('tb_citizens', 'tb_ovir_lists.id_citizen', '=', 'tb_citizens.id')
            ->when($filters['last_name'] ?? null, function ($q) use ($filters) {
                $q->where('tb_ovir_lists.sname', 'like', '%' . $filters['last_name'] . '%');
            })
            ->when($filters['first_name'] ?? null, function ($q) use ($filters) {
                $q->where('tb_ovir_lists.fname', 'like', '%' . $filters['first_name'] . '%');
            })
            ->when($filters['birth_date'] ?? null, function ($q) use ($filters) {
                $q->whereDate('tb_ovir_lists.dtbirth', $filters['birth_date']);
            })
            ->when($filters['passport'] ?? null, function ($q) use ($filters) {
                $q->where('tb_ovir_lists.psp_numb', 'like', '%' . $filters['passport'] . '%');
            })
            ->when($filters['citizenship'] ?? null, function ($q) use ($filters) {
                $q->where('tb_ovir_lists.id_citizen', '=', $filters['citizenship']);
            })
            ->when($filters['gender'] ?? null, function ($q) use ($filters) {
                $q->where('tb_ovir_lists.sex', '=', $filters['gender']);
            })
            ->when($filters['start_date'] && $filters['end_date'], function ($q) use ($filters) {
                $q->whereBetween('tb_ovir_lists.border_in', [$filters['start_date'] . ' 00:00:00', $filters['end_date'] . ' 23:59:59']);
            })
            ->select(
                'tb_ovir_lists.regnum',
                'tb_ovir_lists.sname',
                'tb_ovir_lists.mname',
                'tb_ovir_lists.fname',
                DB::raw("DATE_FORMAT(tb_ovir_lists.border_in, '%d.%m.%Y %H:%i') AS border_in"),
                DB::raw("DATE_FORMAT(tb_ovir_lists.border_out, '%d.%m.%Y %H:%i') AS border_out"),
                'tb_ovir_lists.id_citizen AS ctz',
                'tb_citizens.SP_NAME03 AS ctzn'
            );

        $listokData = $listokQuery->get()->map(function ($item) {
            return [
                'regNum' => $item->regNum,
                'fio' => trim($item->surname . ' ' . $item->firstname . ' ' . $item->lastname),
                'ctz' => $this->formatCitizenship($item->ctz, $item->ctzn),
                'dateVisitOn' => $item->dateVisitOn,
                'dateVisitOff' => $item->dateVisitOff,
            ];
        });

        $selfListokData = $selfListokQuery->get()->map(function ($item) {
            return [
                'regNum' => $item->regNum,
                'fio' => trim($item->surname . ' ' . $item->firstname . ' ' . $item->lastname),
                'ctz' => $this->formatCitizenship($item->ctz, $item->ctzn),
                'dateVisitOn' => $item->dateVisitOn,
                'dateVisitOff' => $item->dateVisitOff,

            ];
        });

        $ovirListsData = $ovirListsQuery->get()->map(function ($item) {
            return [
                'regNum' => $item->regnum,
                'fio' => trim($item->sname . ' ' . $item->fname . ' ' . $item->mname),
                'ctz' => $this->formatCitizenship($item->ctz, $item->ctzn),
                'dateVisitOn' => $item->border_in,
                'dateVisitOff' => $item->border_out,

            ];
        });

        return response()->json([
            'listok' => $listokData,
            'selflistok' => $selfListokData,
            'ovirlists' => $ovirListsData,
        ]);
    }

    private function formatCitizenship($ctz, $ctzn)
    {
        $flagPath = public_path('uploads/flags/' . $ctz . '.png');
        if (!file_exists($flagPath)) {
            $flagUrl = asset('uploads/flags/default.png');
        } else {
            $flagUrl = asset('uploads/flags/' . $ctz . '.png');
        }

        return '
        <img src="' . $flagUrl . '"
             title="' . e($ctzn) . '"
             width="40px" height="24px"
             style="border:1px solid #777;" />
        <span style="display:none;">' . e($ctzn) . '</span>
    ';
    }


}
