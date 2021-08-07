<?php

return [
    'parent'=> 'parent_id',
    'primary_key' => 'id',
    'generate_url'   => false,
    'childNode' => 'child',
    'body' => [
        'id',
        'title',
//        'slug',
    ],
    'html' => [
        'label' => 'title',
        'href'  => 'title'
    ],
    'dropdown' => [
        'prefix' => '',
        'label' => 'title',
        'value' => 'id'
    ]
];
