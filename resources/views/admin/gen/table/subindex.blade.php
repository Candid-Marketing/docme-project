@extends('admin.dashboard')

@section('content')
<style>
    .container h1 {
        font-size: 24px;
        font-weight: 600;
        text-align: left;
        margin-left: 60px;
    }
    .custom-breadcrumb {
        margin-left: 70px;
        margin-bottom: 15px;
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
        margin-bottom: 80px;
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

        @media (max-width: 576px) {
            .main {
                    position: static !important;
                    width: 100% !important;
                    height: auto !important;
                    overflow-y: auto !important;
                    padding-bottom: 80px; /* so bottom content isn’t cut off */
                    margin-top: 45px;
                }

                body, html {
                    overflow-x: hidden;
                }


        }

        .scrollable-main {
            height: calc(100vh - 60px); /* Adjust based on header/footer */
            overflow-y: auto;
            padding-bottom: 30px;
        }


</style>
<div class="main scrollable-main">
    <h1 >{{ $folder->folder_name }} - Files</h1>
    @php
        use App\Models\UserStructureFolder;
        $breadcrumbs = [];
        $current = $folder->id;

        while ($current) {
            $crumb = UserStructureFolder::find($current);
            if ($crumb) {
                array_unshift($breadcrumbs, $crumb);
                $current = $crumb->parent_id;
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
    <div class="container-fluid">
        <div class="card shadow p-4 table-container">
            <div class="d-flex justify-content-between mb-3 align-items-center">
                <h4 class="mb-0">Files List</h4>
                <button class="btn" style="background-color: #3b68b2; color: white;" data-bs-toggle="modal" data-bs-target="#addFileModal">
                    Add Files
                </button>
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
                            <th class="text-center">Created Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $index => $file)
                        <tr>
                            <td class="text-center">{{ ($files->currentPage() - 1) * $files->perPage() + $index + 1 }}</td>
                            <td class="text-center">{{ $file->file_name }}</td>
                            <td class="text-center">{{ $file->file_type }}</td>
                            <td class="text-center">
                                {{ is_numeric($file->file_size) ? number_format($file->file_size / 1024, 2) . ' KB' : 'N/A' }}
                            </td>
                            <td class="text-center">{{ $file->folder_name }}</td>
                            <td class="text-center">{{ $file->created_by }}</td>
                            <td class="text-center">{{ $file->created_at->format('Y-m-d H:i:s') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.files.view', $file->id) }}" target="_blank" class="btn btn-sm" style="background-color: #c0a6cf; color: white;">
                                    View
                                </a>

                                <button class="btn invite-btn"
                                    style="background-color: #683695; color: white;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#inviteModal"
                                    data-id="{{ $file->id }}"
                                    data-file_name="{{ $file->file_name }}"
                                    data-file_type="{{ $file->file_type }}"
                                    data-folder_name="{{ $file->folder_name }}">
                                    Invite
                                </button>


                                <button class="btn btn-sm edit-btn"
                                        style="background-color: #683695; color: white;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editFileModal"
                                        data-id="{{ $file->id }}"
                                        data-file_name="{{ $file->file_name }}"
                                        data-file_type="{{ $file->file_type }}"
                                        data-folder_name="{{ $file->folder_name }}">
                                    Edit
                                </button>


                                <button class="btn btn-sm delete-btn"
                                    style="background-color: #ed1d7e; color: white;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteFileModal"
                                    data-id="{{ $file->id }}">
                                    Delete
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

<div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inviteModalLabel">Shared Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- File Details -->
                <p><strong>File Name:</strong> <span id="fileName"></span></p>
                <p><strong>File Type:</strong> <span id="fileType"></span></p>
                <p><strong>Profile Created :</strong> <span id="folderName"></span></p>

                <!-- Invitation Form -->
                <form  id="inviteForm">
                    @csrf
                    <input type="hidden" id="fileId" name="file_id">
                    <div class="mb-3">
                        <label for="guestEmail" class="form-label">Guest Email</label>
                        <select class="form-control" id="guestEmail" name="guest_email" required>
                            <option value="">Select a guest</option>
                            @foreach ($guest as $guest)
                                <option value="{{ $guest->email }}">{{ $guest->name }} {{ $guest->email }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message (optional)</label>
                        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="availableFrom" class="form-label">Available From</label>
                        <input type="datetime-local" class="form-control" id="availableFrom" name="available_from">
                    </div>
                    <div class="mb-3">
                        <label for="availableUntil" class="form-label">Available Until</label>
                        <input type="datetime-local" class="form-control" id="availableUntil" name="available_until">
                    </div>
                    <div class="modal-footer">
                         <a href="{{ route('admin.manage') }}" class="btn" style="background-color: #6c757d; color: white;">
                            Add Guest
                        </a>
                        <button type="button" class="btn" style="background-color: #ed1d7e; color: white;" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" style="background-color: #3b68b2; color: white;" id="confirmInvite">
                            Send Invitation
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addFileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="fileUploadForm" enctype="multipart/form-data">
                    @csrf
                    <!-- Hidden Inputs -->
                    <input type="hidden" name="id" id="folder_id" value="{{ $folder->id }}">
                    <input type="hidden" name="name" id="folder_name" value="{{ $folder->folder_name }}">


                    <div class="col-12">
                        <label for="file_upload" class="form-label">Upload File</label>
                        <input type="file" id="file_upload" name="file_upload" class="form-control" required>
                        <span class="text-danger" id="error_file_upload"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn" style="background-color: #ed1d7e; color: white;" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn " style="background-color: #3b68b2; color: white;" onclick="uploadFile()">
                            <span id="submitFileText">Upload File</span>
                            <span id="submitFileSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit User Modal -->
<div class="modal fade" id="editFileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="fileEditForm" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <!-- Hidden ID -->
                    <input type="hidden" name="id" id="editFileId">

                    <div class="mb-3">
                        <label for="editFileName" class="form-label">File Name</label>
                        <input type="text" id="editFileName" name="file_name" class="form-control">
                        <span class="text-danger" id="error_edit_file_upload"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn" style="background-color: #ed1d7e; color: white;" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn" style="background-color: #3b68b2; color: white;" onclick="updateFile()">
                            <span id="submitEditText">Update File</span>
                            <span id="submitEditSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
                <input type="hidden" id="delete_user_id"> <!-- Hidden input to store user ID -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #3b68b2; color: white;" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn" style="background-color: #ed1d7e; color: white;"  id="confirmDelete">Delete</button>
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

    function uploadFile() {
        let formData = new FormData();

        // Append file and other fields
        formData.append("file_upload", document.getElementById("file_upload").files[0]);
        formData.append("file_name", document.getElementById("file_upload").files[0].name);
        formData.append("folder_name", document.getElementById("folder_name").value);
        formData.append("folder_id", document.getElementById("folder_id").value);
        formData.append("_token", "{{ csrf_token() }}");

        let submitButton = document.querySelector("button[onclick='uploadFile()']");
        let submitText = document.getElementById("submitFileText");
        let submitSpinner = document.getElementById("submitFileSpinner");

        // Show loading spinner
        submitSpinner.classList.remove("d-none");
        submitText.textContent = "Uploading...";

        axios.post("{{ route('admin.files.innerchild_folder_upload') }}", formData, {
            headers: { "Content-Type": "multipart/form-data" }
        })
        .then(function (response) {

            // Close modal and reset form
            document.getElementById("fileUploadForm").reset();
            $('#addFileModal').modal("hide");

            // Append new file to the table (if applicable)
            Swal.fire({
                title: 'Success!',
                text: 'File uploaded successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
        })
        .catch(function (error) {
            if (error.response && error.response.data.errors) {
                let errors = error.response.data.errors;
                document.getElementById("error_file_upload").innerText = errors.file_upload ? errors.file_upload[0] : "";
            } else {
                alert("Something went wrong. Please try again.");
            }
        })
        .finally(function () {
            // Hide spinner and restore button text
            submitSpinner.classList.add("d-none");
            submitText.textContent = "Upload File";
        });
    }


    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function () {
            // Get data from button attributes
            let fileId = this.getAttribute("data-id");
            let fileName = this.getAttribute("data-file_name");
            // Set modal input values
            document.getElementById("editFileId").value = fileId;
            document.getElementById("editFileName").value = fileName;
        });
    });

    function updateFile() {
        const form = document.getElementById("fileEditForm");
        const formData = new FormData(form);

        // Show spinner
        document.getElementById("submitEditText").classList.add("d-none");
        document.getElementById("submitEditSpinner").classList.remove("d-none");

        axios.post(`{{ route('admin.files.innerchild_folder_update') }}`, formData, {
            headers: { "Content-Type": "multipart/form-data" }
        })
        .then(response => {
            // Hide spinner
            document.getElementById("submitEditText").classList.remove("d-none");
            document.getElementById("submitEditSpinner").classList.add("d-none");

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editFileModal'));
            modal.hide();

            // Show success alert
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'File updated successfully.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload(); // Optional: reload after success alert
            });
        })
        .catch(error => {
            document.getElementById("submitEditText").classList.remove("d-none");
            document.getElementById("submitEditSpinner").classList.add("d-none");

            let errorMsg = "An error occurred. Please try again.";

            if (error.response && error.response.data && error.response.data.errors) {
                const errors = error.response.data.errors;
                if (errors.file_upload) {
                    document.getElementById("error_edit_file_upload").textContent = errors.file_upload[0];
                    errorMsg = errors.file_upload[0];
                }
            }

            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: errorMsg
            });
        });
    }

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const fileId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This will permanently delete the file.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ed1d7e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`{{ route('admin.files.innerchild_folder_delete') }}`, {
                        id: fileId,
                        _token: '{{ csrf_token() }}'
                    })
                    .then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The file has been deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // or dynamically remove row
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Delete Failed',
                            text: 'Something went wrong. Please try again.'
                        });
                    });
                }
            });
        });
    });

    document.getElementById("searchInput").addEventListener("keyup", function () {
        let query = this.value.trim();


        if (query.length > 0) {
            axios.get("{{ route('admin.files.innerchild_folder_search') }}", {
                params: {
                    query: query,
                    main_id: mainId,
                    sub_id: subId,
                    inner_id: innerId,
                    child_id: childId,
                    innerchild_id: innerchildId,
                }
            })
            .then(response => {
                let files = response.data.files;
                let tableBody = document.querySelector(".table tbody");
                tableBody.innerHTML = "";

                if (files.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="7" class="text-center">No files found.</td></tr>`;
                } else {
                    files.forEach((file, index) => {
                        let sizeInKB = (file.file_size / 1024).toFixed(2);
                        let newRow = `
                            <tr>
                                <td class="text-center">${index + 1}</td>
                                <td class="text-center">${file.file_name}</td>
                                <td class="text-center">${file.file_type}</td>
                                <td class="text-center">${sizeInKB} KB</td>
                                <td class="text-center">${file.folder_name}</td>
                                <td class="text-center">${file.created_by}</td>
                                <td class="text-center">
                                    <button class="btn invite-btn"
                                            style="background-color: #683695; color: white;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#"
                                            data-id="${file.id}"
                                            data-file_name="${file.file_name}"
                                            data-file_type="${file.file_type}"
                                            data-folder_name="${file.folder_name}">
                                        Invite
                                    </button>

                                    <button class="btn btn-sm edit-btn"
                                            style="background-color: #683695; color: white;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editFileModal"
                                            data-id="${file.id}"
                                            data-file_name="${file.file_name}"
                                            data-file_type="${file.file_type}"
                                            data-folder_name="${file.folder_name}">
                                        Edit
                                    </button>

                                    <button class="btn btn-sm delete-btn"
                                            style="background-color: #ed1d7e; color: white;"
                                            data-id="${file.id}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableBody.innerHTML += newRow;
                    });

                    // Re-bind any dynamic event listeners (optional)
                }
            })
            .catch(error => {
                console.error("Search failed", error);
            });
        } else {
            location.reload(); // Reload full list if search cleared
        }
    });

    document.querySelectorAll(".invite-btn").forEach(button => {
        button.addEventListener("click", function () {
            let fileId = this.getAttribute("data-id");
            let fileName = this.getAttribute("data-file_name");
            let fileType = this.getAttribute("data-file_type");
            let folderName = this.getAttribute("data-folder_name");
            console.log('Logs', fileName);

            // Populate the modal fields
            document.getElementById("fileId").value = fileId;
            document.getElementById("fileName").textContent = fileName; // Use textContent for span
            document.getElementById("fileType").textContent = fileType; // Use textContent for span
            document.getElementById("folderName").textContent = folderName; // Use textContent for span
        });
    });


    document.addEventListener('DOMContentLoaded', function () {
        const inviteForm = document.getElementById('inviteForm');
        const sendInviteBtn = document.getElementById('confirmInvite');

        inviteForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(inviteForm);
            sendInviteBtn.disabled = true;
            sendInviteBtn.innerHTML = 'Sending...';

            axios.post("{{ route('admin.store.invite') }}", formData, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: response.data.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b68b2'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });

                inviteForm.reset();
                const inviteModal = bootstrap.Modal.getInstance(document.getElementById('inviteModal'));
                inviteModal.hide();
            })
            .catch(error => {
                let errorMsg = 'Something went wrong.';

                if (error.response && error.response.data && error.response.data.errors) {
                    const errors = Object.values(error.response.data.errors).flat();
                    errorMsg = errors.join('\n');
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Invite Failed',
                    text: errorMsg
                });
            })
            .finally(() => {
                sendInviteBtn.disabled = false;
                sendInviteBtn.innerHTML = 'Send Invitation';
            });
        });
    });

</script>

@endsection
