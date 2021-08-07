<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
               /**
     * @var string
      * @SWG\Property(property="is_updated",type="boolean")
         * @SWG\Property(property="supplier",type="string")
      * @SWG\Property(property="size_system_id",type="integer")
      
      * @SWG\Property(property="address",type="string")
   
     * @SWG\Property(property="phone",type="string")
     * @SWG\Property(property="default_phone",type="string")
     * @SWG\Property(property="whatsapp_number",type="string")
     * @SWG\Property(property="email",type="string")
     * @SWG\Property(property="default_email",type="string")
     * @SWG\Property(property="social_handle",type="string")
     * @SWG\Property(property="instagram_handle",type="string")
     * @SWG\Property(property="website",type="string")
     * @SWG\Property(property="gst",type="string")
     * @SWG\Property(property="status",type="string")
     * @SWG\Property(property="supplier_category_id",type="integer")
     * @SWG\Property(property="supplier_sub_category_id",type="integer")
     * @SWG\Property(property="scrapper",type="string")
     * @SWG\Property(property="supplier_status_id",type="integer")
     * @SWG\Property(property="is_blocked",type="boolean")
     * @SWG\Property(property="supplier_price_range_id",type="integer")
      * @SWG\Property(property="est_delivery_time",type="datetime")
     */

    use SoftDeletes;

    protected $casts = [
        'notes' => 'array'
    ];

    protected $fillable = [
        'is_updated', 
        'supplier', 
        'size_system_id',
        'address', 
        'phone', 
        'default_phone', 
        'whatsapp_number', 
        'email', 
        'default_email', 
        'social_handle', 
        'instagram_handle', 
        'website', 
        'gst', 
        'status',
        'supplier_category_id',
        'supplier_sub_category_id',
        'scrapper',
        'supplier_status_id',
        'is_blocked',
        'supplier_price_range_id',
        'est_delivery_time'
    ];

    protected static function boot()
    {
        parent::boot();
        self::updating(function ($model) {
            if(!empty(\Auth::id())) {
               $model->updated_by = \Auth::id();
            }
        });
        self::saving(function ($model) {
            if(!empty(\Auth::id())) {
               $model->updated_by = \Auth::id();
            }
        });
        self::creating(function ($model) {
            if(!empty(\Auth::id())) {
               $model->updated_by = \Auth::id();
            }
        });
    }

    public function agents()
    {
        return $this->hasMany('App\Agent', 'model_id')->where('model_type', 'App\Supplier');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product', 'product_suppliers', 'supplier_id', 'product_id');
    }
    public function lastProduct()
    {
        return $this->hasOne('App\Product', 'supplier_id', 'id')->latest();
    }

    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }

    public function emails()
    {
        return $this->hasMany('App\Email', 'model_id')->where(function($query) {
            $query->where('model_type', 'App\Purchase')->orWhere('model_type', 'App\Supplier');
        });
    }

    public function whatsapps()
    {
        return $this->hasMany('App\ChatMessage', 'supplier_id')->whereNotIn('status', ['7', '8', '9'])->latest();
    }

    public function category()
    {
        //return $this->belongsToMany('App\SupplierCategory', 'supplier_category', 'supplier_category_id', 'id');
        return $this->hasMany('App\SupplierCategory');
    }

    public function status()
    {
        return $this->belongsToMany('App\SupplierStatus', 'supplier_status', 'supplier_status_id', 'id');
        //return $this->hasMany('App\SupplierStatus');
    }

    public function whatsappAll($needBroadCast = false)
    {
        if($needBroadCast) {
            return $this->hasMany('App\ChatMessage', 'supplier_id')->whereIn('status', ['7', '8', '9', '10'])->latest();
        }

        return $this->hasMany('App\ChatMessage', 'supplier_id')->whereNotIn('status', ['7', '8', '9', '10'])->latest();
    }

    public function whoDid()
    {
        return $this->hasOne('App\User',"id","updated_by");
    }

    public function scraperMadeBy()
    {
        return $this->hasOne('App\User',"id","scraper_madeby");
    }

    public function scraperParent()
    {
        return $this->hasOne('App\Supplier',"id","scraper_parent_id");
    }

    public function scraper()
    {
        return $this->hasOne('App\Scraper',"supplier_id","id");
    }

    public function scrapers()
    {
        return $this->hasMany('App\Scraper',"supplier_id","id");
    }

    public function getSupplierExcelFromSupplierEmail(){

        if($this->scraper != null){
            if (strpos($this->scraper->scraper_name, 'excel') !== false) {
                return $this->scraper->scraper_name;
            }
        }
        $supplier_array = ['birba_excel','colognese_excel','cologneseSecond_excel','cologneseThird_excel',
        'cologneseFourth_excel','distributionet_excel','gru_excel','ines_excel','le-lunetier_excel',
        'lidia_excel','maxim_gucci_excel','lidiafirst_excel','modes_excel','mv1_excel','tory_excel','valenti_excel','dna_excel','master',
        ];
        foreach ($supplier_array as $supp) {
            $supp = str_replace('_excel', '', $supp);
            if (strpos($this->email, $supp) !== false) {
              if($supp != 'master'){
                return $supplier = $supp.'_excel';
                }else{
                return $supplier = $supp;
                }
            }
        }

        return $supplier = 'master';

    }

    /**
     *  Get information by ids
     *  @param []
     *  @return Mixed
     */

    public static function getInfoByIds($ids, $fields = ["*"], $toArray = false)
    {
        $list = self::whereIn("id",$ids)->select($fields)->get();

        if($toArray) {
            $list = $list->toArray();
        }

        return $list;
    }
    public function inventory()
    {

        return $this->hasMany('App\Product', 'supplier_id', 'id');
    }

    // START - Purpose : Product Inquiry Data -DEVTASK-4048
    public function inquiryproductdata()
	{
		return $this->hasMany('App\SupplierOrderInquiryData', 'supplier_id', 'id');
	}
    // END -DEVTASK-4048

}