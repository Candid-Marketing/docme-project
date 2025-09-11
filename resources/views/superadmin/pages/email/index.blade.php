@extends('superadmin.dashboard')

@section('content')
<style>
    .email-settings-wrapper {
        padding: 30px;
    }
    .email-settings-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        /* max-width: 1000px; */
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
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }
    .input-group-text {
        background-color:  #3b68b2;;
        color: white;
        font-size: 13px;
        border: none;
        width: 45px;
        justify-content: center;
    }
    .form-control {
        font-size: 13px;
        padding: 8px;
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
    .btn-secondary {
        font-size: 13px;
        padding: 8px 16px;
        border-radius: 4px;
        background-color: #7f8c8d;
        border: none;
    }
    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .modal-backdrop.show {
        opacity: 0.2 !important; /* Default is 0.5 */
    }

    @media (max-width: 768px) {
        .email-settings-wrapper {
            padding: 15px;
        }

        .email-settings-card {
            padding: 20px;
        }

        .email-settings-header {
            font-size: 18px;
            text-align: center;
        }

        .section-title {
            text-align: center;
            margin-bottom: 10px;
        }

        .btn {
            width: 100%;
            margin-top: 10px;
        }

        .modal-dialog {
            margin: 10px;
        }

        .form-control {
            font-size: 14px;
        }
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
            <div class="email-settings-card">
                <h1 class="email-settings-header">Email Settings</h1>
                <div class="row align-items-center">
                    <div class="col-md-6"><div class="section-title">My Profile</div></div>
                    <div class="col-md-6 text-end">
                        <button class="btn" style="background-color: #3b68b2; color: white;"  data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                    </div>
                </div>
                <form>
                    <div class="row">
                        @foreach ([
                            ['Driver', 'text'],
                            ['Host', 'text'],
                            ['Port', 'text'],
                            ['User', 'text'],
                            ['Password', 'password'],
                            ['Sender Name', 'text'],
                            ['Sender Email', 'email']
                        ] as [$label, $type])
                        <div class="col-md-6">
                            <label class="form-label">{{ $label }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                <input type="{{ $type }}" class="form-control" placeholder="Enter {{ $label }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            @foreach ([
                                ['Driver', 'text'],
                                ['Host', 'text'],
                                ['Port', 'text'],
                                ['User', 'text'],
                                ['Password', 'password'],
                                ['Sender Name', 'text'],
                                ['Sender Email', 'email']
                            ] as [$label, $type])
                            <div class="col-md-6">
                                <label class="form-label">{{ $label }}</label>
                                <input type="{{ $type }}" class="form-control" placeholder="Enter {{ $label }}">
                            </div>
                            @endforeach
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background-color: #c0a6cf; color: white;"  data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn" style="background-color: #3b68b2; color: white;" >Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const myModal = new bootstrap.Modal(document.getElementById('editProfileModal'), {
    backdrop: false
    });
</script>
@endsection
