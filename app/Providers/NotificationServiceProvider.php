<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;


class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
	    view()->composer('partials.notifications','App\Http\Composers\NotificaitonComposer');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
