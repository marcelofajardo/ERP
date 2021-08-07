<?php

namespace App\Http\Controllers;

use App\Benchmark;
use Illuminate\Http\Request;

class BenchmarkController extends Controller
{
	public function create(){


		$benchmark = Benchmark::orderBy('for_date', 'DESC')->first();

		$data = [];

		if ( empty( $benchmark ) ) {
			$benchmark = new Benchmark();

			foreach ( $benchmark->getFillable() as $item ) {
				$data[ $item ] = 0;
			}
		}
		else{

			$data = $benchmark->toArray();
		}

		$data['for_date'] = date( 'Y-m-d' );

		return view('activity.benchmark',$data);
	}

	public function store(Request $request){

		$data = $request->all();
		$data['for_date'] = date( 'Y-m-d' );

		Benchmark::updateOrCreate( [ 'for_date' => date( 'Y-m-d' ) ], $data );

		return back()->with('status', 'Benchmark Updated');
	}

}
