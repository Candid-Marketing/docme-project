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

        <div class="add-folder-btn">
            <!-- Button to Open Modal -->
            {{-- <button class="btn" style="background-color: #3b68b2; color: white;" data-bs-toggle="modal" data-bs-target="#addFolderModal">
                ➕ Add Folder
            </button> --}}
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Inner Child Folder Name</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($folders as $folder)
                        <tr>
                            <td>
                                <a href="#" class="folder-link" onclick="#">
                                    <span class="folder-icon">📁</span> {{ $folder->last_folder_name }}
                                </a>
                            </td>
                            <td>
                                <!-- Edit Button (Purple) -->
                                <button class="btn btn-sm" style="background-color: #683695; color: white;"
                                onclick="editSubFolder('{{$main_id}}', '{{$sub_id}}','{{$id_main}}', '{{$inner_id}}','{{ $folder->id }}', '{{ $folder->innerchild_folder_name }}', '{{ $folder->innerchild_folder_description }}')">
                                ✏️ Edit
                                </button>

                                <!-- Delete Button (Pink) -->
                                <button class="btn btn-sm" style="background-color: #ed1d7e; color: white;"
                                onclick="deleteSubFolder('{{$main_id}}', '{{$sub_id}}','{{$id_main}}', '{{$inner_id}}','{{ $folder->id }}')">
                                🗑 Delete
                                </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-muted">No last folders available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-container d-flex justify-content-center align-items-center mt-3">
                <!-- Previous Button -->
                <button class="btn me-2" style="background-color: #3b68b2; color: white;" onclick="changePage(-1)"
                        {{ $folders->onFirstPage() ? 'disabled' : '' }}>
                    Prev
                </button>

                <!-- Page Input -->
                <input type="number" id="pageInput" class="form-control text-center me-2" min="1" max="{{ $folders->lastPage() }}"
                       value="{{ $folders->currentPage() }}" style="width: 50px;" onkeypress="handleKeyPress(event)">

                <!-- Next Button -->
                <button class="btn" style="background-color: #3b68b2; color: white;" onclick="changePage(1)"
                        {{ $folders->currentPage() == $folders->lastPage() ? 'disabled' : '' }}>
                    Next
                </button>
            </div>
        </div>


        <form id="folderForm" action="{{ route('superadmin.lastfolders.show') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="main_id" id="mainId">
            <input type="hidden" name="sub_id" id="subId">
            <input type="hidden" name="inner_id" id="innerId">
            <input type="hidden" name="child_id" id="childId">
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
                <h5 class="modal-title" id="addFolderModalLabel">➕ Add Sub Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSubFolderForm">
                    @csrf
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Sub Folder Name</label>
                        <input type="hidden" name="sub_id" id="idMain" value="{{ $id_main }}">
                        <input type="hidden" name="main_id" id="mainIdValue" value="{{ $main_id }}">
                        <input type="text" class="form-control" name="inner_folder_name" id="innerfolderName" placeholder="Enter subfolder name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Sub Folder Description</label>
                        <input type="text" class="form-control" name="inner_folder_desc" id="description" placeholder="Enter subfolder description" required>
                    </div>
                    <button type="submit" class="btn" style="background-color: #3b68b2; color: white;">Save Folder</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Inner Folder Modal -->
<div class="modal fade" id="editInnerFolderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✏️ Edit Inner Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editInnerFolderForm">
                    @csrf
                    <input type="hidden" id="editmainID">
                    <input type="hidden" id="editInnerFolderId">
                    <input type="hidden" id="editsubfolderID">
                    <input type="hidden" id="editchildfolderID">
                    <input type="hidden" id="editinnerchildfolderId">
                    <div class="mb-3">
                        <label class="form-label">Inner Folder Name</label>
                        <input type="text" class="form-control" id="editInnerFolderName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Inner Folder Description</label>
                        <input type="text" class="form-control" id="editInnerDescription">
                    </div>
                    <button type="submit" class="btn" style="background-color: #3b68b2; color: white;" >Update Folder</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

