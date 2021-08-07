<?php

namespace App\Imports;

use App\ColdLeads;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ColdLeadsImport implements ToCollection, WithHeadingRow
{

    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $lead) {
            $name = $lead['name'];
            $contact = (integer) $lead['contact_no'];
            $city = $lead['cities'];

            $coldLead = new ColdLeads();
            $coldLead->name = $name;
            $coldLead->username = $contact;
            $coldLead->platform = 'whatsapp';
            $coldLead->platform_id = $contact;
            $coldLead->rating = 10;
            $coldLead->bio = 'Imported from excel sheet';
            $coldLead->because_of = 'Excel Import';
            $coldLead->country = 'India';
            $coldLead->address = $city;
            $coldLead->is_imported = 1;
            $coldLead->save();

            dump($lead['sn']);

        }
    }
}
