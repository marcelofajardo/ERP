@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="float-left">
                <h2>Notifications</h2>
            </div>
            <div class="float-right pb-2">
                <form action="{{ route('notifications') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            Sort By :
                        </div>
                        <div class="col-md-6">
					        <?php
					        $sort_by = [ 'by_task' => 'By Task', 'by_user' => 'By User'];
					        echo Form::select( 'sort_by', $sort_by, ( isset( $sort ) ? $sort : '' ), [
						        'placeholder' => 'Select a option',
						        'class'       => 'form-control'
					        ] );?>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-image"><img src="/images/filter.png" /></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table notification-table">
        @foreach ($notifications as $notification)
            <tr class="{{ $notification->isread ? 'isread' : '' }}">
                <td>
                    <a class="notification-link" href="{{ $notification->sale_id ? route('sales.show',$notification->sale_id) : route('products.show',$notification->product_id)  }}">
                    {{ $notification->uname }} {{ $notification->message }}
                    {{$notification->pname ? $notification->pname : $notification->sku }} at {{ Carbon\Carbon::parse($notification->created_at)->format('d-m H:i') }}
                    </a>
                </td>
                <td style="width: 20px"><button class="btn btn-notify" data-id="{{ $notification->id }}" >&#10003</button></td>
            </tr>
        @endforeach
    </table>

    {!! $notifications->appends(Request::except('page'))->links() !!}

@endsection
