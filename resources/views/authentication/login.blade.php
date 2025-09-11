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


   @if (session('social_existing_user'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Welcome Back!',
                text: 'You’re logged in using your existing account.',
                confirmButtonColor: '#3b68b2'
            });
        </script>
        @endif

        @if (session('social_new_user'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Account Created!',
                    text: 'Your account was successfully created using {{ ucfirst(session('social_new_user')) }}.',
                    confirmButtonColor: '#3b68b2'
                });
            </script>
        @endif

    @if ($errors->any())
    <script>
        // Delay to ensure DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            // If there's an error AND at least one input is from the registration form
            @if(old('fname') || old('lname') || old('cpass'))
                document.querySelector('.container').classList.add('active'); // Optional: show register tab if you toggle forms this way

                Swal.fire({
                    icon: 'error',
                    title: 'Registration Error',
                    text: '{{ $errors->first() }}', // Shows first validation error
                    confirmButtonColor: '#3b68b2'
                });
            @endif
        });
    </script>
@endif

@if (session('register_success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Registration Complete!',
                text: '{{ session('register_success') }}',
                confirmButtonColor: '#3b68b2'
            });
        });
    </script>
@endif

@if ($errors->has('auth'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: '{{ $errors->first("auth") }}',
            confirmButtonColor: '#683695'
        });
    });
</script>
@endif


  <div class="container">
      <div class="form-box login">
        <form action="{{ route('login.authenticate') }}" method="POST">
            @csrf
            <h1>Login</h1>
            <div class="input-box">
                <input type="email" placeholder="Email" name="email" required>
                <i class='bx bx-envelope' ></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Password" name="password" required>
                <i class='bx bx-lock'></i>
            </div>
            <div class= "forget-link">
                <a href="{{route('forgot.password')}}">Forgot Password?</a>
            </div>
            <button type="submit" class="btn" style="background-color: #683695; color: white;" >Login</button>
            <p> or login with social platforms</p>
            <div class="social-icons">
                <a href="{{ url('auth/google') }}"><i class='bx bxl-google'></i></a>
                <a href="{{ url('auth/facebook') }}"><i class='bx bxl-facebook-circle'></i></a>
                <a href=""><i class='bx bxl-twitter' ></i></a>
                <a href="{{ url('auth/linkedin') }}"><i class='bx bxl-linkedin-square' ></i></a>
            </div>
        </form>
      </div>

      <div class="form-box register">
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <h1>Registration</h1>
            <div class="input-box">
                <input type="text" placeholder="First Name" name="fname" required value="{{ old('fname') }}">
                <i class='bx bxs-user-detail'></i>
            </div>
            <div class="input-box">
               <input type="text" placeholder="Last Name" name="lname" required value="{{ old('lname') }}">
                <i class='bx bxs-user-detail'></i>
            </div>
            <div class="input-box">
                <input type="email" placeholder="Email" name="email" required value="{{ old('email') }}">
                <i class='bx bx-envelope' ></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Password" name="pass" required>
                <i class='bx bx-lock'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Confirm Password" name="cpass"  required>
                <i class='bx bx-lock'></i>
            </div>
            <button type="submit" class="btn" style="background-color: #3b68b2; color: white;" >Register</button>
            <p> or register with social platforms</p>
            <div class="social-icons">
                <a href="{{ url('auth/google') }}"><i class='bx bxl-google'></i></a>
                <a href="{{ url('auth/facebook') }}"><i class='bx bxl-facebook-circle'></i></a>
                <a href=""><i class='bx bxl-twitter' ></i></a>
                <a href="{{ url('auth/linkedin') }}"><i class='bx bxl-linkedin-square' ></i></a>
            </div>
        </form>
      </div>

      <div class="toggle-box">
         <div class ="toggle-panel toggle-left">
            <img src="{{ asset('imgs/docme_logo.png') }}" alt="DocME Logo" class="logo img-fluid" width="300" height="100">
            <br>
            <h2> Hello, Welcome to DocME<h2>
            <p> Don't have  an account ?</p>
            <button class="btn register-btn " style="background-color: #3b68b2; color: white;" > Register</button>
         </div>

         <div class ="toggle-panel toggle-right">

             <img src="{{ asset('imgs/docme_logo.png') }}" alt="DocME Logo" class="logo img-fluid" width="300" height="100">
            <br>
            <h2>Welcome Back <h2>
            <p> Already have an account ?</p>
            <button class="btn login-btn" style="background-color: #683695; color: white;"> Login</button>
         </div>
      </div>
  </div>
  <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>
