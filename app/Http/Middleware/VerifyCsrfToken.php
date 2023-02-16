<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'payment/payfast/notify',
        'payment/payfast/notify/app',
        'payment/paypal/notify',
        'payment/mobbex/notify',
        'webhook/lalamove',

        /** routes for edit order **/
        'edit-order/*',
        'business/business-check-client',
        'check/business/login'
    ];
}
