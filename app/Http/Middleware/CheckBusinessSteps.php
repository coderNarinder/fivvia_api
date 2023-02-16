<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class CheckBusinessSteps
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
          if(auth()->check() && !empty(Auth::user()->client)) {

            if(!empty(Auth::user()->client->client_billing_details) 
              && Auth::user()->client->client_billing_details->paid_status == 1){
                return redirect()->route('business.dashboard');
            }
            if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1){
                  if(Auth::user()->client->completed_steps < 10){
                      return $next($request);
                  }
                   
            }
          }
          return redirect()->route('business.dashboard');
    }
}
