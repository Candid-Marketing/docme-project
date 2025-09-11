@extends('superadmin.dashboard')

@section('content')

<style>
    .main {
        display: flex !important;
        justify-content: center !important;
        padding-top: 15px !important;
    }
    .email-settings-wrapper {
        padding: 20px;
    }

    .email-settings-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 12px rgb(9 9 9 / 19%);
        /* max-width: 850px;
        width: 100%; */
        margin: 0;
    }

    .email-settings-header {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 12px;
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 5px;
        color: #333;
    }

    .input-group-text {
        background-color: #3b68b2;
        color: white;
        font-size: 12px;
        border: none;
        width: 40px;
        justify-content: center;
    }

    .form-control {
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }

    .btn-primary {
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 4px;
        background-color: #3b68b2;
        border: none;
    }

    .alert {
        font-size: 13px;
    }

    #userProfilePreview {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 10px;
        border: 2px solid #3b68b2;
    }

    .profile-preview-wrapper {
        text-align: center;
        margin-bottom: 20px;
    }

    @media (max-width: 576px) {
        .main {
                position: static !important;
                width: 100% !important;
                height: auto !important;
                overflow-y: auto !important;
                padding-bottom: 80px; /* so bottom content isn’t cut off */
                margin-top: 45px;
            }

            body, html {
                overflow-x: hidden;
            }


    }
</style>

<div class="main">
    <div class="container-fluid">
        <div class="email-settings-wrapper">
            <div class="email-settings-card">

                <h1 class="email-settings-header">Information Details</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li style="font-size: 0.85rem;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('superadmin.information-update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" value="{{ $user->id ?? '' }}">

                    <div class="profile-preview-wrapper">
                        <img id="userProfilePreview"
                            src="{{ isset($user->user_profile) ? asset('uploads/' . $user->user_profile) : asset('imgs/profile.png') }}"
                            alt="Profile">
                    </div>

                    <div class="row mb-3">
                        <label class="form-label">Profile Image</label>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-upload"></i></span>
                                <input type="file" class="form-control @error('user_profile') is-invalid @enderror"
                                       name="user_profile" accept="image/*" onchange="previewImage(event)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @foreach([
                            ['first_name', 'First Name', 'fas fa-user'],
                            ['last_name', 'Last Name', 'fas fa-user'],
                            ['address1', 'Address Line 1', 'fas fa-map-marker-alt'],
                            ['address2', 'Address Line 2', 'fas fa-map-marker'],
                            ['city', 'City', 'fas fa-city'],
                            ['postal_code', 'Postal Code', 'fas fa-envelope']
                        ] as [$field, $placeholder, $icon])
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ $placeholder }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="{{ $icon }}"></i></span>
                                <input type="text" class="form-control"
                                       name="{{ $field }}" value="{{ $user->$field ?? '' }}"
                                       placeholder="{{ $placeholder }}">
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile Details</button>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var output = document.getElementById('userProfilePreview');
        if (event.target.files && event.target.files[0]) {
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src);
            };
        }
    }
</script>
@endsection
