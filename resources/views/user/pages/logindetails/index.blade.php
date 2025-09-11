@extends('user.dashboard')

@section('content')

<style>
    .email-settings-wrapper {
        padding: 30px;
    }

    .email-settings-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 12px rgb(9 9 9 / 19%);
        margin: auto;
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
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
        color: #333;
    }

    .input-group-text {
        background-color: #3b68b2;
        color: white;
        font-size: 12px;
        border: none;
        width: 40px;
        justify-content: center;
        padding: 6px 0;
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

    @media (max-width: 767px) {
        .form-control, .input-group-text {
            font-size: 13px;
        }
    }
</style>

<div class="main">
    <div class="email-settings-wrapper">
        <div class="email-settings-card left-aligned">

            <h1 class="email-settings-header">Login Details</h1>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('user.login-update') }}">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $user->id ?? '' }}">

                <div class="row">
                    @foreach([
                        ['email', 'Email', 'fas fa-envelope', 'text'],
                        ['old_password', 'Old Password', 'fas fa-lock', 'password'],
                        ['password', 'New Password', 'fas fa-user-secret', 'password'],
                        ['password_confirmation', 'Confirm Password', 'fas fa-key', 'password']
                    ] as [$name, $label, $icon, $type])
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ $label }}</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="{{ $icon }}"></i>
                                </span>
                                <input type="{{ $type }}" name="{{ $name }}" class="form-control"
                                       placeholder="{{ $label }}"
                                       value="{{ $name == 'email' ? ($user->email ?? '') : '' }}">
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update Login Details</button>
            </form>

        </div>
    </div>
</div>


@endsection
