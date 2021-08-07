<?php

namespace Modules\BookStack\Providers;

use Modules\BookStack\Actions\ActivityService;
use Modules\BookStack\Actions\ViewService;
use Modules\BookStackModules\BookStack\Settings\SettingService;
use Modules\BookStack\Uploads\ImageService;
use Illuminate\Support\ServiceProvider;

class CustomFacadeProvider extends ServiceProvider
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
        $this->app->bind('activity', function () {
            return $this->app->make(ActivityService::class);
        });

        $this->app->bind('views', function () {
            return $this->app->make(ViewService::class);
        });

        $this->app->bind('setting', function () {
            return $this->app->make(SettingService::class);
        });

        $this->app->bind('images', function () {
            return $this->app->make(ImageService::class);
        });
    }
}
