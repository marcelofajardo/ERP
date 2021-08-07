<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\LaravelLog;
use App\Routes;
use DB;
use Exception;

class RoutesSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'routes:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the registered routes in DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
			\Log::channel('errorlog')->info("Schedule Job Start : Routes Sync in DB ");
			$method = 'GET';
			$routes = \Route::getRoutes()->getRoutesByMethod();

			$routesByGET  = $routes[$method];
			$routesByPOST = $routes['POST'];
            
            $AllRoutes = array_merge( $routesByGET, $routesByPOST );

			foreach ($AllRoutes as $route ){
				if (Routes::where('url', '=', $route->uri)->count() > 0) 
				{	
					continue;
				}
				\Log::channel('errorlog')->info("URL---".$route->uri);
				Routes::create(['url' => $route->uri]);
			}
			
			\Log::channel('errorlog')->info("Schedule Job End : Routes Sync in DB");
		}
		catch (\Exception $e) {
            \Log::channel('errorlog')->info("EXCEPTION---".$e->getMessage());
        }
    }
}
