<html>
<head>
    <title>Sololuxury Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background: #e2e1e0;
        }

        .card {
            background: #fff;
            border-radius: 2px;
            margin: 1rem;
            position: relative;
            padding: 1rem;
        }

        .card-1 {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
        }

        .card-1:hover {
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
        }

        .same-color {
            color: #949494;
        }

        .pagination {
            display: inline-flex !important;
        }

        .slider .tooltip.in {
            opacity: 1;
        }

        .slider .tooltip.top .tooltip-arrow {
            bottom: 0;
            left: 50%;
            margin-left: -5px;
            border-width: 5px 5px 0;
            border-top-color: #000;
        }

        .slider .tooltip-arrow {
            position: absolute;
            width: 0;
            height: 0;
            border-color: transparent;
            border-style: solid;
        }

        .slider .tooltip.top {
            padding: 5px 0;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center mt-4 p-4">
                <img src="https://sololuxury.co.in/skin/frontend/bewear/default/images/logo_solo.png" alt="https://sololuxury.co.in/skin/frontend/bewear/default/images/logo_solo.png">
            </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center mt-2">
            {!! $products->links() !!}
        </div>
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>Original Image</th>
                    <th>Cropped Image</th>
                </tr>
                @foreach($products as $product)
                    <tr>
                        <td>
                            <img style="width: 100%" src="{{ $product->media ? $product->media->getUrl() : '' }}" alt="">
                        </td>
                        <td>
                            <p>
                                Cropped at: {{ date('d-m-Y H:i:s', strtotime($product->created_at)) }} (<?php echo env('TIMEZONE', 'Asia/Kolkata'); ?>)
                            </p>
                            <img src="{{ $product->newMedia ? $product->newMedia->getUrl() : '' }}" alt="" style="width: 100%;">
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="col-md-12 text-center mt-3 mb-4">
            {!! $products->links() !!}
        </div>
    </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"/>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/css/bootstrap-slider.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.6.1/bootstrap-slider.min.js"></script>

<script>
    $(document).ready(function (event) {
        $('.select2').select2();
    });
</script>
</html>