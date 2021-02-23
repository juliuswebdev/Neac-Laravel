<style>
  h1 { margin-top: 2 0px!important; }
  footer.main-footer,
  nav, aside, .breadcrumb { display: none!important }
  body.sidebar-mini .wrapper .content-wrapper { margin-left: 0!important }
</style>
@extends('layouts.dashboard')

@section('name_content')
    Edit Profile [{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}]
    <small>({{$user->user_type }})</small>
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
              {{ session()->get('message') }}
          </div>
        </div>
      @endif
      <div class="col-md-12">
        <form action="{{ route('applicants.update',$user) }}" method="POST" enctype="multipart/form-data"> 
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">Profile Information</h3>
            </div>
            <div class="card-body">
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
                      <div class="form-group">
                            @php
                                $required = ($input->required) ? 'required' : '';
                            @endphp 
                            @if($input->id == 483)
                            <label>{{ $input->label }} @if($required)<span style="color:red;">*</span>@endif</label>
                            @endif
                            <div class="form-content">
                                @if($input->id == 483)
                                    <input type="text" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control" placeholder="{{ $input->placeholder }}" {{ $required }}>
                                @endif
                            </div>
                      </div>
                  @endforeach
                  <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </div>
        </form>
      </div>

  </section>
  <!-- /.content -->

@endsection