@extends('superadmin.dashboard')

@section('content')
<div class="main">
    <div class="container">
        <h1 class="text-center mb-4">File Manager</h1>

        <div class="row justify-content-center">
            @php
                $files = [
                    ['route' => 'superadmin.file.loan', 'img' => 'bank_loan.png', 'modal' => false],
                    ['img' => 'house_loan.jpg', 'modal' => '#modal2'],
                    ['img' => 'assets.png', 'modal' => '#modal3'],
                    ['img' => 'shares.png', 'modal' => '#modal4'],
                    ['img' => 'company.png', 'modal' => '#modal5'],
                    ['img' => 'personal_info.png', 'modal' => '#modal6'],
                    ['img' => 'car_insurance.png', 'modal' => '#modal7'],
                    ['img' => 'file.png', 'modal' => '#modal8'],
                    ['img' => 'renew.png', 'modal' => '#modal9'],
                ];
            @endphp

            @foreach ($files as $file)
                <div class="col-md-4 col-sm-6 col-12 mb-4 d-flex justify-content-center">
                    @if(isset($file['route']))
                        <a href="{{ route($file['route']) }}">
                            <img src="{{ asset('imgs/' . $file['img']) }}" class="img-fluid file-icon">
                        </a>
                    @else
                        <img src="{{ asset('imgs/' . $file['img']) }}" class="img-fluid file-icon" data-toggle="modal" data-target="{{ $file['modal'] }}">
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .file-icon {
        width: 150px; /* Set a fixed width */
        height: 150px; /* Set a fixed height */
        object-fit: cover; /* Ensure images maintain aspect ratio */
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease-in-out;
    }
    .file-icon:hover {
        transform: scale(1.1);
    }
</style>
@endsection
