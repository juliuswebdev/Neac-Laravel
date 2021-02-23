@extends('layouts.dashboard')

@section('name_content')
    Edit Profile {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}  <small>({{$user->email}})</small>
@endsection
@section('content')
  <!-- Main content -->
  @php
    $document_path = '/documents/';
  @endphp
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="card card-outline card-info">
          <div class="card-header">
            <h3 class="card-title">Basic Information</h3>
            <div class="card-tools">
              <strong>Role: </strong>
              @foreach($roles as $role)
                  @php echo ($user->user_type == $role->name) ? $role->label  : ''; @endphp
              @endforeach
            </div>
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
            <form action="{{ route('profile.update',$user) }}" method="POST" enctype="multipart/form-data">  
              @csrf
              @method('PUT')
                <div class="form-group">
                  <img style="max-width: 150px; margin-bottom: 10px;" src="{{ $document_path }}{{ $user->employee->image }}" alt="">
                </div>
                <div class="form-group form-content">
                    <label for="uploadImage">Upload your Image here</label><br>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="form-control-file custom-file-input" id="uploadImage" name="image" accept=".jpg, .gif, .png">
                        <label class="custom-file-label" for="uploadImage">Choose Files</label>
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
                  <label for="profession">Position</label>
                  <div class="form-content">
                    <input type="text" class="form-control" value="{{ $user->profession }}" id="profession" name="profession">
                  </div>
                </div>

                <div class="form-group">
                  <label for="alternate_email">Alternative Email Address</label>
                  <div class="form-content">
                    <input type="email" class="form-control" value="{{ $user->employee->alternate_email }}" id="alternate_email" name="alternate_email">
                  </div>
                </div>

                <div class="form-group">
                  <label for="gender">Gender</label>
                  <div class="form-content">
                    <select class="form-control" id="gender" name="gender">        
                      <option value="1" @if($user->employee->gender == 1) selected @endif>Male</option>
                      <option value="2" @if($user->employee->gender == 2) selected @endif>Female</option>
                    </select> 
                  </div>
                </div>

                <div class="form-group">
                  <label for="birth_date">Birth Date</label>
                  <div class="form-content">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" name="birth_date" value="{{ $user->employee->birth_date }}" id="birth_date" class="form-control" placeholder="">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="telephone_number">Telephone Number</label>
                  <div class="form-content">
                    <input type="text" class="form-control" value="{{ $user->employee->telephone_number }}" id="telephone_number" name="telephone_number">
                  </div>
                </div>

                <div class="form-group">
                  <label for="mobile_number">Mobile Number</label>
                  <div class="form-content">
                    <input type="text" class="form-control" value="{{ $user->employee->mobile_number }}" id="mobile_number" name="mobile_number">
                  </div>
                </div>

                <div class="form-group">
                  <label for="home_address">Home Address</label>
                  <div class="form-content">
                    <input type="text" class="form-control" value="{{ $user->employee->home_address }}" id="home_address" name="home_address">
                  </div>
                </div>

                <div class="form-group">
                  <label for="city">City</label>
                  <div class="form-content">
                    <input type="text" class="form-control" value="{{ $user->employee->city }}" id="city" name="city">
                  </div>
                </div>

                <div class="form-group">
                  <label for="state">State/Province</label>
                  <div class="form-content">
                    <input type="text" class="form-control" value="{{ $user->employee->state }}" id="state" name="state">
                  </div>
                </div>

                <div class="form-group">
                  <label for="postal_code">Zip/Postal Code</label>
                  <div class="form-content">
                    <input type="text" class="form-control" value="{{ $user->employee->postal_code }}" id="postal_code" name="postal_code">
                  </div>
                </div>

                <div class="form-group">
                  <label for="country">Country</label>
                  <div class="form-content">  
                    <select class="form-control" id="country"  name="country">
                      @foreach ($countries as $country)
                      <option value="{{ $country->code }}" @if($user->employee->country == $country->code) selected @endif>{{ $country->name }}</option>
                      @endforeach
                    </select>     
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session()->get('success') }}
            </div>
        @endif

        <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">Reset Password</h3>
            </div>
            <div class="card-body">
              @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul style="margin-bottom: 0;">
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
              @if(session()->has('danger'))
                  <div class="alert alert-danger">
                      <ul style="margin-bottom: 0;">
                        <li>{{ session()->get('danger') }}</li>
                      </ul>
                  </div>
              @endif
              <form action="{{ route('profile.resetpassword', $user) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="row mx-0">
                      <div class="col-md-5 p-2">
                          <p class="m-0 text-muted">Old Password</p>
                      </div>
                      <div class="col-md-7 p-2">
                          <input type="password" name="old_password" class="form-control font-md"/>
                      </div>
                  </div>
                  <hr>
                  <div class="row mx-0">
                      <div class="col-md-5 p-2">
                          <p class="m-0 text-muted">New Password
                          </p>
                      </div>
                      <div class="col-md-7 p-2">
                          <input type="password" name="new_password" id="new_password" class="form-control font-md"/>
                      </div>
                  </div>
                  <div class="row mx-0">
                      <div class="col-md-5 p-2">
                          <p class="m-0 text-muted">Confirm New Password</p>
                      </div>
                      <div class="col-md-7 p-2">
                          <input type="password" name="confirm_password" id="confirm_password" class="form-control font-md"/>
                      </div>
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary font-sm mt-2">
                      Update
                  </button>
              </form>
            </div>
        </div>
      </div>
  </section>
@endsection