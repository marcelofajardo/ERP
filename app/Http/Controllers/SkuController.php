<?php

namespace App\Http\Controllers;

use App\SkuColorReferences;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Input;

class SkuController extends Controller
{
    public function colorCodes(Request $request)
    {
        // Set order
        $orderBy = $request->get('order_by', 'brands.name');

        // Data
        $data = SkuColorReferences::join('brands', 'sku_color_references.brand_id', '=', 'brands.id')
            ->select('sku_color_references.*', 'brands.name');

        // Check for filters
        if (!empty($request->get('brand', ''))) {
            $data = $data->where('brands.name', 'like', '%' . $request->get('brand', '') . '%');
        }

        // Add pagination
        $data = $data->orderBy($orderBy, 'ASC')->paginate(50)->appends(request()->except(['page']));

        // If the request is ajax
        if ($request->ajax()) {
            return response()->json([
                'tbody' => view('sku.color-codes-presult', compact('data'))->render(),
                'links' => (string)$data->render()
            ], 200);
        }

        // Return view
        return view('sku.color-codes', compact('data'));
    }

    public function colorCodesUpdate(Request $request)
    {
        // Check for required data
        if ((int)$request->get('id') > 0 && !empty($request->get('color_code'))) {
            // Get color reference
            $skuColorReference = SkuColorReferences::find((int)$request->get('id'));

            // We have a winner
            if ($skuColorReference != null) {
                // Update SKU color reference
                $skuColorReference->color_code = $request->get('color_code');
                $skuColorReference->save();

                // Return success
                return response()->json(['status' => 'ok'], 200);
            }
        }

        // Still here? Return NOK
        return response()->json(['status' => 'nok'], 200);
    }
}
