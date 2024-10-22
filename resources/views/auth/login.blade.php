@extends('main')

@section('content')
    <section class="loginSection--main overflow-hidden"
        style="background-color: #1a1c1e;">
        <div class="container-fluid">
            <div class="row vh-100">
                <div class="col-md-7">
                    <div class="row justify-content-center align-items-center h-100">
                        <div class="col-md-6">
                            <div
                                class="loginBox--main">
                                <div class="loginInner--boxMain">
                                    <div class="siteLogo--box mb-3">
                                        <img src="{{ asset('storage/image/desktop-dark.png') }}" alt="Site Logo">
                                    </div>
                                    <div class="loginInner--form-Main">
                                        <div class="form--heading mb-3">
                                            <h4 class="text-capitalize text-white fs-4">Login</h4>
                                        </div>
                                        <!-- Display Error Messages -->
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="form-group mb-2">
                                                <label for="email"
                                                    class="text-capitalize text-white fs-6 mb-2">Email</label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    placeholder="username@email.com" autocomplete="off" required>
                                            </div>
                                            <div class="form-group mb-2">
                                                <label for="password"
                                                    class="text-capitalize text-white fs-6 mb-2">Password</label>
                                                <div class="passwordGroup--main position-relative">
                                                    <input type="password" name="password" id="password"
                                                        class="form-control" placeholder="Password" required>
                                                    <div
                                                        class="show--hideBtn d-flex align-items-center justify-content-center position-absolute top-0 h-100 end-0 px-2">
                                                        <div class="showIcon text-white" style="display: none;">
                                                            <i class="fa-regular fa-eye-slash"></i>
                                                        </div>
                                                        <div class="hideIcon text-white">
                                                            <i class="fa-regular fa-eye"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" name="remember" id="remember"
                                                    class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
                                                <label for="remember" class="form-check-label text-white">Remember Password ?</label>
                                            </div>
                                            <button type="submit"
                                                class="login--formBtn text-uppercase border-0 text-white w-100 rounded btn btn-primary mt-3 fw-normal">
                                                Login
                                            </button>
                                            <div class="mt-2">
                                                <a href="{{ route('password.request') }}" class="text-danger" style="font-size: 14px;">Forgot Your
                                                    Password ?</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 p-0 position-relative">
                    <div class="login--backgroundImg">
                        <img src="{{ asset('storage\image\login--side.jpg') }}" class="position-absolute top-0 bottom-0 start-0 end-0">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
