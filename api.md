# API Documentation

This API uses `POST` request to communicate and HTTP [response codes](https://en.wikipedia.org/wiki/List_of_HTTP_status_codes) to indenticate status and errors. All responses come in standard JSON. All requests must include a `content-type` of `application/json` and the body must be valid JSON.

## Response Codes

### Response Codes

```
200: Success
400: Bad request
401: Unauthorized
404: Cannot be found
405: Method not allowed
422: Unprocessable Entity
50X: Server Error
```

## Ticket Create For Store

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/ticket/create
Accept: application/json
Content-Type: application/json
{
    "name": "Pravin",
    "last_name": "Solanki",
    "email": "abc@example.com",
    "type_of_inquiry" : "Orders",
    "order_no": "ORDER-NO",
    "country": "India",
    "subject": "Some subject name",
    "message": "Some message need to ask",
    "source_of_ticket": "site url",
    "phone_no" : "919876543210",
    "sku":"7768484226295",
    "amount":"415.00",
    "notify_on":"phone",
    "brand":"Enter brand",
    "style":"Enter style",
    "keyword":"Enter keyword",
    "image":"Enter image",
    "lang_code":"ae_ar", // Enter language code
}
```

// please send type_of_inquirey:special_notes so we can understand this is special notes
// also this is the required fields which we need to pass 'name','last_name','email','type_of_inquiry','subject','message'

key : ticket.success

**Successful Response:**

```json
Content-Type: application/json
{
    "status": "success",
    "data": {
        "id": "T20201009155741"
    },
    "message": "Ticket #T20201009155741 created successfully"
}
```
Key : ticket.failed, ticket.failed.validation, ticket.failed.email_or_phone
**Failed Response:**

```json
Content-Type: application/json

{
    "status": "error",
    "message": "Unable to create ticket"
}
```

## Friend Referral API

//referrer => person refering other person
//referee=> person being reffered by referrer.
**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/friend/referral/create
Accept: application/json
Content-Type: application/json
{
    "referrer_first_name": "Pravin", //required, length maxminum 30
    "referrer_last_name": "Solanki", //length maxminum 30
    "referrer_email": "abc@example.com",
    "referrer_phone" : "7777777777", //length maxminum 20
    "referee_first_name": "karamjit",//required,length maxminum 30
    "referee_last_name": "Singh", //required,length maxminum 30
    "referee_email": "Singh.karamjit1689@gmail.com", //required,email,length maxminum 20
    "referee_phone": "9999999999", //length maxminum 20
    "website": "WWW.SOLOLUXURY.COM",//required, must be a website in store websites
    "lang_code":"ae_ar", // Enter language code
}
```

Key : refera.friend.success

**Successful Response:**

```json
Content-Type: application/json
{
    "status": "success",
    "message": "refferal created successfully",
    "referrer_code": "o4kx9LzcrbYCMFj",
    "referrer_email": "abc@example.com",
    "referee_email": "Singh.karamjit1689@gmail.com",
    "lang_code":"ae_ar", // Enter language code
}
```

Key : refera.friend.failed, refera.friend.failed.validation, coupon.failed.refferal_program

**Failed Response:**

```json
HTTP/1.1 500
Content-Type: application/json

{
    "status" : "failed",
    "message" : "Unable to create coupon",
}
```

## Gift Card API

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/giftcards/add
Accept: application/json
Content-Type: application/json
{   "sender_name" : "sender", //required, length maxminum 30
    "sender_email" : "sender@example.com",
    "receiver_name" : "reciever", //required, length maxminum 30
    "receiver_email" : "reciever@example.com", //required, email
    "gift_card_coupon_code" : "A1A22A111FFF333", //required, unique, upto 50 chars
    "gift_card_description" : "dummy description", //required, length maxminum 1000
    "gift_card_amount" : "100", //required, integer
    "gift_card_message" : "test message", //length maxminum 200
    "expiry_date" : "2020-10-16", //required, date after yesterday
    "website"  : "WWW.SOLOLUXURY.COM", //required, must be a website in store websites
    "lang_code":"ae_ar", // Enter language code
}
```
Key : giftcard.success

**Successful Response:**

```json
Content-Type: application/json
{
    "status": "success",
    "message": "gift card added successfully",
}
```

Key : giftcard.failed, giftcard.failed.validation

**Failed Response:**

```json
Content-Type: application/json

{
    "status" : "failed",
    "message" : "Unable to add gift card at the moment. Please try later !",
}
```

## Gift Card Amount Check API

**Request:**

```json
GET https://erp.theluxuryunlimited.com/api/giftcards/check-giftcard-coupon-amount
Accept: application/json
Content-Type: application/json
{   
    "coupon_code" : "A1A22A111FFF333", //required, length maxminum 30, existing in gift_cards
    "lang_code":"ae_ar", // Enter language code 
}
```
Key : giftcard.amount.success

**Successful Response:**
```json
Content-Type: application/json
{
    "status": "success",
    "message": "gift card amount fetched successfully",
    "data": {
        "gift_card_amount": 120,
        "gift_card_coupon_code":"A1A22A111FFF333",
        "updated_at": "2020-10-16 11:13:41"
    }
}
```
Key : giftcard.amount.failed, giftcard.amount.failed.validation

**Failed Response:**

```
{
    "status" : "failed",
    "message" : "coupon does not exists in record !",
}
```

## Order status for a customer 

**Request:**

```json
GET https://erp.theluxuryunlimited.com/api/customer/order-details?email=solanki7492@gmail.com&website=www.veralusso.com&order_no=000000001
Accept: application/json
Content-Type: application/json
'Authorization: Bearer (Requested_website_token)'
```
Key : customer.order.success

**Successful Response:**
```json
Content-Type: application/json

{
    "message":"Orders Fetched successfully",
    "status":200,
    "data":[
        {
            "id":6,
            "customer_id":2001,
            "order_id":"OFF-1000005",
            "order_type":"offline",
            "order_date":"2019-11-03",
            "price":null,
            "awb":null,
            "client_name":"Pravin Solanki",
            "city":null,
            "contact_detail":"919016398686",
            "clothing_size":null,
            "shoe_size":null,
            "advance_detail":null,
            "advance_date":null,
            "balance_amount":null,
            "sales_person":null,
            "office_phone_number":null,
            "order_status":"product shipped to client",
            "order_status_id":9,
            "date_of_delivery":null,
            "estimated_delivery_date":null,
            "note_if_any":null,
            "payment_mode":null,
            "received_by":null,
            "assign_status":null,
            "user_id":49,
            "refund_answer":null,
            "refund_answer_date":null,
            "auto_messaged":0,
            "auto_messaged_date":null,
            "auto_emailed":0,
            "auto_emailed_date":null,
            "remark":null,
            "is_priority":0,
            "coupon_id":null,
            "deleted_at":null,
            "created_at":"2019-11-03 23:10:23",
            "updated_at":"2020-10-10 11:05:24",
            "whatsapp_number":null,
            "currency":null,
            "invoice_id":2,
            "status_histories":[
                {
                    "status":"advance recieved",
                    "created_at":"2020-10-10 11:05:24"
                },
                {
                    "status":"proceed without advance",
                    "created_at":"2020-10-10 11:00:24"
                }
            ],
            "action":null
        },
    ]
}
```

Key : customer.order.failed, customer.order.failed.reference_no_absent, customer.order.failed.store_url_absent, customer.order.failed.store_not_found, customer.order.failed.token_missing, customer.order.failed.no_order_found

**Failed Response:**

```json
Content-Type: application/json
{
    "status": "400",
    "message": "Email is absent in your request"
}
```
## Buyback | Return | Exchange | Refund Check Product API

**Request:**

```json
GET https://erp.theluxuryunlimited.com/api/orders/products
Accept: application/json
Content-Type: application/json
{
    "customer_email" : "firasath90@gmail.com",
    "website" : "www.brands-labels.com",
    "order_id" : "000000012",
    "lang_code":"ae_ar", // Enter language code
}
```

**Successful Response:**
```json
{
    "status": "success",
    "orders": {
        "1": [
            {
                "product_name": "Dr. Osborne Harber",
                "product_price": "0",
                "sku": "6493033100078",
                "product_id": 296561,
                "order_id": "1"
            },
            {
                "product_name": "Dr. Osborne Harber",
                "product_price": "0",
                "sku": "6493033100079",
                "product_id": 296560,
                "order_id": "1"
            }
        ],
        "2": [
            {
                "product_name": "Dr. Osborne Harber",
                "product_price": "0",
                "sku": "6493033100080",
                "product_id": 296569,
                "order_id": "2"
            }
        ]
    }
}
```


key : buyback.failed, buyback.failed.validation, buyback.failed.no_order_found

**Failed Response:**
```json
Content-Type: application/json
{
    "status": "failed",
    "message": "Customer not found with this email !"
}
```
## Create Buyback | Return | Exchange | Cancellation | Refund request API

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/return-exchange-buyback/create
Accept: application/json
Content-Type: application/json
{
    "customer_email" : "firasath90@gmail.com",
    "website" : "www.brands-labels.com",
    "order_id" : "000000012",
    "product_sku" : "Test01",
    "type":"exchange",
    "lang_code":"ae_ar", // Enter language code
}
```
For type expected value will be "return","exchange","buyback","refund", "cancellation"


Type : return, exchange, buyback, refund, cancellation

key : [type].success

**Successful Response:**
```json
Content-Type: application/json
{
    "status": "success",
    "message": "Exchange request created successfully"
}
```

Type : return, exchange, buyback, refund, cancellation

Key : [type].failed, [type].failed.validation, [type].failed.no_order_found

**Failed Response:**
```json
Content-Type: application/json
{
    "status": "failed",
    "message": "Unable to create Exchange request!"
}
```

## Price comparison API

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/price_comparision/details
Accept: application/json
Content-Type: application/json
{
    "sku" : "565655VT0406512FW2019",
    "country" : "IN",
    "lang_code":"ae_ar", // Enter language code
}


key : price_compare.success
```
**Successful Response:**
```json
Content-Type: application/json
{
    "status": "sucess",
    "results": [
        {
            "name": "N/A",
            "currency": "USD",
            "price": "631.99",
            "country_code": "in"
        }
    ]
}
```

key : price_compare.failed, price_compare.failed.validation, price_compare.failed.no_price_comparision

**Failed Response:**
```json
Content-Type: application/json
{
    "status": "failed",
    "message": "No Category Found"
}
```


## Affilates Api

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/affiliate/add
Accept: application/json
Content-Type: application/json
{
    "website" : "www.brands-labels.com", //existing website
    "first_name":"xyz", //optional
    "last_name":"ABC", //optional
    "phone":"9999999999", //optional, string
    "emailaddress":"example@domain.com", //optional
    "website_name":"sololuxury", //optional, string
    "url":"url", //optional, string
    "unique_visitors_per_month":"unique_visitors_per_month", //optional, string
    "page_views_per_month":"page_views_per_month", //optional, string
    "street_address":"address", //optional, string
    "city":"Texas", //optional, string
    "postcode":"111111", //optional, string
    "country":"United States Of America", //optional, string
    "lang_code":"ae_ar", // Enter language code

}
```


key : affiliates.success

**Successful Response:**
```json
HTTP/1.1 200
Content-Type: application/json
{
    "status": "success",
    "message": "affiliate added successfully !"
}
```

key : affiliates.failed, affiliates.failed.validation

**Failed Response:**
```json
HTTP/1.1 500
Content-Type: application/json
{
    "status": "failed",
    "message": "unable to add affiliate !"
}
```


## Magneto Customer Reference Store API

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/magento/customer-reference
Accept: application/json
Content-Type: application/json
{
    "name" : "Wahiduzzaman", //required
    "email" : "wahidlaskar05@gmail.com", //required
    "phone": "918638973610", //optional
    "website": "WWW.SOLOLUXURY.COM",//required, must be a website in store websites
    "dob": "2020-10-23", //optional
    "wedding_anniversery": "2020-10-23", //optional
    "lang_code":"ae_ar", // Enter language code
    
}
```

key : customer_reference.success
**Successful Response:**
```json
Content-Type: application/json
{
     "status": "200",
    "message": "Saved SucessFully"
}
```

key : customer_reference.403
**Failed Response:**
```json
Content-Type: application/json
{
    "status": "403",
    "message": "Email is required"
}
```

## Tickets API

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/ticket/send
Accept: application/json
Content-Type: application/json
{
    "website" : "live_chat",
    "email" : "bardam.yus@gmail.com", //optional if ticket_id is set 
    "ticket_id":"PWTCR", //optional if email is set
    "per_page":"10", //optional, default is 15
    "lang_code":"ae_ar", // Enter language code
}
```
**Successful Response:**
```json
{
    "status": "success",
    "tickets": {
        "current_page": 1,
        "data": [
            {
                "id": 3,
                "customer_id": 3008,
                "name": "Bardambek Yusupov",
                "last_name": null,
                "email": "bardam.yus@gmail.com",
                "ticket_id": "PWTCR",
                "subject": "Task test",
                "message": "Message: Hi",
                "assigned_to": null,
                "source_of_ticket": "live_chat",
                "status_id": 1,
                "date": "2020-08-25 01:26:31",
                "created_at": "2020-09-11 11:48:23",
                "updated_at": "2020-09-11 12:08:33",
                "type_of_inquiry": null,
                "country": null,
                "phone_no": null,
                "order_no": null,
                "status": "open"
            },
            {
                "id": 4,
                "customer_id": 3008,
                "name": "Bardambek Yusupov",
                "last_name": null,
                "email": "bardam.yus@gmail.com",
                "ticket_id": "J7XPB",
                "subject": "About new Products",
                "message": "Message: Hi",
                "assigned_to": null,
                "source_of_ticket": "live_chat",
                "status_id": 1,
                "date": "2020-08-25 01:25:30",
                "created_at": "2020-09-11 11:48:23",
                "updated_at": "2020-09-11 11:48:23",
                "type_of_inquiry": null,
                "country": null,
                "phone_no": null,
                "order_no": null,
                "status": "open"
            }
        ],
        "first_page_url": "http://127.0.0.1:8000/api/ticket/send?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://127.0.0.1:8000/api/ticket/send?page=1",
        "next_page_url": null,
        "path": "http://127.0.0.1:8000/api/ticket/send",
        "per_page": 15,
        "prev_page_url": null,
        "to": 2,
        "total": 2
    }
}
```

key : ticket.send.failed, ticket.send.failed.validation, ticket.send.failed.ticket_or_email

**Failed Response:**
```json
Content-Type: application/json
{
    "status": "failed",
    "message": "Tickets not found for customer !"
}
```
## Push Notifications API

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/notification/create
Accept: application/json
Content-Type: application/json
{
    "website" : "WWW.SOLOLUXURY.COM", //required , exists in store websites
    "token" : "sdsad2e232dsdsd", //required 
    "lang_code":"ae_ar", // Enter language code
}
```

key : notification.success
**Successful Response:**
```json
HTTP/1.1 200
{
    "status": "success",
    "message": "Notification created successfully !"
}
```

key : notification.failed, notification.failed.validation

**Failed Response:**
```json
HTTP/1.1 500
Content-Type: application/json
{
    "status": "failed",
    "message": "Unable to create notifications !"
}

```

## Influencer Api

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/influencer/add
Accept: application/json
Content-Type: application/json
{
    "website" : "www.veralusso.com", //existing website
    "first_name":"Solo", //optional
    "last_name":"Luxury", //optional
    "phone":"9999999999", //optional, string
    "emailaddress":"solo@luxury.com", //optional
    "facebook":"example@domain.com", //optional
    "facebook_followers":"12M", //optional
    "instagram":"example@domain.com", //optional
    "instagram_followers":"10M", //optional
    "twitter":"example@domain.com", //optional
    "twitter_followers":"12M", //optional
    "youtube":"example@domain.com", //optional
    "youtube_followers":"25K", //optional
    "linkedin":"example@domain.com", //optional
    "linkedin_followers":"10K", //optional
    "pinterest":"example@domain.com", //optional
    "pinterest_followers":"5K", //optional
    "worked_on":"example@domain.com", //optional
    "website_name":"sololuxury", //optional, string
    "url":"url", //optional, string
    "country":"United States Of America", //optional, string
    "type" :"influencer",
    "lang_code":"ae_ar", // Enter language code
}
```

key : influencer.success

**Successful Response:**
```json
HTTP/1.1 200
Content-Type: application/json
{
    "status": "success",
    "message": "influencer added successfully !"
}
```

key : influencer.failed.validation, influencer.failed

**Failed Response:**
```json
HTTP/1.1 500
Content-Type: application/json
{
    "status": "failed",
    "message": "unable to add influencer !"
}
```

key : newsletter.success
## Newsletter Api
```json
POST https://erp.theluxuryunlimited.com/api/mailinglist/add
Accept: application/json
Content-Type: application/json
{
    "website" : "www.veralusso.com", //existing website
    "email":"Solo@theluxuryunlimited.com",
    "store_name" : "store name or store code",
    "lang_code":"ae_ar", // Enter language code
}
```

key : newsletter.success
**Successful Response:**

```json
Content-Type: application/json
{
    "code": 200,
    "message": "Newsletter has been added succesfully SOLO LUXURY"
}
```


key : newsletter.failed, newsletter.failed.already_subscribed

**Failed Response:**

```json
Content-Type: application/json
{
    "code": 500,
    "message": "You have already subscibed newsletter"
}
```

## Store data into the laravel logs
**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/laravel-logs/save
Accept: application/json
Content-Type: application/json
{
    "message": "error-message",
    "website": "Farfetch",  
    "url": "https:\/\/www.farfetch.com\/mt\/shopping\/kids\/young-versace-crystal-logo-t-shirt-item-15339323.aspx?q=YC000346YA00019A1008",
    "lang_code":"ae_ar", // Enter language code
}
```


key : laravel.log.success
**Successful Response:**

```json
Content-Type: application/json
{
    "status": "success",
    "message": "Log data Saved"
}
```


key : laravel.log.failed
**Failed Response:**

```json
Content-Type: application/json

{
    "status": "failed",
    "message": "{message}"
}
```
## Return response status for order ID if its returnable or cancelable
**Request:**

```json
GET https://erp.theluxuryunlimited.com/api/orders/check-cancellation
Accept: application/json
Content-Type: application/json
{
    "website" : "www.brands-labels.com",
    "order_id" : "000000012",
    "lang_code":"ae_ar", // Enter language code
}
```

Key : order.cancel.success

**Successful Response:**
```json
{
    "code": 200,
    "message": "Success",
    "data": {
        "order_id": "000000001",
        "website": "www.veralusso.com",
        "iscanceled": false,
        "isrefund": true
    }
}
```
Key : order.cancel.failed, order.cancel.failed.website_missing

**Failed Response:**
```json
{
    "code": 500,
    "message": "data not found.",
    "data": []
}
```

## Magento order create

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/magento/order-create
Accept: application/json
Content-Type: application/json
{
           
            "website": "WWW.SOLOLUXURY.COM",
            "lang_code":"ae_ar", // Enter language code
            "base_currency_code": "EUR",
            "base_discount_amount": 0,
            "base_grand_total": 495,
            "base_discount_tax_compensation_amount": 0,
            "base_shipping_amount": 0,
            "base_shipping_discount_amount": 0,
            "base_shipping_discount_tax_compensation_amnt": 0,
            "base_shipping_incl_tax": 0,
            "base_shipping_tax_amount": 0,
            "base_subtotal": 495,
            "base_subtotal_incl_tax": 495,
            "base_tax_amount": 0,
            "base_total_due": 495,
            "base_to_global_rate": 1,
            "base_to_order_rate": 4.4756,
            "billing_address_id": 8,
            "created_at": "2021-02-25 17:37:17",
            "customer_email": "jamesadolf1970@iclodd.com",
            "customer_firstname": "James",
            "customer_group_id": 1,
            "customer_id": 72,
            "customer_is_guest": 0,
            "customer_lastname": "Adolf",
            "customer_note_notify": 1,
            "discount_amount": 0,
            "email_sent": 1,
            "entity_id": 4,
            "global_currency_code": "EUR",
            "grand_total": 2220,
            "discount_tax_compensation_amount": 0,
            "increment_id": "206000000001",
            "is_virtual": 0,
            "order_currency_code": "AED",
            "protect_code": "91531c793d0bbcef689c3a74d01c1522",
            "quote_id": 13,
            "remote_ip": "86.99.143.188",
            "shipping_amount": 0,
            "shipping_description": "Free Shipping - Free",
            "shipping_discount_amount": 0,
            "shipping_discount_tax_compensation_amount": 0,
            "shipping_incl_tax": 0,
            "shipping_tax_amount": 0,
            "state": "new",
            "status": "pending",
            "store_currency_code": "EUR",
            "store_id": 206,
            "store_name": "UAE\nUAE\nEnglish",
            "store_to_base_rate": 0,
            "store_to_order_rate": 0,
            "subtotal": 2220,
            "subtotal_incl_tax": 2220,
            "tax_amount": 0,
            "total_due": 2220,
            "total_item_count": 1,
            "total_qty_ordered": 1,
            "updated_at": "2021-02-25 17:37:20",
            "weight": 0,
            "x_forwarded_for": "86.99.143.188",
            "items": [
                {
                    "amount_refunded": 0,
                    "base_amount_refunded": 0,
                    "base_discount_amount": 0,
                    "base_discount_invoiced": 0,
                    "base_discount_tax_compensation_amount": 0,
                    "base_original_price": 495,
                    "base_price": 495,
                    "base_price_incl_tax": 495,
                    "base_row_invoiced": 0,
                    "base_row_total": 495,
                    "base_row_total_incl_tax": 495,
                    "base_tax_amount": 0,
                    "base_tax_invoiced": 0,
                    "created_at": "2021-02-25 17:37:17",
                    "discount_amount": 0,
                    "discount_invoiced": 0,
                    "discount_percent": 0,
                    "free_shipping": 0,
                    "discount_tax_compensation_amount": 0,
                    "is_qty_decimal": 0,
                    "is_virtual": 0,
                    "item_id": 4,
                    "name": "SNEAKERS",
                    "no_discount": 0,
                    "order_id": 4,
                    "original_price": 2220,
                    "price": 2220,
                    "price_incl_tax": 2220,
                    "product_id": 7931,
                    "product_type": "simple",
                    "qty_canceled": 0,
                    "qty_invoiced": 0,
                    "qty_ordered": 1,
                    "qty_refunded": 0,
                    "qty_shipped": 0,
                    "quote_item_id": 12,
                    "row_invoiced": 0,
                    "row_total": 2220,
                    "row_total_incl_tax": 2220,
                    "row_weight": 0,
                    "sku": "CS1823AW478White",
                    "store_id": 206,
                    "tax_amount": 0,
                    "tax_invoiced": 0,
                    "tax_percent": 0,
                    "updated_at": "2021-02-25 17:37:17",
                    "weight": 0
                }
            ],
            "billing_address": {
                "address_type": "billing",
                "city": "Dubai",
                "country_id": "AE",
                "customer_id": 72,
                "email": "jamesadolf1970@iclodd.com",
                "entity_id": 8,
                "firstname": "james",
                "lastname": "adolf",
                "parent_id": 4,
                "postcode": "13323",
                "street": [
                    "3759 No.232, Al-Narjis District"
                ],
                "telephone": "0508309192"
            },
            "payment": {
                "account_status": null,
                "additional_information": [
                    "Cash On Delivery",
                    ""
                ],
                "amount_ordered": 2220,
                "base_amount_ordered": 495,
                "base_shipping_amount": 0,
                "cc_last4": null,
                "entity_id": 4,
                "method": "cashondelivery",
                "parent_id": 4,
                "shipping_amount": 0
            },
            "status_histories": [],
            "extension_attributes": {
                "shipping_assignments": [
                    {
                        "shipping": {
                            "address": {
                                "address_type": "shipping",
                                "city": "Dubai",
                                "country_id": "AE",
                                "customer_id": 72,
                                "email": "jamesadolf1970@iclodd.com",
                                "entity_id": 7,
                                "firstname": "james",
                                "lastname": "adolf",
                                "parent_id": 4,
                                "postcode": "13323",
                                "street": [
                                    "3759 No.232, Al-Narjis District"
                                ],
                                "telephone": "0508309192"
                            },
                            "method": "freeshipping_freeshipping",
                            "total": {
                                "base_shipping_amount": 0,
                                "base_shipping_discount_amount": 0,
                                "base_shipping_discount_tax_compensation_amnt": 0,
                                "base_shipping_incl_tax": 0,
                                "base_shipping_tax_amount": 0,
                                "shipping_amount": 0,
                                "shipping_discount_amount": 0,
                                "shipping_discount_tax_compensation_amount": 0,
                                "shipping_incl_tax": 0,
                                "shipping_tax_amount": 0
                            }
                        },
                        "items": [
                            {
                                "amount_refunded": 0,
                                "base_amount_refunded": 0,
                                "base_discount_amount": 0,
                                "base_discount_invoiced": 0,
                                "base_discount_tax_compensation_amount": 0,
                                "base_original_price": 495,
                                "base_price": 495,
                                "base_price_incl_tax": 495,
                                "base_row_invoiced": 0,
                                "base_row_total": 495,
                                "base_row_total_incl_tax": 495,
                                "base_tax_amount": 0,
                                "base_tax_invoiced": 0,
                                "created_at": "2021-02-25 17:37:17",
                                "discount_amount": 0,
                                "discount_invoiced": 0,
                                "discount_percent": 0,
                                "free_shipping": 0,
                                "discount_tax_compensation_amount": 0,
                                "is_qty_decimal": 0,
                                "is_virtual": 0,
                                "item_id": 4,
                                "name": "SNEAKERS",
                                "no_discount": 0,
                                "order_id": 4,
                                "original_price": 2220,
                                "price": 2220,
                                "price_incl_tax": 2220,
                                "product_id": 7931,
                                "product_type": "simple",
                                "qty_canceled": 0,
                                "qty_invoiced": 0,
                                "qty_ordered": 1,
                                "qty_refunded": 0,
                                "qty_shipped": 0,
                                "quote_item_id": 12,
                                "row_invoiced": 0,
                                "row_total": 2220,
                                "row_total_incl_tax": 2220,
                                "row_weight": 0,
                                "sku": "CS1823AW478White",
                                "store_id": 206,
                                "tax_amount": 0,
                                "tax_invoiced": 0,
                                "tax_percent": 0,
                                "updated_at": "2021-02-25 17:37:17",
                                "weight": 0
                            }
                        ]
                    }
                ],
                "payment_additional_info": [
                    {
                        "key": "method_title",
                        "value": "Cash On Delivery"
                    },
                    {
                        "key": "instructions",
                        "value": ""
                    }
                ],
                "applied_taxes": [],
                "item_applied_taxes": []
            }
}
```
Key : magento.order.success

**Successful Response:**

```json
Content-Type: application/json
{
    "status": true,
    "message": "Order create successfully"
}
```

Key : magento.order.failed, magento.order.failed.validation

**Failed Response:**

```json
Content-Type: application/json

{
    "status": false,
    "message": "Something went wrong, Please try again"
}
```

## Send screenshot from scraper
**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/scrape/send-screenshot
"website" : "www.brands-labels.com",
"screenshot" : "insert-file-here"
```

**Successful Response:**
```json
{
    "code": 200,
    "data": [],
    "message": "Screenshot saved successfully"
}
```
**Failed Response:**
```json
{
    "code": 500,
    "data": [],
    "message": "Error message"
}
```

## Send position from scraper
**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/scrape/send-position
{
    "website": "giglio",
    "comment" : "Hello world"
}
```

**Successful Response:**
```json
{
    "code": 200,
    "data": [],
    "message": "History saved successfully"
}
```
**Failed Response:**
```json
{
    "code": 500,
    "data": [],
    "message": "Error message"
}
```


## check auto restart scraper
**Request:**

```json
GET https://erp.theluxuryunlimited.com/api/scraper/auto-restart?website=giglio
{
    "website": "giglio"
}
```

**Successful Response:**
```json
{
    "code": 200,
    "auto_restart": 1
}
```
**Failed Response:**
```json
{
    "code": 500,
    "data": [],
    "message": "Error message"
}
```

## set restart time on erp
**Request:**

```json
GET https://erp.theluxuryunlimited.com/api/scraper/update-restart-time?website=giglio
{
    "website": "giglio"
}
```

**Successful Response:**
```json
{
    "code": 200,
    "auto_restart": 1
}
```
**Failed Response:**
```json
{
    "code": 500,
    "data": [],
    "message": "Error message"
}
```

## Check Return refund request
**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/order/check-return
{
   "website":"https://www.farfetch.com",
   "product_sku" :"5I246C959F0118-*",
   "order_id" : ""
}
```

**Successful Response:**
```json
{
    "code": 200,
    "message": "Success",
    "data": {
        "has_return_request": false
    }
}
```
**Failed Response:**
```json
{
    "code": 500,
    "data": [],
    "message": "Error message"
}
```

## Create wish list request

Key : wishlist.failed.validation, wishlist.create.success

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/wishlist/create
{
   "website":"WWW.SOLOLUXURY.COM",
   "product_sku" :"5I246C959F0118-*",
   "customer_name" : "Customer name",
   "customer_email" : "info@solo.com",
   "language_code" : "es-ca",
   "product_name" : "some name",
   "product_price" : "250",
   "product_currency" : "USD"
}
```

**Successful Response:**
```json
{
    "status": "200",
    "message": "Wishlist created successfully"
}
```
**Failed Response:**
```json
{
    "code": 500,
    "data": [],
    "message": "Error message"
}
```


## Remove wish list request

Key : wishlist.failed.validation, wishlist.remove.success, wishlist.remove.no_product

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/wishlist/remove
{
   "website":"WWW.SOLOLUXURY.COM",
   "product_sku" :"5I246C959F0118-*",
   "customer_email" : "info@solo.com"
}
```

**Successful Response:**
```json
{
    "status": "200",
    "message": "Wishlist removed successfully"
}
```
**Failed Response:**
```json
{
    "code": 500,
    "data": [],
    "message": "Error message"
}
```


## Add address

**Request:**

```json
POST https://erp.theluxuryunlimited.com/api/customer/add_customer_data?website=www.brands-labels.com&email=test@gmail.com
[{
    "entity_id" : 12,
    "address_type" : "shipping",
    "region" : "EU",
    "region_id" : "12",
    "postcode" : "12",
    "firstname" : "1ad",
    "middlename" : "J",
    "company" : "Test",
    "country_id" :96,
    "telephone" : "987542011",
    "prefix" : "mr",
    "street" : "test"
}]
```

**Successful Response:**
```json
{
    "status": "200"
}
```
**Failed Response:**
```json
{
    "code": 404
}
```
