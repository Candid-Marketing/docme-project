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
use Illuminate\Pagination\Paginator;
use App\Models\UserStructureFolder;
use App\Services\FolderSyncService;
use App\Models\FolderInvitation;
use App\Mail\GuestFolderInviteMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Invitation;


class UserFolderController extends Controller
{
   // Define the path to the root folder where your subfolders are located
   protected $rootDir = 'private';  // This will refer to storage/app/private

   public function index(Request $request, FolderSyncService $syncService)
    {

        $userId = Auth::id();
        $parentId = $request->input('parent_id');

        $folders = UserStructureFolder::where('user_id', $userId)
            ->when($parentId, fn($q) => $q->where('parent_id', $parentId), fn($q) => $q->whereNull('parent_id'))
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $files = File::where('folder_id', $parentId)->paginate(5);

        // Fetch all folders for dropdown (not paginated)
        $allFolders = UserStructureFolder::where('user_id', $userId)
        ->where('parent_id', $parentId) // ✅ This is correct
        ->get();



     $folderOptions = $allFolders->map(function ($folder) {
        return ['id' => $folder->id, 'name' => $folder->folder_name];
    });
   $guest = Auth::user()
    ->usersAdded() // relationship: users added via pivot
    ->whereHas('roles', function ($query) {
        $query->where('role_id', 3);
    })
    ->paginate(10);

        return view('admin.pages.folder.index', compact('folders', 'parentId', 'files', 'folderOptions','guest'));
    }

    private function buildFolderOptions($folders, $prefix = '')
    {
        $options = [];
        foreach ($folders as $folder) {
            $options[] = ['id' => $folder->id, 'name' => $prefix . $folder->folder_name];
            if ($folder->childrenRecursive && $folder->childrenRecursive->count()) {
                $options = array_merge($options, $this->buildFolderOptions($folder->childrenRecursive, $prefix . '↳ '));
            }
        }
        return $options;
    }



    public function shared_files()
    {
        $files = \DB::table('files')
        ->join('invitations', 'files.id', '=', 'invitations.file_id')
        ->where('files.created_by', Auth::user()->email)
        ->join('users', 'invitations.guest_email', '=', 'users.email')
        ->select('files.*','users.first_name as fn','users.last_name as ln','invitations.available_from','invitations.available_until', 'invitations.accepted_at') // select only files columns, adjust as needed
        ->paginate(5);
        return view('admin.gen.table.lastindex',compact('files'));
    }

   public function show(Request $request, $folder)
   {
       // Correct path to the selected folder
       $folderPath = $this->rootDir . '/' . $folder;
       // Get the subfolders of the selected folder
       $subfolders = $this->listFolders($folderPath);
       // Pass the subfolders to the view along with the parent folder name
       return view('admin.pages.folder.index', compact('subfolders', 'folder'));
   }

   public function folder_show(Request $request)
   {
       $name = $request->folder_name;
       $folders = Subfolder::where('folder_id', $request->folder_id)->where('sub_folder_created_by',Auth::user()->email)->paginate(10);
       $id_main = $request->folder_id;
       return view('admin.gen.folder.show', compact('folders', 'name','id_main'));
   }

   public function inner_show(Request $request)
    {
        $main_id = $request->main_id;
        $main_folder = MainFolder::where('id', $main_id)->pluck('folder_name')->first();
        $name = $request->folder_name;
        $folders = InnerFolder::where('folder_id', $request->folder_id)->where('inner_folder_created_by',Auth::user()->email)->get();
        $id_main = $request->folder_id;
        return view('admin.gen.folder.inner', compact('folders', 'name','id_main','main_id','main_folder'));
    }

    public function child_show(Request $request)
    {
        $main_id = $request->main_id;
        $sub_id = $request->sub_id;
        $main_folder = MainFolder::where('id', $main_id)->pluck('folder_name')->first();
        $name = $request->folder_name;
        $folders = ChildFolder::where('folder_id', $request->folder_id)->get();
        $id_main = $request->folder_id;
        return view('admin.gen.folder.child', compact('folders', 'name','id_main','main_id','sub_id','main_folder'));
    }

    public function innerchild_show(Request $request)
    {
        $main_id = $request->main_id;
        $sub_id = $request->sub_id;
        $inner_id =$request->inner_id;
        $main_folder = MainFolder::where('id', $main_id)->pluck('folder_name')->first();
        $name = $request->folder_name;
        $folders = InnerchildFolder::where('folder_id', $request->folder_id)->get();
        $id_main = $request->folder_id;
        if ($folders->isNotEmpty()) {
            // If there are folders, return the innerchild view
            return view('admin.gen.folder.innerchild', compact('folders', 'name','id_main','main_id','sub_id','inner_id','main_folder'));
        }
        else
        {
            $guest = User::where('user_status','3')->get();
            $files = File::where('main_id', $main_id)
            ->where('sub_id', $sub_id)
            ->where('inner_id', $inner_id)
            ->where('child_id',$request->folder_id)
            ->paginate(4);
            return view('admin.gen.table.index',compact('guest','files','folders', 'name','id_main','main_id','sub_id','inner_id','main_folder'));
        }
    }

    public function files_search(Request $request)
    {
        $query = $request->query('query');
        $files = File::where(function($queryBuilder) use ($query) {
            $queryBuilder->where('file_name', 'like', '%' . $query . '%')
                ->orWhere('file_type', 'like', '%' . $query . '%')
                ->orWhere('folder_name', 'like', '%' . $query . '%')
                ->orWhere('created_by', 'like', '%' . $query . '%');
        })
        ->where('main_id', $request->main_id)
        ->where('sub_id', $request->sub_id)
        ->where('inner_id', $request->inner_id)
        ->where('child_id', $request->child_id)
        ->get();

        return response()->json(['files' => $files]);

    }

    public function innerchild_folder_search(Request $request)
    {
        $query = $request->query('query');
        $files = File::where(function($queryBuilder) use ($query) {
            $queryBuilder->where('file_name', 'like', '%' . $query . '%')
                ->orWhere('file_type', 'like', '%' . $query . '%')
                ->orWhere('folder_name', 'like', '%' . $query . '%')
                ->orWhere('created_by', 'like', '%' . $query . '%');
        })
        ->where('main_id', $request->main_id)
        ->where('sub_id', $request->sub_id)
        ->where('inner_id', $request->inner_id)
        ->where('child_id', $request->child_id)
        ->where('innerchild_id', $request->innerchild_id)
        ->get();

        return response()->json(['files' => $files]);
    }

    public function lastfolder_show(Request $request)
    {
        $main_id = $request->main_id;
        $sub_id = $request->sub_id;
        $inner_id =$request->inner_id;
        $child_id = $request->child_id;
        $main_folder = MainFolder::where('id', $main_id)->pluck('folder_name')->first();
        $name = $request->folder_name;
        $folders = LastLevelFolder::where('folder_id', $request->folder_id)->get();
        $id_main = $request->folder_id;
        if ($folders->isNotEmpty()) {
            // If there are folders, return the innerchild view
            return view('admin.gen.folder.lastfolder', compact('folders', 'name','child_id','id_main','main_id','sub_id','inner_id','main_folder'));
        }
        else
        {
            $files = File::where('main_id', $main_id)
            ->where('sub_id', $sub_id)
            ->where('inner_id', $inner_id)
            ->where('child_id',$child_id)
            ->where('innerchild_id',$request->folder_id)
            ->paginate(4);
            $guest = User::where('user_status','3')->get();
            return view('admin.gen.table.subindex',compact('guest','files','folders', 'name','id_main','main_id','sub_id','inner_id','child_id','main_folder'));
        }

    }

    public function last_folder_table (Request $request)
    {
        $main_id = $request->main_id;
        $sub_id = $request->sub_id;
        $main_folder = MainFolder::where('id', $main_id)->pluck('folder_name')->first();
        $name = $request->folder_name;
        $inner_id = $request->inner_id;
        $child_id = $request->child_id;
        $innerchild_id = $request->innerchild_id;
        $last_id = $request->folder_id;
        $files = File::where('main_id', $main_id)
        ->where('sub_id', $sub_id)
        ->where('inner_id', $inner_id)
        ->where('child_id',$child_id)
        ->where('innerchild_id',$innerchild_id)
        ->where('lastchild_id',$last_id)
        ->paginate(4);
        $guest = User::where('user_status', '=', 3)->get();
        return view('admin.gen.table.lastindex',compact('guest','files', 'name','innerchild_id','child_id','main_id','sub_id','inner_id','main_folder','last_id'));
    }

    public function last_file_update(Request $request)
    {
        $updateFile = File::findOrFail($request->id);
        $updateFile->file_name =$request->file_name;
        $updateFile->updated_by = Auth::user()->email;
        $updateFile->save();

        return response()->json(['message' => 'File updated successfully.']);
    }

    public function files_update(Request $request)
    {
        $updateFile = File::findOrFail($request->id);
        $updateFile->file_name =$request->file_name;
        $updateFile->updated_by = Auth::user()->email;
        $updateFile->save();

        return response()->json(['message' => 'File updated successfully.']);
    }

    public function files_upload(Request $request)
    {
       // Validate the uploaded file
       $request->validate([
        'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,png,jpeg,jpg|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('file_upload');

        // Get the original file name
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Get the file's size and type
        $fileSize = $file->getSize(); // Size in bytes
        $fileType = $file->getMimeType(); // File MIME type (e.g., pdf, jpeg, etc.)

        // Define the file storage path
        $main = MainFolder::findOrFail($request->main_id);
        $sub = Subfolder::findOrFail($request->sub_id);
        $inner = InnerFolder::findOrFail($request->inner_id);
        $child = ChildFolder::findOrFail($request->folder_id);
        // Define old and new folder paths
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name . DIRECTORY_SEPARATOR . $child->child_folder_name;

        // Store file metadata in the database
        $savedFile = File::create([
            'main_id' =>$request->main_id,
            'sub_id' =>$request->sub_id,
            'inner_id'=>$request->inner_id,
            'child_id'=>$request->folder_id,
            'file_name' => $fileName,
            'file_path' => $oldPath,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'folder_name' => $child->child_folder_name,
            'created_by' => Auth::user()->email, // Assuming you're using Laravel's Auth system
            'updated_by' =>  Auth::user()->email, // You can update this later when the file is updated
        ]);

        return response()->json([
            'message' => 'File uploaded successfully!',
            'file' => $savedFile
        ], 200);
    }

    public function last_file_delete(Request $request)
    {
        $file = File::findOrFail($request->id);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }

    public function files_delete(Request $request)
    {
        $file = File::findOrFail($request->id);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }

    public function innerchild_folder_delete(Request $request)
    {
        $file = File::findOrFail($request->id);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }


    public function innerchild_folder_update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:files,id',
            'file_name' => 'required|string|max:255'
        ]);

        $fileRecord = File::findOrFail($request->id);
        $oldPath = $fileRecord->file_path;
        $userId = Auth::id();

        $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
        $newFileNameWithExt = $request->file_name . '.' . $extension;
        $newPath = "private/{$userId}/files/{$newFileNameWithExt}";

        // Rename the file in storage
        if (Storage::exists($oldPath)) {
            Storage::move($oldPath, $newPath);
        } else {
            return response()->json(['message' => 'Original file not found in storage.'], 404);
        }

        // Update database record
        $fileRecord->file_name = $request->file_name;
        $fileRecord->file_path = $newPath;
        $fileRecord->updated_by = Auth::user()->email;
        $fileRecord->save();

