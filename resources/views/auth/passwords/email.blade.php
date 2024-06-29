<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <title>Send Reset Password Link | {{ config('app.name') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo1.jpg') }}">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="d-flex align-items-center min-vh-100 bg-light">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- <div class="text-center mb-4">
                <img width="500" src="{{ asset('images/logo1.png') }}" alt="Logo">
            </div> -->
            <div class="card p-4 border-0 shadow-sm">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="post" action="{{ url('/password/email') }}">
                        @csrf
                        <h3 class="mb-3">Reset Your Password</h3>
                        <p class="text-muted mb-4">Enter your email to receive a password reset link</p>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                            </div>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                   value="{{ old('email') }}" placeholder="Email">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-envelope"></i> Send Password Reset Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="{{ mix('js/app.js') }}" defer></script>

</body>
</html>