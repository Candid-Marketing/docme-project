<div class="navigation">
    <!-- Logo Section -->
    <div class="logo-container text-center">
        <a href="{{ route('superadmin.dashboard') }}">
            <img src="{{ asset('imgs/docme_new_logo.png') }}" alt="Company Logo" class="sidebar-logo">
        </a>
    </div>

    <!-- Navigation Menu -->
    <ul id="menu" class="w-100">
        <li><a href="{{ route('superadmin.dashboard') }}">
            <span class="icon"><ion-icon name="grid-outline"></ion-icon></span>
            <span class="title">Dashboard</span></a></li>
        <li><a href="{{ route('superadmin.user') }}">
            <span class="icon"><ion-icon name="people-outline"></ion-icon></span>
            <span class="title">Users</span></a></li>
        <li><a href="{{ route('superadmin.folders.index') }}">
            <span class="icon"><ion-icon name="folder-outline"></ion-icon></span>
            <span class="title">File Manager</span></a></li>
        <li><a href="{{ route('superadmin.stripe') }}">
            <span class="icon"><ion-icon name="card-outline"></ion-icon></span>
            <span class="title">Finance</span></a></li>
        <li><a href="{{ route('superadmin.invoice') }}">
            <span class="icon"><ion-icon name="card-outline"></ion-icon></span>
            <span class="title">Invoice</span></a></li>
        <li><a href="{{ route('superadmin.homepage') }}">
            <span class="icon"><ion-icon name="planet-outline"></ion-icon></span>
            <span class="title">Homepage</span></a></li>
        <li><a href="{{ route('superadmin.email') }}">
            <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
            <span class="title">Email</span></a></li>
        <hr class="divider">
        <li><a href="#">
            <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
            <span class="title">FAQ'S</span></a></li>
         <li>
                        <a href="mailto:support@doc-me.com.au?subject=Help Request from {{ auth()->user()->first_name }}" title="Need Help?">
                            <span class="icon">
                            <ion-icon name="help-circle-outline"></ion-icon>
                            </span>
                            <span class="title">Help</span>
                        </a>
                    </li>
                <hr class="divider">
    </ul>
    <!-- User Profile Section (Footer, Stays at Bottom) -->
    <div class="user-profile">
        <a href="#">
            <span class="icon">
                <img src="{{ auth()->user()->user_profile ? asset('uploads/' . auth()->user()->user_profile) : asset('imgs/profile.png') }}"
                    alt="User Photo" class="user-photo">
            </span>
            <div class="user-info">
                <span class="title">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                <p class="user-role">Role: <strong>{{ auth()->user()->user_status == 1 ? 'Admin' : (auth()->user()->user_status == 2 ? 'User' : 'Guest') }}</strong></p>
            </div>
        </a>
    </div>

</div>


<!-- Main Content -->
<div class="content-wrapper" style="margin-left: 250px; width: calc(100% - 250px); padding-right: 20px;">

    <nav class="navbar navbar-light bg-light fixed-top d-flex justify-content-between">
        <!-- Toggle Button (on the left side) -->
        <div class="d-flex align-items-center">
            <div class="toggle me-2">
                <ion-icon name="menu-outline"></ion-icon>
            </div>
        </div>

        <!-- User Profile (on the right side) -->
        <div class="d-flex align-items-center test">
            <div class="dropdown">
                <a
                    class="nav-link dropdown-toggle d-flex align-items-center"
                    href="#"
                    id="navbarDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                <img
                    src="{{ auth()->user()->user_profile ? asset('uploads/' . auth()->user()->user_profile) : asset('imgs/profile.png') }}"
                    alt="User Photo"
                    class="rounded-circle me-2"
                    style="height: 30px; width: 30px; object-fit: cover;"
                />

                    <span class="d-none d-md-inline">{{ auth()->user()->first_name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('superadmin.information') }}">Profile</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('superadmin.login-details') }}">Settings</a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('superadmin.login-details') }}">Login Details</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout.show') }}">Logout</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
</div>
</div>

<footer class="footer">
<p>Doc Me Copyright © 2025</p> <a href="https://candidmarketing.com.au/" target="_blank">By Candid Marketing</a>
</footer>
