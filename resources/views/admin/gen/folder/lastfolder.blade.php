@extends('superadmin.dashboard')

@section('content')
<div class="main">
    <nav aria-label="breadcrumb" class="custom-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Main Folder</li>
            <li class="breadcrumb-item">{{$main_folder}}</li>
            <li class="breadcrumb-item active" aria-current="page">{{$name}} </li>
        </ol>
    </nav>
    <div class="container mt-4">
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

        <ul class="list-group folder-list">
            @forelse($folders as $folder)
            <li class="list-group-item d-flex justify-content-between align-items-center" data-folder-id="{{ $folder->id }}">
                    <a href="#" class="folder-link" onclick="submitFolder('{{$main_id}}', '{{$sub_id}}','{{$inner_id}}','{{$child_id}}', '{{$id_main}}','{{ $folder->id }}', '{{ $folder->last_folder_name }}')">
                        <span class="folder-icon">📁</span> {{ $folder->last_folder_name }}
                    </a>
                </li>
            @empty
                <li class="list-group-item text-muted">No inner folders available</li>
            @endforelse
        </ul>

        <form id="folderForm" action="{{ route('admin.files.last_show') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="main_id" id="mainId">
            <input type="hidden" name="sub_id" id="subId">
            <input type="hidden" name="inner_id" id="innerId">
            <input type="hidden" name="child_id" id="childId">
            <input type="hidden" name="innerchild_id" id="innerchildId">
            <input type="hidden" name="folder_id" id="folderId">
            <input type="hidden" name="folder_name" id="folderName">
        </form>

    </div>
</div>
<style>
    .container
    {
        margin-top:100px !important;
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
        font-size: 14px;
        padding: 10px 15px;
        border-radius: 5px;
        display: inline-block;
    }

    .custom-breadcrumb a {
    text-decoration: none;
    color: #007bff;
    transition: color 0.3s ease-in-out;
    }

    .custom-breadcrumb a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .custom-breadcrumb span {
        color: #6c757d;
        margin: 0 5px;
    }
    .container{
        bottom: 45px !important;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    function submitFolder(mainId,subId,innerId,childId,innerchildId, folderId, folderName) {
        document.getElementById('mainId').value = mainId;
        document.getElementById('subId').value = subId;
        document.getElementById('innerId').value = innerId;
        document.getElementById('childId').value = childId;
        document.getElementById('innerchildId').value =innerchildId;
        document.getElementById('folderId').value = folderId;
        document.getElementById('folderName').value = folderName;
        document.getElementById('folderForm').submit();
    }
</script>


@endsection
