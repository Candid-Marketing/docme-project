@extends('user.dashboard')

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
        background-color: #f8f9fa !important;
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

    .tree-item {
        margin: 0;
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
        position: relative;
        margin: 2px 0;
    }

    .tree-folder:hover {
        background-color: #f8f9fa;
    }

    .tree-folder-info {
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
    }

    .tree-folder-details {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-left: auto;
        font-size: 12px;
        color: #666;
    }

    .tree-folder-badge {
        background: #e9ecef;
        color: #495057;
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 11px;
        white-space: nowrap;
    }

    .tree-folder-date {
        color: #6c757d;
        font-size: 11px;
        white-space: nowrap;
    }

    .tree-folder-disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .tree-folder-disabled:hover {
        background-color: transparent;
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
        margin-left: 20px;
        position: relative;
        display: none;
    }

    .tree-children.expanded {
        display: block;
    }

    .tree-children::before {
        content: '';
        position: absolute;
        left: -10px;
        top: 0;
        bottom: 0;
        width: 1px;
        background: #ddd;
    }

    .tree-children .tree-item:last-child::after {
        content: '';
        position: absolute;
        left: -10px;
        top: 0;
        bottom: 50%;
        width: 1px;
        background: #fff;
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
        background: #007bff;
        border: 1px solid #007bff;
        padding: 4px 8px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 11px;
        color: white;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
        margin-left: 8px;
    }

    .tree-action-btn:hover {
        background: #0056b3;
        border-color: #0056b3;
        color: white;
        text-decoration: none;
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

    .pagination-container {
        text-align: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .pagination input,
    .pagination button {
        margin: 5px;
    }

    @media (max-width: 768px) {
        .tree-folder {
            padding: 6px 8px;
            font-size: 14px;
        }

        .tree-folder-details {
            gap: 8px;
            font-size: 11px;
        }

        .tree-folder-badge {
            font-size: 10px;
            padding: 1px 4px;
        }

        .tree-folder-date {
            font-size: 10px;
        }

        .tree-action-btn {
            padding: 3px 6px;
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

        .tree-folder {
            flex-direction: column;
            align-items: flex-start;
            padding: 8px;
        }

        .tree-folder-info {
            width: 100%;
            margin-bottom: 4px;
        }

        .tree-folder-details {
            width: 100%;
            justify-content: flex-start;
            margin-left: 0;
        }

        .tree-children {
            margin-left: 15px;
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
                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.folders.index') }}">Folders</a></li>
                @foreach($breadcrumbs as $crumb)
                    <li class="breadcrumb-item">
                        <a href="{{ route('user.folders.index', ['parent_id' => $crumb->id]) }}">
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

        <!-- Table View -->
        <div class="table-container" id="tableView">
            <table class="table">
                <thead>
                    <tr>
                        <th>Folder Name</th>
                        <th>Files</th>
                        <th>Created Date</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($folders as $folder)
                        <tr>
                            <td>
                                @php
                                    $hasChildren = \App\Models\UserStructureFolder::where('parent_id', $folder->id)->exists();
                                    $hasFiles = $folderFileMap[$folder->id] ?? false;
                                    $fileCount = $folderFileCounts[$folder->id] ?? 0;
                                @endphp

                                @if($hasChildren)
                                    <a href="{{ route('user.folders.index', ['parent_id' => $folder->id]) }}" class="folder-name">
                                        📁 {{ $folder->folder_name }}
                                        @if($hasFiles)
                                            <span class="file-indicator" title="{{ $fileCount }} file(s) in this folder">📄</span>
                                        @endif
                                    </a>
                                @else
                                    <span class="folder-name text-muted" style="cursor: default;">
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
                                @php
                                     $isMainFolder = $folder->parent_id === null;
                                    $hasFiles = $folderFileMap[$folder->id] ?? false;
                                 $isSubFolder = $folder->parent_id !== null && optional($folder->parent)->parent_id === null;

                                @endphp

                                @if($isSubFolder || $hasFiles)
                                        <form id="viewForm{{ $folder->id }}" action="{{ route('user.last-table.show') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                                            <input type="hidden" name="folder_name" value="{{ $folder->folder_name }}">
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="View/Add Files">
                                                👁️ View Files
                                            </button>
                                        </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No folders available</td>
                        </tr>
                    @endforelse
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

        <!-- Tree View -->
        <div class="tree-view" id="treeView">
            <div class="tree-container" id="treeContainer">
                @if(isset($treeRootFolders) && $treeRootFolders->count() > 0)
                    @foreach($treeRootFolders as $folder)
                        @include('user.pages.folder.tree-item', ['folder' => $folder, 'level' => 0, 'folderFileMap' => $folderFileMap ?? [], 'folderFileCounts' => $folderFileCounts ?? [], 'treeFolders' => $treeFolders ?? collect()])
                    @endforeach
                @else
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <div style="font-size: 48px; margin-bottom: 16px;">📁</div>
                        <h3>No folders found</h3>
                        <p>No shared folders available at this time.</p>
                    </div>
                @endif
            </div>
        </div>
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
            localStorage.setItem('userFolderView', 'table');
        } else {
            tableView.style.display = 'none';
            treeView.classList.add('active');
            tableToggle.classList.remove('active');
            treeToggle.classList.add('active');
            localStorage.setItem('userFolderView', 'tree');
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
    function viewFiles(folderId, folderName) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("user.last-table.show") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const folderIdInput = document.createElement('input');
        folderIdInput.type = 'hidden';
        folderIdInput.name = 'folder_id';
        folderIdInput.value = folderId;

        const folderNameInput = document.createElement('input');
        folderNameInput.type = 'hidden';
        folderNameInput.name = 'folder_name';
        folderNameInput.value = folderName;

        form.appendChild(csrfToken);
        form.appendChild(folderIdInput);
        form.appendChild(folderNameInput);
        document.body.appendChild(form);
        form.submit();
    }

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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function () {
        // Set initial view based on localStorage or default to table
        const savedView = localStorage.getItem('userFolderView') || 'table';
        toggleView(savedView);
    });
</script>
@endsection
