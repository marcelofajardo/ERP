<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorSupplierController extends Controller
{
    public function index()
    {
        return view("vendor-supplier.index");
    }

    public function vendorForm()
    {
        return view("vendor-supplier.vendor-form");
    }

    public function supplierForm()
    {
        $suppliercategory = \App\SupplierCategory::pluck('name', 'id')->toArray();
        $supplierstatus = \App\SupplierStatus::pluck('name', 'id')->toArray();

        return view("vendor-supplier.supplier-form", compact(['suppliercategory','supplierstatus']));
    }
}
