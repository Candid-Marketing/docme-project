@extends('admin.dashboard')

@section('content')
<div class="main">
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.folders.index', ['id' => $id_main]) }}">Main Folder</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$name}}</li>
            </ol>
        </nav>

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
                            window.location.reload();
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

            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Folder Name</th>
                            <th style="width: 180px;">Actions</th>>  <!-- Align action column to the right -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($folders as $folder)
                            <tr>
                                <td>
                                    <a href="#" class="folder-link" onclick="submitFolder('{{$id_main}}','{{ $folder->id }}', '{{ $folder->sub_folder_name }}')">
                                        📁 {{ $folder->sub_folder_name }}
                                    </a>
                                </td>
                                <td class="text-right"> <!-- Aligning action buttons to the right -->
                                    <button class="btn  btn-sm" onclick="#" style="background-color: #3b68b2; color: white;" >Share</button>
                                    <button class="btn  btn-sm" onclick="#" style="background-color: #3abfdd; color: white;" >Edit</button>
                                    <button class="btn  btn-sm" onclick="#"  style="background-color: #ed1d7e; color: white;" >Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-muted">No subfolders available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination-container d-flex justify-content-center align-items-center mt-3">
                    <!-- Previous Button -->
                    <button class="btn me-2" style="background-color: #3b68b2; color: white;"
                            onclick="changePage(-1)"
                            {{ $folders->onFirstPage() ? 'disabled' : '' }}>
                        Prev
                    </button>

                    <!-- Page Input -->
                    <input type="number" id="pageInput" class="form-control text-center me-2"
                           min="1" max="{{ $folders->lastPage() }}"
                           value="{{ $folders->currentPage() }}"
                           style="width: 50px;"
                           onkeypress="handleKeyPress(event)">

                    <!-- Next Button -->
                    <button class="btn" style="background-color: #3b68b2; color: white;"
                            onclick="changePage(1)"
                            {{ $folders->currentPage() == $folders->lastPage() ? 'disabled' : '' }}>
                        Next
                    </button>
                </div>
            </div>

            <form id="folderForm" action="{{ route('admin.innerfolders.show') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="main_id" id="mainId">
                <input type="hidden" name="folder_id" id="folderId">
                <input type="hidden" name="folder_name" id="folderName">
            </form>
        </div>
    </div>
</div>

<style>
    .custom-breadcrumb
    {
        margin-left:25px;
    }
    .container {
        margin-top: 60px !important;
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
        margin-top: 50px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f8f9fa;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .folder-link {
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        color: #007bff;
        transition: color 0.3s ease;
    }

    .folder-link:hover {
        color: #0056b3;
    }

    .add-folder-btn {
        text-align: right;
        margin-bottom: 10px;
        margin-right: 50px;
    }

    .add-folder-btn button {
        padding: 10px 20px;
        background-color: #3b68b2;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .add-folder-btn button:hover {
        background-color: #2a4b84;
    }

    .pagination-container {
        text-align: center;
        margin-top: 20px;
    }

    .pagination a {
        padding: 8px 16px;
        margin: 0 4px;
        border-radius: 4px;
        background-color: #3b68b2;
        color: white;
        text-decoration: none;
    }

    .pagination a:hover {
        background-color: #2a4b84;
    }

    .pagination .active {
        background-color: #007bff;
        color: white;
    }

</style>

<script>
    function changePage(step) {
        const currentPage = parseInt(document.getElementById('pageInput').value);
        const newPage = currentPage + step;
        const maxPage = parseInt(document.getElementById('pageInput').max);
        if (newPage >= 1 && newPage <= maxPage) {
            window.location.href = `?page=${newPage}`;
        }
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            const page = parseInt(event.target.value);
            const max = parseInt(event.target.max);
            const min = parseInt(event.target.min);
            if (page >= min && page <= max) {
                window.location.href = `?page=${page}`;
            }
        }
    }

    function submitFolder(mainId, folderId, folderName) {
        document.getElementById('mainId').value = mainId;
        document.getElementById('folderId').value = folderId;
        document.getElementById('folderName').value = folderName;
        document.getElementById('folderForm').submit();
    }
</script>

@endsection
