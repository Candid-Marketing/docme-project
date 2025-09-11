@extends('user.dashboard')

@section('content')
<style>
    .container h1 {
        font-size: 24px;
        font-weight: 600;
        text-align: left;
        padding-left: 60px;
    }

    .custom-breadcrumb {
        margin-left: 60px;
    }

    .table-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 0 auto;
        width: 90%;
        display: flex;
        flex-direction: column;
    }
    table {
        width: 100%;
        margin: 20px 0;
        border-collapse: collapse;
    }
    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f8f9fa !important;
        font-weight: 600;
    }
    td {
        font-size: 16px;
        font-weight: 400;
    }
    tr:hover {
        background-color: #f1f1f1;
    }

    .folder-name {
        font-size: 16px;
        font-weight: bold;
        color: #333;
        text-decoration: none;
    }

    .folder-name:hover {
        color: #007bff;
    }

    .add-folder-btn {
        text-align: right;
        margin-right: 20px;
        margin-bottom: 10px;
    }

    .pagination-container {
        text-align: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .pagination input,
    .pagination button {
        margin: 5px;
    }

    .btn-group > .btn {
        margin-right: 4px;
    }


    .pagination-container {
        text-align: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .pagination input,
    .pagination button {
        margin: 5px;
    }

    @media (max-width: 576px) {
        .main {
            position: static !important;
            width: 100% !important;
            height: auto !important;
            overflow-y: auto !important;
            padding-bottom: 80px;
            margin-top: 45px;
        }

        body, html {
            overflow-x: hidden;
        }
    }
</style>

<div class="main">
    <div class="container-fluid">
        <h1>File Manager</h1>

        @php
            use App\Models\UserStructureFolder;
            $breadcrumbs = [];
            $current = request('parent_id');
            while ($current) {
                $folder = UserStructureFolder::find($current);
                if ($folder) {
                    array_unshift($breadcrumbs, $folder);
                    $current = $folder->parent_id;
                } else {
                    break;
                }
            }
        @endphp

        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.folders.index') }}">Folders</a></li>
                @foreach($breadcrumbs as $crumb)
                    <li class="breadcrumb-item">
                        <a href="{{ route('user.folders.index', ['parent_id' => $crumb->id]) }}">
                            {{ $crumb->folder_name }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session("success") }}',
                        confirmButtonColor: '#28a745'
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: '{{ session("error") }}',
                        confirmButtonColor: '#dc3545'
                    });
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error!',
                        text: '{{ $errors->first() }}',
                        confirmButtonColor: '#dc3545'
                    });
                });
            </script>
        @endif

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Folder Name</th>
                        <th> Created Date </th>

                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($folders as $folder)
                        <tr>
                            <td>
                                @php
                                    $hasChildren = \App\Models\UserStructureFolder::where('parent_id', $folder->id)->exists();
                                @endphp

                                @if($hasChildren)
                                    <a href="{{ route('user.folders.index', ['parent_id' => $folder->id]) }}" class="folder-name">
                                        📁 {{ $folder->folder_name }}
                                    </a>
                                @else
                                    <span class="folder-name text-muted" style="cursor: default;">
                                        📁 {{ $folder->folder_name }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $folder->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                @php
                                     $isMainFolder = $folder->parent_id === null;
                                    $hasFiles = $folderFileMap[$folder->id] ?? false;
                                 $isSubFolder = $folder->parent_id !== null && optional($folder->parent)->parent_id === null;

                                @endphp

                                @if($isSubFolder || $hasFiles)
                                        <form id="viewForm{{ $folder->id }}" action="{{ route('user.last-table.show') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                                            <input type="hidden" name="folder_name" value="{{ $folder->folder_name }}">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                👁️ View Files
                                            </button>
                                        </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">No folders available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-container d-flex justify-content-center align-items-center">
                <button class="btn me-2" style="background-color: #3b68b2; color: white;"
                        onclick="changePage(-1)" @if ($folders->onFirstPage()) disabled @endif>Prev</button>
                <input type="number" id="pageInput" class="form-control text-center me-2"
                       min="1" max="{{ $folders->lastPage() }}" value="{{ $folders->currentPage() }}"
                       style="width: 60px;" onkeypress="handleKeyPress(event)">
                <button class="btn" style="background-color: #3b68b2; color: white;"
                        onclick="changePage(1)" @if ($folders->currentPage() == $folders->lastPage()) disabled @endif>Next</button>
            </div>
        </div>
    </div>
</div>

<script>
    function changePage(step) {
        const currentPage = parseInt(document.getElementById('pageInput').value);
        const maxPage = parseInt(document.getElementById('pageInput').max);
        const minPage = parseInt(document.getElementById('pageInput').min);
        const parentId = "{{ request('parent_id') }}";
        const newPage = currentPage + step;

        if (newPage < minPage || newPage > maxPage) return;

        const url = new URL(window.location.href);
        url.searchParams.set('page', newPage);
        if (parentId) url.searchParams.set('parent_id', parentId);
        window.location.href = url.toString();
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            const page = parseInt(event.target.value);
            const max = parseInt(event.target.max);
            const min = parseInt(event.target.min);
            const parentId = "{{ request('parent_id') }}";

            if (page >= min && page <= max) {
                const url = new URL(window.location.href);
                url.searchParams.set('page', page);
                if (parentId) url.searchParams.set('parent_id', parentId);
                window.location.href = url.toString();
            }
        }
    }
</script>
@endsection
