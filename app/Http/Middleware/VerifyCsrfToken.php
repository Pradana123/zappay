<?php

namespace App\Http\Middleware;

class VerifyCsrfToken extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken
{
    /**
     * URI yang dikecualikan dari CSRF hanya untuk callback provider eksternal.
     * Endpoint ini tetap wajib memvalidasi signature/secret di controller masing-masing.
     *
     * @var array<int, string>
     */
    protected $except = [
        'digiflazz/callback',
        'vip/callback',
        'tokopay/callback',
        'paydisini/callback',
        'tripay/callback',
        'apigames/callback',
        'callback',
        'callbackpl',
        'payload',
    ];
}
