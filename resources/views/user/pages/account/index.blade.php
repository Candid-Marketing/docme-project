@extends('user.dashboard')

@section('content')
<style>
    .main-scrollable {
            height: calc(100vh - 60px); /* adjust based on your header/nav height */
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 30px;
        }

    .card {
        transition: transform 0.2s ease-in-out;
        border-radius: 12px;
    }
    .card-title {
        font-size: 1rem;
        font-weight: 600;
        margin-top: 10px;
    }

    h4 {
        font-size: 22px;
        font-weight: 600;
    }

    .card p {
        margin-bottom: 6px;
        font-size: 14px;
    }

    .profile-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #ccc;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
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

    .btn-sm {
        font-size: 12px;
        padding: 4px 8px;
       background-color: #683695; color: white;
    }


</style>


<div class="main main-scrollable">
    <div class="container-fluid">
        <div class="card shadow p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Linked Accounts</h4>
            </div>

            @if ($users->count())
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($users as $user)
                        <div class="col">
                            <div class="card border-0 shadow-sm h-100">
                                 <form action="{{ route('user.switch.account', $user->id) }}" method="POST"
                                    style="position: absolute; top: 0px; right: 15px; z-index: 10;">
                                     @csrf
                                    <button class="btn btn-sm btn-outline-primary" type="submit">Switch Profile</button>
                                </form>
                                <div class="card-body position-relative">


                                    <div class="card-header mb-3">
                                        <img src="{{ $user->user_profile ? asset('uploads/' . $user->user_profile) : asset('imgs/profile.png') }}"
                                            alt="Profile"
                                            class="profile-img">
                                        <div>
                                            <h5 class="card-title">{{ $user->first_name }} {{ $user->last_name }}</h5>
                                            <p class="text-muted mb-1" style="font-size: 13px;">
                                                {{ $user->email }} {!! $user->email_verified_at ? '✅' : '❌' !!}
                                            </p>
                                        </div>
                                    </div>

                                    <p><strong>Role:</strong>
                                        @if($user->user_status == 1)
                                            Admin
                                        @elseif($user->user_status == 2)
                                            User
                                        @elseif($user->user_status == 3)
                                            Guest
                                        @else
                                            Unknown
                                        @endif
                                    </p>
                                    <p><strong>User ID:</strong> {{ $user->id }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4 d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            @else
                <p class="text-center mt-5">No users found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
