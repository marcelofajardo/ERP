<?php

namespace App\Providers;

/**
 * This  provider is using for update the whatsapp number direct from database
 *
 *
 */

use Illuminate\Support\ServiceProvider;

class WhatsappConfigProvider extends ServiceProvider
{
    public static function getWhatsappConfigs()
    {
        try {
            $q = \DB::table("whatsapp_configs")->select([
                "number", "instance_id", "token", "is_customer_support", "status", "is_default","is_use_own"
            ])->where("instance_id", "!=", "")
                ->where("token", "!=", "")
                //->where("status", 1)
                ->orderBy("is_default", "DESC")
                ->get();
        } catch (\Illuminate\Database\QueryException $e) {
            $q = null;
        }

        return $q;
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // we have to check with try catch so we don't have issue while running migration
        try {
            $instance = self::getWhatsappConfigs();
            $default  = [];
            $others   = [];
            if (!empty($instance)) {
                foreach ($instance as $inst) {
                    $array = [
                        "number"          => $inst->number,
                        "instance_id"     => $inst->instance_id,
                        "token"           => $inst->token,
                        "customer_number" => ($inst->is_customer_support == 1) ? true : false,
                        "is_use_own"      => $inst->is_use_own,
                    ];
                    if ($inst->is_default == 1) {
                        $others[0] = $array;
                    }
                    $others[$inst->number] = $array;

                }
                // merge array to default instances and update the config file
                $nos = $others;
                if(!empty($nos)) {
                    config(['apiwha.instances' => $nos]);
                }
            }

            // get the all zoom key
            $settings = \App\Setting::where(function($q){
                $q->orWhere("name","like","ZOOM_%")->orWhere("name","like","PLESK_%");
            })->get();
            
            if(!$settings->isEmpty()) {
                foreach($settings as $setting) {
                    putenv ("{$setting->name}={$setting->val}");
                }
            }
        } catch (\Exeception $e) {

        }
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
