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
        overflow-x: auto; /* Enables horizontal scrolling when content overflows */
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        table-layout: auto; /* Adjusts column width dynamically */
        min-width: 1000px; /* Ensures table does not shrink too much */
    }

    .table thead {
        background-color: #f8f9fa;
        font-size: 13px;
        text-transform: uppercase;
        color: #6c757d;
        border-bottom: 2px solid #dee2e6;
        display: table-header-group; /* Keeps headers visible */
    }

    .table th, .table td {
        padding: 8px;
        text-align: left;
        word-wrap: break-word; /* Ensures words break properly */
        overflow-wrap: break-word;
        white-space: normal; /* Allows text wrapping */
        max-width: 200px; /* Prevents overly wide columns */
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
        padding: 4px 10px; /* Reduced button padding for smaller buttons */
        font-size: 12px; /* Smaller font size for buttons */
        transition: all 0.3s;
    }
    .btn-warning {
        background-color: #ffcc00;
        border: none;
        color: #212529;
    }
    .btn-warning:hover {
        background-color: #e6b800;
    }
    .btn-danger {
        background-color: #ff4d4d;
        border: none;
        color: #fff;
    }
    .btn-danger:hover {
        background-color: #cc0000;
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
        font-size: 24px; /* Reduced font size */
        font-weight: 600;
        text-align: left;
        margin-left:55px;
    }

      /* Remove number input arrows */
      #pageInput::-webkit-outer-spin-button,
        #pageInput::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        #pageInput {
            -moz-appearance: textfield; /* Firefox */
        }

</style>
<div class="main">
    <h1 class="mb-4">{{$name}} Filesss</h1>
    <div class="container-fluid">
        <div class="card shadow p-4 table-container">
            <div class="d-flex justify-content-between mb-3 align-items-center">
                <h4 class="mb-0">Files List</h4>
            </div>
            <div class="mb-3 d-flex justify-content-left">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search files..." style="border-radius: 6px;">
                </div>
            </div>
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
                        @foreach ($files as $index => $file)
                        <tr>
                            <td class="text-center">{{ ($files->currentPage() - 1) * $files->perPage() + $index + 1 }}</td>
                            <td class="text-center">{{ $file->file->file_name }}</td>
                            <td class="text-center">{{ $file->file->file_type }}</td>
                            <td class="text-center">{{ number_format($file->file->file_size / 1024, 2) }} KB</td>
                            <td class="text-center">{{ $file->file->folder_name }}</td>
                            <td class="text-center">{{ $file->file->created_by }}</td>
                            <td class="text-center">

                                <button class="btn view-btn"
                                    style="background-color: #683695; color: white;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewModal"
                                    data-id="{{ $file->id }}"
                                    data-file_name="{{ $file->file_name }}"
                                    data-file_type="{{ $file->file_type }}"
                                    data-folder_name="{{ $file->folder_name }}">
                                    View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-container d-flex justify-content-center align-items-center mt-3">
                <!-- Previous Button -->
                <button class="btn me-2" style="background-color: #3b68b2; color: white;" id="prevPage" onclick="changePage(-1)">Prev</button>

                <!-- Page Input -->
                <input type="number" id="pageInput" class="form-control text-center me-2"
                       min="1" max="{{ $files->lastPage() }}" value="{{ $files->currentPage() }}"
                       style="width: 50px;" onkeypress="handleKeyPress(event)">

                <!-- Next Button -->
                <button class="btn" style="background-color: #3b68b2; color: white;" id="nextPage" onclick="changePage(1)">Next</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
      let userIdToDelete = null;
      let searchInput = document.getElementById("searchInput");
     function changePage(direction) {
        let inputField = document.getElementById("pageInput");
        let currentPage = parseInt(inputField.value);
        let lastPage = {{ $files->lastPage() }};

        // Calculate the new page number
        let newPage = currentPage + direction;

        // Ensure it's within valid range
        if (newPage >= 1 && newPage <= lastPage) {
            window.location.href = "{{ $files->url(1) }}".replace("page=1", "page=" + newPage);
        }
    }

    function handleKeyPress(event) {
        if (event.key === "Enter") {
            let inputField = document.getElementById("pageInput");
            let newPage = parseInt(inputField.value);
            let lastPage = {{ $files->lastPage() }};

            // Ensure it's a valid page number
            if (newPage >= 1 && newPage <= lastPage) {
                window.location.href = "{{ $files->url(1) }}".replace("page=1", "page=" + newPage);
            } else {
                alert("Please enter a valid page number between 1 and " + lastPage);
            }
        }
    }

</script>

@endsection
