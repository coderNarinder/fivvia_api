<?php

namespace App\Http\Middleware;

use Cache;
use Config;
use Closure;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\Models\{Client, ClientPreference, Language, ClientLanguage, Currency, ClientCurrency, Product,Country};
use App\Providers\Custom\ClientCacheClass;
class CustomDomain{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
         $current_domain_url = url('/');
          if(env('Main_Domain') == $current_domain_url){
                return $next($request);
          }
      
          $obj = new ClientCacheClass;
          $clientID =
          $redisData = $obj->client_head;
          $callback = url('/auth/facebook/callback');
          if($redisData){  
              $obj->cacheBasicCache();
          }else{
            return redirect()->route('error_404');
          }
          return $next($request);
    }
}