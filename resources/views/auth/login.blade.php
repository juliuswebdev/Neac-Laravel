@extends('layouts.landing-page')
@section('content')    
              
 <form class="form-signin bg-white shadow p-5 mr-auto ml-auto" style="margin-top: -10%" method="POST" action="{{ route('login') }}">
    @csrf
        <div class="text-center">
            <img src="{{asset('storage/photos/logo.png')}}" class="img-fluid mb-2" width="120" />
            <h1 class="h3 mb-1 font-weight-normal">NEAC | Admin</h1>
            <p>Sign in to start your session</p>
        </div>
        <div class="form-group">
            <label>Email address</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input id="email" class="form-control" type="email" @error('email') is-invalid @enderror name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}">    
            </div>
        </div>
        <div class="form-group">   
            <label>Password</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input id="password" type="password" class="form-control"  @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
            </div>
        </div>

        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember">
                    <label for="remember">
                        Remember Me
                    </label>
            </div>
        </div>
        <p><a href="/password/reset">Forgot password ?<br><i class="ti-arrow-right"></i></a></p>
        @error('email') 
            <p class="col-md-12"><span style="color: red"><strong>{{ $message }}</strong></span></p>
        @enderror
        @error('password')   
            <p class="col-md-12"><span style="color: red"> <strong>{{ $message }}</strong></span></p>  
        @enderror
        <!-- /.col -->
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block"> {{ __('Login') }}</button>
        </div>
    </div>
</form> 
                
@endsection
