<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login &middot; Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>body{background:#1f2937;}</style>
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
<div class="container" style="max-width:400px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h4 class="text-center mb-1 fw-bold"><i class="bi bi-box-seam me-2"></i>Inventory</h4>
            <p class="text-center text-muted mb-4">Sign in to your account</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button class="btn btn-primary w-100">Sign In</button>
            </form>
        </div>
    </div>
    <p class="text-center text-secondary small mt-3">Default: admin@example.com / password</p>
</div>
</body>
</html>
