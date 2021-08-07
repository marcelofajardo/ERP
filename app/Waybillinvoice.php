<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class Waybillinvoice extends Model
{

	    /**
     * @var string
      
      * @SWG\Property(property="waybill_invoices",type="string")
      * @SWG\Property(property="line_type",type="string")
      * @SWG\Property(property="billing_source",type="string")
      * @SWG\Property(property="original_invoice_number",type="string")
      * @SWG\Property(property="invoice_number",type="string")
      * @SWG\Property(property="invoice_identifier",type="string")
      * @SWG\Property(property="invoice_type",type="string")
      * @SWG\Property(property="invoice_date",type="datetime")
       * @SWG\Property(property="payment_terms",type="string")
      * @SWG\Property(property="due_date",type="datetime")
      * @SWG\Property(property="billing_account",type="string")
      * @SWG\Property(property="billing_account_name",type="string")
      * @SWG\Property(property="billing_account_name_additional",type="string")
      * @SWG\Property(property="billing_postcode",type="string")
      * @SWG\Property(property="billing_city",type="string")
      * @SWG\Property(property="billing_state_province",type="string")
      * @SWG\Property(property="billing_country_code",type="string")
      * @SWG\Property(property="billing_contact",type="integer")
      * @SWG\Property(property="shipment_number",type="string")
      * @SWG\Property(property="shipment_date",type="datetime")
      * @SWG\Property(property="product_name",type="string")
      * @SWG\Property(property="pieces",type="string")
      * @SWG\Property(property="origin",type="string")
      * @SWG\Property(property="orig_name",type="string")
      * @SWG\Property(property="orig_country_name",type="string")
      * @SWG\Property(property="senders_name",type="string")
      * @SWG\Property(property="invoice_amount",type="string")
      * @SWG\Property(property="invoice_currency",type="string")
 
     */
    protected $table='waybill_invoices';
    protected $fillable=['line_type','billing_source','original_invoice_number','invoice_number','invoice_identifier','invoice_type','invoice_date','payment_terms','due_date','billing_account','billing_account_name','billing_account_name_additional','billing_address_1','billing_postcode','billing_city','billing_state_province','billing_country_code','billing_contact','shipment_number','shipment_date','product','product_name','pieces','origin','orig_name','orig_country_code','orig_country_name','senders_name','senders_city','invoice_amount','invoice_currency'];
}
