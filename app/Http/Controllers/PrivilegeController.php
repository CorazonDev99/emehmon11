<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PrivilegeController extends Controller
{

    public function index()
    {
        $modules = DB::table('tb_module')->get();

        return view('core.privileges.index', [
            'modules' => $modules
        ]);
    }

    public function getData(Request $request)
    {
        $d = DB::table('tb_module')->select([
            'module_id',
            'module_name',
            'module_title',
            'module_note',
            'module_author',
            'module_created',
            'module_desc',
            'module_db',
            'module_db_key',
            'module_type',
            'activate',
        ]);

        return DataTables::of($d)
            ->addIndexColumn()
            ->make(true);
    }



    public function createModule(Request $request)
    {
        $moduleData = [
            'module_name' => $request->input('module_name'),
            'module_title' => $request->input('module_title'),
            'module_desc' => $request->input('module_desc'),
            'module_note' => $request->input('module_note'),
            'module_author' => $request->input('module_author'),
            'module_created' => now(),
            'module_db' => $request->input('module_db'),
            'module_db_key' => $request->input('module_db_key'),
            'module_type' => $request->input('module_type'),
            'activate' => $request->input('activate'),
            'module_config' => null,
            'module_lang' => null,
        ];

        $inserted = DB::table('tb_module')->insert($moduleData);

        if ($inserted) {
            return response()->json(['success' => true, 'message' => 'Module created successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to create module']);
        }
    }

    public function editModule(Request $request)
    {
        $moduleId = $request->input('module_id');
        $moduleName = $request->input('module_name');
        $moduleTitle = $request->input('module_title');
        $moduleDesc = $request->input('module_desc');
        $moduleNote = $request->input('module_note');
        $moduleAuthor = $request->input('module_author');
        $moduleDb = $request->input('module_db');
        $moduleDbKey = $request->input('module_db_key');
        $moduleType = $request->input('module_type');
        $moduleActivate = $request->input('activate');

        try {
            DB::table('tb_module')
                ->where('module_id', $moduleId)
                ->update([
                    'module_name'   => $moduleName,
                    'module_title'  => $moduleTitle,
                    'module_desc'   => $moduleDesc,
                    'module_note'   => $moduleNote,
                    'module_author' => $moduleAuthor,
                    'module_db'     => $moduleDb,
                    'module_db_key' => $moduleDbKey,
                    'module_type'   => $moduleType,
                    'activate'   => $moduleActivate,
                ]);

            return response()->json(['success' => true, 'message' => 'Module updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update module', 'error' => $e->getMessage()]);
        }
    }

    public function deleteModule(Request $request)
    {
        try {
            $deleted = DB::table('tb_module')->where('module_id', $request->get("module_id"))->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => __('Module deleted successfully.')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('Module not found.')
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while deleting the module.'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
