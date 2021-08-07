<?php

$minutes = range(0, 59);
$hrs = range(1, 24);
$days = range(1, 31);
$months = range(1, 12);
$weeks = range(0, 6);

return array(
    'uploads_dir' => '/uploads/',
    'archive__dir' => '/uploads/archives/',
    'media_tags' => ['untagged'],
    'attach_image_tag' => ['original','gallery','untagged'],
    'media_original_tag' => ['original'],
    'media_gallery_tag' => 'gallery',
    'media_barcode_tag' => ['barcode'],
    'media_screenshot_tag' => ['screenshot'],
    'media_barcode_path' => "/uploads/product-barcode/",
    'paginate' => '10',
    'image_per_folder' => '10000',
    'excelimporter' => 'excelimporter',
    'gd_supported_files' => ["jpg", "jpeg", "png", "webp", "gif"],
    'cron_minutes' => [
            "*" => "Every Minutes",
            "5" => "Every Five Minutes",
            "10" => "Every Ten Minutes",
            "15" => "Every Fifteen Minutes",
        ] + $minutes,
    'cron_hours' => [
            "*" => "Every Hours",
            "4" => "Every Four Hours",
            "6" => "Every Six Hours",
        ] + $hrs,
    'cron_days' => [
            "*" => "Every Day",
        ] + $days,
    'cron_months' => [
            "*" => "Every Months",
        ] + $months,
    'cron_weekdays' => [
            "*" => "Every WeekDay",
        ] + $weeks,
    'google_text_search' => 'googletextsearch',

    'size_chart_media_tag' => 'size_chart',


    /* Google Webmaster Credentials */
    'GOOGLE_CLIENT_APPLICATION_NAME' => env('GOOGLE_CLIENT_APPLICATION_NAME'),
    'GOOGLE_CLIENT_ID' => env('GOOGLE_CLIENT_ID'),
    'GOOGLE_CLIENT_SECRET' => env('GOOGLE_CLIENT_SECRET'),
    'GOOGLE_CLIENT_KEY' => env('GOOGLE_CLIENT_KEY'),
    'py_facebook_script' => env('PY_FB_SCRIPT_URL')
);
