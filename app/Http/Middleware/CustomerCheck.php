<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class CustomerCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $exemptRoutes = [
            'user-logout',
            // Add other routes as necessary
        ];

        // Check if the current route is in the exempt list
        if (in_array(Route::currentRouteName(), $exemptRoutes)) {
            return $next($request);
        }
		if(!$request->session()->has('id')){
		    $request->session()->flash('error','Please authenticate first...');
		    return redirect('login');
		}
		$user = \App\Models\Customer::find($request->session()->get('id'));

        // Check if the user's mobile number is null
        if ($user && is_null($user->mobile)) {
            $request->session()->flash('error','Please complete the sign up...');
            return redirect()->route('first.details');
        }
        return $next($request);
    }
	
}
