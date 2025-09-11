@extends('superadmin.dashboard')

@section('content')

<style>
    .main {
        display: flex;
        justify-content: center;
        /* padding: 40px 15px; */
        min-height: 100vh;
    }

    .dashboard-wrapper {
        padding: 30px
    }

    .dashboard-card {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 12px rgb(9 9 9 / 19%);
        /* max-width: 1000px; */
        margin: auto;
        margin-top: 4px;
        margin-left:20px;
        margin-right: 2px;
    }

    .dashboard-title {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 12px;
        margin-bottom: 25px;
    }

    .dashboard-buttons .btn {
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .dashboard-buttons .btn:hover {
        transform: scale(1.05);
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
    }

    .dashboard-buttons i {
        font-size: 20px;
        margin-right: 8px;
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
        <div class="dashboard-wrapper">
            <div class="dashboard-card">

                <h1 class="dashboard-title">Business System Dashboard</h1>

                {{-- SweetAlert for success messages --}}
                <script>
                    @if(session('success'))
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: '{{ session('success') }}',
                            confirmButtonText: 'OK'
                        });
                    @endif
                </script>

                {{-- Dashboard Button Grid --}}
                <div class="dashboard-buttons">
                    <div class="row g-2 text-center">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-lg w-100" style="background-color: #3c2464; color: white;" data-bs-toggle="modal" data-bs-target="#homeSettingsModal">
                                <i class="fas fa-home"></i> Home
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-lg w-100" style="background-color: #683695; color: white;" data-bs-toggle="modal" data-bs-target="#aboutUsModal">
                                <i class="fas fa-info-circle"></i> About Us
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-lg w-100" style="background-color: #c0a6cf; color: white;" data-bs-toggle="modal" data-bs-target="#featureModal">
                                <i class="fas fa-cogs"></i> Features
                            </button>
                        </div>
                    </div>
                    <div class="row g-2 text-center mt-2">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-lg w-100" style="background-color: #3b68b2; color: white;" data-bs-toggle="modal" data-bs-target="#servicesModal">
                                <i class="fas fa-concierge-bell"></i> Services
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-lg w-100" style="background-color: #3abfdd; color: white;" data-bs-toggle="modal" data-bs-target="#pricingModal">
                                <i class="fas fa-dollar-sign"></i> Pricing
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-lg w-100" style="background-color: #ed1d7e; color: white;" data-bs-toggle="modal" data-bs-target="#contactModal">
                                <i class="fas fa-envelope"></i> Contact
                            </button>
                        </div>
                    </div>
                    <div class="row g-2 text-center mt-2">
                        <div class="col-md-4 offset-md-4">
                            <button type="button" class="btn btn-lg w-100" style="background-color: black; color: white;" data-bs-toggle="modal" data-bs-target="#footerModal">
                                <i class="fas fa-border-all"></i> Footer
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


