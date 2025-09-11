<div id="sidebar" class="sidebar">
    <div class="logo-container">
            <img src="{{ asset('imgs/profile.png') }}" alt="Logo" class="logo" />
    </div>
    <h2>Menu</h2>
    <ul class="sidebar-menu">
        @if(Auth::check())
            {{-- @php
                $permissions = [
                    'view-dashboard' => ['label' => 'Dashboard', 'route' => 'dashboard'],
                    'manage-users' => ['label' => 'Manage Users', 'route' => 'users.index'],
                    'view-reports' => ['label' => 'Reports', 'route' => 'reports.index'],
                    'edit-profile' => ['label' => 'Edit Profile', 'route' => 'profile.edit'],
                    'add-profile' => ['label' => 'Add Profile', 'route' => 'profile.add'],
                ];
            @endphp --}}

            @foreach($permissions as $permission => $details)
                @can($permission)
                    <li><a href="">{{ $details['label'] }}</a></li>
                @endcan
            @endforeach

            <li><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            <form id="logout-form" action="" method="POST" style="display: none;">
                @csrf
            </form>
        @else
            <li><a href="">Login</a></li>
        @endif
    </ul>
</div>
