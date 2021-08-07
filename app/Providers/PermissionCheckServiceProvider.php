<?php

namespace App\Providers;

use App\Helpers\PermissionCheck;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\View\Factory as ViewFactory;


class PermissionCheckServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ViewFactory $view)
    {
        $view->composer('*', 'App\Http\Composers\GlobalComposer');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        App::bind('permissioncheck', function()
        {
            return new \App\Helpers\PermissionCheck;
        });
    }
}
