<?php

return [
    'api'     => [
        'DHL_ID'          => env('DHL_ID', ''),
        'DHL_KEY'         => env('DHL_KEY', ''),
        'DHL_USER'        => env('DHL_USER', ''),
        'DHL_PASSWORD'    => env('DHL_PASSWORD', ''),
        'DHL_ACCOUNT'     => env('DHL_ACCOUNT', ''),
        'DHL_COUNTRY'     => env('DHL_COUNTRY', 'ZA'),
        'DHL_CURRECY'     => env('DHL_CURRECY', 'USD'),
        'DHL_COUNTRYCODE' => env('DHL_COUNTRYCODE', ''),
        'DHL_POSTALCODE'  => env('DHL_POSTALCODE', ''),
        'DHL_CITY'        => env('DHL_CITY', ''),
    ],
    'shipper' => [
        "street"       => "105 , 5 E A DAFZA DAFZA",
        "city"         => "DUBAI",
        "postal_code"  => "0000",
        "country_code" => "AE",
        "person_name"  => "JAMES ADOLF",
        "company_name" => "LUXURY UNLIMITED",
        "phone"        => "971502609192",
    ],
];