function submitFolder(mainId,subId,innerId,childId,folderId, folderName) {
        document.getElementById('mainId').value = mainId;
        document.getElementById('subId').value = subId;
        document.getElementById('innerId').value = innerId;
        document.getElementById('childId').value = childId;
        document.getElementById('folderId').value = folderId;
        document.getElementById('folderName').value = folderName;
        document.getElementById('folderForm').submit();
    }

    $(document).ready(function () {
        $("#addSubFolderForm").submit(function (event) {
            event.preventDefault(); // Prevent normal form submission
            let idmain = "{{ $main_id }}";
            let formData = {
                _token: "{{ csrf_token() }}",
                sub_id  : $("#idMain").val(),
                main_id: $("#mainIdValue").val(),
                inner_folder_name: $("#innerfolderName").val(),
                inner_folder_desc: $("#description").val()
            };

            $.ajax({
                type: "POST",
                url: "{{ route('superadmin.add.innerfolders') }}", // Ensure this route matches your controller method
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#3b68b2'
                    }).then(() => {
                        window.location.reload(); // Reload the page after clicking "OK"
                    });

                    // Reset the form fields
                    $("#innerfolderName").val('');
                    $("#description").val('');

                    // Close the modal
                    $("#addFolderModal").modal('hide');
                },
                error: function (xhr) {
                    console.log(xhr);
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join("\n"); // Convert errors into a readable string
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: errorMessage,
                        confirmButtonColor: '#dc3545'
                    });
                    $("#addFolderModal").modal('hide');
                    $("#innerfolderName").val('');
                    $("#description").val('');
                }
            });
        });
    });

    function editSubFolder(mainFolder,subfolderID,innerId,childId,folderId, folderName, folderDesc) {
    $("#editmainID").val(mainFolder);
    $("#editsubfolderID").val(subfolderID);
    $("#editInnerFolderId").val(innerId);
    $("#editchildfolderID").val(childId);
    $("#editinnerchildfolderId").val(folderId);
    $("#editInnerFolderName").val(folderName);
    $("#editInnerDescription").val(folderDesc);
    let modal = new bootstrap.Modal(document.getElementById("editInnerFolderModal"));
    modal.show();
    }

    $("#editInnerFolderForm").submit(function (event) {
        event.preventDefault();

        let formData = {
            _token: "{{ csrf_token() }}",
            main_id: $("#editmainID").val(),
            sub_id: $("#editsubfolderID").val(),
            inner_id: $("#editInnerFolderId").val(),
            child_id: $("#editchildfolderID").val(),
            inner_child_id: $("#editinnerchildfolderId").val(),
            sub_folder_name: $("#editInnerFolderName").val(),
            sub_folder_desc: $("#editInnerDescription").val()
        };

        $.post("{{ route('superadmin.update.innerchildfolders') }}", formData, function (response) {
            console.log('DATA', response.updated_folder.inner_folder_name); // Log the correct response data

            Swal.fire({
                title: "Success!",
                text: response.message,
                icon: "success",
                confirmButtonText: "OK",
                 confirmButtonColor: '#3b68b2'
            }).then(() => {
                window.location.reload(); // Reloads the page after clicking "OK"
            });

            // Hide the modal
            $("#editInnerFolderModal").modal('hide');

        }).fail(function (xhr) {
            Swal.fire("Error!", xhr.responseJSON.message, "error");
        });
    });


    function deleteSubFolder(mainId, subId, innerId, childId,folderId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('superadmin.delete.innerchildfolders') }}",
                    type: "POST",
                    data: {
                        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        folder_id: folderId,
                        main_id: mainId,
                        sub_id: subId,
                        inner_id:innerId,
                        child_id:childId
                    },
                    success: function (response) {
                        Swal.fire({
                            title: "Deleted!",
                            text: response.message,
                            icon: "success",
                            confirmButtonText: "OK",
                            confirmButtonColor: '#3b68b2'
                        }).then(() => {
                            window.location.reload(); // Reload the page after clicking "OK"
                        });

                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON ? xhr.responseJSON.message : 'An error occurred',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });
            }
        });
    }



</script>


@endsection
