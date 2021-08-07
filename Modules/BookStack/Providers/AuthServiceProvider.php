<?php

namespace Modules\BookStack\Providers;

use Auth;
use Modules\BookStack\Auth\Access\LdapService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Auth::provider('ldap', function ($app, array $config) {
            return new LdapUserProvider($config['model'], $app[LdapService::class]);
        });
    }
}
