<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Code</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Invalid Code',
        text: '{{ session('error') }}',
        confirmButtonColor: '#3b68b2'
    });
</script>
@endif

@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Check your inbox',
        text: '{{ session('success') }}',
        confirmButtonColor: '#3b68b2'
    });
</script>
@endif

<div class="container">
    <div class="form-box login">
        <form action="{{ route('verify.code') }}" method="POST">
            @csrf
            <h1>Enter Verification Code</h1>
            <p>Please enter the 6-digit code sent to your email.</p>

            <input type="hidden" name="email" value="{{ session('email') }}">

            <div class="input-box">
                <input type="text" name="code" placeholder="6-digit code" required>
            </div>

            <button type="submit" class="btn" style="background-color: #683695; color: white;">Verify</button>
              <div class="text-center" style="margin-top: 15px;">
                <a href="{{ route('login') }}" style="color: #683695;">Back to Login</a>
            </div>
        </form>
    </div>



  <div class="toggle-box">
     <div class="toggle-panel toggle-left">
            <img src="{{ asset('imgs/docme_logo.png') }}" alt="DocME Logo" class="logo img-fluid" width="300" height="100">
            <br>
            <h2>Verify to Continue</h2>
            <p style="margin: 10px 0;">Enter the code to reset your password.</p>
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
</body>
</html>
