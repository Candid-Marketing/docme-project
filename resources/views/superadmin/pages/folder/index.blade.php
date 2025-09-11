@extends('superadmin.dashboard')

@section('content')
<style>
    h1 {
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
        background-color: #f8f9fa;
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
        margin-right: 62px;
        margin-bottom: 20px;
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

    @media (max-width: 576px) {
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
<div class="main">
    <div class="container-fluid">
        <h1>File Manager</h1>
        @php
        use App\Models\AdminFolderTemplate;
        $breadcrumbs = [];
        $current = request('parent_code');
        while ($current) {

            $folder = AdminFolderTemplate::where('parent_code',$current)->first();
            if ($folder) {
                array_unshift($breadcrumbs, $folder);
                $current = $folder->parent_code;
            } else {
                break;
            }
        }
    @endphp

    <nav aria-label="breadcrumb" class="custom-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.folders.index') }}">Folders</a></li>
            @foreach($breadcrumbs as $crumb)
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.folders.index', ['parent_id' => $crumb->id]) }}">
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

        <div class="add-folder-btn">
            <button class="btn" style="background-color: #3b68b2; color: white;" data-bs-toggle="modal" data-bs-target="#addFolderModal">
                ➕ Add Folder
            </button>
        </div>
        {{-- <div class="add-folder-btn">
            <form method="POST" action="{{ route('superadmin.folder-template.copy') }}" class="d-inline">
                @csrf
                <select name="source_code" required class="form-select d-inline w-auto">
                    @foreach($availableTemplates as $template)
                        <option value="{{ $template->unique_code }}">{{ $template->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="new_name" placeholder="New Structure Name" required class="form-control d-inline w-auto">
                <button type="submit" class="btn btn-primary">📋 Copy Structure</button>
            </form>
        </div> --}}

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Folder Name</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($folders as $folder)
                        <tr>
                            <td>
                                <a href="{{ route('superadmin.folders.index', ['parent_code' => $folder->unique_code]) }}" class="folder-name">
                                    📁 {{ $folder->name }}
                                </a>
                            </td>
                            <td>
                                <button class="btn btn-sm" style="background-color: #683695; color: white;" onclick="editFolder('{{ $folder->id }}', '{{ $folder->name }}', '{{ $folder->description }}')">
                                    ✏️ Edit
                                </button>
                                <button class="btn btn-sm" style="background-color: #ed1d7e; color: white;" onclick="deleteFolder('{{ $folder->id }}')">
                                    🗑 Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-muted">No folders available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-container d-flex justify-content-center align-items-center mt-3">
                {{-- Prev Button --}}
                <button type="button"
                        class="btn me-2"
                        style="background-color: #3b68b2; color: white;"
                        onclick="changePage(-1)"
                        @if ($folders->onFirstPage()) disabled @endif>
                    Prev
                </button>

                {{-- Page Input --}}
                <input type="number"
                       id="pageInput"
                       class="form-control text-center me-2"
                       min="1"
                       max="{{ $folders->lastPage() }}"
                       value="{{ $folders->currentPage() }}"
                       style="width: 60px;"
                       onkeypress="handleKeyPress(event)">

                {{-- Next Button --}}
                <button type="button"
                        class="btn"
                        style="background-color: #3b68b2; color: white;"
                        onclick="changePage(1)"
                        @if ($folders->currentPage() == $folders->lastPage()) disabled @endif>
                    Next
                </button>
            </div>

        </div>
    </div>
</div>
<!-- Add Folder Modal -->
<div class="modal fade" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFolderModalLabel">➕ Add New Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('superadmin.add.folders') }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_code" value="{{ $parentCode ?? '' }}">
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" name="folder_name" id="folderName" placeholder="Enter folder name" required>
                    </div>
                    <div class="mb-3">
                        <label for="folderDesc" class="form-label">Folder Description</label>
                        <input type="text" class="form-control" name="folder_desc" id="folderDesc" placeholder="Enter folder description">
                    </div>
                    <button type="submit" class="btn" style="background-color: #3b68b2; color: white;">Save Folder</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Folder Modal -->
<div class="modal fade" id="editFolderModal" tabindex="-1" aria-labelledby="editFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editFolderForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editFolderModalLabel">✏️ Edit Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editFolderId" name="folder_id">

                    <div class="mb-3">
                        <label for="editFolderName" class="form-label">Folder Name</label>
                        <input type="text" class="form-control" id="editFolderName" name="folder_name" required placeholder="Enter new folder name">
                    </div>

                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Folder Description</label>
                        <input type="text" class="form-control" id="editDescription" name="folder_desc" placeholder="Enter folder description">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function editFolder(folderId, folderName, folderDesc) {
        console.log('clicked');
        $("#editFolderId").val(folderId);
        $("#editFolderName").val(folderName);
        $("#editDescription").val(folderDesc);
        $("#editFolderModal").modal('show');
    }

    $(document).ready(function () {
        $("#editFolderForm").on('submit', function (event) {
            event.preventDefault();

            const formData = {
                _token: "{{ csrf_token() }}",
                folder_id: $("#editFolderId").val(),
                folder_name: $("#editFolderName").val(),
                folder_desc: $("#editDescription").val()
            };

            console.log("Sending form data:", formData);

            $.ajax({
                type: "POST",
                url: "{{ route('superadmin.update.folders') }}",
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#3b68b2'
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function (xhr) {
                    let errorMessage = "Could not update folder. Try again.";
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join("\n");
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        confirmButtonColor: '#dc3545'
                    }).then(() => {
                        window.location.reload();
                    });
                }
            });
        });
    });


    function deleteFolder(folderId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('superadmin.delete.folders') }}", { _token: "{{ csrf_token() }}", folder_id: folderId }, function (response) {
                    Swal.fire({
                        title: "Deleted!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                        confirmButtonColor: '#3b68b2'
                    }).then(() => {
                        window.location.reload();
                    });
                }).fail(function (xhr) {
                    Swal.fire("Error!", xhr.responseJSON.message, "error");
                });
            }
        });
    }

    function changePage(step) {
    const currentPage = parseInt(document.getElementById('pageInput').value);
    const newPage = currentPage + step;
    const maxPage = parseInt(document.getElementById('pageInput').max);
    const minPage = parseInt(document.getElementById('pageInput').min);
    const parentCode = "{{ request('parent_code') }}";

    if (newPage >= minPage && newPage <= maxPage) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', newPage);
        if (parentCode) {
            url.searchParams.set('parent_code', parentCode);
        }
        window.location.href = url.toString();
    }
}

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        const page = parseInt(event.target.value);
        const max = parseInt(event.target.max);
        const min = parseInt(event.target.min);
        const parentCode = "{{ request('parent_code') }}";

        if (page >= min && page <= max) {
            const url = new URL(window.location.href);
            url.searchParams.set('page', page);
            if (parentCode) {
                url.searchParams.set('parent_code', parentCode);
            }
            window.location.href = url.toString();
        }
    }
}

</script>
@endsection
