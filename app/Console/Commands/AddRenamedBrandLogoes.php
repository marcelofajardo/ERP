<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use App\BrandLogo;
use Auth;


class AddRenamedBrandLogoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rename:brandLogo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $path = public_path('brand_logo');

                 BrandLogo::truncate();
                    try{
                     
                        $files = File::allFiles($path);
                        $fileNameArray = array();
                        foreach($files as $key=>$file){

                            $fileName = basename($file);

                        $brand_logo = BrandLogo::where('logo_image_name',$fileName)->first();

                        if(!$brand_logo){
                            // dump('asd');

                            $params['logo_image_name'] = $fileName;
                            $params['user_id'] = Auth::id();
            
                            // dd($params);
                            $log = BrandLogo::create($params);
                        }
                        }
                    }catch(\Exception $e){
                        
                    }


    }
}
