<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Verification</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- Favicons -->
  <link href="{{ asset('imgs/icon.png') }}" rel="icon">
  <link href="{{ asset('imgs/docme_logo.png') }}" rel="apple-touch-icon">

  <style>
    body {
      background: linear-gradient(135deg, #3abfdd, #3b68b2);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .otp-container {
      background-color: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      max-width: 420px;
      width: 100%;
      text-align: center;
    }

    .otp-container img {
      max-width: 180px;
      margin-bottom: 20px;
    }

    .otp-container h2 {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .otp-container p {
      font-size: 0.95rem;
      color: #666;
      margin-bottom: 20px;
    }

    .form-control {
      padding: 10px;
      border-radius: 8px;
    }

    .btn-verify {
      background-color: #3b68b2;
      color: white;
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      margin-top: 10px;
      font-weight: 500;
      font-size: 1rem;
    }

    .btn-verify:hover {
      opacity: 0.9;
    }

    .resend-link {
      font-size: 0.875rem;
      margin-top: 15px;
      display: block;
    }

    .resend-link a {
      color: #3b68b2;
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .otp-container {
        padding: 20px;
      }

      .otp-container h2 {
        font-size: 1.5rem;
      }

      .btn-verify {
        font-size: 0.95rem;
      }
    }
  </style>
</head>
<body>

  <!-- SweetAlert error popup -->
  @if ($errors->any())
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Oops!',
        text: '{{ $errors->first() }}',
      });
    </script>
  @endif

  <div class="otp-container">
    <!-- Logo -->
    <img src="{{ asset('imgs/docme_logo.png') }}" alt="DocME Logo">

    <!-- Title -->
    <h2>Verify Your Email</h2>
    <p>We've sent a one-time password (OTP) to your registered email. Please enter it below to verify your account.</p>

    <!-- Flash message -->
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <!-- OTP Form -->
    <form action="{{ route('verify-otp') }}" method="POST">
      @csrf
      <input type="hidden" name="email" value="{{ $email }}">
      <div class="mb-3 text-start">
        <label for="otp" class="form-label">OTP Code</label>
        <input type="text" name="otp" id="otp" class="form-control" placeholder="Enter the 6-digit code" required>
      </div>
      <button type="submit" class="btn btn-verify">Verify OTP</button>
    </form>

    <span class="resend-link">Didn't receive the code? <a href="#">Resend</a></span>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Prevent back navigation
    window.onload = () => {
      window.history.pushState(null, null, window.location.href);
    };
    window.onpopstate = () => {
      window.history.pushState(null, null, window.location.href);
    };
  </script>
</body>
</html>
