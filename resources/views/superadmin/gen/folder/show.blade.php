@extends('superadmin.dashboard')

@section('content')
<style>
    h1 {
        padding: 15px;
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
        margin-top: 20px;
        text-align: right;
        margin-right: 62px;
        margin-bottom: 20px;
    }
</style>

<div class="main">
    <div class="container-fluid">
    <h1>Sub Folders</h1>

    <nav aria-label="breadcrumb" class="custom-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('superadmin.folders.index', ['id' => $id_main]) }}">Main Folder</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $name }}</li>
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

    <div class="add-folder-btn">
        <button class="btn" style="background-color: #3b68b2; color: white;" data-bs-toggle="modal" data-bs-target="#addFolderModal">
            ➕ Add Sub Folder
        </button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Sub Folder Name</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($folders as $folder)
                    <tr>
                        <td>
                            <a href="#" class="folder-name" onclick="submitFolder('{{ $id_main }}', '{{ $folder->id }}', '{{ $folder->sub_folder_name }}')">
                                📁 {{ $folder->sub_folder_name }}
                            </a>
                        </td>
                        <td>
                            <button class="btn btn-sm" style="background-color: #683695; color: white;"
                                onclick="editSubFolder('{{ $id_main }}', '{{ $folder->id }}', '{{ $folder->sub_folder_name }}', '{{ $folder->sub_folder_description }}')">
                                ✏️ Edit
                            </button>
                            <button class="btn btn-sm" style="background-color: #ed1d7e; color: white;"
                                onclick="deleteSubFolder('{{ $id_main }}', '{{ $folder->id }}')">
                                🗑 Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-muted">No subfolders available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination Form for POST -->
        <form id="paginationForm" action="{{ route('superadmin.folders.show') }}" method="POST">
            @csrf
            <input type="hidden" name="folder_id" value="{{ $id_main }}">
            <input type="hidden" name="folder_name" value="{{ $name }}">
            <input type="hidden" id="pageInputHidden" name="page" value="{{ $folders->currentPage() }}">

            <div class="pagination-container d-flex justify-content-center align-items-center mt-3">
                <button type="button" class="btn me-2" style="background-color: #3b68b2; color: white;"
                        onclick="changePage(-1)"
                        @if ($folders->onFirstPage()) disabled @endif>
                    Prev
                </button>

                <input type="number" id="pageInput" class="form-control text-center me-2"
                    min="1" max="{{ $folders->lastPage() }}"
                    value="{{ $folders->currentPage() }}"
                    style="width: 50px;"
                    oninput="document.getElementById('pageInputHidden').value = this.value"
                    onkeypress="handleKeyPress(event)">

                <button type="button" class="btn" style="background-color: #3b68b2; color: white;"
                        onclick="changePage(1)"
                        @if ($folders->currentPage() == $folders->lastPage()) disabled @endif>
                    Next
                </button>
            </div>
        </form>


    </div>

    <form id="folderForm" action="{{ route('superadmin.innerfolders.show') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="main_id" id="mainId">
        <input type="hidden" name="folder_id" id="folderId">
        <input type="hidden" name="folder_name" id="folderName">
    </form>
    </div>
</div>

<!-- Add Sub Folder Modal -->
<div class="modal fade" id="addFolderModal" tabindex="-1" aria-labelledby="addFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">➕ Add Sub Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSubFolderForm">
                    @csrf
                    <input type="hidden" name="main_id" id="idMain" value="{{ $id_main }}">
                    <div class="mb-3">
                        <label class="form-label">Sub Folder Name</label>
                        <input type="text" class="form-control" name="sub_folder_name" id="subFolderName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sub Folder Description</label>
                        <input type="text" class="form-control" name="sub_folder_desc" id="subDescription" required>
                    </div>
                    <button type="submit" class="btn" style="background-color: #3b68b2; color: white;">Save Folder</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sub Folder Modal -->
