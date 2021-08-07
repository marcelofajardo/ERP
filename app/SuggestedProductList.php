<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class SuggestedProductList extends Model
{

	     /**
     * @var string
    
     
      * @SWG\Property(property="suggested_products_id",type="integer")
      * @SWG\Property(property="customer_id",type="integer")
      * @SWG\Property(property="product_id",type="integer")
      * @SWG\Property(property="chat_message_id",type="integer")
      * @SWG\Property(property="remove_attachment",type="string")
     * @SWG\Property(property="date",type="datetime")
     */
    protected $fillable = ['suggested_products_id','customer_id','product_id','chat_message_id','remove_attachment','date'];

}
