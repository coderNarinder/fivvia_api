<?php

namespace Yoco\Laravel;


use Illuminate\Console\Application as Artisan;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

use Yoco\YocoClient;

class YocoServiceProvider extends ServiceProvider
{


    protected $commands = [
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->publish();

        if (config('yoco.add_demo_routes')) {
            Route::group(['prefix' => '/yoco/demo'], function() {
                $client = new YocoClient(config('yoco.secret_key'), config('yoco.public_key'));
                Route::get('/inline', function () use ($client) {
                    return view('yoco.demo.inline', ['client' => $client]);
                })->name('yoco.demo.inline');

                Route::get('/popup', function () use ($client) {
                    return view('yoco.demo.popup', ['client' => $client]);
                })->name('yoco.demo.popup');

                Route::get('/chooser', function () {
                    return view('yoco.demo.chooser');
                })->name('yoco.demo.chooser');
            });
        }

        if (config('yoco.add_charge_routes')) {
            Route::post('/yoco/charge', '\\App\\Http\\Controllers\\ChargeController@charge')
                ->name('yoco.charge');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    private function publish()
    {

        $config = [
            __DIR__ . '/../../laravel/config' => config_path('/'),
        ];

        $src = [
            __DIR__ . '/../../laravel/Http' => app_path('/Http'),
        ];

        $public = [
            __DIR__ . '/../../laravel/public' => public_path('/'),
        ];

        $views = [
            __DIR__ . '/../../laravel/views' => resource_path('/views'),
        ];


        $this->publishes($config, ['config','yoco']);
        $this->publishes($public,['yoco-demo']);
        $this->publishes($views,['yoco-demo']);
        $this->publishes($src,'yoco');
    }

}
