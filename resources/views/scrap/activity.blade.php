@extends('layouts.app')

@section('favicon' , 'scrapactivity.png')

@section('title', 'Scrap Activity - ERP Sololuxury')

@section('content')

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h2 class="page-heading">Scrap Activity</h2>
            {{-- <div class="pull-left">
                <form action="/order/" method="GET">
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
            <div class="pull-right">
                <a class="btn btn-secondary" href="{{ route('order.create') }}">+</a>
            </div> --}}
        </div>
    </div>

    @include('partials.flash_messages')

    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th rowspan="2">Date</th>
            <th colspan="5" class="text-center">G&B</th>
            <th colspan="5" class="text-center">Wise Boutique</th>
            <th colspan="5" class="text-center">Double F</th>
            <th colspan="5" class="text-center">Lidia</th>
            <th colspan="5" class="text-center">Tory</th>
            <th colspan="5" class="text-center">Cuccuini</th>
            <th colspan="5" class="text-center">Alducadaosta</th>
            <th colspan="5" class="text-center">Angel Ominetti</th>
            <th colspan="5" class="text-center">Biffi</th>
            <th colspan="5" class="text-center">Brunarosso</th>
            <th colspan="5" class="text-center">Carofiglio Junior</th>
            <th colspan="5" class="text-center">Divo</th>
            <th colspan="5" class="text-center">Italiani</th>
            <th colspan="5" class="text-center">Spinnaker</th>
            <th colspan="5" class="text-center">Concept Store</th>
            <th colspan="5" class="text-center">Deliberti</th>
            <th colspan="5" class="text-center">Giglio</th>
            <th colspan="5" class="text-center">Griffo 210</th>
            <th colspan="5" class="text-center">La ferramenta</th>
            <th colspan="5" class="text-center">Leam</th>
            <th colspan="5" class="text-center">Les Market</th>
            <th colspan="5" class="text-center">Linoricci</th>
            <th colspan="5" class="text-center">Mimmanni Shop</th>
            <th colspan="5" class="text-center">Monti Boutique</th>
            <th colspan="5" class="text-center">Nugnes 1920</th>
            <th colspan="5" class="text-center">Railso</th>
            <th colspan="5" class="text-center">Coltorti</th>
            <th colspan="5" class="text-center">Stilmoda</th>
            <th colspan="5" class="text-center">Maria Store</th>
          </tr>
          <tr>
            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>

            <th>Links</th>
            <th>Scraped</th>
            <th>New</th>
            <th>Inventory</th>
            <th>Removed</th>
          </tr>
        </thead>

        <tbody>
          @foreach ($data as $date => $item)
            <tr>
              <td>{{ \Carbon\Carbon::parse($date)->format('d-m') }}</td>
              <td>{{ $item['G&B']['links'] ?? 0 }}</td>
              <td>{{ $item['G&B']['scraped'] ?? 0 }}</td>
              <td>{{ $item['G&B']['created'] ?? 0 }}</td>
              <td>{{ $item['G&B'][1] ?? 0 }}</td>
              <td>{{ $item['G&B'][0] ?? 0 }}</td>

              <td>{{ $item['Wiseboutique']['links'] ?? 0 }}</td>
              <td>{{ $item['Wiseboutique']['scraped'] ?? 0 }}</td>
              <td>{{ $item['Wiseboutique']['created'] ?? 0 }}</td>
              <td>{{ $item['Wiseboutique'][1] ?? 0 }}</td>
              <td>{{ $item['Wiseboutique'][0] ?? 0 }}</td>

              <td>{{ $item['DoubleF']['links'] ?? 0 }}</td>
              <td>{{ $item['DoubleF']['scraped'] ?? 0 }}</td>
              <td>{{ $item['DoubleF']['created'] ?? 0 }}</td>
              <td>{{ $item['DoubleF'][1] ?? 0 }}</td>
              <td>{{ $item['DoubleF'][0] ?? 0 }}</td>

              <td>{{ $item['lidiashopping']['links'] ?? 0 }}</td>
              <td>{{ $item['lidiashopping']['scraped'] ?? 0 }}</td>
              <td>{{ $item['lidiashopping']['created'] ?? 0 }}</td>
              <td>{{ $item['lidiashopping'][1] ?? 0 }}</td>
              <td>{{ $item['lidiashopping'][0] ?? 0 }}</td>

              <td>{{ $item['Tory']['links'] ?? 0 }}</td>
              <td>{{ $item['Tory']['scraped'] ?? 0 }}</td>
              <td>{{ $item['Tory']['created'] ?? 0 }}</td>
              <td>{{ $item['Tory'][1] ?? 0 }}</td>
              <td>{{ $item['Tory'][0] ?? 0 }}</td>

              <td>{{ $item['Cuccini']['links'] ?? 0 }}</td>
              <td>{{ $item['Cuccini']['scraped'] ?? 0 }}</td>
              <td>{{ $item['Cuccini']['created'] ?? 0 }}</td>
              <td>{{ $item['Cuccini'][1] ?? 0 }}</td>
              <td>{{ $item['Cuccini'][0] ?? 0 }}</td>

              <td>{{ $item['alducadaosta']['links'] ?? 0 }}</td>
              <td>{{ $item['alducadaosta']['scraped'] ?? 0 }}</td>
              <td>{{ $item['alducadaosta']['created'] ?? 0 }}</td>
              <td>{{ $item['alducadaosta'][1] ?? 0 }}</td>
              <td>{{ $item['alducadaosta'][0] ?? 0 }}</td>

              <td>{{ $item['angelominetti']['links'] ?? 0 }}</td>
              <td>{{ $item['angelominetti']['scraped'] ?? 0 }}</td>
              <td>{{ $item['angelominetti']['created'] ?? 0 }}</td>
              <td>{{ $item['angelominetti'][1] ?? 0 }}</td>
              <td>{{ $item['angelominetti'][0] ?? 0 }}</td>

              <td>{{ $item['biffi']['links'] ?? 0 }}</td>
              <td>{{ $item['biffi']['scraped'] ?? 0 }}</td>
              <td>{{ $item['biffi']['created'] ?? 0 }}</td>
              <td>{{ $item['biffi'][1] ?? 0 }}</td>
              <td>{{ $item['biffi'][0] ?? 0 }}</td>

              <td>{{ $item['brunarosso']['links'] ?? 0 }}</td>
              <td>{{ $item['brunarosso']['scraped'] ?? 0 }}</td>
              <td>{{ $item['brunarosso']['created'] ?? 0 }}</td>
              <td>{{ $item['brunarosso'][1] ?? 0 }}</td>
              <td>{{ $item['brunarosso'][0] ?? 0 }}</td>

              <td>{{ $item['carofigliojunior']['links'] ?? 0 }}</td>
              <td>{{ $item['carofigliojunior']['scraped'] ?? 0 }}</td>
              <td>{{ $item['carofigliojunior']['created'] ?? 0 }}</td>
              <td>{{ $item['carofigliojunior'][1] ?? 0 }}</td>
              <td>{{ $item['carofigliojunior'][0] ?? 0 }}</td>

              <td>{{ $item['Divo']['links'] ?? 0 }}</td>
              <td>{{ $item['Divo']['scraped'] ?? 0 }}</td>
              <td>{{ $item['Divo']['created'] ?? 0 }}</td>
              <td>{{ $item['Divo'][1] ?? 0 }}</td>
              <td>{{ $item['Divo'][0] ?? 0 }}</td>

              <td>{{ $item['italiani']['links'] ?? 0 }}</td>
              <td>{{ $item['italiani']['scraped'] ?? 0 }}</td>
              <td>{{ $item['italiani']['created'] ?? 0 }}</td>
              <td>{{ $item['italiani'][1] ?? 0 }}</td>
              <td>{{ $item['italiani'][0] ?? 0 }}</td>

              <td>{{ $item['Spinnaker']['links'] ?? 0 }}</td>
              <td>{{ $item['Spinnaker']['scraped'] ?? 0 }}</td>
              <td>{{ $item['Spinnaker']['created'] ?? 0 }}</td>
              <td>{{ $item['Spinnaker'][1] ?? 0 }}</td>
              <td>{{ $item['Spinnaker'][0] ?? 0 }}</td>


              <td>{{ $item['conceptstore']['links'] ?? 0 }}</td>
              <td>{{ $item['conceptstore']['scraped'] ?? 0 }}</td>
              <td>{{ $item['conceptstore']['created'] ?? 0 }}</td>
              <td>{{ $item['conceptstore'][1] ?? 0 }}</td>
              <td>{{ $item['conceptstore'][0] ?? 0 }}</td>

              <td>{{ $item['deliberti']['links'] ?? 0 }}</td>
              <td>{{ $item['deliberti']['scraped'] ?? 0 }}</td>
              <td>{{ $item['deliberti']['created'] ?? 0 }}</td>
              <td>{{ $item['deliberti'][1] ?? 0 }}</td>
              <td>{{ $item['deliberti'][0] ?? 0 }}</td>

              <td>{{ $item['giglio']['links'] ?? 0 }}</td>
              <td>{{ $item['giglio']['scraped'] ?? 0 }}</td>
              <td>{{ $item['giglio']['created'] ?? 0 }}</td>
              <td>{{ $item['giglio'][1] ?? 0 }}</td>
              <td>{{ $item['giglio'][0] ?? 0 }}</td>

              <td>{{ $item['griffo210']['links'] ?? 0 }}</td>
              <td>{{ $item['griffo210']['scraped'] ?? 0 }}</td>
              <td>{{ $item['griffo210']['created'] ?? 0 }}</td>
              <td>{{ $item['griffo210'][1] ?? 0 }}</td>
              <td>{{ $item['griffo210'][0] ?? 0 }}</td>

              <td>{{ $item['laferramenta']['links'] ?? 0 }}</td>
              <td>{{ $item['laferramenta']['scraped'] ?? 0 }}</td>
              <td>{{ $item['laferramenta']['created'] ?? 0 }}</td>
              <td>{{ $item['laferramenta'][1] ?? 0 }}</td>
              <td>{{ $item['laferramenta'][0] ?? 0 }}</td>

              <td>{{ $item['leam']['links'] ?? 0 }}</td>
              <td>{{ $item['leam']['scraped'] ?? 0 }}</td>
              <td>{{ $item['leam']['created'] ?? 0 }}</td>
              <td>{{ $item['leam'][1] ?? 0 }}</td>
              <td>{{ $item['leam'][0] ?? 0 }}</td>

              <td>{{ $item['les-market']['links'] ?? 0 }}</td>
              <td>{{ $item['les-market']['scraped'] ?? 0 }}</td>
              <td>{{ $item['les-market']['created'] ?? 0 }}</td>
              <td>{{ $item['les-market'][1] ?? 0 }}</td>
              <td>{{ $item['les-market'][0] ?? 0 }}</td>

              <td>{{ $item['linoricci']['links'] ?? 0 }}</td>
              <td>{{ $item['linoricci']['scraped'] ?? 0 }}</td>
              <td>{{ $item['linoricci']['created'] ?? 0 }}</td>
              <td>{{ $item['linoricci'][1] ?? 0 }}</td>
              <td>{{ $item['linoricci'][0] ?? 0 }}</td>

              <td>{{ $item['mimmannishop']['links'] ?? 0 }}</td>
              <td>{{ $item['mimmannishop']['scraped'] ?? 0 }}</td>
              <td>{{ $item['mimmannishop']['created'] ?? 0 }}</td>
              <td>{{ $item['mimmannishop'][1] ?? 0 }}</td>
              <td>{{ $item['mimmannishop'][0] ?? 0 }}</td>

              <td>{{ $item['montiboutique']['links'] ?? 0 }}</td>
              <td>{{ $item['montiboutique']['scraped'] ?? 0 }}</td>
              <td>{{ $item['montiboutique']['created'] ?? 0 }}</td>
              <td>{{ $item['montiboutique'][1] ?? 0 }}</td>
              <td>{{ $item['montiboutique'][0] ?? 0 }}</td>

              <td>{{ $item['nugnes1920']['links'] ?? 0 }}</td>
              <td>{{ $item['nugnes1920']['scraped'] ?? 0 }}</td>
              <td>{{ $item['nugnes1920']['created'] ?? 0 }}</td>
              <td>{{ $item['nugnes1920'][1] ?? 0 }}</td>
              <td>{{ $item['nugnes1920'][0] ?? 0 }}</td>

              <td>{{ $item['railso']['links'] ?? 0 }}</td>
              <td>{{ $item['railso']['scraped'] ?? 0 }}</td>
              <td>{{ $item['railso']['created'] ?? 0 }}</td>
              <td>{{ $item['railso'][1] ?? 0 }}</td>
              <td>{{ $item['railso'][0] ?? 0 }}</td>

              <td>{{ $item['coltorti']['links'] ?? 0 }}</td>
              <td>{{ $item['coltorti']['scraped'] ?? 0 }}</td>
              <td>{{ $item['coltorti']['created'] ?? 0 }}</td>
              <td>{{ $item['coltorti'][1] ?? 0 }}</td>
              <td>{{ $item['coltorti'][0] ?? 0 }}</td>

              <td>{{ $item['stilmoda']['links'] ?? 0 }}</td>
              <td>{{ $item['stilmoda']['scraped'] ?? 0 }}</td>
              <td>{{ $item['stilmoda']['created'] ?? 0 }}</td>
              <td>{{ $item['stilmoda'][1] ?? 0 }}</td>
              <td>{{ $item['stilmoda'][0] ?? 0 }}</td>

              <td>{{ $item['mariastore']['links'] ?? 0 }}</td>
              <td>{{ $item['mariastore']['scraped'] ?? 0 }}</td>
              <td>{{ $item['mariastore']['created'] ?? 0 }}</td>
              <td>{{ $item['mariastore'][1] ?? 0 }}</td>
              <td>{{ $item['mariastore'][0] ?? 0 }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {!! $data->appends(Request::except('page'))->links() !!}

    <hr>

    <h1>Monitor Lnks</h1>

    <table class="table table-striped">
        <tr>
            <th>Date</th>
            <th>Scanned Link</th>
            <th>Website</th>
        </tr>
        @foreach($link_entries as $entry)
            <tr>
                <td>{{ $entry->scraped_date }}</td>
                <td>{{ $entry->link_count }}</td>
                <td>{{ $entry->website }}</td>
            </tr>
        @endforeach
    </table>

@endsection
