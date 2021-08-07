<?php

namespace App\Services\Instagram;


use App\PeopleNames;

class Nationality {

    public function isIndian($name, $username) {
        $nameParts = explode(' ', $name);

        $name = PeopleNames::whereIn('name', $nameParts)
                        ->orWhere(\DB::raw("LOCATE(`name`, $name)"))
                        ->orWhere(\DB::raw("LOCATE(`name`, $username)"))
                        ->first();

        if ($name) {
            return [
                $name->gender,
                $name->race,
            ];
        }

        return [false, false];
    }
}
