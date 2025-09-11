@extends('user.dashboard')

@section('content')

<style>
    body, html {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
    }

    .main {
        padding: 20px;
    }

    @media (max-width: 576px) {
        h1, h5 {
            text-align: center;
        }

        .text-decoration-none {
            display: block;
            margin-bottom: 8px;
        }

        .ratio {
            margin-top: 1rem;
        }
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
    <div class="container-fluid py-3">
        <h1 class="h3 mb-4">Guest Dashboard</h1>

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-12 col-md-6 col-lg-5">
                <h5 class="text-uppercase">Welcome to DocME 👋</h5>
                <p>
                   Your digital filing cabinet, built for structure, speed, and simplicity.
                We’re here to help you stay organised, collaborate with confidence, and eliminate the chaos of scattered paperwork.
                </p>

                  <p>
                  Use the sidebar to navigate folders, upload files, or invite others securely. Need support? Click the help icon anytime.
                Let’s get your documents where they belong — safely stored and easy to find.
                </p>

                <p class="mt-4 fw-semibold">What do you want to do today?</p>
                <p><a href="{{ route('user.profile') }}" class="text-decoration-none">Edit Profile</a></p>
                <p><a href="#" class="text-decoration-none">View your Files</a></p>
                <p><a href="#" class="text-decoration-none">Help Topics</a></p>
            </div>

            <!-- Right Column -->
            <div class="col-12 col-md-6 col-lg-5 offset-lg-1">
                <h5 class="text-uppercase mt-4 mt-md-0">How It Works</h5>
                <p class="mt-2">Watch our video tutorial:</p>

                <div class="ratio ratio-16x9">
                    <iframe
                        src="https://www.youtube.com/embed/QPORKS-sbXo"
                        title="YouTube video"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
