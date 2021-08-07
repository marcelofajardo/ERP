<?php

namespace App\Exports;

use App\Customer;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomersExport implements FromArray, ShouldAutoSize
{
  protected $customers;

  public function __construct(array $customers)
  {
    $this->customers = $customers;
  }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
      $new_customers = [];

      foreach ($this->customers as $key => $customer) {
        $filtered = str_replace('-', ' ', $customer['name']);
        $explode = explode(' ', $filtered);

        $new_customers[$key]['name'] = $explode[0] . (array_key_exists(1, $explode) ? (" " . $explode[1]) : "");
        $new_customers[$key]['phone'] = $customer['phone'];
      }

      return $new_customers;
    }
}
