@extends('admin.dashboard')

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
        margin-top:40px;
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

        .main-scrollable {
            height: calc(100vh - 60px); /* adjust based on your header/nav height */
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 30px;
        }

</style>
<div class="main main-scrollable">
    <div class="container-fluid">
        <div class="card shadow p-4 table-container">
            <div class="d-flex justify-content-between mb-3 align-items-center">
                <h4 class="mb-0">Manage Guest</h4>
                <button class="btn" style="background-color: #3b68b2; color: white;" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    Add Guest
                </button>
            </div>
            <div class="mb-3 d-flex justify-content-left">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search guest..." style="border-radius: 6px;">
                </div>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">First Name</th>
                            <th class="text-center">Last Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Invoice</th>
                            <th class="text-center">Verification Status</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                        <tr>
                            <td class="text-center" data-label="ID">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td class="text-center" data-label="First Name">{{ $user->first_name }}</td>
                            <td class="text-center" data-label="Last Name">{{ $user->last_name }}</td>
                            <td class="text-center" data-label="Email">
                                {{ $user->email }} {{ $user->email_verified_at ? '✅' : '❌' }}
                            </td>
                            <td class="text-center" data-label="Role">
                               Guest
                            </td>
                            <td class="text-center" data-label="Invoice">
                                @if ($user->invoice && $user->invoice->invoice_path)
                                    <a href="{{ asset('receipts/' . basename($user->invoice->invoice_path)) }}" target="_blank">
                                        <i class="fas fa-file-pdf text-danger"></i> {{ $user->invoice->invoice_file }}
                                    </a>
                                @endif
                            </td>
                            <td class="text-center" data-label="Verification">
                                {{ $user->is_verified ? 'Verified' : 'Not Verified' }}
                            </td>
                            <td class="text-center" data-label="Status">
                                {{ $user->status ? 'Settled' : 'Pending' }}
                            </td>
                            <td class="text-center" data-label="Actions">
                                <button class="btn btn-sm edit-btn"
                                        style="background-color: #683695; color: white;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal"
                                        data-id="{{ $user->id }}"
                                        data-first_name="{{ $user->first_name }}"
                                        data-last_name="{{ $user->last_name }}"
                                        data-email="{{ $user->email }}"
                                        data-user_status="{{ $user->user_status }}">
                                    Edit
                                </button>
                                <button class="btn btn-sm delete-btn"
                                        style="background-color: #ed1d7e; color: white;"
                                        data-bs-toggle="modal"
                                        data-target="#deleteUserModal"
                                        data-id="{{ $user->id }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            </div>
            <div class="pagination-container d-flex justify-content-center align-items-center mt-3">
                <!-- Previous Button -->
                <button class="btn me-2" style="background-color: #3b68b2; color: white;" id="prevPage" onclick="changePage(-1)">Prev</button>

                <!-- Page Input -->
                <input type="number" id="pageInput" class="form-control text-center me-2"
                       min="1" max="{{ $users->lastPage() }}" value="{{ $users->currentPage() }}"
                       style="width: 50px;" onkeypress="handleKeyPress(event)">

                <!-- Next Button -->
                <button class="btn" style="background-color: #3b68b2; color: white;" id="nextPage" onclick="changePage(1)">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" id="first_name" class="form-control">
                        <span class="text-danger" id="error_first_name"></span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" id="last_name" class="form-control">
                        <span class="text-danger" id="error_last_name"></span>
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control">
                        <span class="text-danger" id="error_email"></span>
                    </div>
                    <div class="col-12">
                        <label for="user_status" class="form-label">Role</label>
                        <select id="user_status" class="form-select">
                            <option value="">Select Role</option>
                            <option value="3">Guest</option>
                        </select>
                        <span class="text-danger" id="error_user_status"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" style="background-color: #ed1d7e; color: white;"  data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn" id="submitUserBtn" style="background-color: #3b68b2; color: white;" onclick="submitUser()">
                    <span id="submitUserText">Add User</span>
                    <span id="submitUserSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_user_id"> <!-- Hidden field for User ID -->

                <div class="row">
                    <div class="col-12 col-md-6">
                        <label for="edit_first_name" class="form-label">First Name</label>
                        <input type="text" id="edit_first_name" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="edit_last_name" class="form-label">Last Name</label>
                        <input type="text" id="edit_last_name" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" id="edit_email" class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="edit_user_status" class="form-label">Role</label>
                        <select id="edit_user_status" class="form-select">
                            <option value="">Select Role</option>
                            <option value="3">Guest</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background-color: #ed1d7e; color: white;" >Close</button>
                <button type="submit" class="btn saveButton" style="background-color: #3b68b2; color: white;">Save Changes</button>
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
        let lastPage = {{ $users->lastPage() }};

        // Calculate the new page number
        let newPage = currentPage + direction;

        // Ensure it's within valid range
        if (newPage >= 1 && newPage <= lastPage) {
            window.location.href = "{{ $users->url(1) }}".replace("page=1", "page=" + newPage);
        }
    }

    function handleKeyPress(event) {
        if (event.key === "Enter") {
            let inputField = document.getElementById("pageInput");
            let newPage = parseInt(inputField.value);
            let lastPage = {{ $users->lastPage() }};

            // Ensure it's a valid page number
            if (newPage >= 1 && newPage <= lastPage) {
                window.location.href = "{{ $users->url(1) }}".replace("page=1", "page=" + newPage);
            } else {
                alert("Please enter a valid page number between 1 and " + lastPage);
            }
        }
    }

    function submitUser() {
        let first_name = document.getElementById("first_name").value;
        let last_name = document.getElementById("last_name").value;
        let email = document.getElementById("email").value;
        let user_status = document.getElementById("user_status").value;
        let submitButton = document.getElementById("submitUserBtn");
        let submitText = document.getElementById("submitUserText");
        let submitSpinner = document.getElementById("submitUserSpinner");

        // Show loading spinner
        submitSpinner.classList.remove("d-none");
        submitText.textContent = "Processing...";

        axios.post("{{route('admin.add-user')}}", {
            first_name: first_name,
            last_name: last_name,
            email: email,
            user_status: user_status,
            _token: "{{ csrf_token() }}"
        })
        .then(function (response) {
            let user = response.data.user;

            // Append the new user to the table dynamically
            let table = document.querySelector(".table tbody");
            Swal.fire({
                title: "Success!",
                text: "User added successfully.",
                icon: "success",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(); // 🔁 Reload the page
                }
            }); // Add the new row to the top of the table

            // Clear form inputs
            document.getElementById("first_name").value = "";
            document.getElementById("last_name").value = "";
            document.getElementById("email").value = "";
            document.getElementById("user_status").value = "";

            // Close the modal
            $('#addUserModal').modal("hide");
        })
        .catch(function (error) {
             const message = error.response?.data?.message || "Please check your inputs and try again.";
            console.log("Error adding user:", message);
            if (error.response && error.response.data.errors) {
                let errors = error.response.data.errors;

                document.getElementById("error_first_name").innerText = errors.first_name ? errors.first_name[0] : "";
                document.getElementById("error_last_name").innerText = errors.last_name ? errors.last_name[0] : "";
                document.getElementById("error_email").innerText = errors.email ? errors.email[0] : "";
                document.getElementById("error_user_status").innerText = errors.user_status ? errors.user_status[0] : "";
                Swal.fire({
                    icon: "error",
                    title: "Add User Failed",
                    text: message,
                    confirmButtonColor: "#ed1d7e"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Something went wrong, please try again.",
                    text: message,
                    confirmButtonColor: "#ed1d7e"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });

            }
        })
        .finally(function () {
            // Re-enable button and restore text after request finishes
            submitButton.disabled = false;
            submitButton.innerHTML = "Submit";
        });
    }


    document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function () {
                // Get user data from button attributes
                let userId = this.getAttribute("data-id");
                let firstName = this.getAttribute("data-first_name");
                let lastName = this.getAttribute("data-last_name");
                let email = this.getAttribute("data-email");
                let userStatus = this.getAttribute("data-user_status");

                // Set modal input values
                document.getElementById("edit_user_id").value = userId;
                document.getElementById("edit_first_name").value = firstName;
                document.getElementById("edit_last_name").value = lastName;
                document.getElementById("edit_email").value = email;
                document.getElementById("edit_user_status").value = userStatus;
            });
        });

        // Handle edit form submission
        document.querySelector(".saveButton").addEventListener("click", function () {
            let userId = document.getElementById("edit_user_id").value;
            let firstName = document.getElementById("edit_first_name").value;
            let lastName = document.getElementById("edit_last_name").value;
            let email = document.getElementById("edit_email").value;
            let userStatus = document.getElementById("edit_user_status").value;

            axios.post("{{route('admin.edit-user')}}", {
                user_id: userId,
                first_name: firstName,
                last_name: lastName,
                email: email,
                user_status: userStatus,
                _token: "{{ csrf_token() }}"
            })
            .then(response => {
                // Show success message
                Swal.fire({
                title: "Success!",
                text: "User Edited successfully.",
                icon: "success",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(); // 🔁 Reload the page
                }
            });

                // Update button attributes dynamically to avoid stale data
                button.setAttribute("data-first_name", firstName);
                button.setAttribute("data-last_name", lastName);
                button.setAttribute("data-email", email);
                button.setAttribute("data-user_status", userStatus);

                // Close the modal
                $('#editUserModal').modal("hide");
            })
            .catch(error => {
                Swal.fire({
                    icon: "error",
                    title: "Update Failed",
                    text: "Please check your inputs and try again.",
                    confirmButtonColor: "#ed1d7e"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(); // 🔁 Reload the page after OK is clicked
                    }
                });

                // Display validation errors
                let errors = error.response.data.errors;
                document.getElementById("error_first_name").innerText = errors.first_name ? errors.first_name[0] : "";
                document.getElementById("error_last_name").innerText = errors.last_name ? errors.last_name[0] : "";
                document.getElementById("error_email").innerText = errors.email ? errors.email[0] : "";
                document.getElementById("error_user_status").innerText = errors.user_status ? errors.user_status[0] : "";
            });
        });

         // When clicking the delete button, store the user ID
         document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function () {
                userIdToDelete = this.getAttribute("data-id");
                document.getElementById("delete_user_id").value = userIdToDelete;
            });
        });

        // When confirming delete
        document.getElementById("confirmDelete").addEventListener("click", function () {
            if (!userIdToDelete) return;

            axios.post("{{route('admin.delete-user')}}", {
                user_id: userIdToDelete,
                _token: "{{ csrf_token() }}",

            })
            .then(response => {
                    Swal.fire({
                    title: "Success!",
                    text: "User Deleted successfully.",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(); // 🔁 Reload the page
                    }
                });
            })
            .catch(error => {
                Swal.fire({
                    icon: "error",
                    title: "Delete Failed",
                    text: "Something went wrong. Please try again.",
                    confirmButtonColor: "#ed1d7e"
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(); // 🔁 Reload the page after OK is clicked
                    }
                });

            });
        });

        document.getElementById("searchInput").addEventListener("keyup", function () {
            let query = searchInput.value.trim();
            if (query.length > 0) {
                axios.get("{{route('admin.search-user')}}", { params: { query: query } })
                    .then(response => {
                        let users = response.data.users;
                        let tableBody = document.querySelector(".table tbody");
                        tableBody.innerHTML = ""; // Clear table

                        if (users.length === 0) {
                            tableBody.innerHTML = `<tr><td colspan="9" class="text-center">No users found.</td></tr>`;
                        } else {
                            users.forEach((user, index) => {
                                let role = user.user_status == 1 ? "Admin" : (user.user_status == 2 ? "User" : "Guest");

                                let newRow = `
                                    <tr>
                                        <td class="text-center">${index + 1}</td>
                                        <td class="text-center">${user.first_name}</td>
                                        <td class="text-center">${user.last_name}</td>
                                        <td class="text-center">${user.email} ${user.email_verified_at ? '✅' : '❌'}</td>
                                        <td class="text-center">${role}</td>
                                        <td class="text-center">${user.invoice ? `<a href="${user.invoice}" target="_blank"><i class="fas fa-file-pdf text-danger"></i></a>` : ''}</td>
                                        <td class="text-center">${user.is_verified ? 'Verified' : 'Not Verified'}</td>
                                        <td class="text-center">${user.status ? 'Settled' : 'Pending'}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm edit-btn"
                                                    style="background-color: #683695; color: white;"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal"
                                                    data-id="${user.id}"
                                                    data-first_name="${user.first_name}"
                                                    data-last_name="${user.last_name}"
                                                    data-email="${user.email}"
                                                    data-user_status="${user.user_status}">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm delete-btn"
                                                    style="background-color: #ed1d7e; color: white;"
                                                    data-bs-toggle="modal"
                                                    data-id="${user.id}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                tableBody.innerHTML += newRow;
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching search results", error);
                    });
            } else {
                location.reload(); // Reload table if search is empty
            }
        });

</script>

@endsection
