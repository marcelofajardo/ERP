<?php

namespace App\Http\Controllers;

use App\Library\Duty\SimplyDuty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryDutyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Country duties";

        return view("country-duty.index", compact('title'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response Json
     */
    public function search(Request $request)
    {
        // validation report generator
        $validator = Validator::make($request->all(), [
            'hs_code'               => 'required',
            'origin_country'        => 'required',
            'destination_country.*' => 'required',
            'item_value'            => 'required',
        ]);

        if ($validator->fails()) {
            $outputString = "";
            $messages     = $validator->errors()->getMessages();
            foreach ($messages as $k => $errr) {
                foreach ($errr as $er) {
                    $outputString .= "$k : " . $er . "<br>";
                }
            }
            return response()->json(["code" => 500, "error" => $outputString]);
        }

        $hscode      = $request->hs_code;
        $origin      = $request->origin_country;
        $value       = $request->item_value;
        $destination = $request->destination_country;

        $simplyDuty   = new SimplyDuty;
        $errorMessage = [];
        $response     = [];
        if ($destination != null) {
            foreach (explode(",", $destination) as $dest) {
                $dest   = strtoupper($dest);
                $result = $simplyDuty->calculate(
                    $origin,
                    $dest,
                    $hscode,
                    1,
                    $value
                );

                if (!empty($result->error)) {
                    $errorMessage[] = $result->error;
                    continue;
                } else {
                    $result                = json_decode(json_encode($result), true);
                    $result["Origin"]      = $origin;
                    $result["Destination"] = $dest;
                    $response[]            = $result;
                }
            }
        }

        return response()->json(["code" => 200, "data" => $response, "error_message" => $errorMessage, "total" => count($response)]);
    }

    public function saveCountryGroup(Request $request)
    {
        // validation report generator
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'groups.*' => 'required',
        ]);

        if ($validator->fails()) {
            $outputString = "";
            $messages     = $validator->errors()->getMessages();
            foreach ($messages as $k => $errr) {
                foreach ($errr as $er) {
                    $outputString .= "$k : " . $er . "<br>";
                }
            }
            return response()->json(["code" => 500, "message" => $outputString]);
        }

        $rates  = [];
        $groups = $request->groups;

        foreach ($groups as $k => $g) {
            $rates[$k] = $g["duty-rate"];
        }

        $k = array_keys($rates, max($rates));

        if (isset($k[0]) && !empty($groups[$k[0]])) {
            $key = $k[0];
            // first create a group and then assing country to that group
            $group          = new \App\DutyGroup;
            $group->name    = $request->name;
            $group->hs_code = $groups[$key]["hs-code"];
            $group->vat     = $groups[$key]["vat-rate"];
            $group->duty    = $groups[$key]["duty-rate"];

            if ($group->save()) {
                foreach ($groups as $gM) {
                    $countryDuty                  = new \App\CountryDuty;
                    $countryDuty->hs_code         = $gM["hs-code"];
                    $countryDuty->origin          = $gM["origin"];
                    $countryDuty->destination     = $gM["destination"];
                    $countryDuty->currency        = $gM["currency-origin"];
                    $countryDuty->price           = $gM["total"];
                    $countryDuty->duty            = $gM["duty-val"];
                    $countryDuty->vat             = $gM["vat-val"];
                    $countryDuty->duty_percentage = $gM["duty-rate"];
                    $countryDuty->vat_percentage  = $gM["vat-rate"];
                    $countryDuty->duty_group_id   = $group->id;
                    if ($countryDuty->save()) {

                    }
                }

                return response()->json(["code" => 200, "data" => [], "message" => "Added successfully"]);
            }

            return response()->json(["code" => 500, "data" => [], "message" => "Group record missing while checking max ranage."]);
        }

        return response()->json(["code" => 500, "data" => [], "message" => "Oops, some field is missing."]);

    }

    function list(Request $request) {

        $title = "Country Group List";

        return view("country-duty.group.index", compact('title'));

    }

    public function records(Request $request)
    {
        $records = \App\CountryDuty::leftJoin("duty_groups as dg", "dg.id", "country_duties.duty_group_id");

        if ($request->keyword != null) {
            $records = $records->where("country_duties.hs_code", "like", "%" . $request->keyword . "%");
        }

        if ($request->destination != null) {
            $records = $records->where("country_duties.destination", "like", "%" . $request->destination . "%");
        }

        if ($request->group_name != null) {
            $records = $records->where("dg.name", "like", "%" . $request->group_name . "%");
        }

        $records = $records->select(["country_duties.*", "dg.name as group_name", "dg.duty as group_duty", "dg.vat as group_vat"])->get();

        return response()->json(["code" => 200, "data" => $records, "total" => $records->count(), "is_admin" => \Auth::user()->isAdmin()]);
    }

    /**
     * Edit Page
     * @param  Request $request [description]
     * @return
     */

    public function edit(Request $request, $id)
    {
        $modal = \App\CountryDuty::where("id", $id)->first();

        if ($modal) {
            return response()->json(["code" => 200, "data" => $modal]);
        }

        return response()->json(["code" => 500, "error" => "Id is wrong!"]);
    }

    /**
     * delete Page
     * @param  Request $request [description]
     * @return
     */

    public function delete(Request $request, $id)
    {
        $modal = \App\CountryDuty::where("id", $id)->first();

        if ($modal) {
            $modal->delete();
            return response()->json(["code" => 200, "data" => $modal]);
        }

        return response()->json(["code" => 500, "error" => "Id is wrong!"]);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');

        $data = \App\CountryDuty::create($data);

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function updateGroupField(Request $request)
    {
        $params = $request->all();

        if(isset($params['id'])) {
            $countryGroup = \App\DutyGroup::find($params['id']); 
            if($countryGroup) {
                $countryGroup->{$params['field']} = $params['value'];
                $countryGroup->save();
                return response()->json(["code" => 200, "error" => "Stored successfully"]);
            }

        }

        return response()->json(["code" => 500, "error" => "Id is wrong!"]);
    }

}
