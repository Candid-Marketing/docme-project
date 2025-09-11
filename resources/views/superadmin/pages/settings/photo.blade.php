@extends('superadmin.dashboard')

@section('content')
<div class="main">
    <div class="container d-flex justify-content-center">
        <div class="w-100" style="max-width: 600px;">
            <h1 class="mt-4 h1-title text-center">Admin Finance</h1>
            <h5 class="mt-4 text-center">Stripe Setting</h5>

            <form method="POST" action="{{ route('superadmin.profile-update') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $user->id ?? '' }}">

                <!-- User Profile Image Upload -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-image"></i> <!-- Image icon -->
                            </span>
                            <input type="file" class="form-control @error('user_profile') is-invalid @enderror" name="user_profile">
                        </div>
                        @error('user_profile')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary w-100">Update Configuration</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
