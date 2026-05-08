<?php

namespace App\Http\Middleware;

use App\Models\Adminsettings;
use Closure;
use Illuminate\Http\Request;

class ChangeMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $admin = Adminsettings::first();

        if ($admin && $admin->is_site_maintainance) {
            // Site is in maintenance mode
            return response()->view('errors.maintenance', [], 503); // Customize the maintenance page
        }

        return $next($request);
    }
}
