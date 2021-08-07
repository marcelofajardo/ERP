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
        //
		'twilio/*',
        'whatsapp/*',
        'livechat/*',
        'api/instagram/post',
        'duty/v1/calculate',
        'hubstaff/linkuser',
        'calendar',
        'calendar/*',
        'api/wetransfer-file-store',
        'cold-leads-broadcasts'
    ];
}
