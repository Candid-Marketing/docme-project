<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doc Me – Reset Password</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Favicons -->
  <link href="{{ asset('imgs/icon.png') }}" rel="icon">
  <link href="{{ asset('imgs/docme_logo.png') }}" rel="apple-touch-icon">
</head>
<body>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Reset Error',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#3b68b2'
        });
    });
</script>
@endif

@if (session('status'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('status') }}',
            confirmButtonColor: '#3b68b2'
        });
    });
</script>
@endif

<div class="container">
  <div class="form-box login">
    <form action="{{route('password.custom.reset')}}" method="POST">
        @csrf
        <h1>Reset Your Password</h1>
        <p style="text-align: center; margin-bottom: 20px;">
            Enter and confirm your new password below.
        </p>

        <input type="hidden" name="email" value="{{ old('email', $email ?? session('email')) }}">



        <div class="input-box">
            <input type="password" name="password" placeholder="New Password" required>
            <i class='bx bx-lock-alt'></i>
        </div>

        <div class="input-box">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <i class='bx bx-lock-alt'></i>
        </div>

        <button type="submit" class="btn" style="background-color: #683695; color: white;">Reset Password</button>

        <div class="text-center" style="margin-top: 15px;">
            <a href="{{ route('login') }}" style="color: #683695;">Back to Login</a>
        </div>
    </form>
  </div>

  <div class="toggle-box">
    <div class="toggle-panel toggle-left">
        <img src="{{ asset('imgs/docme_logo.png') }}" alt="DocME Logo" class="logo img-fluid" width="300" height="100">
        <br>
        <h2>Create your new password</h2>
        <p style="margin: 10px 0;">Passwords must match and be at least 6 characters long.</p>
    </div>

    <div class="toggle-panel toggle-right">
        <img src="{{ asset('imgs/docme_logo.png') }}" alt="DocME Logo" class="logo img-fluid" width="300" height="100">
        <br>
        <h2>Need help logging in?</h2>
        <p>Return to the login screen if you remember your password.</p>
        <button class="btn login-btn" onclick="window.location.href='{{ route('login') }}'" style="background-color: #683695; color: white;">Login</button>
    </div>
  </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
