@extends('user.dashboard')

@section('content')
<style>
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

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        table-layout: auto;
        min-width: 1000px;
    }

    .table thead {
        background-color: #f8f9fa;
        font-size: 13px;
        text-transform: uppercase;
        color: #6c757d;
        border-bottom: 2px solid #dee2e6;
    }

    .table th, .table td {
        padding: 8px;
        text-align: left;
        word-wrap: break-word;
        white-space: normal;
        max-width: 200px;
    }

    .table tbody tr {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    .table tbody tr:hover {
        background: #f1f3f5;
        transform: scale(.98);
    }

    .btn {
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 12px;
        transition: all 0.3s;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: auto;
        border-radius: 10px;
    }

    .pagination .page-link {
        color: black;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 13px;
    }

    .pagination .page-item.active .page-link {
        background-color: #3b68b2;
        border-color: #3b68b2;
    }

    h1 {
        padding: 15px;
        font-size: 24px;
        font-weight: 600;
        text-align: left;
        margin-left: 55px;
    }
    .custom-breadcrumb {
        margin-left: 70px !important;
        margin-bottom: 15px !important;
    }
</style>
<div class="main">
    <h1 class="mb-4">{{ $name }} Files</h1>

    <nav aria-label="breadcrumb" class="custom-breadcrumb mb-4 ms-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('user.dashboard') }}">Dashboard</a>
            </li>
            @foreach ($breadcrumbs ?? [] as $crumb)
                <li class="breadcrumb-item">
                    <a href="{{ route('user.folders.index', ['parent_id' => $crumb->id]) }}">
                        {{ $crumb->folder_name }}
                    </a>
                </li>
            @endforeach
        </ol>
    </nav>

    <div class="container-fluid">
        <div class="card shadow p-4 table-container">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">File Name</th>
                            <th class="text-center">File Type</th>
                            <th class="text-center">File Size</th>
                            <th class="text-center">Folder Name</th>
                            <th class="text-center">Uploaded By</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $index => $file)

                        <tr>
                            <td class="text-center">{{ ($files->currentPage() - 1) * $files->perPage() + $index + 1 }}</td>
                            <td class="text-center">{{ $file->file_name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $file->file_type ?? 'N/A' }}</td>
                            <td class="text-center">{{ isset($file->file_size) ? number_format($file->file_size / 1024, 2) . ' KB' : 'N/A' }}</td>
                            <td class="text-center">{{ $file->folder_name ?? 'N/A' }}</td>
                            <td class="text-center">{{ $file->created_by ?? 'N/A' }}</td>
                            <td class="text-center">
                                @if(isset($file->file_path))
                                    <a href="{{ route('user.files.secure.view.force', ['folder' => $file->uploader->id, 'filename' => basename($file->file_path)]) }}"
                                        target="_blank" class="btn btn-outline" style="background-color: #3b68b2; color: white;">
                                        View
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No files available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination-container d-flex justify-content-center align-items-center mt-3">
                <button class="btn me-2" style="background-color: #3b68b2; color: white;" id="prevPage" onclick="changePage(-1)">Prev</button>
                <input type="number" id="pageInput" class="form-control text-center me-2"
                       min="1" max="{{ $files->lastPage() }}" value="{{ $files->currentPage() }}"
                       style="width: 50px;" onkeypress="handleKeyPress(event)">
                <button class="btn" style="background-color: #3b68b2; color: white;" id="nextPage" onclick="changePage(1)">Next</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function changePage(direction) {
        let inputField = document.getElementById("pageInput");
        let currentPage = parseInt(inputField.value);
        let lastPage = {{ $files->lastPage() }};
        let newPage = currentPage + direction;
        if (newPage >= 1 && newPage <= lastPage) {
            window.location.href = "{{ $files->url(1) }}".replace("page=1", "page=" + newPage);
        }
    }

    function handleKeyPress(event) {
        if (event.key === "Enter") {
            let inputField = document.getElementById("pageInput");
            let newPage = parseInt(inputField.value);
            let lastPage = {{ $files->lastPage() }};
            if (newPage >= 1 && newPage <= lastPage) {
                window.location.href = "{{ $files->url(1) }}".replace("page=1", "page=" + newPage);
            } else {
                alert("Please enter a valid page number between 1 and " + lastPage);
            }
        }
    }
</script>
@endsection