<div class="modal fade" id="editSubFolderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✏️ Edit Sub Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSubFolderForm">
                    @csrf
                    <input type="hidden" id="editMainFolderId">
                    <input type="hidden" id="editSubFolderId">
                    <div class="mb-3">
                        <label class="form-label">Sub Folder Name</label>
                        <input type="text" class="form-control" id="editSubFolderName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sub Folder Description</label>
                        <input type="text" class="form-control" id="editSubDescription">
                    </div>
                    <button type="submit" class="btn" style="background-color: #3b68b2; color: white;">Update Folder</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function changePage(step) {
        const input = document.getElementById('pageInput');
        const hiddenInput = document.getElementById('pageInputHidden');
        const currentPage = parseInt(input.value);
        const newPage = currentPage + step;
        const maxPage = parseInt(input.max);

        if (newPage >= 1 && newPage <= maxPage) {
            input.value = newPage;
            hiddenInput.value = newPage;
            document.getElementById('paginationForm').submit();
        }
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // prevent default form submit
            const page = parseInt(event.target.value);
            const max = parseInt(event.target.max);
            const min = parseInt(event.target.min);

            if (page >= min && page <= max) {
                document.getElementById('pageInputHidden').value = page;
                document.getElementById('paginationForm').submit();
            }
        }
    }


    function submitFolder(mainId, folderId, folderName) {
        document.getElementById('mainId').value = mainId;
        document.getElementById('folderId').value = folderId;
        document.getElementById('folderName').value = folderName;
        document.getElementById('folderForm').submit();
    }

    function editSubFolder(mainID, folderId, folderName, folderDesc) {
        $("#editMainFolderId").val(mainID);
        $("#editSubFolderId").val(folderId);
        $("#editSubFolderName").val(folderName);
        $("#editSubDescription").val(folderDesc);
        let modal = new bootstrap.Modal(document.getElementById("editSubFolderModal"));
        modal.show();
    }

    $("#editSubFolderForm").submit(function (event) {
        event.preventDefault();
        let formData = {
            _token: "{{ csrf_token() }}",
            main_folder_id: $("#editMainFolderId").val(),
            folder_id: $("#editSubFolderId").val(),
            sub_folder_name: $("#editSubFolderName").val(),
            sub_folder_desc: $("#editSubDescription").val()
        };

        $.post("{{ route('superadmin.update.subfolders') }}", formData, function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                confirmButtonColor: '#3b68b2'
            }).then(() => {
                window.location.reload();
            });
        }).fail(function (xhr) {
            let errorMessage = xhr.responseJSON?.message || "Something went wrong.";
            Swal.fire("Error!", errorMessage, "error");
        });
    });

    function deleteSubFolder(mainId, folderId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('superadmin.delete.subfolders') }}", {
                    _token: "{{ csrf_token() }}",
                    main_id: mainId,
                    folder_id: folderId
                }, function (response) {
                    Swal.fire({
                        title: "Deleted!",
                        text: response.message,
                        icon: "success",
                        confirmButtonColor: '#3b68b2'
                    }).then(() => {
                        window.location.reload();
                    });
                }).fail(function (xhr) {
                    let errorMessage = xhr.responseJSON?.message || "Unable to delete folder.";
                    Swal.fire("Error!", errorMessage, "error");
                });
            }
        });
    }

    $("#addSubFolderForm").submit(function (event) {
        event.preventDefault();
        let formData = {
            _token: "{{ csrf_token() }}",
            main_id: $("#idMain").val(),
            sub_folder_name: $("#subFolderName").val(),
            sub_folder_desc: $("#subDescription").val()
        };

        $.post("{{ route('superadmin.add.subfolders') }}", formData, function (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: response.message,
                confirmButtonColor: '#3b68b2'
            }).then(() => {
                window.location.reload();
            });
        }).fail(function (xhr) {
            let errorMessage = xhr.responseJSON?.message || "Validation failed.";
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: errorMessage,
                confirmButtonColor: '#dc3545'
            });
        });
    });
</script>
@endsection
