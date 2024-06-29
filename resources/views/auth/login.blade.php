<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Login | {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo1.jpg') }}">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/app.css">
</head>

<body class="d-flex align-items-center min-vh-100 bg-light">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            @if(Session::has('account_deactivated'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('account_deactivated') }}
                </div>
            @endif
            <div class="card p-4 border-0 shadow-sm">
                <div class="card-body">
                    <form id="login" method="post" action="{{ url('/login') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center mb-4">
                                <img width="450" src="{{ asset('images/logo1.png') }}" alt="Logo">
                            </div>
                        </div>
                        <p class="text-muted">Sign In to your account</p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                            </div>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" placeholder="Email">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                            </div>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" placeholder="Password">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <button id="submit" class="btn btn-primary px-4 d-flex align-items-center" type="submit">
                                    Login
                                    <div id="spinner" class="spinner-border text-light" role="status"
                                         style="height: 20px; width: 20px; margin-left: 5px; display: none;">
                                        <span class="visually-hidden"></span>
                                    </div>
                                </button>
                            </div>
                            <div class="col-8 text-end">
                                <a class="btn btn-link px-0" href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    let login = document.getElementById('login');
    let submit = document.getElementById('submit');
    let email = document.getElementById('email');
    let password = document.getElementById('password');
    let spinner = document.getElementById('spinner');

    login.addEventListener('submit', (e) => {
        submit.disabled = true;
        email.readOnly = true;
        password.readOnly = true;

        spinner.style.display = 'block';
    });

    setTimeout(() => {
        submit.disabled = false;
        email.readOnly = false;
        password.readOnly = false;

        spinner.style.display = 'none';
    }, 3000);
</script>
</body>
</html>