{{-- Home modal --}}
<div class="modal fade" id="homeSettingsModal" tabindex="-1" aria-labelledby="homeSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow rounded-4 border-0">
            <div class="modal-header text-white rounded-top-4" style="background: #3b68b2;">
                <h5 class="modal-title fw-semibold" id="homeSettingsModalLabel">⚙️ Home Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('superadmin.homestore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="home">

                <div class="modal-body p-4">
                    <div class="row g-4">

                        <!-- Favicon -->
                        <div class="col-md-6">
                            <label class="form-label">Favicon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-upload text-primary"></i></span>
                                <input type="file" name="favicon_image" class="form-control image-preview" accept="image/*" data-preview="previewFavicon">
                            </div>
                            <img id="previewFavicon" src="{{ old('favicon_image', asset($homepage->where('name', 'favicon_image')->first()->image_path ?? 'imgs/image_logo.png')) }}" alt="Favicon" class="img-thumbnail mt-2" style="height: 50px;">
                        </div>

                        <!-- Navbar Logo -->
                        <div class="col-md-6">
                            <label class="form-label">Navbar Logo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-upload text-primary"></i></span>
                                <input type="file" name="nav_image" class="form-control image-preview" accept="image/*" data-preview="previewNav">
                            </div>
                            <img id="previewNav" src="{{ old('nav_image', asset($homepage->where('name', 'nav_image')->first()->image_path ?? 'imgs/image_logo.png')) }}" alt="Navbar Logo" class="img-thumbnail mt-2" style="height: 50px;">
                        </div>

                        <!-- Home Title -->
                        <div class="col-md-6">
                            <label class="form-label">Homepage Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-primary"></i></span>
                                <input type="text" class="form-control" name="home_title" placeholder="Enter homepage title" value="{{ old('home_title', $homepage->where('name', 'home_title')->first()->content ?? '') }}">
                            </div>
                        </div>

                        <!-- Home Description -->
                        <div class="col-md-6">
                            <label class="form-label">Homepage Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-align-left text-primary"></i></span>
                                <textarea class="form-control" name="home_description" rows="1" placeholder="Enter homepage description">{{ old('home_subtitle', $homepage->where('name', 'home_subtitle')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Homepage Hero Image -->
                        <div class="col-md-12">
                            <label class="form-label">Homepage Image</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-image text-primary"></i></span>
                                <input type="file" name="home_image" class="form-control image-preview" accept="image/*" data-preview="previewHome">
                            </div>
                            <img id="previewHome" src="{{ old('home_image', asset($homepage->where('name', 'home_image')->first()->image_path ?? 'imgs/image_logo.png')) }}" alt="Homepage Image" class="img-thumbnail mt-2" style="max-height: 180px;">
                        </div>

                        <hr class="mt-4">

                        <!-- Icon Grid -->
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="col-md-3">
                            <label class="form-label">Icon {{ $i }} Image</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-icons text-primary"></i></span>
                                <input type="file" name="icon_image{{ $i }}" class="form-control image-preview" accept="image/*" data-preview="previewIcon{{ $i }}">
                            </div>
                            <img id="previewIcon{{ $i }}" src="{{ old('icon_image'.$i, asset($homepage->where('name', 'icon_image'.$i)->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-4" style="height: 50px; width: 50px; object-fit: cover;" alt="Icon {{ $i }} Preview">
                            </br>
                            <label class="form-label mt-3">Icon {{ $i }} Title</label>
                            <input type="text" name="icon_title{{ $i }}" class="form-control" placeholder="Enter title" value="{{ old('icon_title'.$i, $homepage->where('name', 'icon_title'.$i)->first()->content ?? '') }}">

                            <label class="form-label mt-3">Icon {{ $i }} Description</label>
                            <textarea class="form-control" name="icon_desc{{ $i }}" rows="2" placeholder="Enter short description">{{ old('icon_desc'.$i, $homepage->where('name', 'icon_desc'.$i)->first()->content ?? '') }}</textarea>
                        </div>
                        @endfor

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn rounded-pill" data-bs-dismiss="modal" style="background-color: #ed1d7e; color: white;">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background-color: #3b68b2; color: white;">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- About us Modal -->
<div class="modal fade" id="aboutUsModal" tabindex="-1" aria-labelledby="aboutUsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow rounded-4 border-0">
            <div class="modal-header text-white rounded-top-4" style="background: #3b68b2;">
                <h5 class="modal-title fw-semibold" id="aboutUsModalLabel">ℹ️ About Us Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('superadmin.homestore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="about_us">

                <div class="modal-body p-4">
                    <div class="row g-4">

                        <!-- About Us Title -->
                        <div class="col-md-6">
                            <label class="form-label">Section Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-primary"></i></span>
                                <textarea class="form-control" name="about_us_title" rows="2" placeholder="Enter title">{{ old('about_us_title', $homepage->where('name', 'about_us_title')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- About Us Description -->
                        <div class="col-md-6">
                            <label class="form-label">Section Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-align-left text-primary"></i></span>
                                <textarea class="form-control" name="about_us_description" rows="2" placeholder="Enter description">{{ old('about_us_description', $homepage->where('name', 'about_us_description')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Main About Image -->
                        <div class="col-md-12">
                            <label class="form-label">Main About Us Image</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-image text-primary"></i></span>
                                <input type="file" name="about_us_image" class="form-control image-preview" accept="image/*" data-preview="previewImageMain">
                            </div>
                            <img id="previewImageMain" src="{{ old('about_us_image', asset($homepage->where('name', 'about_us_image')->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-2" style="max-height: 180px;" alt="About Us Main Image">
                        </div>

                        <!-- List Items -->
                        @for ($i = 1; $i <= 6; $i++)
                        <div class="col-md-6">
                            <label class="form-label">Highlight Point {{ $i }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-check-circle text-primary"></i></span>
                                <textarea class="form-control" name="about_us_list{{ $i }}" rows="2" placeholder="Enter key point">{{ old("about_us_list$i", $homepage->where('name', "about_us_list$i")->first()->content ?? '') }}</textarea>
                            </div>
                        </div>
                        @endfor

                        <!-- Additional Images -->
                        <div class="col-md-6">
                            <label class="form-label">Supporting Image 1</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-image text-primary"></i></span>
                                <input type="file" name="about_us_image1" class="form-control image-preview" accept="image/*" data-preview="previewImageAboutUs">
                            </div>
                            <img id="previewImageAboutUs" src="{{ old('about_us_image1', asset($homepage->where('name', 'about_us_image1')->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-2" style="max-height: 150px;" alt="Supporting Image 1">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Supporting Image 2</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-image text-primary"></i></span>
                                <input type="file" name="about_us_image2" class="form-control image-preview" accept="image/*" data-preview="previewImageAboutUs2">
                            </div>
                            <img id="previewImageAboutUs2" src="{{ old('about_us_image2', asset($homepage->where('name', 'about_us_image2')->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-2" style="max-height: 150px;" alt="Supporting Image 2">
                        </div>

                        <!-- Founder Info -->
                        <div class="col-md-6">
                            <label class="form-label">Founder Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user text-primary"></i></span>
                                <input type="text" class="form-control" name="founder_name" placeholder="Enter founder name" value="{{ old('founder_name', $homepage->where('name', 'founder_name')->first()->content ?? '') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Founder Role</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-briefcase text-primary"></i></span>
                                <input type="text" class="form-control" name="founder_role" placeholder="Enter founder role" value="{{ old('founder_role', $homepage->where('name', 'founder_role')->first()->content ?? '') }}">
                            </div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone-alt text-primary"></i></span>
                                <input type="text" class="form-control" name="mobile_num" placeholder="Enter mobile number" value="{{ old('mobile_num', $homepage->where('name', 'mobile_num')->first()->content ?? '') }}">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn rounded-pill" data-bs-dismiss="modal" style="background-color: #ed1d7e; color: white;">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background-color: #3b68b2; color: white;">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Feature Modal -->
<div class="modal fade" id="featureModal" tabindex="-1" aria-labelledby="featureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow rounded-4 border-0">
            <div class="modal-header text-white rounded-top-4" style="background: #3b68b2;">
                <h5 class="modal-title fw-semibold" id="featureModalLabel">🧩 Feature Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('superadmin.homestore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="feature">

                <div class="modal-body p-4">
                    <div class="row g-4">

                        <!-- Feature Overview -->
                        <div class="col-md-6">
                            <label class="form-label">Feature Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-primary"></i></span>
                                <textarea class="form-control" name="feature_title" rows="2" placeholder="Enter title">{{ old('feature_title', $homepage->where('name', 'feature_title')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Feature Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-align-left text-primary"></i></span>
                                <textarea class="form-control" name="feature_description" rows="2" placeholder="Enter description">{{ old('feature_description', $homepage->where('name', 'feature_description')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <hr class="mt-4">

                        <!-- Navigation Titles -->
                        @for ($i = 1; $i <= 3; $i++)
                        <div class="col-md-4">
                            <label class="form-label">Navigation {{ $i }} Title</label>
                            <textarea class="form-control" name="feat_nav{{ $i }}" rows="2" placeholder="Title">{{ old('feat_nav'.$i, $homepage->where('name', 'feat_nav'.$i)->first()->content ?? '') }}</textarea>
                        </div>
                        @endfor

                        <!-- Navigation Images & Content -->
                        @for ($i = 1; $i <= 3; $i++)
                        <div class="col-md-4 text-center">
                            <label class="form-label">Navigation Image {{ $i }}</label>
                            <input type="file" class="form-control image-preview" name="nav_image{{ $i }}" accept="image/*" data-preview="previewImageNavigation{{ $i }}">
                            <img id="previewImageNavigation{{ $i }}" src="{{ old('nav_image'.$i, asset($homepage->where('name', 'nav_image'.$i)->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-2" style="max-height: 150px;" alt="Image {{ $i }}">
                        </div>
                        @endfor

                        @for ($i = 1; $i <= 3; $i++)
                        <div class="col-md-4">
                            <label class="form-label">Navigation Title Below Image {{ $i }}</label>
                            <textarea class="form-control" name="nav_title{{ $i }}" rows="2" placeholder="Title">{{ old('nav_title'.$i, $homepage->where('name', 'nav_title'.$i)->first()->content ?? '') }}</textarea>
                        </div>
                        @endfor

                        @for ($i = 1; $i <= 3; $i++)
                        <div class="col-md-4">
                            <label class="form-label">Navigation Description {{ $i }}</label>
                            <textarea class="form-control" name="nav_desc{{ $i }}" rows="2" placeholder="Description">{{ old('nav_desc'.$i, $homepage->where('name', 'nav_desc'.$i)->first()->content ?? '') }}</textarea>
                        </div>
                        @endfor

                        <!-- Navigation Check Lists -->
                        @for ($i = 1; $i <= 9; $i++)
                        <div class="col-md-4">
                            <label class="form-label">Navigation Checklist {{ $i }}</label>
                            <textarea class="form-control" name="nav_check{{ $i }}" rows="2" placeholder="Navigation Links">{{ old('nav_check'.$i, $homepage->where('name', 'nav_check'.$i)->first()->content ?? '') }}</textarea>
                        </div>
                        @endfor

                        <hr class="mt-4">

                        <!-- Feature Cards -->
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="col-md-3">
                            <label class="form-label">Card Image {{ $i }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-icons text-primary"></i></span>
                                <input type="file" name="card_image{{ $i }}" class="form-control image-preview" accept="image/*" data-preview="previewCard{{ $i }}">
                            </div>
                            <img id="previewCard{{ $i }}" src="{{ old('card_image'.$i, asset($homepage->where('name', 'card_image'.$i)->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-2" style="height: 50px; width: 50px; object-fit: cover;" alt="Card Image {{ $i }}">
                            </br>
                            <label class="form-label mt-3">Card Title</label>
                            <input type="text" name="card_title{{ $i }}" class="form-control" placeholder="Card Title" value="{{ old('card_title'.$i, $homepage->where('name', 'card_title'.$i)->first()->content ?? '') }}">

                            <label class="form-label mt-3">Card Description</label>
                            <textarea class="form-control" name="card_desc{{ $i }}" rows="2" placeholder="Short description">{{ old('card_desc'.$i, $homepage->where('name', 'card_desc'.$i)->first()->content ?? '') }}</textarea>
                        </div>
                        @endfor

                        <hr class="mt-4">

                        <!-- Footer Content -->
                        @for ($i = 1; $i <= 6; $i++)
                        <div class="col-md-6">
                            <label class="form-label">Footer Image {{ $i }}</label>
                            <input type="file" name="footer_image{{ $i }}" class="form-control image-preview" accept="image/*" data-preview="previewFooter{{ $i }}">
                            <img id="previewFooter{{ $i }}" src="{{ old('footer_image'.$i, asset($homepage->where('name', 'footer_image'.$i)->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-2" style="height: 50px; width: 50px; object-fit: cover;" alt="Footer Icon {{ $i }}">
                            </br>
                            <label class="form-label mt-2">Footer Title</label>
                            <input type="text" name="footer_title{{ $i }}" class="form-control" placeholder="Title" value="{{ old('footer_title'.$i, $homepage->where('name', 'footer_title'.$i)->first()->content ?? '') }}">

                            <label class="form-label mt-2">Footer Description</label>
                            <textarea class="form-control" name="footer_desc{{ $i }}" rows="2" placeholder="Description">{{ old('footer_desc'.$i, $homepage->where('name', 'footer_desc'.$i)->first()->content ?? '') }}</textarea>
                        </div>
                        @endfor

                        <!-- Mockup Image -->
                        <div class="col-md-12">
                            <label class="form-label">Mockup Image</label>
                            <input type="file" name="mock_image" class="form-control image-preview" accept="image/*" data-preview="previewMock">
                            <img id="previewMock" src="{{ old('mock_image', asset($homepage->where('name', 'mock_image')->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-2" style="max-height: 180px;" alt="Mockup Image">
                        </div>

                        <!-- Call to Action -->
                        <div class="col-md-6">
                            <label class="form-label">Call to Action Title</label>
                            <textarea class="form-control" name="call_title" rows="2" placeholder="Title">{{ old('call_title', $homepage->where('name', 'call_title')->first()->content ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Call to Action Description</label>
                            <textarea class="form-control" name="call_description" rows="2" placeholder="Description">{{ old('call_description', $homepage->where('name', 'call_description')->first()->content ?? '') }}</textarea>
                        </div>
                        <hr class="mt-4">
                        @php
                            $showClients = old('show_clients', $homepage->where('name', 'show_clients')->first()->content ?? 'yes');
                        @endphp

                        <div class="col-md-12">
                            <label class="form-label d-block mb-2">Show Clients?</label>

                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="show_clients" id="showYes" value="yes" {{ $showClients === 'yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="showYes">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="show_clients" id="showNo" value="no" {{ $showClients === 'no' ? 'checked' : '' }}>
                            <label class="form-check-label" for="showNo">No</label>
                            </div>
                        </div>

                          <hr class="mt-4">
                        <!-- Client Logos -->
                        @for ($i = 1; $i <= 6; $i++)
                        <div class="col-md-6">
                            <label class="form-label">Client Logo {{ $i }}</label>
                            <input type="file" name="client_image{{ $i }}" class="form-control image-preview" accept="image/*" data-preview="previewClient{{ $i }}">
                            <img id="previewClient{{ $i }}" src="{{ old('client_image'.$i, asset($homepage->where('name', 'client_image'.$i)->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-2" style="height: 50px; width: 50px; object-fit: cover;" alt="Client Logo {{ $i }}">
                        </div>
                        @endfor
                        <hr class="mt-4">

                        @php
                            $showClients = old('show_testimonials', $homepage->where('name', 'show_testimonials')->first()->content ?? 'yes');
                        @endphp

                        <div class="col-md-12">
                            <label class="form-label d-block mb-2">Show Testimonials?</label>

                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="show_testimonials" id="showYes" value="yes" {{ $showClients === 'yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="showYes">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="show_testimonials" id="showNo" value="no" {{ $showClients === 'no' ? 'checked' : '' }}>
                            <label class="form-check-label" for="showNo">No</label>
                            </div>
                        </div>

                          <hr class="mt-4">
                        <!-- Testimonials -->
                        <div class="col-md-6">
                            <label class="form-label">Testimonial Header</label>
                            <textarea class="form-control" name="test_head" rows="2" placeholder="Header">{{ old('test_head', $homepage->where('name', 'test_head')->first()->content ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Testimonial Description</label>
                            <textarea class="form-control" name="test_description" rows="2" placeholder="Description">{{ old('test_description', $homepage->where('name', 'test_description')->first()->content ?? '') }}</textarea>
                        </div>


                        @for ($i = 1; $i <= 4; $i++)
                        <div class="col-md-6">
                            <label class="form-label">Testimonial Image {{ $i }}</label>
                            <input type="file" name="test_image{{ $i }}" class="form-control image-preview" accept="image/*" data-preview="previewTest{{ $i }}">
                            <img id="previewTest{{ $i }}" src="{{ old('test_image'.$i, asset($homepage->where('name', 'test_image'.$i)->first()->image_path ?? 'imgs/image_logo.png')) }}" class="img-thumbnail mt-2" style="height: 50px; width: 50px; object-fit: cover;" alt="Testimonial {{ $i }}">
                            </br>
                            <label class="form-label mt-2">Name</label>
                            <input type="text" name="test_name{{ $i }}" class="form-control" placeholder="Name" value="{{ old('test_name'.$i, $homepage->where('name', 'test_name'.$i)->first()->content ?? '') }}">

                            <label class="form-label mt-2">Role</label>
                            <input type="text" name="test_role{{ $i }}" class="form-control" placeholder="Role" value="{{ old('test_role'.$i, $homepage->where('name', 'test_role'.$i)->first()->content ?? '') }}">

                            <label class="form-label mt-2">Statement</label>
                            <textarea class="form-control" name="test_state{{ $i }}" rows="2" placeholder="Testimonial">{{ old('test_state'.$i, $homepage->where('name', 'test_state'.$i)->first()->content ?? '') }}</textarea>
                        </div>
                        @endfor

                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn rounded-pill" data-bs-dismiss="modal" style="background-color: #ed1d7e; color: white;">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background-color: #3b68b2; color: white;">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Services Modal --}}
<div class="modal fade" id="servicesModal" tabindex="-1" aria-labelledby="servicesSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow rounded-4 border-0">
            <div class="modal-header text-white rounded-top-4" style="background:#3b68b2;">
                <h5 class="modal-title fw-semibold" id="servicesSettingsModalLabel">🛠️ Services Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('superadmin.homestore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="service">

                <div class="modal-body p-4">
                    <div class="row g-4">

                        <!-- Services Section Title -->
                        <div class="col-md-6">
                            <label class="form-label">Section Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-heading text-primary"></i>
                                </span>
                                <textarea class="form-control" name="service_title" rows="2" placeholder="Enter services section title">{{ old('service_title', $homepage->where('name', 'service_title')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Services Section Description -->
                        <div class="col-md-6">
                            <label class="form-label">Section Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-align-left text-primary"></i>
                                </span>
                                <textarea class="form-control" name="service_description" rows="2" placeholder="Enter section description">{{ old('service_description', $homepage->where('name', 'service_description')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Individual Services -->
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="col-md-6">
                            <label class="form-label">Service {{ $i }} Title</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-check-circle text-success"></i>
                                </span>
                                <textarea class="form-control" name="services_title{{ $i }}" rows="1" placeholder="Enter service title">{{ old('services_title'.$i, $homepage->where('name', 'services_title'.$i)->first()->content ?? '') }}</textarea>
                            </div>

                            <label class="form-label mt-2">Service {{ $i }} Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-align-left text-secondary"></i>
                                </span>
                                <textarea class="form-control" name="services_desc{{ $i }}" rows="2" placeholder="Enter service description">{{ old('services_desc'.$i, $homepage->where('name', 'services_desc'.$i)->first()->content ?? '') }}</textarea>
                            </div>
                        </div>
                        @endfor

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn rounded-pill" data-bs-dismiss="modal" style="background-color: #ed1d7e; color: white;">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background-color: #3b68b2; color: white;">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Pricing --}}
<div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="pricingSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow rounded-4 border-0">
            <div class="modal-header text-white rounded-top-4" style="background:#3b68b2;">
                <h5 class="modal-title fw-semibold" id="pricingSettingsModalLabel">💰 Pricing Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('superadmin.homestore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="price">

                <div class="modal-body p-4">
                    <div class="row g-4">

                        <!-- Section Title & Description -->
                        <div class="col-md-6">
                            <label class="form-label">Pricing Section Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-success"></i></span>
                                <textarea class="form-control" name="price_title" rows="2" placeholder="Enter pricing section title">{{ old('price_title', $homepage->where('name', 'price_title')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Pricing Section Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-align-left text-success"></i></span>
                                <textarea class="form-control" name="price_description" rows="2" placeholder="Enter pricing section description">{{ old('price_description', $homepage->where('name', 'price_description')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Pricing Plans -->
                        <div class="col-md-4">
                            <label class="form-label">💵 Daily Price</label>
                            <textarea class="form-control" name="price_day" rows="1" placeholder="e.g. $49/day">{{ old('price_day', $homepage->where('name', 'price_day')->first()->content ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">📆 Monthly Price</label>
                            <textarea class="form-control" name="price_month" rows="1" placeholder="e.g. $199/month">{{ old('price_month', $homepage->where('name', 'price_month')->first()->content ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">📅 Yearly Price</label>
                            <textarea class="form-control" name="price_year" rows="1" placeholder="e.g. $999/year">{{ old('price_year', $homepage->where('name', 'price_year')->first()->content ?? '') }}</textarea>
                        </div>

                        <!-- Pricing Descriptions -->
                        <div class="col-md-4">
                            <label class="form-label">📝 Daily Plan Description</label>
                            <textarea class="form-control" name="price_desc1" rows="2" placeholder="Short description">{{ old('price_desc1', $homepage->where('name', 'price_desc1')->first()->content ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">📝 Monthly Plan Description</label>
                            <textarea class="form-control" name="price_desc2" rows="2" placeholder="Short description">{{ old('price_desc2', $homepage->where('name', 'price_desc2')->first()->content ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">📝 Yearly Plan Description</label>
                            <textarea class="form-control" name="price_desc3" rows="2" placeholder="Short description">{{ old('price_desc3', $homepage->where('name', 'price_desc3')->first()->content ?? '') }}</textarea>
                        </div>

                        <!-- Included Features -->
                        @for ($i = 1; $i <= 12; $i++)
                        <div class="col-md-4">
                            <label class="form-label mt-2">✅ Feature Included {{ $i }}</label>
                            <textarea class="form-control" name="include_state{{ $i }}" rows="2" placeholder="Feature name or description">{{ old('include_state'.$i, $homepage->where('name', 'include_state'.$i)->first()->content ?? '') }}</textarea>
                        </div>
                        @endfor

                        <!-- FAQ Section Title/Description -->
                        <div class="col-md-6">
                            <label class="form-label">FAQ Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-success"></i></span>
                                <textarea class="form-control" name="quest_title" rows="2" placeholder="Enter FAQ title">{{ old('quest_title', $homepage->where('name', 'quest_title')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">FAQ Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-align-left text-success"></i></span>
                                <textarea class="form-control" name="quest_description" rows="2" placeholder="Enter FAQ description">{{ old('quest_description', $homepage->where('name', 'quest_description')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- FAQ Questions & Answers -->
                        @for ($i = 1; $i <= 6; $i++)
                        <div class="col-md-6">
                            <label class="form-label mt-2">❓ Question {{ $i }}</label>
                            <textarea class="form-control" name="quest_number{{ $i }}" rows="2" placeholder="Enter question text">{{ old('quest_number'.$i, $homepage->where('name', 'quest_number'.$i)->first()->content ?? '') }}</textarea>

                            <label class="form-label mt-2">💬 Answer {{ $i }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-align-left text-success"></i></span>
                                <textarea class="form-control" name="answer_number{{ $i }}" rows="2" placeholder="Enter answer text">{{ old('answer_number'.$i, $homepage->where('name', 'answer_number'.$i)->first()->content ?? '') }}</textarea>
                            </div>
                        </div>
                        @endfor

                        <!-- Call to Action -->
                        <div class="col-md-6">
                            <label class="form-label">Call to Action Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-success"></i></span>
                                <textarea class="form-control" name="act_title" rows="2" placeholder="Enter CTA title">{{ old('act_title', $homepage->where('name', 'act_title')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Call to Action Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-align-left text-success"></i></span>
                                <textarea class="form-control" name="act_description" rows="2" placeholder="Enter CTA description">{{ old('act_description', $homepage->where('name', 'act_description')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn rounded-pill" data-bs-dismiss="modal" style="background-color: #ed1d7e; color: white;">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background-color: #3b68b2; color: white;">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



{{-- Contact Form --}}
<div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow rounded-4 border-0">
            <div class="modal-header text-white rounded-top-4" style="background: #3b68b2;">
                <h5 class="modal-title fw-semibold" id="contactModalLabel">📬 Contact Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('superadmin.homestore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="contact">

                <div class="modal-body p-4">
                    <div class="row g-4">

                        <!-- Title & Description -->
                        <div class="col-md-6">
                            <label class="form-label">Contact Section Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-info"></i></span>
                                <textarea class="form-control" name="contact_title" rows="2" placeholder="e.g. Get in Touch">{{ old('contact_title', $homepage->where('name', 'contact_title')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Section Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-align-left text-info"></i></span>
                                <textarea class="form-control" name="contact_description" rows="2" placeholder="Brief intro or instructions">{{ old('contact_description', $homepage->where('name', 'contact_description')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>
                        @php
                            $showClients = old('show_clients', $homepage->where('name', 'show_phone')->first()->content ?? 'yes');
                        @endphp

                        <div class="col-md-12">
                            <label class="form-label d-block mb-2">Show Phone Number Section ?</label>

                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="show_phone" id="showYes" value="yes" {{ $showClients === 'yes' ? 'checked' : '' }}>
                            <label class="form-check-label" for="showYes">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="show_phone" id="showNo" value="no" {{ $showClients === 'no' ? 'checked' : '' }}>
                            <label class="form-check-label" for="showNo">No</label>
                            </div>
                        </div>
                        <!-- Address & Phone -->
                        <div class="col-md-4">
                            <label class="form-label">📍 Address</label>
                            <textarea class="form-control" name="location_ad" rows="2" placeholder="e.g. 123 Main St, Griffith">{{ old('location_ad', $homepage->where('name', 'location_ad')->first()->content ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">📞 Primary Phone</label>
                            <textarea class="form-control" name="phone_num1" rows="2" placeholder="e.g. +61 412 345 678">{{ old('phone_num1', $homepage->where('name', 'phone_num1')->first()->content ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">📞 Secondary Phone</label>
                            <textarea class="form-control" name="phone_num2" rows="2" placeholder="Optional">{{ old('phone_num2', $homepage->where('name', 'phone_num2')->first()->content ?? '') }}</textarea>
                        </div>

                        <!-- Email & CTA Message -->
                        <div class="col-md-6">
                            <label class="form-label">✉️ Contact Email</label>
                            <textarea class="form-control" name="contact_email" rows="2" placeholder="e.g. hello@example.com">{{ old('contact_email', $homepage->where('name', 'contact_email')->first()->content ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">💬 Get in Touch Message</label>
                            <textarea class="form-control" name="touch_desc" rows="2" placeholder="e.g. We'd love to hear from you!">{{ old('touch_desc', $homepage->where('name', 'touch_desc')->first()->content ?? '') }}</textarea>
                        </div>

                        <!-- Contact Info Section -->
                        <div class="col-md-6">
                            <label class="form-label">Contact Info Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-info"></i></span>
                                <textarea class="form-control" name="contactinfo_title" rows="2" placeholder="e.g. How to Reach Us">{{ old('contactinfo_title', $homepage->where('name', 'contactinfo_title')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Info Description</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-align-left text-info"></i></span>
                                <textarea class="form-control" name="contactinfo_description" rows="2" placeholder="More detailed info">{{ old('contactinfo_description', $homepage->where('name', 'contactinfo_description')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Get In Touch Title -->
                        <div class="col-md-6">
                            <label class="form-label">Get In Touch Section Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading text-info"></i></span>
                                <textarea class="form-control" name="get_title" rows="2" placeholder="e.g. Send Us a Message">{{ old('get_title', $homepage->where('name', 'get_title')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn rounded-pill" data-bs-dismiss="modal" style="background-color: #ed1d7e; color: white;">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background-color: #3b68b2; color: white;">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



{{-- Footer Form --}}
<div class="modal fade" id="footerModal" tabindex="-1" aria-labelledby="footerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow rounded-4 border-0">
            <div class="modal-header text-white rounded-top-4" style="background:#3b68b2;">
                <h5 class="modal-title fw-semibold" id="footerModalLabel">🔻 Footer Settings</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('superadmin.homestore') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="footer">

                <div class="modal-body p-4">
                    <div class="row g-4">

                        <!-- Footer Logo Upload -->
                        <div class="col-md-6">
                            <label class="form-label">Footer Logo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-upload text-primary"></i>
                                </span>
                                <input type="file" name="footer_title" class="form-control image-preview" accept="image/*" data-preview="previewImageFooter">
                            </div>
                            <img id="previewImageFooter"
                                src="{{ old('footer_title', asset($homepage->where('name', 'footer_title')->first()->image_path ?? 'imgs/image_logo.png')) }}"
                                alt="Footer Logo"
                                class="img-thumbnail mt-2"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            <small class="text-muted d-block mt-1">Recommended size: 50x50px</small>
                        </div>

                        <!-- Address -->
                        <div class="col-md-6">
                            <label class="form-label">📍 Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                </span>
                                <textarea class="form-control" name="footer_add" rows="3" placeholder="e.g. 123 Main Street, Griffith NSW">{{ old('footer_add', $homepage->where('name', 'footer_add')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <label class="form-label">📞 Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-phone-alt text-primary"></i>
                                </span>
                                <textarea class="form-control" name="footer_phone" rows="3" placeholder="e.g. +61 400 123 456">{{ old('footer_phone', $homepage->where('name', 'footer_phone')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label">✉️ Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <textarea class="form-control" name="footer_email" rows="3" placeholder="e.g. hello@yourcompany.com">{{ old('footer_email', $homepage->where('name', 'footer_email')->first()->content ?? '') }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn rounded-pill" data-bs-dismiss="modal" style="background-color: #ed1d7e; color: white;">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-4" style="background-color: #3b68b2; color: white;">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    // Global image preview for all .image-preview inputs
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".image-preview").forEach(input => {
        input.addEventListener("change", function () {
            const previewId = input.dataset.preview;
            const previewImg = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    });
});

</script>
@endsection
