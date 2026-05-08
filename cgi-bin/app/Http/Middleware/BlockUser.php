<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlockUser
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
        if ($request->session()->has('id')) {
            $userId = $request->session()->get('id');
            $blockCount = DB::table('block_count')->where('user_id', $userId)->value('count');
            // Check if the block count exceeds the threshold (e.g., 3)
            if ($blockCount >= 4) {
                // Redirect the user to the login page with a message
                return redirect()->route('/')->with('user-blocked', 'Your account has been temporarily suspended due to multiple violations.');
            }
        }
        return $next($request);
    }
}
