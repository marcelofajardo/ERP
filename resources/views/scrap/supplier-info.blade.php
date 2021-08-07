@extends('layouts.app')
@section('title', 'Supplier Scrapping Info')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <h2 class="page-heading">Supplier Scrapping Info</h2>
    </div>
    <div class="col-lg-12 margin-tb">
        <form method="get" class="form-inline">
            <div class="col-md-4 col-lg-3 col-xl-3 p-0 mt-4">
                <div class="form-group">
                    <input name="search" type="text" placeholder="Search" class="form-control"
                        value="{{!empty(request()->search) ? request()->search : ''}}">
                </div>
            </div>
            <div class="col-md-4 col-lg-2 col-xl-2 mt-4 p-0">
                <div class="form-group">
                    {!! Form::select('search_type', array(), null, ['class' => 'form-control', 'placeholder' => 'Search Type']) !!}
                </div>
            </div>
            <div class="col-md-4 col-lg-4 col-xl-4 p-0">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input name="start_date" type="date" placeholder="Start Date" class="form-control"
                        value="{{!empty(request()->start_date) ? request()->start_date : ''}}">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input name="end_date" type="date" placeholder="End Date" class="form-control"
                        value="{{!empty(request()->end_date) ? request()->end_date : ''}}">
                </div>
            </div>
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Sr No.</th>
                        <th scope="col" class="text-center">Supplier ID</th>
                        <th scope="col" class="text-center">Supplier Name</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Scrapper Type</th>
                        <th scope="col" class="text-center">Last Scrapped</th>
                        <th scope="col" class="text-center">Progress</th>
                        <th scope="col" class="text-center">Inventory</th>
                        <th scope="col" class="text-center">New</th>
                        <th scope="col" class="text-center">Removed</th>
                        <th scope="col" class="text-center">Total</th>
                        <th scope="col" class="text-center">Info Scrapping</th>
                        <th scope="col" class="text-center">Developer</th>
                        <th scope="col" class="text-center">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>12</td>
                        <td>amrstore</td>
                        <td>NodeJS</td>
                        <td>21-08 15:30</td>
                        <td>100%</td>
                        <td>2128</td>
                        <td>212</td>
                        <td></td>
                        <td>2340</td>
                        <td>N/A</td>
                        <td>Ashok</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection