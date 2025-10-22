<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Folder;
use App\Models\MainFolder;
use App\Models\Subfolder;
use Illuminate\Support\Facades\Auth;
use App\Models\InnerFolder;
use App\Models\ChildFolder;
use App\Models\InnerchildFolder;
use App\Models\User;
use App\Models\File;
use App\Models\LastLevelFolder;
use App\Models\Invitation;
use App\Models\UserStructureFolder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\FolderInvitation;


class GuestFolderController extends Controller
{
   public function index(Request $request)
{
    $parentId = $request->input('parent_id');
    $perPage = 5;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();

    $isSwitched = session()->has('original_user_id');
    $effectiveUser = $isSwitched
        ? \App\Models\User::find(session('original_user_id'))
        : Auth::user();
    // dd($effectiveUser->activeRole()->id);
    // ✅ 1. Get direct file invitations
    $fileInvites = Invitation::with('file')
        ->where(function ($query) use ($effectiveUser) {
            if ($effectiveUser->activeRole()->id === 3) {
                $query->where('guest_email', $effectiveUser->email);
            } else {
                $query->whereHas('file', function ($q) use ($effectiveUser) {
                    $q->where('inviter_id', $effectiveUser->id);
                });
            }
        })
        ->where(function ($query) {
            $query->whereNull('available_until')
                ->orWhere('available_until', '>', now());
        })
        ->get();
    // ✅ 2. Get folder invitations
    $folderInvites = \App\Models\FolderInvitation::with('folder')
        ->where(function ($query) use ($effectiveUser) {
            if ($effectiveUser->activeRole()->id === 3) {
                $query->where('guest_email', $effectiveUser->email);
            } else {
                $query->where('inviter_id', $effectiveUser->id);
            }
        })
        ->where(function ($query) {
            $query->whereNull('available_until')
                ->orWhere('available_until', '>', now());
        })
        ->get();

    // ✅ 3. Get all descendant folder IDs from folder invitations
    $invitedFolderIds = [];
    foreach ($folderInvites as $inv) {
        if ($inv->folder) {
            $invitedFolderIds[] = $inv->folder->id;
            $invitedFolderIds = array_merge($invitedFolderIds, $this->getAllDescendantFolderIds($inv->folder->id));
        }
    }
    $invitedFolderIds = array_unique($invitedFolderIds);

    // ✅ 4. Combine files from folder invites and direct invites
    $filesFromFolders = \App\Models\File::whereIn('folder_id', $invitedFolderIds)->get();
    $filesFromDirectInvites = $fileInvites->pluck('file')->filter();

    $allFiles = $filesFromFolders->merge($filesFromDirectInvites)->unique('id');

    // ✅ 5. Get all parent folders from all invited files
    $allFolders = collect();
    foreach ($allFiles as $file) {
        $folder = $file->folder ?? null;
        while ($folder) {
            $allFolders->push($folder);
            $folder = $folder->parent_id
                ? \App\Models\UserStructureFolder::find($folder->parent_id)
                : null;
        }
    }
    $allFolders = $allFolders->unique('id');

    // ✅ 6. Filter by current parent folder
    $filteredFolders = $allFolders->filter(function ($folder) use ($parentId) {
        return $folder->parent_id == $parentId;
    })->values();

    // ✅ 7. Determine if folders have shared files in themselves or their descendants
    $folderFileMap = [];
    $folderFileCounts = [];
    foreach ($filteredFolders as $folder) {
        $descendantFolderIds = $this->getAllDescendantFolderIds($folder->id);
        $descendantFolderIds[] = $folder->id;

        // Count files that user has access to in this folder
        $accessibleFiles = $allFiles->filter(function ($file) use ($folder) {
            return $file->folder_id == $folder->id;
        });

        $hasFiles = $accessibleFiles->count() > 0;
        $fileCount = $accessibleFiles->count();

        $folderFileMap[$folder->id] = $hasFiles;
        $folderFileCounts[$folder->id] = $fileCount;
    }


    // ✅ 8. Paginate filtered folders
    $paginatedFolders = new \Illuminate\Pagination\LengthAwarePaginator(
        $filteredFolders->forPage($currentPage, $perPage),
        $filteredFolders->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    // ✅ 9. Get all descendant folders for tree view
    $treeFolders = collect();
    foreach ($filteredFolders as $folder) {
        $treeFolders->push($folder);
        $this->addDescendantFolders($folder, $treeFolders, $allFolders);
    }

    // ✅ 9.5. Calculate file counts for all tree folders (including descendants)
    foreach ($treeFolders as $folder) {
        if (!isset($folderFileCounts[$folder->id])) {
            // Count files that user has access to in this folder
            $accessibleFiles = $allFiles->filter(function ($file) use ($folder) {
                return $file->folder_id == $folder->id;
            });

            $hasFiles = $accessibleFiles->count() > 0;
            $fileCount = $accessibleFiles->count();

            $folderFileMap[$folder->id] = $hasFiles;
            $folderFileCounts[$folder->id] = $fileCount;
        }
    }

    // ✅ 10. For tree view, only show root level folders (those that match current parentId)
    $treeRootFolders = $treeFolders->filter(function ($folder) use ($parentId) {
        return $folder->parent_id == $parentId;
    });

    return view('user.pages.folder.index', [
        'folders' => $paginatedFolders,
        'parentId' => $parentId,
        'folderFileMap' => $folderFileMap,
        'folderFileCounts' => $folderFileCounts,
        'treeFolders' => $treeFolders,
        'treeRootFolders' => $treeRootFolders
    ]);
}







    public function show(Request $request)
    {
        $name = $request->folder_name;
        $folders = Invitation::with([
            'file.sub',
        ])->where('guest_email', auth()->user()->email)
          ->get()
          ->filter(function ($invitation) use ($request) {
            return $invitation->file && $invitation->file->sub && $invitation->file->sub->folder_id == $request->folder_id;
        })
        ->unique(function ($invitation) {
            return $invitation->file->sub->sub_folder_name ?? null; // adjust to your actual unique field
        });
        return view('user.pages.subfolder.index', compact('folders','name'));
    }

    public function inner_folder(Request $request)
    {
        $name = $request->folder_name;

        $folders = Invitation::with([
            'file.main',
            'file.inner',
        ])->where('guest_email', auth()->user()->email) // assuming guests are logged in
          ->get()
          ->filter(function ($invitation) use ($request) {
            return $invitation->file && $invitation->file->inner && $invitation->file->inner->folder_id == $request->folder_id;
            })
            ->unique(function ($invitation) {
                return $invitation->file->inner->inner_folder_name ?? null; // adjust to your actual unique field
            });

          $main_folder = optional($folders->first()?->file?->main)->folder_name ?? 'Main Folder';

          return view('user.pages.inner.index', compact('folders','name', 'main_folder'));
    }

    public function child_folder(Request $request)
    {
        $name = $request->folder_name;
        $folders = Invitation::with([
            'file.main',
            'file.child',
        ])->where('guest_email', auth()->user()->email) // assuming guests are logged in
          ->get()
          ->filter(function ($invitation) use ($request) {
            return $invitation->file && $invitation->file->child && $invitation->file->child->folder_id == $request->folder_id ;
            })
            ->unique(function ($invitation) {
                return $invitation->file->child->child_folder_name ?? null;
            });

          $main_folder = optional($folders->first()?->file?->main)->folder_name ?? 'Main Folder';

          return view('user.pages.child.index', compact('folders','name', 'main_folder'));

    }

    public function innerchild_folder(Request $request)
    {
        $name = $request->folder_name;
        $folders = Invitation::with([
            'file.main',
            'file.innerChild',
        ])->where('guest_email', auth()->user()->email) // assuming guests are logged in
          ->get()
          ->filter(function ($invitation) use ($request) {
            return $invitation->file && $invitation->file->innerChild && $invitation->file->innerChild->folder_id == $request->folder_id ;
            })
            ->unique(function ($invitation) {
                return $invitation->file->innerChild->innerchild_folder_name ?? null;
            });
          $main_folder = optional($folders->first()?->file?->main)->folder_name ?? 'Main Folder';
          $hasInnerChild = $folders->contains(function ($invitation) {
                return $invitation->file && $invitation->file->innerChild;
            });

            if ($hasInnerChild) {
                return view('user.pages.innerchild.index', compact('folders', 'name', 'main_folder'));
            } else {
                $now = Carbon::now('Asia/Manila');

                $files = Invitation::with(['file', 'file.child', 'file.innerChild'])
                    ->where('guest_email', auth()->user()->email)
                    ->where(function ($query) use ($now) {
                        $query->whereNull('available_from')
                              ->orWhere('available_from', '<=', $now);
                    })
                    ->where(function ($query) use ($now) {
                        $query->whereNull('available_until')
                              ->orWhere('available_until', '>=', $now);
                    })
                    ->whereHas('file.child', function ($query) use ($request) {
                        $query->where('id', $request->folder_id);
                    }) // Only get files whose 'child' matches folder_id
                    ->whereDoesntHave('file.innerChild', function ($query) {
                        $query->whereNull('id');
                    })
                    ->paginate(4);
                return view('user.pages.table.index', compact('files', 'name'));
            }
    }

    public function last_folder(Request $request)
    {
        $name = $request->folder_name;
        $folders = Invitation::with([
            'file.main',
            'file.lastChild',
        ])->where('guest_email', auth()->user()->email) // assuming guests are logged in
          ->get()
          ->filter(function ($invitation) use ($request) {
            return $invitation->file && $invitation->file->lastChild && $invitation->file->lastChild->folder_id == $request->folder_id ;
            })
            ->unique(function ($invitation) {
                return $invitation->file->lastChild->last_folder_name ?? null;
            });

          $main_folder = optional($folders->first()?->file?->main)->folder_name ?? 'Main Folder';
          $hasInnerChild = $folders->contains(function ($invitation) {
                return $invitation->file && $invitation->file->lastChild;
            });

            if ($hasInnerChild) {
                return view('user.pages.lastchild.index', compact('folders', 'name', 'main_folder'));
            } else {
                $now = Carbon::now('Australia/Sydney');


                $files = Invitation::with(['file', 'file.innerChild', 'file.lastChild'])
                    ->where('guest_email', auth()->user()->email)
                    ->where(function ($query) use ($now) {
                        $query->whereNull('available_from')
                              ->orWhere('available_from', '<=', $now);
                    })
                    ->where(function ($query) use ($now) {
                        $query->whereNull('available_until')
                              ->orWhere('available_until', '>=', $now);
                    })
                    ->whereHas('file.innerChild', function ($query) use ($request) {
                        $query->where('id', $request->folder_id);
                    }) // Only get files whose 'child' matches folder_id
                    ->whereDoesntHave('file.lastChild', function ($query) {
                        $query->whereNull('id');
                    })
                    ->paginate(4);
                return view('user.pages.table.subindex', compact('files', 'name'));
            }
    }

 public function last_folder_table(Request $request)
{
    $name = $request->folder_name;
    $folderId = $request->folder_id;
    $now = Carbon::now('Australia/Sydney');



    // Step 1: Get breadcrumbs
    $breadcrumbs = collect();
    $current = $folderId;
    while ($current) {
        $folder = UserStructureFolder::find($current);
        if ($folder) {
            $breadcrumbs->prepend($folder);
            $current = $folder->parent_id;
        } else {
            break;
        }
    }

    // Step 2: Get current folder and all descendants
    $mainFolder = UserStructureFolder::findOrFail($folderId);
    $folderIds = [$mainFolder->id];
    $folderIds = array_merge($folderIds, $this->getDescendantFolderIds($mainFolder));
    $folderIds = array_unique($folderIds);

    // Step 3: Check if user is switched
    $isSwitched = session()->has('original_user_id');
    $originalUserId = session('original_user_id');

    // Step 4: Get files directly shared to user
    $directInviteFiles = Invitation::with('file')
        ->when(!$isSwitched, function ($query) use ($now) {
            $query->where('guest_email', auth()->user()->email);
        })
        ->when($isSwitched, function ($query) use ($originalUserId) {
            $query->whereHas('file', function ($q) use ($originalUserId) {
                $q->where('inviter_id', $originalUserId);
            });
        })
        ->where(function ($query) use ($now) {
            $query->whereNull('available_from')->orWhere('available_from', '<=', $now);
        })
        ->where(function ($query) use ($now) {
            $query->whereNull('available_until')->orWhere('available_until', '>=', $now);
        })
        ->whereHas('file', function ($query) use ($folderIds) {
            $query->whereIn('folder_id', $folderIds);
        })
        ->get()
        ->pluck('file')
        ->filter();

    // Step 5: Get files via folder invitations
    $folderInviteIds = \App\Models\FolderInvitation::query()
        ->when(!$isSwitched, function ($query) {
            $query->where('guest_email', auth()->user()->email);
        })
        ->when($isSwitched, function ($query) use ($originalUserId) {
            $query->where('inviter_id', $originalUserId);
        })
        ->where(function ($query) use ($now) {
            $query->whereNull('available_from')->orWhere('available_from', '<=', $now);
        })
        ->where(function ($query) use ($now) {
            $query->whereNull('available_until')->orWhere('available_until', '>=', $now);
        })
        ->pluck('folder_id');

    $folderSharedIds = collect();
    foreach ($folderInviteIds as $fid) {
        $folder = \App\Models\UserStructureFolder::find($fid);
        if ($folder) {
            $folderSharedIds->push($folder->id);
            $folderSharedIds = $folderSharedIds->merge($this->getDescendantFolderIds($folder));
        }
    }
    $folderSharedIds = $folderSharedIds->unique();

    $filesFromFolders = \App\Models\File::with('uploader')
        ->whereIn('folder_id', $folderSharedIds)
        ->whereIn('folder_id', $folderIds) // 🔁 Ensure files are inside the folder being viewed
        ->get();

    // Step 6: Combine all accessible files and paginate manually
    $allFiles = $directInviteFiles->merge($filesFromFolders)->unique('id');
    $page = LengthAwarePaginator::resolveCurrentPage();
    $perPage = 10;
    $currentItems = $allFiles->slice(($page - 1) * $perPage, $perPage)->values();

    $files = new LengthAwarePaginator(
        $currentItems,
        $allFiles->count(),
        $perPage,
        $page,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('user.gen.table.lastindex', compact('files', 'name', 'breadcrumbs'));
}




    private function getDescendantFolderIds(UserStructureFolder $folder)
    {
        $ids = [];

        foreach ($folder->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getDescendantFolderIds($child));
        }

        return $ids;
    }


   public function viewPrivateFile($folder, $filename)
    {
        $path = "private/{$folder}/files/{$filename}";
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $extension = Str::lower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!Storage::exists($path)) {
            abort(404, 'File not found.');
        }

        if (!auth()->check()) {
            abort(403, 'Unauthorised: You must be logged in to view this file.');
        }

        $email = auth()->user()->email;

        // Step 1: Find file record
        $file = File::where('file_name', $name)->first();
        if (! $file) {
            abort(404, 'File record not found.');
        }

        $hasAccess = false;

        // Step 2: Check if the user has a direct invitation to the file
        $invite = Invitation::where('guest_email', $email)
            ->where('file_id', $file->id)
            ->first();

        if ($invite) {
            $hasAccess = true;

            // Mark invite as accepted
            if (is_null($invite->accepted_at)) {
                $invite->accepted_at = now();
                $invite->save();
            }
        }

        // Step 3: If no direct file invite, check for folder-level invitation
        if (! $hasAccess) {
            $user = auth()->user();

            $folderInvites = \App\Models\FolderInvitation::query()
                ->where('guest_email', $email)
                ->where(function ($q) {
                    $q->whereNull('available_until')->orWhere('available_until', '>=', now());
                })
                ->get();

            // ✅ Build full list of folder IDs from invitations and descendants
            $invitedFolderIds = collect();
            foreach ($folderInvites as $folderInvite) {
                if ($folderInvite->folder) {
                    $invitedFolderIds->push($folderInvite->folder->id);
                    $descendants = $this->getDescendantFolderIds($folderInvite->folder);
                    $invitedFolderIds = $invitedFolderIds->merge($descendants);
                }
            }

            $invitedFolderIds = $invitedFolderIds->unique();

            if ($invitedFolderIds->contains($file->folder_id)) {
                $hasAccess = true;
            }
        }

        if (! $hasAccess) {
            abort(403, 'Access denied: You are not invited to view this file.');
        }

        // ✅ Document viewer for viewable types
        $viewableTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
        if (in_array($extension, $viewableTypes)) {
            $signedUrl = URL::temporarySignedRoute(
                'user.files.proxy.public',
                now()->addMinutes(5),
                [
                    'folder' => $folder,
                    'filename' => $filename,
                    'user' => auth()->id()
                ]
            );

            return view('files.secure-document-viewer', [
                'signedUrl' => $signedUrl,
                'filename'  => $filename
            ]);
        }

        // ✅ Fallback for images, videos, plain text, etc.
        $inlineTypes = ['jpeg', 'jpg', 'png', 'gif', 'webp', 'txt', 'mp4', 'webm', 'ogg', 'svg', 'html'];
        $fullPath = storage_path("app/{$path}");
        $mime = mime_content_type($fullPath) ?? 'application/octet-stream';
        $disposition = in_array($extension, $inlineTypes) ? 'inline' : 'attachment';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => "{$disposition}; filename=\"" . basename($filename) . "\""
        ]);
    }



    public function publicFileProxy($folder, $filename)
    {
        $path = "private/{$folder}/files/{$filename}";

        if (!Storage::exists($path)) {
            abort(404);
        }

        return response()->file(storage_path("app/{$path}"), [
            'Content-Type' => mime_content_type(storage_path("app/{$path}")),
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }


    public function getAllDescendantFolderIds($folderId)
    {
        $ids = [$folderId];
        $children = UserStructureFolder::where('parent_id', $folderId)->pluck('id');

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getAllDescendantFolderIds($childId));
        }

        return $ids;
    }

    private function addDescendantFolders($parentFolder, $treeFolders, $allFolders)
    {
        $children = $allFolders->filter(function ($folder) use ($parentFolder) {
            return $folder->parent_id == $parentFolder->id;
        });

        foreach ($children as $child) {
            $treeFolders->push($child);
            $this->addDescendantFolders($child, $treeFolders, $allFolders);
        }
    }


   public function proxyFile($folder, $filename, $userId)
    {
        // ⛔ SECURITY CHECK: Ensure the logged-in user matches the signed user ID
        if (!auth()->check() || auth()->id() != $userId) {
            abort(403, 'Unauthorised access.');
        }

        $path = "private/{$folder}/files/{$filename}";

        if (!Storage::exists($path)) {
            abort(404, 'File not found.');
        }

        $fullPath = storage_path("app/{$path}");
        $mime = mime_content_type($fullPath) ?? 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . basename($filename) . '"'
        ]);
    }





}
