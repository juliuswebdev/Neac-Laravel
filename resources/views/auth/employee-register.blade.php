@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reseller Registration</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="first_name" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('First Name') }}<span style="color:red;">*</span></label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="middle_name" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Middle Name') }}</label>

                            <div class="col-md-6">
                                <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name') }}">

                                @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="last_name" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Last Name') }}<span style="color:red;">*</span></label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}<span style="color:red;">*</span></label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Role') }}<span style="color:red;">*</span></label>
                            <div class="col-md-6">
                            
                                <select name="role" id="role" class="form-control" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->label }}</option>
                                    @endforeach
                                </select>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="font-weight-normal col-md-4 col-form-label text-md-right">{{ __('Password') }}<span style="color:red;">*</span></label>

                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control password" name="password_confirmation" required>
                                <a href="javascript:void(0)" class="btn btn-info show-hide-password" style="margin-top: 5px;" title="Show Hide"><i class="fas fa-eye-slash"></i></a>
                                <a href="javascript:void(0)" class="btn btn-warning generate-password" style="color: #fff; margin-top: 5px;" title="Generate Password"><i class="fas fa-key"></i></a>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>

                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
