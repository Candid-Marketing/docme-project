@extends('superadmin.dashboard')

@section('content')
<style>
     .email-settings-wrapper {
        padding: 30px;
    }
    .stripe-config-container {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        margin-top: 30px;
        border: 1px solid #ced4da;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        margin: auto;
    }

    .stripe-config-container h1 {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 12px;
        margin-bottom: 25px;
    }

    .stripe-config-container h6 {
        font-size: 16px;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .input-group-text {
        background-color: #f8f9fa;
        color: #333;
        border: none;
        width: 45px;
        justify-content: center;
    }

    .form-control {
        font-size: 14px;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .btn-primary {
        font-size: 14px;
        padding: 10px 16px;
        border-radius: 4px;
        background-color: #3b68b2;
        border: none;
        color: white;
        width: 100%;
    }

    .btn-secondary {
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 4px;
        background-color: #7f8c8d;
        border: none;
    }


    @media (max-width: 576px) {
            .main {
                position: static !important;
                width: 100% !important;
                height: auto !important;
                overflow-y: auto !important;
                padding-bottom: 80px; /* so bottom content isn’t cut off */
            }

            body, html {
                overflow-x: hidden;
            }
        }

</style>

<div class="main">
    <div class="container-fluid">
        <div class="email-settings-wrapper">
            <div class="stripe-config-container w-100">
                <h1>Finance Management</h1>
                <h6>Stripe Payment Configuration</h6>

                <form method="POST" action="{{ route('superadmin.stripe-update') }}">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id" value="{{ $stripe->id ?? '' }}">

                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                            <input type="text" class="form-control" name="name" value="{{ $stripe->name ?? '' }}" placeholder="Enter company name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stripe API Key</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="text" class="form-control" name="stripe_key" value="{{ $stripe->stripe_key ?? '' }}" placeholder="Enter Stripe API Key" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stripe Secret Key</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" name="stripe_secret" value="{{ $stripe->stripe_secret ?? '' }}" placeholder="Enter Stripe Secret Key" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Configuration</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
