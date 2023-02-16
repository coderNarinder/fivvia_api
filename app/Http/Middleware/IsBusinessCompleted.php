<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class IsBusinessCompleted
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

            // if(config('app.business') == $current_domain_url){
            //   return redirect()->route('business.home');
            // } 

            //  if(config('app.backend') == $current_domain_url){
            //   return redirect()->route('business.home');
            // } 
            $existRedis = getWebClient();
                
            // dd($existRedis->isCompleted);
            if($existRedis->isCompleted == 1){
                 return $next($request);
            }

            return redirect()->route('comming.soon');    
    }
}
