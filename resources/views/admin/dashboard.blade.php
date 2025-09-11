
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Docme')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin/side_menu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin/file.css') }}">

     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <!-- Favicons -->
  <link href="{{ asset('imgs/icon.png') }}" rel="icon">
  <link href="{{ asset('imgs/docme_logo.png') }}" rel="apple-touch-icon">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  @livewireStyles
</head>
<body>
    <div class="container">
        @include('admin.components.sidebar')
        @yield('content')
    </div>
    <script src="{{ asset('js/superadmin/sidemenu.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="{{ asset('js/admin/index.js') }}"></script> <!-- Custom JS --> --}}
    <script src="{{ asset('js/superadmin/sidemenu.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    @stack('scripts')
    @livewireScripts
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   <!-- Add this at the bottom of the modal or in a global JS section -->
<script>
    $(document).ready(function () {
      $('#parentFolderSelect').select2({
        dropdownParent: $('#addFolderModal'),
        width: '100%',
        allowClear: true
      });
    });
  </script>

</body>
</html>
