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

    /* Tree View Styles */
    .tree-view {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 0 auto;
        width: 90%;
        display: none;
        max-height: 70vh;
        overflow: hidden;
    }

    .tree-view.active {
        display: block;
    }

    .tree-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-height: 60vh;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 10px;
    }

    /* Custom scrollbar for tree container */
    .tree-container::-webkit-scrollbar {
        width: 8px;
    }

    .tree-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .tree-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .tree-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Scroll indicator */
    .tree-scroll-indicator {
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #007bff, transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        z-index: 10;
    }

    .tree-view:hover .tree-scroll-indicator {
        opacity: 0.3;
    }

    /* Loading state for tree */
    .tree-loading {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100px;
        color: #666;
    }

    .tree-loading::after {
        content: '';
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #007bff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .tree-item {
        margin: 2px 0;
        position: relative;
    }

    .tree-folder {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        user-select: none;
    }

    .tree-folder:hover {
        background-color: #f8f9fa;
    }

    .tree-folder.selected {
        background-color: #e3f2fd;
        border: 1px solid #2196f3;
    }

    .tree-toggle {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #666;
        transition: transform 0.2s ease;
    }

    .tree-toggle.expanded {
        transform: rotate(90deg);
    }

    .tree-toggle:before {
        content: '▶';
    }

    .tree-toggle.expanded:before {
        content: '▼';
    }

    .tree-icon {
        margin-right: 8px;
        font-size: 16px;
        color: #ffa726;
    }

    .tree-name {
        flex: 1;
        font-size: 14px;
        color: #333;
        text-decoration: none;
    }

    .tree-name:hover {
        color: #007bff;
        text-decoration: none;
    }

    .tree-children {
        margin-left: 24px;
        border-left: 1px dashed #ddd;
        padding-left: 12px;
        display: none;
    }

    .tree-children.expanded {
        display: block;
    }

    .tree-actions {
        display: none;
        margin-left: auto;
        gap: 4px;
    }

    .tree-folder:hover .tree-actions {
        display: flex;
    }

    .tree-action-btn {
        background: none;
        border: none;
        padding: 4px 6px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 12px;
        color: #666;
        transition: all 0.2s ease;
    }

    .tree-action-btn:hover {
        background: #e9ecef;
        color: #333;
    }

    .view-toggle-container {
        text-align: right;
        margin-bottom: 20px;
        margin-right: 20px;
    }

    .view-toggle-btn {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 8px 16px;
        margin: 0 2px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .view-toggle-btn.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }

    .view-toggle-btn:hover:not(.active) {
        background: #e9ecef;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .tree-view {
            width: 95%;
            max-height: 65vh;
        }

        .tree-container {
            max-height: 55vh;
        }
    }

    @media (max-width: 992px) {
        .tree-view {
            width: 98%;
            padding: 15px;
            max-height: 60vh;
        }

        .tree-container {
            max-height: 50vh;
        }

        .tree-folder {
            padding: 6px 10px;
        }

        .tree-name {
            font-size: 13px;
        }

        .tree-icon {
            font-size: 14px;
        }
    }

    @media (max-width: 768px) {
        .view-toggle-container {
            text-align: center;
            margin-bottom: 15px;
            margin-right: 0;
        }

        .view-toggle-btn {
            padding: 6px 12px;
            font-size: 14px;
        }

        .tree-view {
            width: 100%;
            margin: 0;
            padding: 10px;
            max-height: 55vh;
            border-radius: 8px;
        }

        .tree-container {
            max-height: 45vh;
            padding-right: 5px;
        }

        .tree-folder {
            padding: 5px 8px;
        }

        .tree-name {
            font-size: 12px;
        }

        .tree-icon {
            font-size: 12px;
        }

        .tree-toggle {
            width: 14px;
            height: 14px;
            font-size: 10px;
        }

        .tree-children {
            margin-left: 20px;
            padding-left: 8px;
        }

        .tree-action-btn {
            padding: 2px 4px;
            font-size: 10px;
        }
    }

    @media (max-width: 576px) {
        .main {
            position: static !important;
            width: 100% !important;
            height: auto !important;
            overflow-y: auto !important;
            padding-bottom: 80px;
            margin-top: 45px;
        }

        body, html {
            overflow-x: hidden;
        }

        .tree-view {
            max-height: 50vh;
            padding: 8px;
        }

        .tree-container {
            max-height: 40vh;
        }

        .tree-folder {
            padding: 4px 6px;
            flex-wrap: wrap;
        }

        .tree-name {
            font-size: 11px;
            word-break: break-word;
        }

        .tree-icon {
            font-size: 11px;
        }

        .tree-toggle {
            width: 12px;
            height: 12px;
            font-size: 9px;
        }

        .tree-children {
            margin-left: 16px;
            padding-left: 6px;
        }

        .tree-actions {
            margin-top: 4px;
            margin-left: 0;
            width: 100%;
            justify-content: flex-start;
        }

        .tree-action-btn {
            padding: 2px 3px;
            font-size: 9px;
            margin-right: 2px;
        }

        .custom-breadcrumb {
            margin-left: 10px;
            font-size: 12px;
        }

        .add-folder-btn {
            text-align: center;
            margin-right: 0;
            margin-bottom: 15px;
        }
    }

    @media (max-width: 480px) {
        .tree-view {
            max-height: 45vh;
            padding: 5px;
        }

        .tree-container {
            max-height: 35vh;
        }

        .tree-folder {
            padding: 3px 4px;
        }

        .tree-name {
            font-size: 10px;
        }

        .tree-children {
            margin-left: 12px;
            padding-left: 4px;
        }
    }

    /* Tooltip Styles */
    .folder-tooltip {
        position: relative;
        cursor: help;
    }

    .folder-tooltip .tooltip-content {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        z-index: 1000;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: white;
        text-align: center;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        line-height: 1.4;
        white-space: normal;
        width: 300px;
        max-width: 90vw;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: opacity 0.3s ease, visibility 0.3s ease;
        word-wrap: break-word;
    }

    .folder-tooltip .tooltip-content::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }

    .folder-tooltip:hover .tooltip-content {
        visibility: visible;
        opacity: 1;
    }

    /* Ensure tooltips work well with tree view */
    .tree-folder.folder-tooltip {
        cursor: help;
    }

    .tree-folder.folder-tooltip:hover {
        background-color: #f8f9fa;
    }

    /* File indicator styles */
    .file-indicator {
        margin-left: 8px;
        font-size: 14px;
        color: #28a745;
        cursor: help;
        transition: transform 0.2s ease;
    }

    .file-indicator:hover {
        transform: scale(1.1);
    }

    /* Mobile responsive tooltip */
    @media (max-width: 768px) {
        .folder-tooltip .tooltip-content {
            width: 250px;
            font-size: 13px;
            padding: 10px 12px;
        }

        .file-indicator {
            font-size: 12px;
            margin-left: 6px;
        }
    }

    @media (max-width: 480px) {
        .folder-tooltip .tooltip-content {
            width: 200px;
            font-size: 12px;
            padding: 8px 10px;
        }

        .file-indicator {
            font-size: 11px;
            margin-left: 4px;
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

        <!-- View Toggle Buttons -->
        <div class="view-toggle-container">
            <button class="view-toggle-btn active" onclick="toggleView('table')" id="tableToggle">
                📋 Table View
            </button>
            <button class="view-toggle-btn" onclick="toggleView('tree')" id="treeToggle">
                🌳 Tree View
            </button>
        </div>

       <div class="add-folder-btn">
                <!-- Trigger a modal when inside a child folder -->
                <button class="btn" style="background-color: #3b68b2; color: white;" data-bs-toggle="modal" data-bs-target="#addChildFolderModal">
                    ➕ Add Subfolder
                </button>
                <!-- Link to parent-level folder creation route -->
                <a href="{{ route('admin.file') }}">
                    <button class="btn" style="background-color: #3b68b2; color: white;">➕ Add Folder</button>
                </a>
        </div>


        <!-- Table View -->
        @if($folders->count() > 0)
        <div class="table-container table-responsive" id="tableView">
            <table>
                <thead>
                    <tr>
                        <th>Folder Name</th>
                        <th>Files</th>
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
                            $hasFiles = \App\Models\File::where('folder_id', $folder->id)->exists();
                            $fileCount = \App\Models\File::where('folder_id', $folder->id)->count();
                            $folderName = strtolower($folder->folder_name);
                            $tooltipText = '';

                            // Define tooltip descriptions based on folder names
                            if (strpos($folderName, 'general information') !== false) {
                                $tooltipText = 'This section is for the following records – Bank Loan Application, Personal Identification Documents, Personal Financial Information, e.g Tax returns, Statement of position, Reports, Personal Insurances, Grants, HECS Debt.';
                            } elseif (strpos($folderName, 'income') !== false) {
                                $tooltipText = 'This section is for the following records – Employment Details, Government Income, or other Non-Investment income.';
                            } elseif (strpos($folderName, 'assets') !== false) {
                                $tooltipText = 'This section is for the following records – Residential Property, Investment Properties, Properties Under Construction, Companies, Trusts, Shares, Cryptocurrency, Superannuation, and other significant assets such as Boats, Jewellery, Art, etc.';
                            } elseif (strpos($folderName, 'liabilities') !== false) {
                                $tooltipText = 'This section is for the following records – Home Loan, Investment Loan/s, Personal Loan/s, Car Loan/s, and Credit Cards';
                            }
                            @endphp

                            @if($hasChild)
                                <a href="{{ route('admin.folders.index', ['parent_id' => $folder->id]) }}"
                                   class="folder-name {{ !empty($tooltipText) ? 'folder-tooltip' : '' }}"
                                   @if(!empty($tooltipText)) title="{{ $tooltipText }}" @endif>
                                    📁 {{ $folder->folder_name }}
                                    @if($hasFiles)
                                        <span class="file-indicator" title="{{ $fileCount }} file(s) in this folder">📄</span>
                                    @endif
                                </a>
                            @else
                                <span class="folder-name text-muted {{ !empty($tooltipText) ? 'folder-tooltip' : '' }}"
                                      style="cursor: {{ !empty($tooltipText) ? 'help' : 'default' }};"
                                      @if(!empty($tooltipText)) title="{{ $tooltipText }}" @endif>
                                    📁 {{ $folder->folder_name }}
                                    @if($hasFiles)
                                        <span class="file-indicator" title="{{ $fileCount }} file(s) in this folder">📄</span>
                                    @endif
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($hasFiles)
                                <span class="badge bg-success">{{ $fileCount }} file{{ $fileCount > 1 ? 's' : '' }}</span>
                            @else
                                <span class="text-muted">No files</span>
                            @endif
                        </td>
                        <td>{{ $folder->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <div class="btn-group">
                                @if ($folder->parent_id !== null)
                                    <a href="{{ route('admin.folders.view.files', ['id' => $folder->id]) }}" class="btn btn-sm btn-outline-primary" title="View/Add Files">👁️</a>
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

        <!-- Tree View -->
        <div class="tree-view" id="treeView">
            <div class="tree-container" id="treeContainer">
                @if(isset($allFolders) && $allFolders->count() > 0)
                    @foreach($allFolders as $folder)
                        @include('admin.pages.folder.tree-item', ['folder' => $folder, 'level' => 0])
                    @endforeach
                @else
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <div style="font-size: 48px; margin-bottom: 16px;">📁</div>
                        <h3>No folders found</h3>
                        <p>Create your first folder to get started.</p>
                    </div>
                @endif
            </div>
            <div class="tree-scroll-indicator"></div>
        </div>

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
                        <input type="datetime-local" class="form-control" id="availableFrom" name="available_from" value="{{ date('Y-m-d\TH:i') }}">
                    </div>

                    <div class="mb-3">
                        <label for="availableUntil" class="form-label">Available Until</label>
                        <input type="datetime-local" class="form-control" id="availableUntil" name="available_until" value="{{ date('Y-m-d\T23:59', strtotime('+1 day')) }}">
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
    // View Toggle Functionality
    function toggleView(viewType) {
        const tableView = document.getElementById('tableView');
        const treeView = document.getElementById('treeView');
        const tableToggle = document.getElementById('tableToggle');
        const treeToggle = document.getElementById('treeToggle');

        if (viewType === 'table') {
            tableView.style.display = 'block';
            treeView.classList.remove('active');
            tableToggle.classList.add('active');
            treeToggle.classList.remove('active');
            localStorage.setItem('folderView', 'table');
        } else {
            tableView.style.display = 'none';
            treeView.classList.add('active');
            tableToggle.classList.remove('active');
            treeToggle.classList.add('active');
            localStorage.setItem('folderView', 'tree');
        }
    }

    // Tree Toggle Functionality
    function toggleFolder(folderId) {
        const toggle = document.getElementById('toggle-' + folderId);
        const children = document.getElementById('children-' + folderId);

        if (toggle && children) {
            if (toggle.classList.contains('expanded')) {
                toggle.classList.remove('expanded');
                children.classList.remove('expanded');
            } else {
                toggle.classList.add('expanded');
                children.classList.add('expanded');

                // Smooth scroll to the expanded folder if it's not fully visible
                setTimeout(() => {
                    const folderElement = document.querySelector(`[data-folder-id="${folderId}"]`);
                    if (folderElement) {
                        const treeContainer = document.querySelector('.tree-container');
                        const containerRect = treeContainer.getBoundingClientRect();
                        const elementRect = folderElement.getBoundingClientRect();

                        // Check if element is not fully visible in the container
                        if (elementRect.bottom > containerRect.bottom || elementRect.top < containerRect.top) {
                            folderElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'nearest'
                            });
                        }
                    }
                }, 100);
            }
        }
    }

    // Action Functions
    function viewFiles(folderId) {
        window.location.href = `{{ route('admin.folders.view.files', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', folderId);
    }

    function editFolder(folderId, folderName) {
        document.getElementById('editFolderId').value = folderId;
        document.getElementById('editFolderName').value = folderName;
        const modal = new bootstrap.Modal(document.getElementById('editFolderModal'));
        modal.show();
    }

    function deleteFolder(folderId) {
        if (confirm('Are you sure you want to delete this folder?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ route('admin.folders.destroy', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', folderId);

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function shareFolder(folderId, folderName) {
        document.getElementById('fileId').value = folderId;
        const modal = new bootstrap.Modal(document.getElementById('inviteModal'));
        modal.show();
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function () {
        // Set initial view based on localStorage or default to table
        const savedView = localStorage.getItem('folderView') || 'table';
        toggleView(savedView);

        // Initialize tree view scroll handling
        initializeTreeScroll();

        // Modal event listeners
        const editModal = document.getElementById('editFolderModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (button) {
                const folderId = button.getAttribute('data-id');
                const folderName = button.getAttribute('data-name');
                document.getElementById('editFolderId').value = folderId;
                document.getElementById('editFolderName').value = folderName;
            }
        });

        const inviteModal = document.getElementById('inviteModal');
        inviteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (button) {
                const fileId = button.getAttribute('data-file-id');
                const fileName = button.getAttribute('data-file-name');
                const folderName = button.getAttribute('data-folder-name');
                document.getElementById('fileId').value = fileId;
            }
        });
    });

    // Initialize tree scroll functionality
    function initializeTreeScroll() {
        const treeContainer = document.getElementById('treeContainer');
        if (treeContainer) {
            // Add scroll event listener
            treeContainer.addEventListener('scroll', function() {
                const scrollTop = this.scrollTop;
                const scrollHeight = this.scrollHeight;
                const clientHeight = this.clientHeight;

                // Update scroll indicator opacity based on scroll position
                const scrollIndicator = document.querySelector('.tree-scroll-indicator');
                if (scrollIndicator) {
                    const scrollPercentage = scrollTop / (scrollHeight - clientHeight);
                    scrollIndicator.style.opacity = Math.min(0.3 + (scrollPercentage * 0.4), 0.7);
                }
            });

            // Add touch support for mobile
            let startY = 0;
            treeContainer.addEventListener('touchstart', function(e) {
                startY = e.touches[0].clientY;
            });

            treeContainer.addEventListener('touchmove', function(e) {
                const currentY = e.touches[0].clientY;
                const diffY = startY - currentY;

                // Prevent default scrolling if we're at the top or bottom
                if ((this.scrollTop <= 0 && diffY < 0) ||
                    (this.scrollTop >= this.scrollHeight - this.clientHeight && diffY > 0)) {
                    e.preventDefault();
                }
            }, { passive: false });
        }
    }

    // Pagination functions
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
