@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<div class="section-authentication-cover">
    <div class="row g-0">
        <!-- Left Image -->
        <div class="col-12 col-xl-7 col-xxl-8 d-none d-xl-flex border-end bg-transparent">
            <div class="card rounded-0 mb-0 border-0 shadow-none bg-transparent">
                <div class="card-body">
                    <img src="{{ URL::asset('build/images/auth/login1.png') }}" class="img-fluid auth-img-cover-login" width="650" alt="">
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="col-12 col-xl-5 col-xxl-4 border-top border-4 border-primary border-gradient-1 d-flex align-items-center justify-content-center">
            <div class="card rounded-0 m-3 mb-0 border-0 shadow-none bg-none">
                <div class="card-body p-sm-5">
                    <img src="{{ URL::asset('build/images/logo1.png') }}" class="mb-4" width="145" alt="">
                    <h4 class="fw-bold">Get Started Now</h4>
                    <p class="mb-0">Enter your credentials to login your account</p>

                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="row g-3 mt-4">
                        @csrf

                        <div class="col-12">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                placeholder="john@example.com" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group" id="show_hide_password">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter your password" required>
                                <a href="javascript:void(0);" class="input-group-text bg-transparent"><i class="bi bi-eye-slash-fill"></i></a>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="role" class="form-label">Login As <span class="text-danger">*</span></label>
                            <select class="form-control" name="role" required>
                                <option value="" disabled selected>Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label">Remember Me</label>
                        </div>

                        @if(Route::has('password.request'))
                            <div class="col-md-6 text-end">
                                <a href="{{ route('password.request') }}">Forgot Password?</a>
                            </div>
                        @endif

                        <div class="col-12">
                            <button type="submit" class="btn btn-grd-primary w-100">Login</button>
                        </div>

                        <div class="col-12 text-start">
                            <p class="mb-0">Don't have an account yet? <a href="{{ route('register') }}">Sign Up</a></p>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        let input = $('#show_hide_password input');
        let icon = $('#show_hide_password i');
        if(input.attr("type") == "text"){
            input.attr('type','password');
            icon.addClass("bi-eye-slash-fill").removeClass("bi-eye-fill");
        } else {
            input.attr('type','text');
            icon.removeClass("bi-eye-slash-fill").addClass("bi-eye-fill");
        }
    });
});
</script>
@endpush
