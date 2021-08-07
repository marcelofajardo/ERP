@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2>Sku Color Codes</h2>
        </div>
    </div>

    {!! $data->render() !!}

    <table class="table table-bordered" id="sku-table">
        <thead>
        <tr>
            <th class="sorting" data-sorting_type="asc" data-column_name="brands.name" style="cursor: pointer">Brand</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="color_name" style="cursor: pointer">Color Name</th>
            <th class="sorting" data-sorting_type="asc" data-column_name="color_code" style="cursor: pointer">Color Code</th>
        </tr>
        <tr>
            <th><input type="text" id="filter-brand" style="width: 100%;" value="<?= isset($_GET[ 'brand' ]) ? $_GET[ 'brand' ] : '' ?>"/></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @include('sku.color-codes-presult')
        </tbody>
    </table>

    {!! $data->render(); !!}
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(window).on('hashchange', function () {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    getData(page);
                }
            }
        });

        $(document).ready(function () {
            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();

                $('li').removeClass('active');
                $(this).parent('li').addClass('active');

                var myurl = $(this).attr('href');
                var page = $(this).attr('href').split('page=')[1];

                getData(page);
            });

            $(document).on('click', '.sorting', function () {
                column_name = $(this).data('column_name');
                var page = window.location.hash.replace('#', '');
                getData(page)
            });

            $('#filter-brand').on('keyup', function () {
                filter_brand = $('#filter-brand').val();
                var page = window.location.hash.replace('#', '');
                getData(1);
            });

            $('.update-color-code').on('blur', function () {
                updateData(this, $(this).data('id'), this.value);
            });
        });

        var column_name = 'brands.name';
        var filter_brand = '';

        function getData(page) {
            $.ajax(
                {
                    url: '?page=' + page + '&order_by=' + column_name + '&brand=' + filter_brand,
                    type: "get",
                    datatype: "html"
                }).done(function (data) {
                $("#sku-table tbody").empty().html(data.tbody);
                if (data.links.length > 10) {
                    $('ul.pagination').replaceWith(data.links);
                } else {
                    $('ul.pagination').replaceWith('<ul class="pagination"></ul>');
                }
                location.hash = page;
            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        }

        function updateData(htmlElement, id, color_code) {
            if (id > 0 && color_code.length > 0) {
                $.ajax(
                    {
                        url: '/sku/color-codes-update?id=' + id + '&color_code=' + color_code,
                        type: "get",
                        datatype: "html"
                    }).done(function (data) {
                        console.log(data.status);
                    if (data.status == 'ok') {
                        $(htmlElement).addClass('border').addClass('border-success');
                    }
                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('An unknown error occured');
                });
            }
        }
    </script>
@endsection
