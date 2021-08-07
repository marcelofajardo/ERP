@extends('layouts.app')

@section('favicon' , 'purchase.png')


@section('title', 'Purchase List - ERP Sololuxury')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Prepurchase List</h2>
            <div class="pull-left">

                <form action="/purchases/" method="GET" id="searchForm">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <input name="term" type="text" class="form-control"
                                       value="{{ isset($term) ? $term : '' }}"
                                       placeholder="Search">
                            </div>
                            <div class="col-md-4">
                                <button hidden type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="pull-right form-inline">
              <form action="{{ route('purchase.merge') }}" id="purchaseMergeForm" method="POST">
                @csrf

                <input type="hidden" name="selected_purchases" id="selected_merged_purchases" value="">
                <button type="submit" class="btn btn-secondary" id="purchaseMergeButton">Merge</button>
              </form>

              <form action="{{ route('purchase.export') }}" id="purchaseExportForm" method="POST">
                @csrf

                <input type="hidden" name="selected_purchases" id="selected_purchases" value="">
                <button type="submit" class="btn btn-secondary ml-1" id="purchaseExportButton">Export</button>
              </form>

              <button type="button" class="btn btn-secondary ml-1" data-toggle="modal" data-target="#sendExportModal">Email</button>
              <a class="btn btn-secondary ml-1" href="{{ route('purchase.grid') }}">+</a>
            </div>
        </div>
    </div>

    @include('partials.flash_messages')
    @include('purchase.partials.modal-purchase')

    <div class="card activity-chart my-3">
      <canvas id="purchaseChart" style="height: 100px;"></canvas>
    </div>

    <div id="purchaseList">
      @include('purchase.purchase-item')
    </div>

@endsection

@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js" type="text/javascript"></script>
  <script type="text/javascript">
    var purchases_array = [];
    var agents_array = {!! json_encode($agents_array) !!};

    $(document).on('click', '.pagination a', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');

      getPurchases(url);
    });

    $(document).on('click', '.ajax-sort-link', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');

      getPurchases(url);
    });

    function getPurchases(url) {
      $.ajax({
        url: url
      }).done(function(data) {
        console.log(data);
        $('#purchaseList').html(data.html);
      }).fail(function() {
        alert('Error loading more purchases');
      });
    }

    $('#searchForm').on('submit', function(e) {
      e.preventDefault();

      var url = "{{ route('purchase.index') }}";
      var formData = $('#searchForm').serialize();

      $.ajax({
        url: url,
        data: formData
      }).done(function(data) {
        $('#purchaseList').html(data.html);
      }).fail(function() {
        alert('Error searching for purchases');
      });
    });

    $('#purchaseMergeButton').on('click', function(e) {
      e.preventDefault();

      if (purchases_array.length == 2) {
        $('#selected_merged_purchases').val(JSON.stringify(purchases_array));

        if ($('#purchaseMergeForm')[0].checkValidity()) {
          $('#purchaseMergeForm').submit();
          // $('#sendExportModal').find('.close').click();
        } else {
          $('#purchaseMergeForm')[0].reportValidity();
        }

      } else {
        alert('Please select exactly 2 purchases');
      }
    });

    $('#purchaseExportButton').on('click', function(e) {
      e.preventDefault();

      if (purchases_array.length > 0) {
        $('#selected_purchases').val(JSON.stringify(purchases_array));

        if ($('#purchaseExportForm')[0].checkValidity()) {
          $('#purchaseExportForm').submit();
          // $('#sendExportModal').find('.close').click();
        } else {
          $('#purchaseExportForm')[0].reportValidity();
        }

      } else {
        alert('Please select atleast 1 purchase');
      }
    });

    $(document).on('click', '.export-checkbox', function() {
      var checked = $(this).prop('checked');
      var id = $(this).data('id');

      if (checked) {
        purchases_array.push(id);
      } else {
        purchases_array.splice(purchases_array.indexOf(id), 1);
      }

      console.log(purchases_array);
    });

    $(document).on('change', '#export_supplier', function() {
      var supplier_id = $(this).val();

      agents = agents_array[supplier_id];

      $('#export_agent').empty();

      $('#export_agent').append($('<option>', {
        value: '',
        text: 'Select Agent'
      }));

      Object.keys(agents).forEach(function(agent) {
        $('#export_agent').append($('<option>', {
          value: agent,
          text: agents_array[supplier_id][agent]
        }));
      });
    });

    let purchaseChart = $('#purchaseChart');

    var purchaseChartExample = new Chart(purchaseChart, {
        type: 'horizontalBar',
        data: {
            labels: [
              'Status'
            ],
            datasets: [{
                label: "Other ({{ $purchase_data['0'] }})",
                data: [{{ $purchase_data['0'] }}],
                backgroundColor: "rgba(207, 207, 211, 1)",
                hoverBackgroundColor: "rgba(189, 188, 194, 1)"
            },{
                label: "Italy to Dubai ({{ $purchase_data['1'] }})",
                data: [{{ $purchase_data['1'] }}],
                backgroundColor: "rgba(163,103,126,1)",
                hoverBackgroundColor: "rgba(140,85,100,1)"
            },{
                label: 'In Dubai ({{ $purchase_data['2'] }})',
                data: [{{ $purchase_data['2'] }}],
                backgroundColor: "rgba(63,203,226,1)",
                hoverBackgroundColor: "rgba(46,185,235,1)"
            },{
                label: 'Dubai to India ({{ $purchase_data['3'] }})',
                data: [{{ $purchase_data['3'] }}],
                backgroundColor: "rgba(63,103,126,1)",
                hoverBackgroundColor: "rgba(50,90,100,1)"
            },{
                label: 'In India ({{ $purchase_data['4'] }})',
                data: [{{ $purchase_data['4'] }}],
                backgroundColor: "rgba(94, 80, 226, 1)",
                hoverBackgroundColor: "rgba(74, 58, 223, 1)"
            }]
        },
        options: {
            scaleShowValues: true,
            responsive: true,
            scales: {
              xAxes: [{
                ticks: {
                    beginAtZero:true,
                    fontFamily: "'Open Sans Bold', sans-serif",
                    fontSize:11
                },
                // display: true,
                // scaleLabel: {
                //   display: true,
                //   labelString: 'Sets'
                // }
                stacked: true
              }],
              yAxes: [{
                ticks: {
                    fontFamily: "'Open Sans Bold', sans-serif",
                    fontSize:11
                },
                // display: true,
                // scaleLabel: {
                //   display: true,
                //   labelString: 'Count'
                // }
                stacked: true
              }]
            },
            tooltips: {
              enabled: false
            },
            animation: {
              onComplete: function () {
                var chartInstance = this.chart;
                var ctx = chartInstance.ctx;
                ctx.textAlign = "left";
                // ctx.font = this.scale.font;
                ctx.fillStyle = "#fff";

                // this.datasets.forEach(function (dataset) {
                //   dataset.points.forEach(function (points) {
                //     ctx.fillText(points.value, points.x, points.y - 10);
                //   });
                // })

                Chart.helpers.each(this.data.datasets.forEach(function (dataset, i) {
                    var meta = chartInstance.controller.getDatasetMeta(i);
                    Chart.helpers.each(meta.data.forEach(function (bar, index) {
                        data = dataset.data[index];
                        if(i==0){
                            ctx.fillText(data, 50, bar._model.y+4);
                        } else {
                            ctx.fillText(data, bar._model.x-25, bar._model.y+4);
                        }
                    }),this)
                }),this);
              }
          },
        }
    });
  </script>
@endsection
