
        <div class="navigation">
            <div class="logo-container text-center">
                <a href="{{ route('superadmin.dashboard') }}">
                    <img src="{{ asset('imgs/docme_new_logo.png') }}" alt="Company Logo" class="sidebar-logo">
                </a>
            </div>
            <ul id="menu">
                <li>
                    <a href="{{ route('user.dashboard') }}">
                        <span class="icon">
                            <ion-icon name="grid-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.folders.index') }}">
                        <span class="icon">
                            <ion-icon name="folder-outline"></ion-icon>
                        </span>
                        <span class="title">File Manager</span>
                    </a>
                </li>
                <hr class="divider">
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="key-outline"></ion-icon>
                        </span>
                        <span class="title">FAQ's</span>
                    </a>
                </li>
                @if(session()->has('original_user_id'))
                    <li>
                        <a href="{{ route('user.account-link') }}">
                            <span class="icon">
                                <ion-icon name="swap-horizontal-outline"></ion-icon>
                            </span>
                            <span class="title">Switch Account</span>
                        </a>
                    </li>
                @endif
                <hr class="divider">
                <li class="user-profile">
                    <a href="#">
                        <span class="icon">
                            <img src="{{ auth()->user()->user_profile ? asset('uploads/' . auth()->user()->user_profile) : asset('imgs/profile.png') }}" alt="User Photo" class="user-photo">
                        </span>
                        <span class="title">
                            <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</strong><br>
                            <small>Role: Guest</small>
                        </span>
                    </a>
                </li>
                 @if(!session()->has('original_user_id'))
                  @if(auth()->user()->hasMultipleRoles())
                        <li>
                            <a href="#" class="d-flex align-items-center switch-role-link" style="text-decoration: none;">
                                <span class="icon">
                                    <ion-icon name="repeat-outline"></ion-icon>
                                </span>
                                <span class="title">Switch Role</span>
                            </a>

                            <form id="switchRoleForm" action="{{ route('user.switch-role') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        </li>
                    @endif
                @endif

                  <li>
                        <a href="mailto:support@doc-me.com.au?subject=Help Request from {{ auth()->user()->first_name }}" title="Need Help?">
                            <span class="icon">
                            <ion-icon name="help-circle-outline"></ion-icon>
                            </span>
                            <span class="title">Help</span>
                        </a>
                    </li>


            </ul>
        </div>

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

                         @if(session()->has('original_user_id') && !empty(session('original_user_id')))
                            @php
                                $originalUser = \App\Models\User::find(session('original_user_id'));
                            @endphp

                            @if($originalUser)
                                <li class="dropdown-header text-muted">
                                    <ion-icon name="sync-outline"></ion-icon> Switch Account
                                </li>
                                <li>
                                    <form action="{{ route('user.switch.account', $originalUser->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center">
                                            <img src="{{ $originalUser->user_profile ? asset('uploads/' . $originalUser->user_profile) : asset('imgs/profile.png') }}"
                                                class="rounded-circle me-2" width="28" height="28">
                                            <div>
                                                <strong>{{ $originalUser->first_name }}</strong><br>
                                                <small class="text-muted">{{ $originalUser->email }}</small>
                                            </div>
                                        </button>
                                    </form>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                        @endif

                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile') }}">Profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.login-details') }}">Settings</a>
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


    <footer class="footer">
        <p>Doc Me Copyright © 2025</p> <a href="https://candidmarketing.com.au/" target="_blank">By Candid Marketing</a>
    </footer>

    <style>


        .logo-container {
            padding-bottom: 10px;
        }


        #menu {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        #menu li {
            margin-bottom: 7px;
        }

        .user-profile a {
            display: flex;
            align-items: center;
        }

        .icon {
            margin-right: 10px; /* Add space between the icon and text */
        }

        .title {
            display: block;
            color: #fff; /* Ensure the text is visible */
        }

        .user-photo {
            width: 30px;
            height: 30px;
            border-radius: 50%;
        }

        .content-wrapper {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #34495e;
            color: #ecf0f1;
        }
    </style>

<script>
    document.querySelector('.switch-role-link').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('switchRoleForm').submit();
    });
</script>

