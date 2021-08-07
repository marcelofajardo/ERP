<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Facebook\Facebook;
use Blade;
use Studio\Totem\Totem;
use App\ScrapedProducts;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
	    Schema::defaultStringLength(191);

        // Custom blade view directives
        Blade::directive('icon', function ($expression) {
            return "<?php echo icon($expression); ?>";
        });

        Totem::auth(function($request) {
            // return true / false . For e.g.
            return \Auth::check();
        });

        if (in_array(app('request')->ip(),config('debugip.ips') )) {
            config(['app.debug' => true]);
            config(['debugbar.enabled' => true]);
        }

    }

    /**
     * Register any applicxation services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton(Facebook::class, function ($app) {
            return new Facebook(config('facebook.config'));
        });

        $this->app->singleton(ScrapedProducts::class);

        
    }
}
