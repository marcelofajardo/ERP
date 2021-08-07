<?php


namespace App\Services\Listing;


interface CheckerInterface
{
    public function check($data);

    public function improvise($data, $data2 = null);
}