@extends('main')

@section('content')
<section class="loginSection--main overflow-hidden cover"
    style="background-image: url('/storage/image/loginBackground--image.jpg');">
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-8">
                <div class="loginBox--main border rounded p-4 d-flex align-items-center justify-content-center position-relative overflow-hidden">
                    <div class="loginBox--backgroundImgBox position-absolute top-0 start-0 end-0 bottom-0">
                        <img src="/storage/image/loginBox--backgroundImg.png" class="rounded w-100 h-100">
                    </div>
                    <div class="loginInner--boxMain border rounded">
                        <h2 class="text-center text-white text-capitalize mb-3 fs-4">Your Logo</h2>
                        <div class="loginInner--form-Main">
                            <div class="form--heading mb-3">
                                <h4 class="text-capitalize text-white fs-2">Reset Password</h4>
                            </div>
                            <!-- Display Success Message -->
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
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
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="form-group mb-2">
                                  <label for="email" class="text-capitalize text-white fs-6 mb-2">Email</label>
                                  <input type="email" name="email" id="email" class="form-control" placeholder="username@email.com" required>
                                </div>
                                  <button type="submit" class="login--formBtn text-uppercase border-0 text-white w-100 rounded btn btn-primary mt-3 fw-bolder">
                                    Send Password Reset Link
                                  </button>
                            </form>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
