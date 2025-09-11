@extends('user.dashboard')

@section('content')
<div class="main">
    <nav aria-label="breadcrumb" class="custom-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Main Folder</li>
            <li class="breadcrumb-item active" aria-current="page">{{$name}} </li>
        </ol>
    </nav>

    <div class="container mt-4">
        <div id="folderContainer">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session("success") }}',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        window.location.reload(); // Reload the page after clicking "OK"
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

        <ul class="list-group folder-list">
            @forelse($folders as $folder)
            <li class="list-group-item d-flex justify-content-between align-items-center" data-folder-id="{{ $folder->file->sub->id }}">
                    <a href="#" class="folder-link" onclick="submitFolder('{{ $folder->file->sub->id }}', '{{ $folder->file->sub->sub_folder_name }}')">
                        <span class="folder-icon">📁</span> {{ $folder->file->sub->sub_folder_name }}
                    </a>
                </li>
            @empty
                <li class="list-group-item text-muted">No subfolders available</li>
            @endforelse
        </ul>

        <form id="folderForm" action="{{ route('user.innerfolders.show') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="folder_id" id="folderId">
            <input type="hidden" name="folder_name" id="folderName">
        </form>
        </div>
    </div>
</div>

<style>
    .container{
        margin-top: 100px!important;
    }
    .folder-list {
        max-width: 90%;
        margin: auto;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .list-group-item {
        font-size: 18px;
        font-weight: bold;
        padding: 15px;
        border-bottom: 1px solid #ddd;
    }

    .folder-icon {
        margin-right: 10px;
        color: #007bff;
    }

    .folder-link {
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        transition: color 0.3s ease;
    }

    .folder-link:hover {
        color: #007bff;
    }

    .add-folder-btn {
        margin-top: 20px;
        text-align: right;
        margin-right: 62px;
        margin-bottom: 20px;
    }

    .custom-breadcrumb {
        margin-bottom: 20px;
        margin-left: 20px;
        top: 0;
    }

    .container{
        bottom: 45px !important;
    }

</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
     function submitFolder(folderId, folderName) {
        document.getElementById('folderId').value = folderId;
        document.getElementById('folderName').value = folderName;
        document.getElementById('folderForm').submit();
    }

</script>


@endsection
