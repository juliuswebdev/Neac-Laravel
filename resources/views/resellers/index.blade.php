
@extends('layouts.dashboard')

@section('name_content')
   Reseller
@endsection

@section('content')
<section class="content">
    <div class="row">
        @if(!$user)
        <div class="col-md-4">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Reseller Registration</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('reseller.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="first_name" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('First Name') }}<span style="color:red;">*</span></label>

                            <div class="col-md-7">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="middle_name" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Middle Name') }}</label>

                            <div class="col-md-7">
                                <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="last_name" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Last Name') }}<span style="color:red;">*</span></label>

                            <div class="col-md-7">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}<span style="color:red;">*</span></label>
                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="profession" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Company/Profession') }}</label>
                            <div class="col-md-7">
                                <input id="profession" type="text" class="form-control" name="profession" value="{{ old('profession') }}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="phone_number" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Contact Number') }}<span style="color:red;">*</span></label>
                            <div class="col-md-7">
                                <input id="phone_number" type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Password') }}<span style="color:red;">*</span></label>

                            <div class="col-md-7">
                                <input id="password" type="password" class="form-control password @error('password') is-invalid @enderror" name="password" required>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password-confirm" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}<span style="color:red;">*</span></label>

                            <div class="col-md-7">
                                <input id="password-confirm" type="password" class="form-control password" name="password_confirmation" required>
                                <a href="javascript:void(0)" class="btn btn-info show-hide-password" style="margin-top: 5px;" title="Show Hide"><i class="fas fa-eye-slash"></i></a>
                                <a href="javascript:void(0)" class="btn btn-warning generate-password" style="color: #fff; margin-top: 5px;" title="Generate Password"><i class="fas fa-key"></i></a>
                            </div>
                            
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-7 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <small><strong>Note:</strong> Reseller Codes are automatically generated!</small>
                </div>
            </div>
        </div>
        @endif
        @if($user)
        <div class="col-md-7">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">User Information</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Reseller Code</th>
                            <td>
                                <input type="text" class="form-control mb-1 reseller-input input-disabled" value="{{ $user->reseller_code }}" disabled>
                                <a href="javascript:void(0)" class="input-disabled-edit"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-primary input-disabled-update reseller-update" style="display: none;" data-token="{{ csrf_token() }}" data-user_id="{{$user->id}}">Update</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Fullname</th>
                            <td>{{ $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name  }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Mobile Number</th>
                            <td>
                            @php  $post_contact_number = App\Post::where('user_id', $user->id)->where('input_id', 483)->first() @endphp
                            @if($post_contact_number)
                                {{ $post_contact_number->post }}
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Company/Profession</th>
                            <td>{{ $user->profession }}</td>
                        </tr>
                        <tr>
                            <th>User Type</th>
                            <td>{{ $user->user_type }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Transactions</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('reseller.update', $user->id) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="mode" value="prize">
                        <label for="">Successful Applicant Cost</label>
                        <input type="text" name="reseller_prize" class="form-control" value="{{ $user->reseller_prize }}"
                        style="width: calc(100% - 95px);
                        margin-right: 15px;
                        display: inline-block;
                        vertical-align: bottom;"
                        ><button type="submit" class="btn btn-primary">Update</button>
                    </form>
                    <table class="table mt-4">
                        <tr>
                            <tr>
                                <th>Total Earn ({{ $completed_count }})</th>
                                <td>{{ number_format($total_earn, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Total Deposit ({{ $paid_count }})</th>
                                <td>{{ number_format($total_deposit, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Total Balance</th>
                                <td>{{ number_format($total_balance, 2) }}</td>
                            </tr>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @else
            <div class="col-md-8">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Resellers</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET" class="row mb-3">
                            <div class="col-md-5">
                                <input type="text" name="q" class="form-control" placeholder="ex. Name, Email & Company/Profession" value="@if(isset($_GET['q'])){{$_GET['q']}}@endif">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Code</th>
                                    <th>Email</th>
                                    <th>Full Name</th>
                                    <th>Company/Profession</th>
                                    <th>User Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$users->isEmpty())
                                    @php $users_count = 0; @endphp
                                    @foreach($users as $user)
                                        @php $users_count++; @endphp
                                        <tr class="@php echo ($user->approval == 1) ? 'active' : 'deactivated'; @endphp">
                                            <td>{{  $users_count }}</td>
                                            <td>
                                                <a href="/reseller?code={{ $user->reseller_code }}">{{ $user->reseller_code }}</a>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name }}</td>
                                            <td>{{ $user->profession }}</td>
                                            <td>{{ $user->user_type }}</td>
                                            <td>
                                                @if($user->user_type == 'business')
                                                    <a onclick="open_window('{{ route('reseller.edit',$user->id) }}')" href="javascript:void(0)" class="btn btn-sm mb-1 btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                @else
                                                    <a onclick="open_window('{{ route('applicants.edit',$user->id) }}')" href="javascript:void(0)" class="btn btn-sm mb-1 btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                @endif
                                                @if( $user->status == 1 )
                                                <form action="{{ route('applicants.destroy',$user->id) }}" method="POST" style="display: inline-block">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm mb-1 btn-danger" onclick="return confirm('Do you really want to delete this user?');" title="Delete {{$user->email}}"><i class="fas fa-trash"></i></button>
                                                </form>
                                                @endif
                                                <br>
                                                <button type="button" class="btn btn-sm btn-warning " data-toggle="modal" data-target="#resetPasswords{{ $users_count }}"><i class="fas fa-key" style="color: #fff;"> </i> </button>
                                                <div class="modal fade" id="resetPasswords{{ $users_count }}" tabindex="-1" aria-labelledby="resetPasswords" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                    <form action="{{ route('reseller.resetpassword',$user->id) }}" class="change-password" method="POST">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Change Password <small>[{{ $user->first_name }} {{ $user->last_name }}]</small></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @csrf
                                                                @method('POST')
                                                                <div class="row">
                                                                <label class="col-md-3 col-form-label text-md-right">New Password</label>                                   
                                                                <div class="col-md-6">                      
                                                                    <input type="password" class="password form-control" name="password" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <a href="javascript:void(0)" class="btn btn-info show-hide-password" title="Show Hide"><i class="fas fa-eye-slash"></i></a>
                                                                    <a href="javascript:void(0)" class="btn btn-warning generate-password" style="color: #fff;" title="Generate Password"><i class="fas fa-key"></i></a>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="alert-change-password"></div>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    </div>
                                                </div>
                                                <a onclick="open_window('{{ route('mail.show',$user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-info" title="Send Mail"><i class="fas fa-envelope"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr style="color: #212529; background-color: rgba(0,0,0,.075);">
                                    <td colspan="7" class="text-center"><strong>No result found!</strong></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        @if(!$users->isEmpty())
                            <div class="mt-3">
                            @php 
                                $paginator = $users;
                                $count = $users_count; 
                            @endphp
                            @include('commons.pagination', [$paginator, $count])
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="row">
        @if($reffered)
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Referred Friend Lists</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reseller.update', $user->id) }}" method="POST" class="mb-0">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="mode" value="status">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Mobile Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $count = 1; @endphp
                                    @if(!$reffered->isEmpty())
                                        @foreach($reffered as $item)
                                            <tr>
                                                <td>{{ $count++ }}</td>
                                                <td>{{ $item->first_name }} {{ $item->middle_name }} {{ $item->last_name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->mobile_number }}</td>
                                                <th>
                                                    <select name="status[{{ $item->id }}]">
                                                        <option value="0" @if($item->status == 0) selected @endif>Pending</option>
                                                        <option value="1" @if($item->status == 1) selected @endif>Paid</option>
                                                    </select>
                                                </th>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr style="color: #212529; background-color: rgba(0,0,0,.075);">
                                            <td colspan="6" class="text-center"><strong>No result found!</strong></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if($count > 1)

                            <button type="submit" class="btn btn-primary" style="float: right; ">Update</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        @endif
        @if($registered_referred)
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Successful Registered from Referred Friend</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reseller.update', $user->id) }}" method="POST" class="mb-0">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="mode" value="status">
                            <table class="table table-striped mb-3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Mobile Number</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $count = 1; @endphp
                                    @if(!$registered_referred->isEmpty())
                                        @foreach($registered_referred as $item)
                                            <tr>
                                                <td>{{ $count++ }}</td>
                                                <td>{{ $item->first_name }} {{ $item->middle_name }} {{ $item->last_name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->mobile_number }}</td>
                                                <td>
                                                    @if($item->cart_status == 2)
                                                        <span class="text-success">Successful Applicant</span>
                                                    @else
                                                        <span class="text-primary">Registered</span>
                                                    @endif
                                                </td>
                                                <th>
                                                    <select name="status[{{ $item->id }}]">
                                                        <option value="0" @if($item->status == 0) selected @endif>Pending</option>
                                                        <option value="1" @if($item->status == 1) selected @endif>Paid</option>
                                                    </select>
                                                </th>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr style="color: #212529; background-color: rgba(0,0,0,.075);">
                                            <td colspan="7" class="text-center"><strong>No result found!</strong></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if($count > 1)
                            <button type="submit" class="btn btn-primary" style="float: right; ">Update</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
</section>

@endsection