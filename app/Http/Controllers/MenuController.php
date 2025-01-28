<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userRole = $user->roles->first();

        if (!$userRole) {
            return redirect()->back()->with('error', 'У вас нет назначенной роли.');
        }

        $rolePrivileges = DB::table('cms_privileges_roles')
            ->where('id_cms_privileges', $userRole->id)
            ->where(function ($query) {
                $query->where('is_visible', 1)
                    ->orWhere('is_create', 1)
                    ->orWhere('is_read', 1)
                    ->orWhere('is_edit', 1);
            })
            ->pluck('id_cms_moduls')
            ->toArray();

        $modules = DB::table('tb_module')
            ->where('activate', 1)
            ->whereIn('module_id', $rolePrivileges)
            ->get();

        $topMenus = $this->getMenuHierarchy('top');
        $menus = DB::table('tb_menu')->get();
        $icons = DB::table('tb_icons')->get();

        return view('core.menu.index', compact('topMenus', 'menus', 'modules', 'icons'));
    }
    private function getMenuHierarchy($position)
    {
        $menus = DB::table('tb_menu')
            ->where('position', $position)
            ->orderBy('ordering')
            ->get();

        return $this->buildHierarchy($menus);
    }

    private function buildHierarchy($menus, $parentId = 0)
    {
        $result = [];
        foreach ($menus as $menu) {
            if ($menu->parent_id == $parentId) {
                $children = $this->buildHierarchy($menus, $menu->menu_id);
                if (count($children) > 0) {
                    $menu->children = $children;
                } else {
                    $menu->children = [];
                }
                $result[] = $menu;
            }
        }
        return $result;
    }


    public function store(Request $request)
    {
        $menuName = $request->input('menu_name');
        $menuType = $request->input('menu_type');
        $url = $request->input('url');
        $menuIcons = $request->input('menu_icons');
        $active = $request->input('active');
        $module = $request->input('module');


        $newOrder = DB::table('tb_menu')
                ->where('position', 'top')
                ->max('ordering') + 1;

        DB::table('tb_menu')->insert([
            'entry_by' => auth()->user()->id,
            'menu_name' => $menuName,
            'menu_type' => $menuType,
            'module' => $module,
            'url' => $url,
            'position' => 'top',
            'menu_icons' => $menuIcons,
            'active' => $active,
            'ordering' => $newOrder,
            'parent_id' => 0,
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu item created successfully.');
    }


    public function updateOrder(Request $request)
    {
        try {

            DB::transaction(function () use ($request) {
                if ($request->has('menu')) {
                    $this->saveMenuOrder($request->input('menu'));
                }
            });

            return response()->json(['status' => 'Order updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update order'], 500);
        }
    }

    private function saveMenuOrder($menuItems, $parentId = 0)
    {
        foreach ($menuItems as $index => $menuItem) {


            DB::table('tb_menu')
                ->where('menu_id', $menuItem['id'])
                ->update([
                    'parent_id' => $parentId,
                    'ordering' => $index + 1,
                ]);

            if (isset($menuItem['children']) && count($menuItem['children']) > 0) {
                $this->saveMenuOrder($menuItem['children'], $menuItem['id']);
            }
        }
    }


    public function updateMenu(Request $request)
    {
        $menuId = $request->input('menu_id');
        $menuName = $request->input('menu_name');
        $menuType = $request->input('menu_type');
        $url = $request->input('url');
        $icon = $request->input('menu_icons');
        $module = $menuType === 'external' ? null : $request->input('module');
        $isActive = $request->input('active');
        $entryBy = $request->input('entry_by');


        DB::table('tb_menu')
            ->where('menu_id', $menuId)
            ->update([
                'entry_by' => $entryBy,
                'menu_name' => $menuName,
                'menu_type' => $menuType,
                'menu_icons' => $icon,
                'active' => $isActive,
                'url' => $url,
                'module' => $module,
                'position' => 'top',
            ]);

        return response()->json(['success' => true, 'message' => 'Меню обновлено успешно.']);

    }
}
