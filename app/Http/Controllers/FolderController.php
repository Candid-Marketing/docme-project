<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Folder;
use App\Models\MainFolder;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminFolderTemplate;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    // Define the path to the root folder where your subfolders are located
    protected $rootDir = 'private';  // This will refer to storage/app/private

    public function index(Request $request)
    {
        $parentCode = $request->input('parent_code');

        // Fetch current level folders for display
        $folders = AdminFolderTemplate::where('parent_code', $parentCode)->paginate(5);

        // Fetch top-level folders (those without a parent) to offer as copy options
        $availableTemplates = AdminFolderTemplate::all();

        return view('superadmin.pages.folder.index', compact('folders', 'parentCode', 'availableTemplates'));
    }


    public function show(Request $request, $folder)
    {
        // Correct path to the selected folder
        $folderPath = $this->rootDir . '/' . $folder;
        // Get the subfolders of the selected folder
        $subfolders = $this->listFolders($folderPath);
        // Pass the subfolders to the view along with the parent folder name
        return view('superadmin.pages.folder.index', compact('subfolders', 'folder'));
    }

    public function showSub(Request $request, $folder, $subfolder)
    {
        $folderPath = $this->rootDir . '/' . $folder . '/' . $subfolder;
        $innerfolder = $this->listFolders($folderPath);
        return view('superadmin.pages.folder.index', compact('innerfolder', 'folder', 'subfolder'));
    }

    private function listFolders($dir)
    {
        // Correctly construct the directory path using storage_path()
        $path = storage_path('app/' . $dir);

        // Initialize an empty array to hold folder and file information
        $folders = [];

        // Check if the directory exists
        if (!is_dir($path)) {
            return $folders; // Return empty array if directory doesn't exist
        }

        // Open the directory
        $directory = opendir($path);

        // Loop through the contents of the directory
        while (($file = readdir($directory)) !== false) {
            // Skip the . and .. entries
            if ($file !== '.' && $file !== '..') {
                $folderPath = $dir . '/' . $file;
                $fullPath = storage_path('app/' . $folderPath);

                // Check if the current item is a directory
                if (is_dir($fullPath)) {
                    // Recursively call the function if it is a directory
                    $folders[$file] = [
                        'type' => 'folder',
                        'subfolders' => $this->listFolders($folderPath), // Store nested folders
                        'files' => $this->listFiles($folderPath) // List files in this folder
                    ];
                }
            }
        }

        // Close the directory
        closedir($directory);

        return $folders;
    }

    private function listFiles($folder)
    {
        // Get the path to the current folder
        $path = storage_path('app/' . $folder);

        // Initialize an array to hold the file names
        $files = [];

        // Check if the directory exists
        if (is_dir($path)) {
            // Open the directory
            $directory = opendir($path);

            // Loop through the contents of the directory
            while (($file = readdir($directory)) !== false) {
                // Skip the . and .. entries
                if ($file !== '.' && $file !== '..') {
                    // If the item is a file, add it to the files array
                    $fullPath = $path . '/' . $file;
                    if (is_file($fullPath)) {
                        $files[] = $file; // Add file name
                    }
                }
            }

            // Close the directory
            closedir($directory);
        }

        return $files;
    }


    public function rename_folder(Request $request)
    {

        $request->validate([
            'old_name' => 'required|string',
            'new_name' => 'required|string'
        ]);
        $oldPath = storage_path("app/private/{$request->old_name}");
        $newPath = storage_path("app/private/{$request->new_name}");
        $folder = Folder::where('folder_name', $request->new_name)->first();
        if ($folder) {
            $folder->folder_name = $request->new_name;
            $folder->folder_path = $newPath;
            $folder->updated_by = auth()->user()->email;
            $folder->updated_at = now();
            $folder->save();
        }

        if (!File::exists($oldPath)) {
            return response()->json(['error' => 'Inner folder not found.'], 404);
        }

        // Check if the new inner folder name already exists
        if (File::exists($newPath)) {
            return response()->json(['error' => 'An inner folder with this name already exists.'], 400);
        }

        if (rename($oldPath, $newPath)) {

            return response()->json(['success' => 'Folder renamed successfully.']);
        } else {
            return response()->json(['error' => 'An error occurred while renaming the folder.']);
        }

        File::move($oldPath, $newPath);

        return response()->json(['success' => 'Folder renamed successfully.']);
    }

    public function delete_folder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string'
        ]);

        $folderPath = storage_path("app/private/{$request->folder_name}");

        if (!File::exists($folderPath)) {
            return response()->json(['error' => 'Folder not found.'], 404);
        }

        File::deleteDirectory($folderPath);
        return response()->json(['success' => 'Folder deleted successfully.']);
    }



    public function renameSubfolder(Request $request)
    {
        $request->validate([
            'folder' => 'required|string',
            'old_name' => 'required|string',
            'new_name' => 'required|string'
        ]);


        $oldPath = storage_path("app/private/{$request->folder}/{$request->old_name}");
        $newPath = storage_path("app/private/{$request->folder}/{$request->new_name}");
        $folder = Folder::where('folder_path', $oldPath)->first();
        if ($folder) {
            $folder->folder_name = $request->new_name;
            $folder->folder_path = $newPath;
            $folder->updated_by = auth()->user()->email;
            $folder->updated_at = now();
            $folder->save();
        }

        if (!File::exists($oldPath)) {
            return response()->json(['error' => 'Inner folder not found.'], 404);
        }

        // Check if the new inner folder name already exists
        if (File::exists($newPath)) {
            return response()->json(['error' => 'An inner folder with this name already exists.'], 400);
        }

        // Rename the subfolder
        if (rename($oldPath, $newPath)) {
            return response()->json(['success' => 'Subfolder renamed successfully.']);
        } else {
            return response()->json(['error' => 'An error occurred while renaming the subfolder.'], 500);
        }
    }

    public function deleteSubfolder(Request $request)
    {

        $request->validate([
            'folder' => 'required|string',
            'subfolder' => 'required|string'
        ]);

        $subfolderPath = storage_path("app/private/{$request->folder}/{$request->subfolder}");

        if (!File::exists($subfolderPath)) {
            return response()->json(['error' => 'Subfolder not found.'], 404);
        }

        File::deleteDirectory($subfolderPath);

        return response()->json(['success' => 'Subfolder deleted successfully.']);
    }


    public function renameInnerFolder(Request $request)
    {
        // Validate the request
        $request->validate([
            'folder' => 'required|string',
            'subfolder' => 'required|string',
            'old_name' => 'required|string',
            'new_name' => 'required|string'
        ]);

        // Define folder paths
        $oldPath = storage_path("app/private/{$request->folder}/{$request->subfolder}/{$request->old_name}");
        $newPath = storage_path("app/private/{$request->folder}/{$request->subfolder}/{$request->new_name}");
        $folder = Folder::where('folder_path', $oldPath)->first();
        if ($folder) {
            $folder->folder_name = $request->new_name;
            $folder->folder_path = $newPath;
            $folder->updated_by = auth()->user()->email;
            $folder->updated_at = now();
            $folder->save();
        }
        // Check if the inner folder exists
        if (!File::exists($oldPath)) {
            return response()->json(['error' => 'Inner folder not found.'], 404);
        }

        // Check if the new inner folder name already exists
        if (File::exists($newPath)) {
            return response()->json(['error' => 'An inner folder with this name already exists.'], 400);
        }

        // Rename the inner folder
        if (rename($oldPath, $newPath)) {
            return response()->json(['success' => 'Inner folder renamed successfully.']);
        } else {
            return response()->json(['error' => 'An error occurred while renaming the inner folder.'], 500);
        }
    }

    public function deleteInnerFolder(Request $request)
    {
        // Validate the request
        $request->validate([
            'folder' => 'required|string',
            'subfolder' => 'required|string',
            'innerfolder' => 'required|string'
        ]);

        // Define folder path
        $innerFolderPath = storage_path("app/private/{$request->folder}/{$request->subfolder}/{$request->innerfolder}");
        $folder = Folder::where('folder_path', $request->innerFolderPath)->first();
        if ($folder) {
            $folder->delete();
        }

        // Check if the inner folder exists
        if (!File::exists($innerFolderPath)) {
            return response()->json(['error' => 'Inner folder not found.'], 404);
        }

        // Delete the inner folder
        if (File::deleteDirectory($innerFolderPath)) {
            return response()->json(['success' => 'Inner folder deleted successfully.']);
        } else {
            return response()->json(['error' => 'Failed to delete inner folder. Please check permissions.'], 500);
        }
    }

    public function copyStructure(Request $request)
    {
        $request->validate([
            'source_code' => 'required|exists:admin_folder_templates,unique_code',
            'new_name'    => 'required|string|max:255',
        ]);

        $sourceCode = $request->input('source_code');
        $newName = $request->input('new_name');

        $sourceRoot = AdminFolderTemplate::where('unique_code', $sourceCode)->first();

        // Create the new root structure
        $newRoot = AdminFolderTemplate::create([
            'name' => $newName,
            'unique_code' => $sourceCode,
            'parent_code' => null,
            'description' => $sourceRoot->description,
            'sort_order' => $sourceRoot->sort_order,
            'created_by' => auth()->user()->email,
        ]);

        // Recursively copy children
        $this->cloneFolderChildren($sourceRoot->unique_code, $newRoot->unique_code);

        return redirect()->back()->with('success', 'Folder structure copied successfully.');
    }

    private function cloneFolderChildren($sourceParentCode, $newParentCode)
    {
        $children = AdminFolderTemplate::where('parent_code', $sourceParentCode)->get();

        foreach ($children as $child) {
            $newCode = Str::uuid()->toString();

            $newChild = AdminFolderTemplate::create([
                'name' => $child->name,
                'unique_code' => $newCode,
                'parent_code' => $newParentCode,
                'description' => $child->description,
                'sort_order' => $child->sort_order,
                'created_by' => auth()->user()->email,
            ]);

            $this->cloneFolderChildren($child->unique_code, $newChild->unique_code);
        }
    }


}
