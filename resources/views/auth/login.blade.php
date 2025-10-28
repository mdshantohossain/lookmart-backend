<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8" />
    <title>Admin | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/') }}admin/assets/images/favicon.png" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('/') }}admin/assets/css/bootstrap.min.css"  rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('/') }}admin/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('/') }}admin/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- App js -->
    <script src="{{ asset('/') }}admin/assets/js/plugin.js"></script>
</head>

<body>
<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary-subtle">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p>Sign in to continue to Look Mart.</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ asset('/') }}admin/assets/images/profile-img.png" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="auth-logo">
                            <div class="auth-logo-light">
                                <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ asset('/') }}admin/assets/images/logo-light.svg" alt="" class="rounded-circle" height="34">
                                            </span>
                                </div>
                            </div>

                            <div class="auth-logo-dark">
                                <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ asset('/') }}admin/assets/images/logo.svg" alt="" class="rounded-circle" height="34">
                                            </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-2">

                            @if(Session::has('credentialError'))
                                <div class="alert alert-danger">
                                    {{ session('credentialError') }}
                                </div>
                            @endif

                            <form class="form-horizontal" action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="{{ old('email') }}" name="email" placeholder="Enter email" />
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" class="form-control" name="password" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                                        <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                    </div>
                                    @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="my-2 text-end">
                                    <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock me-1"></i> Forgot your password?</a>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-check">
                                    <label class="form-check-label" for="remember-check">
                                        Remember me
                                    </label>
                                </div>

                                <div class="mt-3 d-grid">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit">Log In</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                <div class="mt-2 text-center">
                    <div>
                        <p>© <script>document.write(new Date().getFullYear())</script> {{ env('APP_NAME') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end account-pages -->

<!-- JAVASCRIPT -->
<script src="{{ asset('/') }}admin/assets/libs/jquery/jquery.min.js"></script>
<script src="{{ asset('/') }}admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('/') }}admin/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="{{ asset('/') }}admin/assets/libs/simplebar/simplebar.min.js"></script>
<script src="{{ asset('/') }}admin/assets/libs/node-waves/waves.min.js"></script>

<!-- App js -->
<script src="{{ asset('/') }}admin/assets/js/app.js"></script>
</body>
</html>
