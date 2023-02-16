<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckMainDomainAuth
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
      $current_domain_url = getDomainName();
       
         
        if(config('app.business') == $current_domain_url){
           return redirect('home');
        }elseif(config('app.backend') == $current_domain_url){
           return redirect('godpanel/login');
        }else{
           return $next($request);
        }

         
        abort(404);
    }
}
