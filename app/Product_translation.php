<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Product_translation extends Model
{
     /**
     * @var string
     * @SWG\Property(property="product_id",type="integer")
     * @SWG\Property(property="locale",type="string")
     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="site_id",type="interger")
     * @SWG\Property(property="is_rejected",type="boolean")
     */
    protected $fillable = [
                        'product_id',
                        'locale',
                        'title',
                        'description',
                        'site_id',
                        'is_rejected'
    ];

    public function product() {
        return $this->belongsTo('App\Product');
    }

    public function site(){
        return $this->hasOne(StoreWebsite::class, 'id', 'site_id');
    }


    // public function translate($locale, $string) {
    //     $str = urlencode($string);
    //     $curl = curl_init();
    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => "https://google-translate1.p.rapidapi.com/language/translate/v2",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => "source=en&q=".$str."&target=".$locale,
    //         CURLOPT_HTTPHEADER => array(
    //             "accept-encoding: application/gzip",
    //             "x-rapidapi-host: google-translate1.p.rapidapi.com",
    //             "x-rapidapi-key: 400c92faccmsh27859b447ca4ec2p1adc89jsn24d4ed586ffe"
    //         ),
    //     ));

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);

    //     curl_close($curl);

    //     if ($err) {
    //         return false;
    //     } else {
    //         return $response;
    //     }
    // }
}
