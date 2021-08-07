<?php

namespace App\Http\Controllers\Marketing;

use App\Customer;
use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ServiceController extends Controller
{
    public function index() {
        $data = Service::orderBy('id', 'desc')->paginate(15);
        return view('marketing.services.index', compact('data'));
    }

    public function store (Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'text' => 'required'
        ]);

        $data = Service::create([
            'name' => $request->name,
            'description' => $request->text
        ]);

        return response()->json([
            $data
        ]);
    }

    public function destroy (Request $request) {
        Service::destroy($request->id);

        return response()->json([
            $request->id
        ]);

    }

    public function update (Request $request) {
/*        dd($request->id);*/

        $updated = Service::findOrFail($request->id);

        $updated->name = $request->name;
        $updated->description  = $request->description;

        $updated->save();

        $data = Service::findOrFail($request->id);

        return response()->json([
            $data
        ]);
    }

}
