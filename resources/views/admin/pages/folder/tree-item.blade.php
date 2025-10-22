@php
    $hasChildren = $folder->childrenRecursive && $folder->childrenRecursive->count() > 0;
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

<div class="tree-item" data-folder-id="{{ $folder->id }}">
    <div class="tree-folder {{ !empty($tooltipText) ? 'folder-tooltip' : '' }}"
         onclick="toggleFolder({{ $folder->id }})"
         @if(!empty($tooltipText)) title="{{ $tooltipText }}" @endif>
        @if($hasChildren)
            <span class="tree-toggle" id="toggle-{{ $folder->id }}"></span>
        @else
            <span class="tree-toggle" style="visibility: hidden;"></span>
        @endif

        <span class="tree-icon">📁</span>
        <span class="tree-name">
            {{ $folder->folder_name }}
            @if($hasFiles)
                <span class="file-indicator" title="{{ $fileCount }} file(s) in this folder">📄</span>
            @endif
        </span>

        <div class="tree-actions">
            @if ($folder->parent_id !== null)
                <button class="tree-action-btn" onclick="event.stopPropagation(); viewFiles({{ $folder->id }})" title="View/Add Files">👁️</button>
            @endif
            <button class="tree-action-btn" onclick="event.stopPropagation(); editFolder({{ $folder->id }}, '{{ $folder->folder_name }}')" title="Edit">✏️</button>
            <button class="tree-action-btn" onclick="event.stopPropagation(); deleteFolder({{ $folder->id }})" title="Delete">🗑️</button>
            @if ($folder->parent_id !== null)
                <button class="tree-action-btn" onclick="event.stopPropagation(); shareFolder({{ $folder->id }}, '{{ $folder->folder_name }}')" title="Share">🔗</button>
            @endif
        </div>
    </div>

    @if($hasChildren)
        <div class="tree-children" id="children-{{ $folder->id }}">
            @foreach($folder->childrenRecursive as $child)
                @include('admin.pages.folder.tree-item', ['folder' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
