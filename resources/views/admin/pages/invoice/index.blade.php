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
        text-align: center;
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
    <h1 class="mb-4">Invoice Statement</h1>
    <div class="container-fluid">
        <div class="card shadow p-4 table-container">
            <div class="d-flex justify-content-between mb-3 align-items-center">
                <h4 class="mb-0">Invoice</h4>
            </div>
            <div class="mb-3 d-flex justify-content-left">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search invoice..." style="border-radius: 6px;">
                </div>
            </div>
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">First Name</th>
                            <th class="text-center">First Name</th>
                            <th class="text-center">Invoice Date</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Invoice</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                        <tr>
                            <td class="text-center">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td class="text-center">{{ $user->first_name }}</td>
                            <td class="text-center">{{ $user->last_name }}</td>
                            <td class="text-center">
                                {{ $user->invoice ? $user->invoice->invoice_date : '' }}
                            </td>
                            <td class="text-center">
                                {{ optional($user->invoice)->amount ? '$ ' . optional($user->invoice)->amount : '' }}
                            </td>
                            <td class="text-center">
                                @if ($user->invoice && $user->invoice->invoice_path)
                                    <a href="{{ asset('receipts/' . basename($user->invoice->invoice_path)) }}" target="_blank">
                                        <i class="fas fa-file-pdf text-danger"></i> {{ $user->invoice->invoice_file }}
                                    </a>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $user->status ? 'Settled' : 'Pending' }}
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
                       min="1" max="{{ $users->lastPage() }}" value="{{ $users->currentPage() }}"
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

        searchInput.addEventListener("keyup", function () {
        let query = searchInput.value.trim();
        if (query.length > 0) {
            axios.get("{{ route('admin.search.invoice') }}", { params: { query: query } })
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
                                    <td class="text-center">${user.invoice_date}</td>
                                    <td class="text-center">${user.amount}</td>
                                    <td class="text-center">${user.invoice ? `<a href="${user.invoice}" target="_blank"><i class="fas fa-file-pdf text-danger"></i></a>` : ''}</td>
                                    <td class="text-center">${user.status ? 'Settled' : 'Pending'}</td>
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
