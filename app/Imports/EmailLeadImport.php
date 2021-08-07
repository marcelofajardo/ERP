<?php

namespace App\Imports;

use App\EmailLead;

use Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;


class EmailLeadImport implements ToModel, ShouldQueue, WithChunkReading, WithValidation
{
	use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
		if($row[0] != "email")
		{
			$count = EmailLead::where('email', '=', $row[0])->get();
			
			if(!$count->count())
			{	
				return new EmailLead([
					'email'            => $row[0],
					'source'           => $row[1],
					'created_at'       => date('Y-m-d H:i:s'),
				]);
			}
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
          'email' => 'unique:email',
		];
    }
}
