@extends('admin.dashboard')

@section('content')

<div class="main">
    <div class="container-fluid">
        <h1 class="dashboard-title">User Dashboard</h1>
        <div class="row">
            <div class="col-md-6">
                <h5 class="section-title">Welcome to DocME 👋</h5>
                <p class="section-description">Your digital filing cabinet, built for structure, speed, and simplicity.
                We’re here to help you stay organised, collaborate with confidence, and eliminate the chaos of scattered paperwork.</p>

                <p class="section-description">Use the sidebar to navigate folders, upload files, or invite others securely. Need support? Click the help icon anytime.
                Let’s get your documents where they belong — safely stored and easy to find.</p>
            </div>
            <div class="col-md-6 text-right">
                <h5 class="section-title">How It Works?</h5>
                <p class="section-description">Learn about the platform's features and functionality to get started quickly.</p>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <h5 class="section-title">What would you like to do today?</h5>
                <ul class="action-list">
                    <li><a href="{{route('admin.profile')}}" class="action-link">Edit Profile</a></li>
                    <li><a href="{{route('admin.folders.index')}}" class="action-link">Edit Your Files</a></li>
                    <li><a href="{{ route('admin.manage') }}" class="action-link">Generate Guest Access</a></li>
                    <li><a href="#" class="action-link">Help Topics</a></li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5 class="section-title">Video Tutorial</h5>
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

<style>
   .main {
        padding: 1.5rem;
    }

    .container {
        padding: 1.5rem;
        margin: 0 auto;
        overflow: auto; /* Allow scroll if content overflows */
    }

    .container h1{
        padding: 0 !important;
    }
    .dashboard-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #333;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        padding-left: 5px;
    }

    .section-description {
        font-size: 0.9rem;
        color: #7f8c8d;
        padding-left: 7px;
    }

    .action-list {
        padding-left: 25px;
    }

    .action-list li {
        margin-bottom: 8px;
    }

    .action-link {
        text-decoration: none;
        color: #3498db;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .action-link:hover {
        color: #2980b9;
    }

    iframe {
        border: none;
        width: 100%;
        height: 100%;
    }

    .ratio-16x9 {
        background-color: #f1f1f1;
        border-radius: 8px;
        overflow: hidden;
    }

    @media (max-width: 576px) {
        .dashboard-title {
            font-size: 1.5rem;
            text-align: center;
        }

        .section-title,
        .section-description {
            text-align: center;
        }

        .action-list {
            padding-left: 15px;
        }

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
