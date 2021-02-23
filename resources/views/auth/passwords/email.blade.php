@extends('layouts.landing-page')
@section('content')  

    <form class="form-signin bg-white shadow p-5 mr-auto ml-auto" style="margin-top: -10%" method="POST" action="{{ route('password.email') }}">
        <div class="text-center">
            <img src="{{asset('storage/photos/logo.png')}}" class="img-fluid mb-2" width="120" />
            <h1 class="h3 mb-1 font-weight-normal">NEAC | Admin</h1>
            <p>Reset Password</p>
        </div>
        @csrf
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="form-group row">
            <label for="email">{{ __('E-Mail Address') }}</label>

            <div class="input-group mb-1">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user" aria-hidden="true"></i></span>
                </div>
                <input id="email"  style="border-left: 1px solid #ced4da;" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-3">
                <button type="submit" class="btn btn-primary">
                    {{ __('Send Password Reset Link') }}
                </button>
            </div>
        </div>
    </form>
@endsection
