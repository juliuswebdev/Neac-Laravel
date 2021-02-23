<style>
  h1 { margin-top: 2 0px!important; }
  footer.main-footer,
  nav, aside, .breadcrumb { display: none!important }
  body.sidebar-mini .wrapper .content-wrapper { margin-left: 0!important }

</style>
@extends('layouts.dashboard')

@section('name_content')
    Edit Profile {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }} <small>({{$user->email}})</small>

    <div class="float-right lock-function">
        @php
            $lock_email = ($user->lock_user_id) ? App\User::find($user->lock_user_id)->email : 0;
        @endphp
        @if($lock_email == auth()->user()->email || auth()->user()->user_type == 'admin')
            <a href="javascript:void(0)" class="btn btn-info" data-applicant_id="{{$user->id}}" data-token="{{ csrf_token() }}">
                @if($user->lock_status)
                    <i class="fas fa-lock" title="Unlock"></i>
                    <span class="ml-1">{{$lock_email}}</span>
                @else
                    <i class="fas fa-unlock" title="Lock"></i>
                @endif
            </a>
        @else
            <p class="btn btn-default">
                <i class="fas fa-lock"></i>
                <span class="ml-1">{{$lock_email}}</span>
            </p>
        @endif
    </div>

    @if($user->approval == 0)
    <style>
        .content-wrapper { background-color: #fff7f8!important; }
    </style>
    <div class="float-right activate-function">
        <a href="javascript:void(0)" class="btn btn-danger" data-applicant_id="{{$user->id}}" data-token="{{ csrf_token() }}">
            <i class="fas fa-check" title="Activate"></i>
            <span class="ml-1">Activate</span>
        </a>
    </div>
    @else
        <div class="float-right">
            <p class="btn btn-success" style="cursor: default;">
                <i class="fas fa-check"></i>
                <span class="ml-1">Active</span>
            </p>
        </div>
    @endif
@endsection

@section('content')
    @if($lock_email == auth()->user()->email || auth()->user()->user_type == 'admin')
        <!-- Main content -->
        @php
            $document_path = '/documents/';
        @endphp
        <section class="content" id="profile-area">
            <div class="row">
            @if(session()->has('message'))
                <div class="col-md-12">
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {!! session()->get('message') !!}
                </div>
                </div>
            @endif
            <div class="col-md-5">

                <div class="card card-outline card-info">
                    <div class="card-header">
                    <h3 class="card-title">Profile Information</h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="email">Username/Email Address</label>
                            </div>
                            <div class="col-md-11">
                                <input type="text" class="form-control mb-1 email-input input-disabled" name="email" id="email" value="{{$user->email}}" disabled style="width: 100%;">
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0)" class="input-disabled-edit"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-primary input-disabled-update email-update" style="display: none;" data-token="{{ csrf_token() }}" data-user_id="{{$user->id}}">Update</a>
                            </div>
                        </div>

                        <form action="{{ route('applicants.update',$user) }}" method="POST" enctype="multipart/form-data"> 
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="uploadImage">Upload your Image here</label>
                                <div class="form-content">
                                <img style="max-width: 150px; margin-bottom: 10px;" src="{{ $document_path }}{{ $user->profile->image }}" alt="">
                                <div class="input-group">
                                    <div class="custom-file">
                                    <input type="file" class="form-control-file custom-file-input" id="uploadImage" name="image" accept=".jpg, .gif, .png">
                                    <label class="custom-file-label" for="uploadImage">Choose Files</label>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <div class="form-content">
                                <input type="text" class="form-control" value="{{ $user->first_name }}" id="first_name" name="first_name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <div class="form-content">
                                <input type="text" class="form-control" value="{{ $user->middle_name }}" id="middle_name" name="middle_name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <div class="form-content">
                                <input type="text" class="form-control" value="{{ $user->last_name }}" id="last_name" name="last_name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alternate_email">Alternative Email Address</label>
                                <div class="form-content">
                                <input type="email" class="form-control" value="{{ $user->profile->alternate_email }}" id="alternate_email" name="alternate_email">
                                </div>
                            </div>
                            @foreach($applicant_profile_form->inputs_w_value($user->id) as $input)
                                @include('commons.form-input', $input)
                            @endforeach
                            <div class="form-group">
                                @php
                                    $refer_check = auth()->user()->can('applicant-refer-friend');
                                @endphp
                                <label for="reseller_code_used">Reseller Code:</label> @if(!$refer_check)  {{ $user->reseller_code_used }} @endif
                                <div class="form-content">
                                    @if($refer_check)
                                        <input type="text" class="form-control" value="{{ $user->reseller_code_used }}" id="reseller_code_used" name="reseller_code_used"> 
                                    @endif
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>   
                    </div>
                </div>

            </div>
            <div class="col-md-7" id="accordion">
                @php
                    $fg = (session()->has('fg')) ? session()->get('fg') : '';
                    $fgt = (session()->has('fgt')) ? session()->get('fgt') : '';
                    $shopify = json_decode($shopify_arr);
                    $offline = json_decode($offline_arr);
                    $status = $cart_arr;

                @endphp
                <div class="card card-outline card-info" style="padding: 0 20px;">
                    @php
                    $count1 = 0;
                    $count2 = 0;
                    @endphp 
                    <div class="card-header d-flex pt-1 pb-1" style="border-bottom: 0; padding-left:0; padding-right: 0;">
                    <h3 class="card-title pt-3 pb-3">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Application Status <i class="fas fa-angle-down"></i>
                        </a>
                    </h3>
                    <ul class="nav nav-pills ml-auto p-2">
                        @foreach($application_status as $application_form)
                        @php 
                            $count1 == 0;
                            $active = '';
                            if($fg == $application_form->id) {
                                $active = 'active';
                            } else {
                                if($count1 == 0 && !$fg) {
                                    $active = 'active';
                                }
                            }
                            $count1++;
                        @endphp
                            <li class="nav-item"><a class="nav-link {{ $active }}" href="#tab_{{ $application_form->id }}" data-toggle="tab">{{ $application_form->name }}</a></li>
                        @endforeach
                    </ul>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in @php echo ($fgt == 1) ? 'show' : ''; @endphp">
                        <div class="tab-content">
                            @foreach($application_status as $application_form)
                                @php 
                                    $count2 == 0;
                                    $active = '';
                                    if($fg == $application_form->id) {
                                        $active = 'active';
                                    } else {
                                        if($count2 == 0 && !$fg) {
                                            $active = 'active';
                                        }
                                    }
                                    $count2++;
                                @endphp
                                <div class="card card-outline card-success tab-pane {{ $active }}" id="tab_{{ $application_form->id }}">
                                
                            <!-- /.card-header -->
                                <div class="card-header">
                                    <h3 class="card-title">{{ $application_form->name }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <strong>Status:</strong>
                                        @if($application_form->application_status_message($user->id))
                                        {{  $application_form->application_status_message($user->id)->application_status_message }}
                                        @else
                                        Inquiry
                                        @endif
                                    </div>
                                    <form action="{{ route('post.update', $user->id) }}" method="POST"  enctype="multipart/form-data">
                                        @csrf
                                        @method('post')
                                        @php
                                        $post_arr = $application_form->inputs_w_value($user->id)->toArray();
                                        @endphp
                                        <input type="hidden" name="form_group_type" value="1">
                                        <input type="hidden" name="form_group_id" value="{{ $application_form->id }}">
                                        @foreach($application_form->inputs_w_value($user->id) as $key => $input)
                                            @include('commons.form-applicationstatus', $input)
                                        @endforeach
                                        <div class="form-group col-md-12">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-info" style="padding: 0 20px;">
                    @php
                    $count3 = 0;
                    $count4 = 0;
                    @endphp 
                    <div class="card-header d-flex pt-1 pb-1" style="border-bottom: 0; padding-left:0; padding-right: 0;">
                    <h3 class="card-title pt-3 pb-3">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        Forms <i class="fas fa-angle-down"></i>
                        </a>
                    </h3>
                    <ul class="nav nav-pills ml-auto p-2">
                        @foreach($forms as $application_form)
                            @php 
                                $count3 == 0;
                                $active = '';
                                if($fg == $application_form->id) {
                                    $active = 'active';
                                } else {
                                    if($count3 == 0 && !$fg) {
                                        $active = 'active';
                                    }
                                }
                                $count3++;
                            @endphp
                        <li class="nav-item"><a class="nav-link {{ $active }}" href="#tab_{{ $application_form->id }}" data-toggle="tab">{{ $application_form->name }}</a></li>

                        @endforeach
                    </ul>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse in @php echo ($fgt == 0) ? 'show' : ''; @endphp">
                        <div class="tab-content">
                            @foreach($forms as $application_form)
                                @php 
                                    $count4 == 0;
                                    $active = '';
                                    if($fg == $application_form->id) {
                                        $active = 'active';
                                    } else {
                                        if($count4 == 0 && !$fg) {
                                            $active = 'active';
                                        }
                                    }
                                    $count4++;
                                @endphp
                            <div class="card card-outline card-success tab-pane {{ $active }}" id="tab_{{ $application_form->id }}">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $application_form->name }}</h3>
                                </div>
                                <div class="card-body">
                                    @if(auth()->user()->user_type == 'admin')
                                    <form action="{{ route('post.update', $user->id) }}" method="POST"  enctype="multipart/form-data">
                                        @csrf
                                        @method('post')
                                        <input type="hidden" name="form_group_type" value="0">
                                        <input type="hidden" name="form_group_id" value="{{ $application_form->id }}">
                                        @foreach($application_form->inputs_w_value($user->id) as $input)
                                            @include('commons.form-input', $input)
                                        @endforeach
                                        <div class="form-group col-md-12">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                    @else
                                        @foreach($application_form->inputs_w_value($user->id) as $input)
                                            @include('commons.form-show', $input)
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card card-outline card-info" style="margin-top: 70px;">
                    <div class="card-header">
                    <h3 class="card-title">Application Status Settings</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('save_user_application_status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        @php
                            $form_group_arr = explode(',', $user->profile->application_status);
                        @endphp
                        @foreach($forms_settings as $application_form)
                            @if($application_form->type == 1)
                            @php
                                $checked = '';
                                if(in_array($application_form->id, $form_group_arr)) {
                                $checked = 'checked';
                                }
                            @endphp

                            <div class="form-group" style="margin-bottom: 5px;">
                                <div div class="form-check">
                                <input class="form-check-input" type="checkbox" name="form_group[]" id="form_group_{{ $application_form->id }}" value="{{ $application_form->id }}" {{ $checked }}>&nbsp;&nbsp;
                                <label class="form-check-label" for="form_group_{{ $application_form->id }}">{{ $application_form->name }}</label>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                        </form>
                    </div>
                </div>

                <div class="card card-outline card-info">
                    <div class="card-header">
                    <h3 class="card-title">Forms Settings</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('save_user_form_group') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        @php
                            $form_group_arr = explode(',', $user->profile->forms);
                        @endphp
                        @foreach($forms_settings as $form)
                            @if($form->type == 0)
                            @php
                                $checked = '';
                                if(in_array($form->id, $form_group_arr)) {
                                $checked = 'checked';
                                }
                            @endphp
                            <div class="form-group" style="margin-bottom: 5px;">
                                <div div class="form-check">
                                <input class="form-check-input" type="checkbox" name="form_group[]" id="form_group_{{ $form->id }}" value="{{ $form->id }}" {{ $checked }}>&nbsp;&nbsp;
                                <label class="form-check-label" for="form_group_{{ $form->id }}">{{ $form->name }}</label>
                                </div>
                            </div>
                            @endif
                        @endforeach  
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                        </form>
                    </div>
                </div>

                <div class="card card-outline card-info" style="margin-top: 70px">
                    <div class="card-header">
                    <h3 class="card-title">Website Transactions <img src="https://cdn.shopify.com/assets/images/logos/shopify-bag.png" width="20" alt=""></h3>
                    </div>
                    <div class="card-body">
                    <div class="transaction-content">
                        <ul class="nav nav-tabs nav-tabs-neac border-0">
                            <li class="nav-item">
                                <a class="nav-link p-0 bg-transparent active" data-toggle="tab" href="#all">
                                    <span class="badge badge-primary px-3 rounded mb-2 font-sm">ALL</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-0 bg-transparent" data-toggle="tab" href="#pending">
                                    <span class="badge badge-danger px-3 rounded mb-2 font-sm">Pending</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-0 bg-transparent" data-toggle="tab" href="#for_approve">
                                    <span class="badge badge-warning px-3 rounded mb-2 font-sm">FOR APPROVED</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-0 bg-transparent" data-toggle="tab" href="#finance_approved">
                                    <span class="badge badge-success px-3 rounded mb-2 font-sm">FINANCE APPROVED</span>
                                </a>
                            </li>
                        </ul>
                        @if($shopify)
                            <div class="tab-content p-0">
                                <?php
                                    $count = 0;
                                    foreach($shopify as $cart) {
                                        $count++;  
                                        $status_class = '';
                                        $status_color = '#fff';

                                        if($cart->financial_status == 'paid') {
                                            $status_class = 'for_approve'; 
                                            $status_color = '#fff5d6';
                                        }
                                        else if ($cart->financial_status == 'pending') {
                                            $status_class = 'pending';
                                            $status_color = '#f0cccf';
                                        }
                                        if (array_key_exists($cart->name, $status)) {
                                            if($status[$cart->name] == 1) {
                                                $status_class = 'for_approve'; 
                                                $status_color = '#fff5d6';
                                            } else if ($status[$cart->name] == 2) {
                                                $status_class = 'finance_approved'; 
                                                $status_color = '#bcddc4';
                                            }
                                        }
                                        
                                ?>
                                        <div class="all <?= $status_class ?>">
                                            <a  style="background-color: <?= $status_color ?>; color: #666" class="cardboard mt-0 p-3 text-decoration-none d-block collapsed mb-1" data-toggle="collapse" href="#collapsenclex<?= $count ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                <div class="float-left">
                                                    <h5 class="mb-0"><?= $cart->name ?></h5>
                                                    <small title="Purchased this day" class="mr-3"><i class="fas fa-clock mr-1"></i> <?= date('F jS Y h:i:s A', strtotime($cart->created_at));?></small>
                                                    <small title="Merchant"><i class="fas fa-money mr-1"></i> <?= $cart->gateway ?></small>
                                                    <?php if($cart->note) { ?>
                                                        <i class="fas fa-sticky-note ml-3"></i>
                                                    <?php } ?>
                                                </div>
                                                <div class="float-right text-center">
                                                    <i class="fas fa-chevron-right"></i>
                                                </div>
                                                <div class="clearfix"></div>
                                            </a>
                                            <div class="collapse" id="collapsenclex<?= $count ?>"  style="background-color: #fff; margin-bottom: 25px; padding: 10px;">
                                                <table class="table table-striped table-hover mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Services</th>
                                                            <th>Price <?= $cart->currency ?></th>
                                                            <th><small>Presentment</small><br>Price <?= $cart->presentment_currency ?></th>
                                                        </tr>
                                                    </thead>
                                                    <?php
                                                        if($cart->line_items) {
                                                    ?>
                                                        <tbody>
                                                            <?php
                                                            foreach($cart->line_items as $item) {
                                                            ?>
                                                                <tr>
                                                                    <td><?= $item->title ?> <br><small><?= $item->variant_title ?></small></td>
                                                                    <td><?= $item->price_set->shop_money->amount ?></td>
                                                                    <td><?= $item->price_set->presentment_money->amount ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th style="border: none;">Sub Total</th>
                                                                <th style="border: none;"><?= $cart->subtotal_price_set->shop_money->amount ?></th>
                                                                <th style="border: none;"><?= $cart->subtotal_price_set->presentment_money->amount ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th style="border: none;">Tax</th>
                                                                <th style="border: none;"><?= $cart->total_tax_set->shop_money->amount ?></th>
                                                                <th style="border: none;"><?= $cart->total_tax_set->presentment_money->amount ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th>Total</th>
                                                                <th><?= $cart->total_price_set->shop_money->amount ?></th>
                                                                <th><?= $cart->total_price_set->presentment_money->amount ?></th>
                                                            </tr>
                                                            <tr>
                                                                <th>Payed</th>
                                                                <th><?= $cart->total_price_set->shop_money->amount ?></th>
                                                                <th><?= $cart->total_price_set->presentment_money->amount ?></th>
                                                            </tr>
                                                        </tfoot>
                                                    <?php
                                                        }
                                                    ?>
                                                </table>
                                                <?php if($cart->note) { ?>
                                                    <p class="p-3 mb-0"><strong>Note : </strong><?= $cart->note ?></p>
                                                <?php } ?>
                                            </div>
                                        </div>
                                <?php
                                        }
                                ?>
                            </div>
                        @else
                            <div class="p-3 bg-white">
                                <strong>No transaction as this moment!</strong>
                            </div>
                        @endif
                    </div>
                    </div>
                </div>

                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Offline Transactions</h3>
                    </div>
                    <div class="card-body">
                        <div class="transaction-content">
                            <ul class="nav nav-tabs nav-tabs-neac border-0">
                                <li class="nav-item">
                                    <a class="nav-link p-0 bg-transparent active" data-toggle="tab" href="#all">
                                        <span class="badge badge-primary px-3 rounded mb-2 font-sm">ALL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link p-0 bg-transparent" data-toggle="tab" href="#for_approve">
                                        <span class="badge badge-warning px-3 rounded mb-2 font-sm">FOR APPROVED</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link p-0 bg-transparent" data-toggle="tab" href="#finance_approved">
                                        <span class="badge badge-success px-3 rounded mb-2 font-sm">FINANCE APPROVED</span>
                                    </a>
                                </li>
                            </ul>
                            <?php 
                                if($offline) {
                            ?>
                                <div class="tab-content p-0">
                                    <?php
                                        $count = 0;
                                        foreach($offline as $cart) {
                                            $count++;  
                                            $status_class = '';
                                            $status_color = '#fff';
                                            
                                            if (array_key_exists($cart->order_no, $status)) {
                                                if($status[$cart->order_no] == 1) {
                                                    $status_class = 'for_approve'; 
                                                    $status_color = '#fff5d6';
                                                } else if ($status[$cart->order_no] == 2) {
                                                    $status_class = 'finance_approved'; 
                                                    $status_color = '#bcddc4';
                                                }
                                            }
                                            
                                    ?>
                                            <div class="all <?= $status_class ?>">
                                                <a  style="background-color: <?= $status_color ?>; color: #666;" class="cardboard mt-0 p-3 text-decoration-none d-block collapsed mb-1" data-toggle="collapse" href="#olfcollapsenclex<?= $count ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                    <div class="float-left">
                                                        <h5 class="mb-0"><?= $cart->order_no ?></h5>
                                                        <small title="Purchased this day" class="mr-3"><i class="fas fa-clock mr-1"></i> <?= date('F jS Y h:i:s A', strtotime($cart->payed_at));?></small>  
                                                        <?php if($cart->notes) { ?>
                                                            <i class="fas fa-sticky-note"></i>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="float-right text-center">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </a>
                                                <div class="collapse" id="olfcollapsenclex<?= $count ?>"  style="background-color: #fff; margin-bottom: 25px; padding: 10px;">
                                                    <table class="table table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Services</th>
                                                                <th>Price <?= $cart->currency ?></th>
                                                            </tr>
                                                        </thead>
                                                            <?php $line_items = json_decode($cart->items); ?>
                                                            <tbody>
                                                                <?php
                                                                    if(isset($line_items->lineItems)) {
                                                                        foreach($line_items->lineItems as $item) {
                                                                ?>
                                                                        <tr>
                                                                            <td><?= $item->title ?> <br><small><?= $item->variant->title ?></small></td>
                                                                            <td><?= $item->variant->price ?></td>
                                                                        </tr>
                                                                <?php
                                                                        }
                                                                    } else {
                                                                ?>
                                                                    <tr>
                                                                        <td colspan="2">No Service</td>
                                                                    </tr>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th style="border: none;">Sub Total</th>
                                                                    <th style="border: none;"><?= $line_items->subtotalPrice ?></th>
                                                                    
                                                                </tr>
                                                                <tr>
                                                                    <th style="border: none;">Tax</th>
                                                                    <th style="border: none;"><?= $line_items->totalTax ?></th>
                                                                    
                                                                </tr>
                                                                <tr>
                                                                    <th>Total</th>
                                                                    <th><?= $line_items->totalPrice ?></th>
                                                                </tr>
                                                                <tr>
                                                                    <th>Payed</th>
                                                                    <th><?= (is_int($cart->total)) ? $cart->total . '.00' : $cart->total  ?></th>
                                                                </tr>
                                                            </tfoot>
                                                    </table>
                                                    <?php if($cart->notes) { ?>
                                                        <p class="p-3 mb-0"><strong>Note : </strong><?= $cart->notes ?></p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                    <?php
                                            }
                                    ?>
                                </div>
                            <?php
                                } else {
                            ?>
                                <div class="p-3 bg-white">
                                    <strong>No Offline Transaction as this moment!</strong>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    @else
        <div class="text-center mt-5">
        <i class="fas fa-lock" style="font-size: 60px;"></i>
        <h2 class="mt-4">This applicant is locked by {{$lock_email}}</h2>
        </div>
    @endif

@endsection