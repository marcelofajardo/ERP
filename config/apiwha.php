<?php

return [
  'api_keys' => [
        [
            'number' => '971562744570',
            'key' => 'Z802FWHI8E2OP0X120QR'
        ],
        [
            'number' => '919152731483',
            'key' => '1KWDP9M0LDCKY9O6QQW8'
        ],
        [
            'number' => '919004418502',
            'key' => 'YRM9TGDQ4JPSFYRQML28'
        ],

    ],
    'media_path' => realpath(implode(DIRECTORY_SEPARATOR, array(__DIR__, "..", "public", "apiwha", "media"))),
    'instances' => [
//        "919004780634" => [
//            "instance_id" => 43281,
//            "token" => "yi841xjhrwyrwrc7",
//            "customer_number" => false,
//        ],
//        "971504289967" => [
//            "instance_id" => 43111,
//            "token" => "wml12asm3opxwgbc",
//            "customer_number" => true,
//        ],
        "971545889192" => [
            "instance_id" => 43112,
            "token" => "vbi9bpkoejv2lvc4",
            "customer_number" => true,
        ],
//        "971562744570" => [
//            "instance_id" => 55202,
//            "token" => "42ndn0qg5om26vzf",
//            "customer_number" => true,
//        ],
//        "971547763482" => [
//            "instance_id" => 55211,
//            "token" => "3b92u5cbg215c718",
//            "customer_number" => true,
//        ],
        "971502609192" => [
            "instance_id" => 62439,
            "token" => "jdcqh3ladeuvwzp4",
            "customer_number" => false,
        ],
        "971569119192" => [
            "instance_id" => 95901,
            "token" => "5m5009tmunnz6g1o",
            "customer_number" => false,
        ],
        // Default
        "0" => [
            "number" => '971502609192', // Default number!
            "instance_id" => 62439,
            "token" => "jdcqh3ladeuvwzp4",
            "customer_number" => false,
        ],
//        OLD 04
//        "919152731483" => [
//            "instance_id" => 55211,
//            "token" => "3b92u5cbg215c718"
//        ],
    ],
    "message_queue_limit" => 300
];