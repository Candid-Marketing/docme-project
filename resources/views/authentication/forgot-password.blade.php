<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doc Me </title>
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



  <div class="container">
    <div class="form-box login">
        <form action="{{route('forgot.password.send')}}" method="POST">
            @csrf
            <h1>Forgot Password</h1>
            <p style="text-align: center; margin-bottom: 20px;">
                Enter the email address associated with your account and we’ll send you a password reset link.
            </p>
            <div class="input-box">
                <input type="email" placeholder="Enter your email address" name="email" required>
                <i class='bx bx-envelope'></i>
            </div>
            <button type="submit" class="btn" style="background-color: #683695; color: white;">Send Reset Link</button>
            <div class="text-center" style="margin-top: 15px;">
                <a href="{{ route('login') }}" style="color: #683695;">Back to Login</a>
            </div>
        </form>
    </div>

    <div class="toggle-box">
        <div class="toggle-panel toggle-left">
            <img src="{{ asset('imgs/docme_logo.png') }}" alt="DocME Logo" class="logo img-fluid" width="300" height="100">
            <br>
            <h2>Reset your password</h2>
            <p style="margin: 10px 0;">We’ll send a reset link to your email.</p>
        </div>

        <div class="toggle-panel toggle-right">
            <img src="{{ asset('imgs/docme_logo.png') }}" alt="DocME Logo" class="logo img-fluid" width="300" height="100">
            <br>
            <h2>Need to sign in?</h2>
            <p>Already have an account?</p>
            <button class="btn login-btn" onclick="window.location.href='{{ route('login') }}'" style="background-color: #683695; color: white;">Login</button>
        </div>
    </div>
</div>

  <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