        return response()->json(['message' => 'File name updated successfully.']);
    }


   public function innerchild_folder_upload(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,png,jpeg,jpg|max:2048',
        ]);

        $file = $request->file('file_upload');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize(); // size in bytes
        $fileType = $file->getMimeType();
        $user = Auth::user();

        // Step 1: Check storage quota
        $plan = $user->plan;
        $totalUsed = File::where('created_by', $user->email)->sum('file_size');
        $maxLimit = $plan->storage_limit ?? null;

        if ($maxLimit && ($totalUsed + $fileSize > $maxLimit)) {
            return response()->json([
                'error' => 'Upload failed. You’ve exceeded your storage limit of ' . number_format($maxLimit / (1024 ** 3), 2) . ' GB.',
            ], 422);
        }

        // Step 2: Store file
        $storagePath = "private/{$user->id}/files";
        $file->storeAs($storagePath, $fileName);
        $storedPath = "{$storagePath}/{$fileName}";

        // Step 3: Record in DB
        $savedFile = File::create([
            'folder_id' => $request->folder_id,
            'file_name' => pathinfo($fileName, PATHINFO_FILENAME),
            'file_path' => $storedPath,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'folder_name' => optional($request)->folder_name ?? 'Unknown Folder',
            'created_by' => $user->email,
            'updated_by' => $user->email,
        ]);

        return response()->json([
            'message' => 'File uploaded successfully!',
            'file' => $savedFile
        ], 200);
    }



    public function last_file_upload(Request $request)
    {
       // Validate the uploaded file
       $request->validate([
        'file_upload' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,png,jpeg,jpg|max:2048',
        ]);


        // Get the uploaded file
        $file = $request->file('file_upload');

        // Get the original file name
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Get the file's size and type
        $fileSize = $file->getSize(); // Size in bytes
        $fileType = $file->getMimeType(); // File MIME type (e.g., pdf, jpeg, etc.)

        // Define the file storage path
        $main = MainFolder::findOrFail($request->main_id);
        $sub = Subfolder::findOrFail($request->sub_id);
        $inner = InnerFolder::findOrFail($request->inner_id);
        $child = ChildFolder::findOrFail($request->child_id);
        $innerchild = InnerchildFolder::findOrFail($request->innerchild_id);
        $last = LastLevelFolder::findOrFail($request->last_id);
        // Define old and new folder paths
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name . DIRECTORY_SEPARATOR . $child->child_folder_name . DIRECTORY_SEPARATOR .  $innerchild->innerchild_folder_name  . DIRECTORY_SEPARATOR .  $last->last_folder_name;

        // Store file metadata in the database
        $savedFile = File::create([
            'main_id' =>$request->main_id,
            'sub_id' =>$request->sub_id,
            'inner_id'=>$request->inner_id,
            'child_id'=>$request->child_id,
            'innerchild_id'=> $request->innerchild_id,
            'lastchild_id' => $request->last_id,
            'file_name' => $fileName,
            'file_path' => $oldPath,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'folder_name' => $last->last_folder_name,
            'created_by' => Auth::user()->email, // Assuming you're using Laravel's Auth system
            'updated_by' =>  Auth::user()->email, // You can update this later when the file is updated
        ]);

        return response()->json([
            'message' => 'File uploaded successfully!',
            'file' => $savedFile
        ], 200);
    }

    public function last_file_search(Request $request)
    {
        $query = $request->query('query');
        $files = File::where(function($queryBuilder) use ($query) {
            $queryBuilder->where('file_name', 'like', '%' . $query . '%')
                ->orWhere('file_type', 'like', '%' . $query . '%')
                ->orWhere('folder_name', 'like', '%' . $query . '%')
                ->orWhere('created_by', 'like', '%' . $query . '%');
        })
        ->where('main_id', $request->main_id)
        ->where('sub_id', $request->sub_id)
        ->where('inner_id', $request->inner_id)
        ->where('child_id', $request->child_id)
        ->where('innerchild_id', $request->innerchild_id)
        ->where('lastchild_id', $request->last_id)
        ->get();

        return response()->json(['files' => $files]);
    }

    public function viewFiles($id)
    {
        $guest = Auth::user()
        ->usersAdded() // relationship: users added via pivot
        ->whereHas('roles', function ($query) {
            $query->where('role_id', 3);
        })
        ->paginate(10);


        // Start from Applicant 1
        $folder = UserStructureFolder::findOrFail($id);

        // Ensure it's not the top-level folder
        if (!$folder->parent_id) {
            abort(403, 'This view is only allowed for child folders like Applicant 1.');
        }

        // Get current + all children IDs
        $folderIds = [$folder->id];
        $folderIds = array_merge($folderIds, $this->getDescendantFolderIds($folder));

        // Get files inside Applicant 1 and all its children
        $files = File::whereIn('folder_id', $folderIds)->paginate(5);

        return view('admin.gen.table.subindex', compact('folder', 'files', 'guest'));
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


    public function update(Request $request)
    {
        $request->validate([
            'folder_id' => 'required|integer|exists:user_structure_folders,id',
            'folder_name' => 'required|string|max:255',
        ]);

        $folder = UserStructureFolder::findOrFail($request->folder_id);
        $folder->folder_name = $request->folder_name;
        $folder->save();

        return redirect()->back()->with('success', 'Folder updated successfully!');
    }

    public function destroy($id)
    {
        $folder = UserStructureFolder::findOrFail($id);

        // Optional: check if folder has children or files before deletion
        // if ($folder->children()->exists()) return redirect()->back()->with('error', 'Folder is not empty.');

        $folder->delete();

        return redirect()->back()->with('success', 'Folder deleted successfully.');
    }

    public function viewFile($id)
    {
        $file = File::findOrFail($id);

        // You can protect it so that only the uploader can view it
        if ($file->created_by !== Auth::user()->email) {
            abort(403, 'Unauthorized');
        }

        // Create the full path
        $path = storage_path('app/' . $file->file_path);

        // Check if file exists
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function addFolder (Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:user_structure_folders,id',
        ]);

        $addFolders = UserStructureFolder::create([
            'user_id' => Auth::id(),
            'folder_name' => $request->folder_name,
            'parent_id' => $request->parent_id,
            'linked_admin_code' => null,
        ]);

        return redirect()->route('admin.folders.index')->with('success', 'Folder created successfully.');
    }

     public function addSubFolder (Request $request)
    {

        $request->validate([
            'folder_name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:user_structure_folders,id',
        ]);

        $addFolders = UserStructureFolder::create([
            'user_id' => Auth::id(),
            'folder_name' => $request->folder_name,
            'parent_id' => $request->parent_id,
            'linked_admin_code' => null,
        ]);

       return redirect()->route('admin.folders.index', ['parent_id' => $request->parent_id])
        ->with('success', 'Folder created successfully.');

    }

    public function inviteFolder(Request $request)
    {

        $request->validate([
            'file_id' => 'required|exists:user_structure_folders,id',
            'guest_email' => 'required|email',
            'message' => 'nullable|string|max:1000',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after_or_equal:available_from',
        ]);

          $user = auth()->user();
            $plan = $user->plan;

            // Step 1: Count both file and folder invitations for this month
            $fileInvites = Invitation::where('inviter_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $folderInvites = FolderInvitation::where('inviter_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            $totalInvites = $fileInvites + $folderInvites;


            // Step 2: Check if user exceeded share limit
        if ($plan && !is_null($plan->share_limit) && $totalInvites >= $plan->share_limit) {

                return response()->json([
                    'success' => false,
                    'error' => 'You have reached your guest sharing limit for this month.',
                ], 403);
            }

       $invite = FolderInvitation::create([
            'folder_id' => $request->file_id,
            'inviter_id' => auth()->id(),
            'guest_email' => $request->guest_email,
            'message' => $request->message,
            'available_from' => $request->available_from ?? now(),
            'available_until' => $request->available_until ?? now()->addDays(7),
            'token' => \Str::uuid(),
        ]);

         Mail::to($invite->guest_email)->send(new \App\Mail\GuestFolderInviteMail($invite));

        return redirect()->route('admin.folders.index', ['parent_id' => $request->parent_id])
        ->with('success', 'Folder shared successfully.');
    }
}
