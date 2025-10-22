@php
    $hasChildren = \App\Models\UserStructureFolder::where('parent_id', $folder->id)->exists();
    $hasFiles = $folderFileMap[$folder->id] ?? false;
    $fileCount = $folderFileCounts[$folder->id] ?? 0;
    $isSubFolder = $folder->parent_id !== null && optional($folder->parent)->parent_id === null;
    $isMainFolder = $folder->parent_id === null;
    $folderHasFiles = $folderFileMap[$folder->id] ?? false;
@endphp

<div class="tree-item" data-folder-id="{{ $folder->id }}">
    <div class="tree-folder" onclick="toggleFolder({{ $folder->id }})">
        <div class="tree-folder-info" style="padding-left: {{ $level * 20 }}px;">
            @if($hasChildren)
                <span class="tree-toggle" id="toggle-{{ $folder->id }}"></span>
            @else
                <span class="tree-toggle" style="visibility: hidden;"></span>
            @endif

            <span class="tree-icon">📁</span>
            <span class="tree-name">
                @if($hasChildren)
                    <a href="{{ route('user.folders.index', ['parent_id' => $folder->id]) }}" class="folder-name">
                        {{ $folder->folder_name }}
                        @if($hasFiles)
                            <span class="file-indicator" title="{{ $fileCount }} file(s) in this folder">📄</span>
                        @endif
                    </a>
                @else
                    <span class="folder-name text-muted" style="cursor: default;">
                        {{ $folder->folder_name }}
                        @if($hasFiles)
                            <span class="file-indicator" title="{{ $fileCount }} file(s) in this folder">📄</span>
                        @endif
                    </span>
                @endif
            </span>
        </div>

        <div class="tree-folder-details">
            @if($hasFiles)
                <span class="tree-folder-badge">{{ $fileCount }} file{{ $fileCount > 1 ? 's' : '' }}</span>
            @endif
            <span class="tree-folder-date">{{ $folder->created_at->format('M d, Y') }}</span>

            @if($isSubFolder || $hasFiles)
                <button class="tree-action-btn" onclick="event.stopPropagation(); viewFiles({{ $folder->id }}, '{{ $folder->folder_name }}')" title="View/Add Files">
                    👁️ View Files
                </button>
            @endif
        </div>
    </div>

    @if($hasChildren)
        <div class="tree-children" id="children-{{ $folder->id }}">
            @php
                // Get child folders from the treeFolders collection
                $childFolders = isset($treeFolders) ? $treeFolders->filter(function($f) use ($folder) {
                    return $f->parent_id == $folder->id;
                }) : collect();
            @endphp
            @foreach($childFolders as $child)
                @include('user.pages.folder.tree-item', ['folder' => $child, 'level' => $level + 1, 'folderFileMap' => $folderFileMap ?? [], 'folderFileCounts' => $folderFileCounts ?? [], 'treeFolders' => $treeFolders ?? collect()])
            @endforeach
        </div>
    @endif
</div>
