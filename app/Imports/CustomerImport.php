<?php

namespace App\Imports;

use Validator;
use App\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class CustomerImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithValidation, SkipsOnError, SkipsOnFailure, ShouldQueue
{

  use Importable, SkipsErrors, SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
      $validator = Validator::make($row, [
        'contact_no' => 'required|unique:customers,phone'
      ]);

      if ($validator->fails()) {
        return NULL;
      } else {
        if (strtoupper($row['mobile']) == 'LXRY 00') {
          $row['mobile'] = '919167152579';
        } elseif (strtoupper($row['mobile']) == 'LXRY 02') {
          $row['mobile'] = '918291920452';
        } elseif (strtoupper($row['mobile']) == 'LXRY 03') {
          $row['mobile'] = '918291920455';
        } elseif (strtoupper($row['mobile']) == 'LXRY 04') {
          $row['mobile'] = '919152731483';
        } elseif (strtoupper($row['mobile']) == 'LXRY 05') {
          $row['mobile'] = '919152731484';
        } elseif (strtoupper($row['mobile']) == 'LXRY 06') {
          $row['mobile'] = '971562744570';
        } elseif (strtoupper($row['mobile']) == 'LXRY 08') {
          $row['mobile'] = '918291352520';
        } elseif (strtoupper($row['mobile']) == 'LXRY 09') {
          $row['mobile'] = '919004008983';
        }

        return new Customer([
          'name'            => $row['name'],
          'phone'           => $row['contact_no'],
          'city'            => $row['cities'],
          'whatsapp_number' => $row['mobile']
        ]);
      }
    }

    public function batchSize(): int
    {
        return 10;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 10;
    }

    public function rules(): array
    {
      return [
          'contact_no' => 'required|unique:customers,phone',

           // Above is alias for as it always validates in batches
           '*.contact_no' => 'required|unique:customers,phone',
      ];
    }
}
