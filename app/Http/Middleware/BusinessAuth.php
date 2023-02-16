<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class BusinessAuth
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
 // dd(Auth::user()->client);
      
          if(auth()->check() && !empty(Auth::user()->client)) {
             if(!empty(Auth::user()->client->client_billing_details) 
              && Auth::user()->client->client_billing_details->paid_status == 1){
                      return $next($request);
             }else{

            if(Auth::user()->is_superadmin == 1 || Auth::user()->is_admin == 1){
                 switch (Auth::user()->client->completed_steps) {
                   case 0:
                     return redirect()->route('business.business_category');
                     break;
                   
                   case 1:
                     return redirect()->route('business.business_type');
                     break;
                   
                   case 2:
                     return redirect()->route('business.business_details');
                     break;
                   
                   case 3:
                     return redirect()->route('business.businessPlan');
                     break;
                   case 4:
                     return redirect()->route('business.paymentOptions');
                     break;
                   case 5:
                     return redirect()->route('business.logistics');
                     break;
                   case 6:
                     return redirect()->route('business.domains');
                     break;
                   case 7:
                     return redirect()->route('business.template');
                     break;
                   case 8:
                     return redirect()->route('business.checkout');
                     break;
                   // case 3:
                   //   return redirect()->route('business.business_category');
                   //   break;
                   // case 3:
                   //   return redirect()->route('business.business_category');
                   //   break;
                   
                   default:
                     return $next($request);
                     break;
                 }
            }
           }
          }
          return redirect()->route('business.home');
    }
}
