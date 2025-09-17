<?php

namespace App\Http\Controllers\Routes;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\SupperAdmin\Menu\SuperAdminMenu;
use App\Models\SupperAdmin\Menu\SuperAdminSubmenu;

class RoutesListController extends Controller
{
    public function getAvailableRoutes()
    {
        // Collect only GET routes from superadmin.subscription.* group
        $allRoutes = collect(Route::getRoutes())->filter(function ($route) {
            return in_array('GET', $route->methods()) // only GET
                && !preg_match('/\{.*?\}/', $route->uri()) // no params
                && str_starts_with($route->getName() ?? '', 'superadmin.subscription'); // only subscription group
        })->map(function ($route) {
            return [
                'uri'  => '/' . ltrim($route->uri(), '/'),
                'name' => $route->getName(),
            ];
        });

        // Fetch already used routes
        $menuRoutes = SuperAdminMenu::pluck('route')->toArray();
        $submenuRoutes = SuperAdminSubmenu::pluck('sub_route')->toArray();

        // Exclude routes already stored
        $availableRoutes = $allRoutes->reject(function ($route) use ($menuRoutes, $submenuRoutes) {
            return in_array($route['uri'], $menuRoutes) 
                || in_array($route['uri'], $submenuRoutes);
        })
        ->values()
        ->reverse()
        ->values();

        return response()->json($availableRoutes);
    }
}
