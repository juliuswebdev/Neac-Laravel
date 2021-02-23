<style>
    .active td { background-color: #ecfff0; }
    .deactivated td { text-decoration:  line-through; background-color: #ffeff1 }
    #services_info,
    #payment_form,
    #user_info,
    #transaction_history {
        border: 1px solid #ccc;
        padding: 20px;
        margin-bottom: 15px;
    }
    .modal-header { font-weight: 400; }
    /* #transaction_history.fixed { 
        position: fixed;
        z-index: 1;
        top: 0;
        background-color: #fff;
        max-width: 966px;
        width: 100%;
        padding: 33px;
        border: 1px solid #ccc;
    } */

</style>
@extends('layouts.dashboard')
@section('name_content')
   Transactions
@endsection
@section('content')

@php
    $get_status = [1,2,3];
    if( isset( $_GET['status'] ) ) {
        $get_status = [$_GET['status']];
    }
@endphp
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Search Form</h3>
                </div>
                <div class="card-body">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-md-4" style="border-right: 1px solid #ccc;">
                                <div class="form-group">
                                    <label for="">Search by Email, Order No.</label>
                                    <input type="text" name="q" class="form-control" placeholder="Email, Order No." value="@php echo isset($_GET['q']) ? $_GET['q'] : '' @endphp">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Search by Date Range</label>
                                    <input type="text" name="s_e" class="form-control date_range_picker_js2" placeholder="Date Range" value="@php echo isset($_GET['s_e']) ? $_GET['s_e'] : '' @endphp" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4" style="border-right: 1px solid #ccc;">
                                <div class="form-group">
                                    <label for="">Filter by Status.</label>
                                    <select name="status" id="" class="form-control">
                                        <option disabled selected>Please Select Status</option>
                                        <option value="2">Approved</option>
                                        <option value="1">Not Approved</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Shopify Transactions <img src="https://cdn.shopify.com/assets/images/logos/shopify-bag.png" width="40px" alt="">
                </div>
                <div class="card-body">
                    <table class="display table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 30px;">#</th>
                                <th style="width: 70px;">Order No#</th>
                                <th style="width: 100px;">&nbsp;</th>
                                <th style="width: 300px;">User</th>
                                <th>Cart</th>
                                <th></th>
                                <th style="width: 100px;">Payment Mode</th>
                                <th>Total Payed</th>
                                <th>Payed At</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>           
                        <tbody>
                        @php $sh_count = 0; @endphp
                        @if($shopify_orders)
                            @foreach($shopify_orders as $cart)
                                @php
                                    $cartx = \App\Cart::where('order_no', $cart->name)->first();
                                    $cart_status = ($cartx) ? $cartx->status : 1;
                                    if(in_array($cart_status, $get_status)) {
                                @endphp
                                    <tr>
                                        @php
                                            $sh_count++;
                                        @endphp

                                        <td>{{ $sh_count }}</td>
                                        <td>{{ $cart->name }}</td>
                                        <td>
                                            @if($cart_status == 2)
                                                Approved <span class="badge badge-success">Done</span>
                                            @else
                                                @if($cart->financial_status == 'paid')
                                                    Completed <span class="badge badge-primary">Paid</span>
                                                @else
                                                    {{ ucfirst($cart->financial_status) }} <span class="badge badge-warning">{{ ucfirst($cart->financial_status) }}</span>
                                                @endif
                                            @endif
                                        </td>

                                        <td>
                                            @if(isset($cart->customer))
                                            {{ $cart->customer->first_name }} {{ $cart->customer->last_name }} ({{ $cart->email }})
                                            @else
                                                @if($cart->email)
                                                {{ $cart->email }}
                                                @else
                                                -
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#sh_transaction-{{ $sh_count }}" class="btn btn-sm btn-primary">View Cart</a>
                                                <div class="modal fade show" id="sh_transaction-{{$sh_count}}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                @if(isset($cart->customer))
                                                                    {{ $cart->customer->first_name }} {{ $cart->customer->last_name }} ({{ $cart->email }})
                                                                @else
                                                                    @if($cart->email)
                                                                    {{ $cart->email }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="modal-body">
                                                                    <table class="table table-striped table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Services</th>
                                                                                <th>Price {{ $cart->currency }}</th>
                                                                                <th><small>Presentment</small><br>Price {{ $cart->presentment_currency }}</th>
                                                                            </tr>
                                                                        </thead>
                                                                        @if($cart->line_items)
                                                                            <tbody>
                                                                                @foreach($cart->line_items as $item)
                                                                                    <tr>
                                                                                        <td>{{ $item->title }} <br><small>{{ $item->variant_title }}</small></td>
                                                                                        <td>{{ $item->price_set->shop_money->amount }}</td>
                                                                                        <td>{{ $item->price_set->presentment_money->amount }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <th style="border: none;">Sub Total</th>
                                                                                    <th style="border: none;">{{ $cart->subtotal_price_set->shop_money->amount }}</th>
                                                                                    <th style="border: none;">{{ $cart->subtotal_price_set->presentment_money->amount }}</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th style="border: none;">Tax</th>
                                                                                    <th style="border: none;">{{ $cart->total_tax_set->shop_money->amount }}</th>
                                                                                    <th style="border: none;">{{ $cart->total_tax_set->presentment_money->amount }}</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Total</th>
                                                                                    <th>{{ $cart->total_price_set->shop_money->amount }}</th>
                                                                                    <th>{{ $cart->total_price_set->presentment_money->amount }}</th>
                                                                                </tr>
                                                                            </tfoot>

                                                                        @endif
                                                                    </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        
                                        </td>
                                        <th>
                                            @if($cart->note)
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#sh_transaction-note-{{ $sh_count }}" style="margin-top: 10px; display: block;yu"><i class="fas fa-sticky-note"></i></a>
                                            <div class="modal fade show" id="sh_transaction-note-{{$sh_count}}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                                @if(isset($cart->customer))
                                                                    {{ $cart->customer->first_name }} {{ $cart->customer->last_name }} ({{ $cart->email }})
                                                                @else
                                                                    @if($cart->email)
                                                                    {{ $cart->email }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                @endif
                                                        </div>
                                                        <div class="modal-body">
                                                            {!! $cart->note !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </th>
                                        <td>
                                        @if($cart->gateway == 'manual')
                                            Draft Order
                                        @else
                                            {{ $cart->gateway }}
                                        @endif

                                        </td>
                                        <td>
                                        {{ $cart->presentment_currency }} {{ $cart->total_price_set->presentment_money->amount }}
                                        </td>
                                        <td>
                                        @if($cart->created_at)
                                            {{ date('Y-m-d', strtotime($cart->created_at)) }}
                                        @else
                                            -
                                        @endif
                                        </td>
                                        <td>
                                            @if($cart_status == 1)
                                                <button type="button" class="btn btn-success " data-toggle="modal" data-target="#approvetransaction-{{ $sh_count }}" title="Approve Payment"><i class="fas fa-check" style="color: #fff;"> </i> </button>
                                                <div class="modal fade" id="approvetransaction-{{ $sh_count }}" tabindex="-1" aria-labelledby="approvetransaction-{{ $sh_count }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('transactions.update', 'sh_update') }}" method="POST" style="display: inline-block">
                                                            <input type="hidden" name="order_no" value="{{ $cart->name }}">
                                                            <input type="hidden" name="email" value="{{ $cart->email }}">
                                                            <input type="hidden" name="total" value="{{ $cart->total_price_set->presentment_money->amount }}">
                                                            <input type="hidden" name="currency" value="{{ $cart->presentment_currency }}">
                                                            <input type="hidden" name="payed_at" value="{{ date('Y-m-d', strtotime($cart->created_at)) }}">
                                                            
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    @if(isset($cart->customer))
                                                                        {{ $cart->customer->first_name }} {{ $cart->customer->last_name }} ({{ $cart->email }})
                                                                    @else
                                                                        @if($cart->email)
                                                                        {{ $cart->email }}
                                                                        @else
                                                                        -
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    @method('put')
                                                                    <h5>Are you sure you want to approve this order?</h5>
                                                                    <input type="hidden" name="type" value="approve_payment">
                                                                    <button type="submit" class="btn btn-success">Approve</button>
                                                                    <a href="javascript:void(0)" data-dismiss="modal" aria-label="Close" class="btn btn-default">Close</a>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @php
                                    }
                                @endphp
                            @endforeach
                        @else
                            <tr style="color: #212529; background-color: rgba(0,0,0,.075);">
                                <td colspan="10" class="text-center"><strong>No result found!</strong></td>
                            </tr>       
                        @endif
                        </tbody>
                    </table>
                    <p class="mt-3 ">Total Orders: @if(isset($_GET['status'])) {{ $sh_count }} @else {{ $shopify_orders_count }} @endif </p>
                    @if($first != $prev)
                    <form action="" method="GET" style="display: inline-block;">
                        <input type="hidden" name="prev" value="{{ $prev }}">
                        <button type="submit" class="btn btn-primary">Prev</button>
                    </form>
                    @endif
                    @if($last != $next)
                    <form action="" method="GET" style="display: inline-block;">
                        <input type="hidden" name="next" value="{{ $next }}">
                        <button type="submit" class="btn btn-primary">Next</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Offline Transactions <a href="javascript:void(0)" data-toggle="modal" data-target="#maketransaction" class="btn btn-primary" style="float: right">Make Transaction</a>
                </div>
                <div class="card-body">
                    <table class="display table table-striped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 30px;">#</th>
                                <th style="width: 70px;">Order No#</th>
                                <th style="width: 100px;">&nbsp;</th>
                                <th style="width: 300px;">User</th>
                                <th>Cart</th>
                                <th></th>
                                <th style="width: 100px;">Payment Mode</th>
                                <th>Total Payed</th>
                                <th>Payed At</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>           
                        <tbody>
                        @php $count = 0 @endphp
                        @if(!$carts->isEmpty())
                            @foreach ($carts as $cart)
                                    <tr>
                                        @php
                                            $count++;
                                        @endphp
                                        <td>{{ $count }}</td>
                                        <td>{{ $cart->order_no }}</td>
                                        <td>
                                            Approved <span class="badge badge-success">Done</span>
                                        </td>
                                        <td>{{ $cart->user->first_name }} {{ $cart->user->last_name }} ({{ $cart->user->email }})</td>
                                        <td>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#transaction-{{ $count }}" class="btn btn-sm btn-primary">View Cart</a>
                                                <div class="modal fade show" id="transaction-{{$count}}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                {{ $cart->user->first_name }} {{ $cart->user->last_name }} ({{ $cart->user->email }}) [{{ $cart->order_no }}]
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-striped table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Services</th>
                                                                            <th>Price {{ $cart->currency }}</th>

                                                                        </tr>
                                                                    </thead>
                                                                        @php $line_items = json_decode($cart->items); @endphp
                                                                        <tbody>
                                                                            @if(isset($line_items->lineItems))
                                                                                @foreach($line_items->lineItems as $item)
                                                                                    <tr>
                                                                                        <td>{{ $item->title }} <br><small>{{ $item->variant->title }}</small></td>
                                                                                        <td>{{ $item->variant->price }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            @else
                                                                                <tr>
                                                                                    <td colspan="2">No Service</td>
                                                                                </tr>
                                                                            @endif
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <tr>
                                                                                <th style="border: none;">Sub Total</th>
                                                                                <th style="border: none;">
                                                                                    @if(isset($line_items->subtotalPrice))
                                                                                        {{ $line_items->subtotalPrice }}
                                                                                    @else
                                                                                    0
                                                                                    @endif
                                                                                </th>
                                                                                
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="border: none;">Tax</th>
                                                                                <th style="border: none;">
                                                                                    @if(isset($line_items->totalTax))
                                                                                        {{ $line_items->totalTax }}
                                                                                    @else
                                                                                    0
                                                                                    @endif
                                                                                </th>
                                                                                
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Total</th>
                                                                                <th>
                                                                                    @if(isset($line_items->totalPrice))
                                                                                        {{ $line_items->totalPrice }}
                                                                                    @else
                                                                                    0
                                                                                    @endif
                                                                                </th>
                                                                            </tr>
                                                                        </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        
                                        </td>
                                        <td>
                                            @if($cart->notes)
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#sh_offline-note-{{ $sh_count }}" style="margin-top: 10px; display: block;yu"><i class="fas fa-sticky-note"></i></a>
                                            <div class="modal fade show" id="sh_offline-note-{{$sh_count}}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            {{ $cart->user->first_name }} {{ $cart->user->last_name }} ({{ $cart->user->email }}) [{{ $cart->order_no }}]
                                                        </div>
                                                        <div class="modal-body">
                                                            {!! $cart->notes !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        <td>
                                        @if($cart->payment_mode)
                                            {{ $cart->payment_mode }}
                                        @else
                                            -
                                        @endif
                                        </td>
                                        <td>
                                        @if($cart->total)
                                            <small>{{ $cart->currency }}</small> {{ $cart->total }}
                                        @else
                                            -
                                        @endif
                                        </td>
                                        <td>
                                        @if($cart->payed_at)
                                            {{ date('Y-m-d', strtotime($cart->payed_at)) }}
                                        @else
                                            -
                                        @endif
                                        </td>
                                        <th>
                                            <form action="{{ route('transactions.destroy',$cart->id) }}" method="POST" style="display: inline-block">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Do you really want to delete this cart?');" title="Remove Cart"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </th>
                                    </tr>
                            @endforeach 
                        @else
                            <tr style="color: #212529; background-color: rgba(0,0,0,.075);">
                                <td colspan="10" class="text-center"><strong>No result found!</strong></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    @if(!$carts->isEmpty())
                        <div class="mt-3">
                        @php $paginator = $carts; @endphp
                        @include('commons.pagination', [$paginator,$count])
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade show" id="maketransaction" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 1000px;">
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Make Transaction <small></small></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="find-user" action="{{ route('transactions.find_user') }}" method="POST">
                    @csrf
                    @method('POST')
                    <label for="application_number">Find user using Application Number: or Register Acccount <a href="{{ route('applicants.create') }}">here</a></label>
                    <input type="text" id="application_number" name="application_number" placeholder="Application Number" class="form-control" style="width: calc(100% - 100px);
                    display: inline-block;
                    vertical-align: bottom;">
                    <button type="submit" class="btn btn-primary">Find</button>
                </form>
                <form id="place_order" action="{{ route('transactions.place_order') }}" method="POST" class=" mb-0 row">
                    <div class="col-md-6">
                        <div id="transaction_history" style="display: none;">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="type" value="payment_offline">
                                <input type="hidden" name="user_id" id="user_id" value="">
                                <h5><strong>Order Summary</strong></h5>
                                <table class="table table-striped table-hover mb-0">
                                    <tr><td>Add Service Below</td></tr>
                                </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="user_info" style="display: none;"></div>
                        <div id="payment_form" style="display: none;">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label for="notes">Order notes</label>
                                    <textarea name="notes" class="form-control" id="notes"></textarea>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="currency">Currency</label>
                                    <select name="currency" id="currency" class="form-control" required="">
                                        <option value="USD">USD</option>
                                        <option value="PHP">PHP</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="total">Total</label>
                                    <input type="number" name="total" id="total" class="form-control" required="" autocomplete="off" step=".01">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="payed_at">Payed At</label>
                                    <input type="text" name="payed_at" id="payed_at" class="form-control payed_at" required="" autocomplete="off">
                                </div>
                                <div class="col-md-4 mt-1">
                                    <button type="submit" class="btn btn-primary" title="Pay Offline">Pay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div id="services_info" style="display: none;">
                            <div class="service_info_search">
                                <input type="text" name="search" id="product_search" class="form-control" placeholder="Search Service">
                            </div>
                            <div class="services_info_content">
                                <p class="mb-0">Loading...</p>
                            </div>
                            <a href="javascript:void(0)" class="next_product btn btn-primary" style="display: block">Next</a>
                        </div>
                    </div>
                </form>

            </div>

        </div>
    
    </div>
</div>
@endsection