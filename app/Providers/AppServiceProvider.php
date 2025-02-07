<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $menuItems = DB::table('tb_menu')
            ->where('active', '1')
            ->orderBy('ordering')
            ->get();

        $menuTree = $this->buildMenuTree($menuItems);

        View::share('menuItems', $menuTree);
    }

    private function buildMenuTree($menuItems, $parentId = 0)
    {
        $branch = [];

        foreach ($menuItems as $item) {
            if ($item->parent_id == $parentId) {
                $children = $this->buildMenuTree($menuItems, $item->menu_id);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }

        return $branch;
    }
}
