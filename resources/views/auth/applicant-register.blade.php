<style>
    .form-applynow h5 {
        padding: 5px 15px;
        background-color: #a5dae4;
    }
    .form-group > p { margin-bottom: 8px!important; }
    .quick-survey-source-item { margin-bottom: 10px; }
</style>
@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Applicant Registration</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin-bottom: 0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('applicants.store') }}" method="POST" class="form-applynow container bg-white mx-auto">
                        @csrf
                        @method('POST')
                        <div>
                            <h5>
                                Log-in Information
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p class="m-0 text-muted">E-mail:<span style="color:red;">* (You must enter a VALID e-mail address as your username)</span></p>
                                        <input class="form-control" name="email" type="email" value="{{ old('email') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <p class="m-0 text-muted">Alternative E-mail: </p>
                                        <input class="form-control" name="alternate_email" type="email" value="{{ old('alternate_email') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <p class="m-0 text-muted">Password: <span style="color:red;">* (6 characters or more)</span></p>
                                        <input class="form-control password" name="password" type="password" required>
                                    </div>
                                    <div class="form-group">
                                        <p class="m-0 text-muted">Re-type Password: <span style="color:red;">*</span></p>
                                        <input class="form-control password" name="confirm_password" type="password" required>
                                        <a href="javascript:void(0)" class="btn btn-info show-hide-password" style="margin-top: 5px;" title="Show Hide"><i class="fas fa-eye-slash"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-warning generate-password" style="color: #fff; margin-top: 5px;" title="Generate Password"><i class="fas fa-key"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-12 form-group">
                                    <p class="mb-0 text-muted">Upload your Image here:</p>
                                    <div class="profile-image-upload"></div>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="form-control-file custom-file-input" id="profile-image" name="image" accept=".jpg, .gif, .png">
                                            <label class="custom-file-label" for="profile-image">Choose Files</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 form-group">
                                    <p class="mb-0 text-muted">First Name:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="first_name" type="text" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="col-md-2 form-group">
                                    <p class="mb-0 text-muted">Middle Name:</p>
                                    <input class="form-control" name="middle_name" type="text" value="{{ old('middle_name') }}"> 
                                </div>
                                <div class="col-md-5 form-group">
                                    <p class="mb-0 text-muted">Last Name:<span style="color:red;">*</span></p>
                                    <input class="form-control" name="last_name" type="text" value="{{ old('last_name') }}" required> 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                           
                            @foreach($applicant_profile_form->inputs as $input)
                                @include('commons.form-input', $input)
                            @endforeach
                        </div>
                        <div>
                            <h5>
                                REFERRED BY: If the applicant is referred by Client. ENDORSED BY: If we have referred the applicant to the client.
                            </h5>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <p class="mb-0 text-muted">To avail a discount enter your promocode here:</p>
                                    <input class="form-control" name="reseller_code" type="text">
                                </div>  
                                <div class="col-md-6 form-group">
                                    <p class="mb-0 text-muted">Agency/BPO/Review Center Referred By:</p>
                                    <select name="referred_by" class="select2 form-control">
                                        <option disabled selected>Select</option>
                                        @foreach($resellers as $reseller)
                                            <option value="{{ $reseller->id }}">{{ $reseller->first_name . ' ' . $reseller->last_name }}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-primary font-sm" type="submit" name="submit">Register</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

@endsection