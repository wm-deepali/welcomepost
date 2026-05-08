<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubAdminPermission;

class CheckUserPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('admin_login'); // Redirect to login if user is not authenticated
        }

        // Fetch the permissions from the database for the authenticated user
        $userPermissions = SubAdminPermission::where('user_id', $user->id)->first();

        if (!$userPermissions || !$userPermissions->{$permission}) {
            abort(403, 'Unauthorized action.'); // Abort with 403 status if user does not have the required permission
        }

        return $next($request);
    }
}
