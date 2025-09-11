@extends('admin.dashboard')

@section('content')
<style>
    .container h1 {
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
        margin-right: 20px;
        margin-bottom: 10px;
    }

    .pagination-container {
        text-align: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .pagination input,
    .pagination button {
        margin: 5px;
    }

    .btn-group > .btn {
        margin-right: 4px;
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
</style>

<div class="main">
    <div class="container-fluid">
        <h1>File Manager</h1>

        @php
            use App\Models\UserStructureFolder;
            $breadcrumbs = [];
            $current = request('parent_id');
            while ($current) {
                $folder = UserStructureFolder::find($current);
                if ($folder) {
                    array_unshift($breadcrumbs, $folder);
                    $current = $folder->parent_id;
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

       <div class="add-folder-btn">
            @if(request()->has('parent_id'))
                <!-- Trigger a modal when inside a child folder -->
                <button class="btn" style="background-color: #3b68b2; color: white;" data-bs-toggle="modal" data-bs-target="#addChildFolderModal">
                    ➕ Add Subfolder
                </button>
            @else
                <!-- Link to parent-level folder creation route -->
                <a href="{{ route('admin.file') }}">
                    <button class="btn" style="background-color: #3b68b2; color: white;">➕ Add Folder</button>
                </a>
            @endif
        </div>


        @if($folders->count() > 0)
        <div class="table-container table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Folder Name</th>
                        <th>Created Date</th>
                        <th style="min-width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($folders as $folder)
                    <tr>
                        <td>
                            @php
                            $hasChild = \App\Models\UserStructureFolder::where('parent_id', $folder->id)->exists();
                            @endphp

                            @if($hasChild)
                                <a href="{{ route('admin.folders.index', ['parent_id' => $folder->id]) }}" class="folder-name">
                                    📁 {{ $folder->folder_name }}
                                </a>
                            @else
                                <span class="folder-name text-muted" style="cursor: default;">
                                    📁 {{ $folder->folder_name }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $folder->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <div class="btn-group">
                                @if ($folder->parent_id !== null)
                                    <a href="{{ route('admin.folders.view.files', ['id' => $folder->id]) }}" class="btn btn-sm btn-outline-primary" title="View">👁️</a>
                                @endif

                                <button type="button" class="btn btn-sm btn-outline-secondary" title="Edit Folder"
                                    data-bs-toggle="modal" data-bs-target="#editFolderModal"
                                    data-id="{{ $folder->id }}" data-name="{{ $folder->folder_name }}">✏️</button>
                                <form action="{{ route('admin.folders.destroy', ['id' => $folder->id]) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this folder?')" title="Delete Folder">🗑️</button>
                                </form>
                                @if ($folder->parent_id !== null)
                                <button class="btn btn-sm btn-outline-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#inviteModal"
                                            data-file-id="{{ $folder->id }}"
                                            data-file-name="{{ $folder->folder_name }}"
                                            data-folder-name="{{ $folder->folder_name }}"
                                            title="Share File">
                                        🔗
                                    </button>

                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-container d-flex justify-content-center align-items-center">
                <button class="btn me-2" style="background-color: #3b68b2; color: white;"
                        onclick="changePage(-1)" @if ($folders->onFirstPage()) disabled @endif>Prev</button>
                <input type="number" id="pageInput" class="form-control text-center me-2"
                       min="1" max="{{ $folders->lastPage() }}" value="{{ $folders->currentPage() }}"
                       style="width: 60px;" onkeypress="handleKeyPress(event)">
                <button class="btn" style="background-color: #3b68b2; color: white;"
                        onclick="changePage(1)" @if ($folders->currentPage() == $folders->lastPage()) disabled @endif>Next</button>
            </div>
        </div>
        @endif

        <form id="folderForm" action="{{ route('admin.folders.show') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="folder_id" id="folderId">
            <input type="hidden" name="folder_name" id="folderName">
        </form>
    </div>
</div>

<!-- Edit Folder Modal -->
<div class="modal fade" id="editFolderModal" tabindex="-1" aria-labelledby="editFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.folders.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="folder_id" id="editFolderId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Folder Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="folder_name" id="editFolderName" class="form-control" required placeholder="Enter folder name">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="inviteForm" method="POST" action="{{ route('admin.folder.share') }}">
            @csrf
            <input type="hidden" id="fileId" name="file_id"> <!-- Or folder_id if needed -->

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inviteModalLabel">Share Folder Access</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Guest Email -->
                    <div class="mb-3">
                        <label for="guestEmail" class="form-label">Guest Email</label>
                        <select class="form-select" id="guestEmail" name="guest_email" required>
                            <option value="">Select a guest</option>
                            @foreach ($guest as $g)
                                <option value="{{ $g->email }}">{{ $g->name }} {{ $g->email }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Optional Message -->
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="3" placeholder="Add a message (optional)"></textarea>
                    </div>

                    <!-- Availability -->
                    <div class="mb-3">
                        <label for="availableFrom" class="form-label">Available From</label>
                        <input type="datetime-local" class="form-control" id="availableFrom" name="available_from">
                    </div>

                    <div class="mb-3">
                        <label for="availableUntil" class="form-label">Available Until</label>
                        <input type="datetime-local" class="form-control" id="availableUntil" name="available_until">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" style="background-color: #3b68b2; color: white;">
                        Send Invitation
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Add Child Folder Modal -->
<div class="modal fade" id="addChildFolderModal" tabindex="-1" aria-labelledby="addChildFolderModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.subfolder.add') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Folder</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Folder</label>
            <select name="parent_id" id="parent_id" class="form-select">
              <option value="{{$parentId}}">New Folder</option>
              @foreach($folderOptions as $option)
                <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="folder_name" class="form-label">Folder Name</label>
            <input type="text" name="folder_name" id="folder_name" class="form-control" placeholder="Enter folder name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Create</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editFolderModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const folderId = button.getAttribute('data-id');
            const folderName = button.getAttribute('data-name');

            document.getElementById('editFolderId').value = folderId;
            document.getElementById('editFolderName').value = folderName;
        });

         const modal = document.getElementById('inviteModal');
            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const fileId = button.getAttribute('data-file-id');
                const fileName = button.getAttribute('data-file-name');
                const fileType = button.getAttribute('data-file-type');
                const folderName = button.getAttribute('data-folder-name');

                document.getElementById('fileId').value = fileId;
                document.getElementById('fileName').textContent = fileName;
                document.getElementById('fileType').textContent = fileType;
                document.getElementById('folderName').textContent = folderName;
            });
    });

    function changePage(step) {
        const currentPage = parseInt(document.getElementById('pageInput').value);
        const maxPage = parseInt(document.getElementById('pageInput').max);
        const minPage = parseInt(document.getElementById('pageInput').min);
        const parentId = "{{ request('parent_id') }}";
        const newPage = currentPage + step;

        if (newPage < minPage || newPage > maxPage) return;

        const url = new URL(window.location.href);
        url.searchParams.set('page', newPage);
        if (parentId) url.searchParams.set('parent_id', parentId);
        window.location.href = url.toString();
    }

    function handleKeyPress(event) {
        if (event.key === 'Enter') {
            const page = parseInt(event.target.value);
            const max = parseInt(event.target.max);
            const min = parseInt(event.target.min);
            const parentId = "{{ request('parent_id') }}";

            if (page >= min && page <= max) {
                const url = new URL(window.location.href);
                url.searchParams.set('page', page);
                if (parentId) url.searchParams.set('parent_id', parentId);
                window.location.href = url.toString();
            }
        }
    }
</script>
@endsection
