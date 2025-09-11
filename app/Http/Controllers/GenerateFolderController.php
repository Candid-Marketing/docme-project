<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainFolder;
use Illuminate\Support\Facades\Auth;
use App\Models\Subfolder;
use Illuminate\Support\Facades\File;
use App\Models\InnerFolder;
use App\Models\ChildFolder;
use App\Models\InnerchildFolder;
use App\Models\LastLevelFolder;
use App\Models\AdminFolderTemplate;

class GenerateFolderController extends Controller
{
    public function addMainFolder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_desc' => 'nullable|string|max:500',
            'parent_code' => 'nullable|string|exists:admin_folder_templates,unique_code',
        ]);

        // Determine depth of current folder
        $depth = 1; // Default is main folder

        if (!empty($request->parent_code)) {
            $parentCode = $request->parent_code;

            // Recursive depth calculation
            while ($parentCode) {
                $parent = AdminFolderTemplate::where('unique_code', $parentCode)->first();
                if (!$parent) break;

                $depth++;
                $parentCode = $parent->parent_code;
            }
        }

        $prefix = "F{$depth}";

        // Get latest code for this prefix
        $lastCode = AdminFolderTemplate::where('unique_code', 'like', "{$prefix}-%")
            ->orderByDesc('id')
            ->value('unique_code');

        $nextNumber = 1;
        if ($lastCode) {
            $lastNumber = (int) str_replace("{$prefix}-", '', $lastCode);
            $nextNumber = $lastNumber + 1;
        }

        $uniqueCode = "{$prefix}-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT); // e.g., F3-001

        // Create new folder
        AdminFolderTemplate::create([
            'name'         => $request->folder_name,
            'unique_code'  => $uniqueCode,
            'parent_code'  => $request->parent_code,
            'description'  => $request->folder_desc,
            'sort_order'   => null,
            'created_by'   => Auth::user()->email,
        ]);

        return redirect()->back()->with('success', 'Folder added successfully!');
    }


    public function folder_show(Request $request)
    {
        $name = $request->folder_name;
        $folders = Subfolder::where('folder_id', $request->folder_id)->paginate(5);
        $id_main = $request->folder_id;
        return view('superadmin.gen.folder.show', compact('folders', 'name','id_main'));
    }


    public function addSubFolder(Request $request)
    {
        $request->validate([
            'sub_folder_name' => 'required|string|max:255',
            'sub_folder_desc' => 'nullable|string|max:500',
        ]);

        $main = MainFolder::find($request->main_id);

        if (!$main) {
            return response()->json(['message' => 'Main folder not found.'], 404);
        }

        // Define folder path in storage
        $folderPath = $main->folder_path . DIRECTORY_SEPARATOR . $request->sub_folder_name;

        // Check if the folder already exists
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true); // Create the folder

            // Save folder details to the database
            $subFolder = Subfolder::create([
                'folder_id' => $request->main_id,
                'sub_folder_name' => $request->sub_folder_name,
                'sub_folder_description' => $request->sub_folder_desc,
                'sub_folder_path' => $folderPath,
                'sub_folder_status' => 1,
                'sub_folder_visibility' => 1,
                'sub_folder_access' => 0,
                'sub_folder_created_by' => $main->folder_created_by,
                'sub_folder_updated_by' => $main->folder_created_by
            ]);

            return response()->json([
                'message' => 'Subfolder created successfully!',
                'sub_folder' => $subFolder
            ], 201);
        } else {
            return response()->json(['message' => 'Folder already exists'], 409);
        }
    }


    public function inner_show(Request $request)
    {
        $main_id = $request->main_id;
        $main_folder = MainFolder::where('id', $main_id)->pluck('folder_name')->first();
        $name = $request->folder_name;
        $folders = InnerFolder::where('folder_id', $request->folder_id)->paginate(5);
        $id_main = $request->folder_id;
        return view('superadmin.gen.folder.inner', compact('folders', 'name','id_main','main_id','main_folder'));
    }

    public function child_show(Request $request)
    {
        $main_id = $request->main_id;
        $sub_id = $request->sub_id;
        $main_folder = MainFolder::where('id', $main_id)->pluck('folder_name')->first();
        $name = $request->folder_name;
        $sub_folder = Subfolder::where('id', $sub_id)->pluck('sub_folder_name')->first();
        $folders = ChildFolder::where('folder_id', $request->folder_id)->paginate(5);
        $id_main = $request->folder_id;
        return view('superadmin.gen.folder.child', compact('folders', 'name','id_main','main_id','sub_id','main_folder','sub_folder'));
    }

    public function lastfolder_show(Request $request)
    {
        $main_id = $request->main_id;
        $sub_id = $request->sub_id;
        $inner_id =$request->inner_id;
        $child_id = $request->child_id;
        $main_folder = MainFolder::where('id', $main_id)->pluck('folder_name')->first();
        $name = $request->folder_name;
        $folders = LastLevelFolder::where('folder_id', $request->folder_id)->paginate(5);
        $id_main = $request->folder_id;
        return view('superadmin.gen.folder.last', compact('folders', 'name','id_main','main_id','sub_id','inner_id','main_folder'));
    }

    public function innerchild_show(Request $request)
    {
        $main_id = $request->main_id;
        $sub_id = $request->sub_id;
        $inner_id =$request->inner_id;
        $main_folder = MainFolder::where('id', $main_id)->pluck('folder_name')->first();
        $sub_folder = Subfolder::where('id', $sub_id)->pluck('sub_folder_name')->first();
        $inner_folder = InnerFolder::where('id',$inner_id)->pluck('inner_folder_name')->first();
        $name = $request->folder_name;
        $folders = InnerchildFolder::where('folder_id', $request->folder_id)->paginate(5);
        $id_main = $request->folder_id;
        return view('superadmin.gen.folder.innerchild', compact('folders', 'name','sub_folder','inner_folder','id_main','main_id','sub_id','inner_id','main_folder'));
    }


    public function addInnerFolder(Request $request)
    {
        $request->validate([
            'inner_folder_name' => 'required|string|max:255',
            'inner_folder_desc' => 'nullable|string|max:500',
        ]);
        $main = MainFolder::find($request->main_id);
        $sub = Subfolder::find($request->sub_id);

        if (!$sub) {
            return response()->json(['message' => 'Sub folder not found.'], 404);
        }
        $basePath = storage_path('app/private');
        $folderPath = $basePath . DIRECTORY_SEPARATOR .$main->folder_name . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $request->inner_folder_name;
        // Check if the folder already exists
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true); // Create the folder

            // Save folder details to the database
            $subFolder = InnerFolder::create([
                'folder_id' => $request->sub_id,
                'inner_folder_name' => $request->inner_folder_name,
                'inner_folder_description' => $request->inner_folder_desc,
                'inner_folder_path' => $folderPath,
                'inner_folder_status' => 1,
                'inner_folder_visibility' => 1,
                'inner_folder_access' => 0,
                'inner_folder_created_by' => $main->folder_created_by,
                'inner_folder_updated_by' => $main->folder_created_by
            ]);
            return response()->json([
                'message' => 'Inner Folder created successfully!',
                'sub_folder' => $subFolder
            ], 201);
        } else {
            return response()->json(['message' => 'Folder already exists'], 409);
        }

    }

    public function addChildFolder(Request $request)
    {
        $request->validate([
            'inner_folder_name' => 'required|string|max:255',
            'inner_folder_desc' => 'nullable|string|max:500',
        ]);
        $main = MainFolder::find($request->main_id);
        $sub = Subfolder::find($request->sub_id);
        $inner = InnerFolder::find($request->child_id);
        if (!$sub) {
            return response()->json(['message' => 'Sub folder not found.'], 404);
        }
        $basePath = storage_path('app/private');
        $folderPath = $basePath . DIRECTORY_SEPARATOR .$main->folder_name . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name . DIRECTORY_SEPARATOR . $request->inner_folder_name;
        // Check if the folder already exists
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true); // Create the folder

            // Save folder details to the database
            $subFolder = ChildFolder::create([
                'folder_id' => $request->child_id,
                'child_folder_name' => $request->inner_folder_name,
                'child_folder_description' => $request->inner_folder_desc,
                'child_folder_path' => $folderPath,
                'child_folder_status' => 1,
                'child_folder_visibility' => 1,
                'child_folder_access' => 0,
                'child_folder_created_by' => $main->folder_created_by,
                'child_folder_updated_by' => $main->folder_created_by
            ]);

            return response()->json([
                'message' => 'Inner Folder created successfully!',
                'sub_folder' => $subFolder
            ], 201);
        } else {
            return response()->json(['message' => 'Folder already exists'], 409);
        }

    }

    public function addInnerChildFolder(Request $request)
    {
        $request->validate([
            'inner_folder_name' => 'required|string|max:255',
            'inner_folder_desc' => 'nullable|string|max:500',
        ]);
        $main = MainFolder::find($request->main_id);
        $sub = Subfolder::find($request->sub_id);
        $inner = InnerFolder::find($request->inner_id);
        $child = ChildFolder::find($request->child_id);
        if (!$sub) {
            return response()->json(['message' => 'Sub folder not found.'], 404);
        }
        $basePath = storage_path('app/private');
        $folderPath = $basePath . DIRECTORY_SEPARATOR .$main->folder_name . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name . DIRECTORY_SEPARATOR . $child->child_folder_name . DIRECTORY_SEPARATOR . $request->inner_folder_name;
        // Check if the folder already exists
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true); // Create the folder

            // Save folder details to the database
            $subFolder = InnerchildFolder::create([
                'folder_id' => $request->child_id,
                'innerchild_folder_name' => $request->inner_folder_name,
                'innerchild_folder_description' => $request->inner_folder_desc,
                'innerchild_folder_path' => $folderPath,
                'innerchild_folder_status' => 1,
                'innerchild_folder_visibility' => 1,
                'innerchild_folder_access' => 0,
                'innerchild_folder_created_by' => $main->folder_created_by,
                'innerchild_folder_updated_by' => $main->folder_created_by
            ]);

            return response()->json([
                'message' => 'Inner Folder created successfully!',
                'sub_folder' => $subFolder
            ], 201);
        } else {
            return response()->json(['message' => 'Folder already exists'], 409);
        }

    }

    public function editMainFolder(Request $request)
    {
        $request->validate([
            'folder_name' => 'required|string|max:255',
            'folder_desc' => 'nullable|string|max:500',
        ]);

        $main = AdminFolderTemplate::findOrFail($request->folder_id);

            $main->name = $request->folder_name;
            $main->description = $request->folder_desc;
            $main->save();
            return response()->json([
                'message' => 'Folder renamed successfully!',
                'updated_folder' => $main
            ], 200);
    }

    public function editSubFolder(Request $request)
    {
        $request->validate([
            'sub_folder_name' => 'required|string|max:255',
            'sub_folder_desc' => 'nullable|string|max:500',
        ]);

        $main = MainFolder::findOrFail($request->main_folder_id);
        $sub = Subfolder::findOrFail($request->folder_id);

        // Define old and new folder paths
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name;
        $newPath = $main->folder_path . DIRECTORY_SEPARATOR . $request->sub_folder_name;
        if (File::exists($newPath)) {
            return response()->json(['message' => 'A folder with this name already exists.'], 409);
        }

        // Rename the folder
        if (File::exists($oldPath)) {
            File::move($oldPath, $newPath);
            $sub->sub_folder_name = $request->sub_folder_name;
            $sub->sub_folder_description = $request->sub_folder_desc;
            $sub->sub_folder_path = $newPath;
            $sub->save();
        } else {
            return response()->json(['message' => 'Old folder does not exist'], 404);
        }

        return response()->json([
            'message' => 'Folder renamed successfully!',
            'updated_folder' => $main
        ], 200);
    }

    public function editInnerFolder(Request $request)
    {
        $request->validate([
            'sub_folder_name' => 'required|string|max:255',
            'sub_folder_desc' => 'nullable|string|max:500',
        ]);

        $main = MainFolder::findOrFail($request->main_id);
        $sub = Subfolder::findOrFail($request->sub_id);
        $inner = InnerFolder::findOrFail($request->folder_id);
        // Define old and new folder paths
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name;
        $newPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR .$request->sub_folder_name; ;
        if (File::exists($newPath)) {
            return response()->json(['message' => 'A folder with this name already exists.'], 409);
        }

        // Rename the folder
        if (File::exists($oldPath)) {
            File::move($oldPath, $newPath);
            $inner->inner_folder_name = $request->sub_folder_name;
            $inner->inner_folder_description = $request->sub_folder_desc;
            $inner->inner_folder_path = $newPath;
            $inner->save();
        } else {
            return response()->json(['message' => 'Old folder does not exist'], 404);
        }

        return response()->json([
            'message' => 'Folder renamed successfully!',
            'updated_folder' => $inner
        ], 200);
    }


    public function editInnerChildFolder(Request $request)
    {
        $request->validate([
            'sub_folder_name' => 'required|string|max:255',
            'sub_folder_desc' => 'nullable|string|max:500',
        ]);
        $main = MainFolder::findOrFail($request->main_id);
        $sub = Subfolder::findOrFail($request->sub_id);
        $inner = InnerFolder::findOrFail($request->child_id);
        $child = ChildFolder::findOrFail($request->inner_id);
        $innerchild = InnerchildFolder::findOrFail($request->inner_child_id);
        // Define old and new folder paths
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name . DIRECTORY_SEPARATOR . $child->child_folder_name . DIRECTORY_SEPARATOR .$innerchild->innerchild_folder_name;
        $newPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR .$inner->inner_folder_name . DIRECTORY_SEPARATOR . $child->child_folder_name . DIRECTORY_SEPARATOR .$request->sub_folder_name;
        if (File::exists($newPath)) {
            return response()->json(['message' => 'A folder with this name already exists.'], 409);
        }

        // Rename the folder
        if (File::exists($oldPath)) {
            File::move($oldPath, $newPath);
            $innerchild->innerchild_folder_name = $request->sub_folder_name;
            $innerchild->innerchild_folder_description = $request->sub_folder_desc;
            $innerchild->innerchild_folder_path = $newPath;
            $innerchild->save();
        } else {
            return response()->json(['message' => 'Old folder does not exist'], 404);
        }

        return response()->json([
            'message' => 'Folder renamed successfully!',
            'updated_folder' => $inner
        ], 200);
    }


    public function editChildFolder(Request $request)
    {

        $request->validate([
            'sub_folder_name' => 'required|string|max:255',
            'sub_folder_desc' => 'nullable|string|max:500',
        ]);

        $main = MainFolder::findOrFail($request->main_id);
        $sub = Subfolder::findOrFail($request->sub_id);
        $inner = InnerFolder::findOrFail($request->inner_id);
        $child = ChildFolder::findOrFail($request->child_id);
        // Define old and new folder paths
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name . DIRECTORY_SEPARATOR . $child->child_folder_name;
        $newPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR .$inner->inner_folder_name . DIRECTORY_SEPARATOR . $request->sub_folder_name ;
        if (File::exists($newPath)) {
            return response()->json(['message' => 'A folder with this name already exists.'], 409);
        }

        // Rename the folder
        if (File::exists($oldPath)) {
            File::move($oldPath, $newPath);
            $child->child_folder_name = $request->sub_folder_name;
            $child->child_folder_description = $request->sub_folder_desc;
            $child->child_folder_path = $newPath;
            $child->save();
        } else {
            return response()->json(['message' => 'Old folder does not exist'], 404);
        }

        return response()->json([
            'message' => 'Folder renamed successfully!',
            'updated_folder' => $inner
        ], 200);
    }

    public function deleteSubFolder(Request $request)
    {
        $main = MainFolder::findOrFail($request->main_id);
        $sub = Subfolder::findOrFail($request->folder_id);
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name;
        if (!$sub) {
            return response()->json(['message' => 'Folder not found'], 404);
        }
        if (File::exists($oldPath)) {
            File::deleteDirectory($oldPath);
            $sub->delete();
        } else {
            return response()->json(['message' => 'Folder does not exist'], 404);
        }
    }

    public function deleteInnerFolder(Request $request)
    {
        $main = MainFolder::findOrFail($request->main_id);
        $sub = Subfolder::findOrFail($request->sub_id);
        $inner = InnerFolder::findOrFail($request->folder_id);
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name;
        if (!$inner) {
            return response()->json(['message' => 'Folder not found'], 404);
        }

        if (File::exists($oldPath)) {
            File::deleteDirectory($oldPath);
            $inner->delete();
        } else {
            return response()->json(['message' => 'Folder does not exist'], 404);
        }

        return response()->json(['message' => 'Folder deleted successfully'], 200);
    }

    public function deleteChildFolder(Request $request)
    {
        $main = MainFolder::findOrFail($request->main_id);
        $sub = Subfolder::findOrFail($request->sub_id);
        $inner = InnerFolder::findOrFail($request->inner_id);
        $child = ChildFolder::findOrFail($request->folder_id);
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name . DIRECTORY_SEPARATOR . $child->child_folder_name;
        if (!$child) {
            return response()->json(['message' => 'Folder not found'], 404);
        }

        if (File::exists($oldPath)) {
            File::deleteDirectory($oldPath);
            $child->delete();
        } else {
            return response()->json(['message' => 'Folder does not exist'], 404);
        }

        return response()->json(['message' => 'Folder deleted successfully'], 200);
    }

    public function deleteInnerChildFolder(Request $request)
    {
        $main = MainFolder::findOrFail($request->main_id);
        $sub = Subfolder::findOrFail($request->sub_id);
        $inner = InnerFolder::findOrFail($request->child_id);
        $child = ChildFolder::findOrFail($request->inner_id);
        $innerchild = InnerchildFolder::findOrFail($request->folder_id);
        // Define old and new folder paths
        $oldPath = $main->folder_path . DIRECTORY_SEPARATOR . $sub->sub_folder_name . DIRECTORY_SEPARATOR . $inner->inner_folder_name . DIRECTORY_SEPARATOR . $child->child_folder_name . DIRECTORY_SEPARATOR .$innerchild->innerchild_folder_name;
        if (!$innerchild) {
            return response()->json(['message' => 'Folder not found'], 404);
        }

        if (File::exists($oldPath)) {
            File::deleteDirectory($oldPath);
            $innerchild->delete();
        } else {
            return response()->json(['message' => 'Folder does not exist'], 404);
        }

        return response()->json(['message' => 'Folder deleted successfully'], 200);
    }

    public function deleteMainFolder(Request $request)
    {
        $main = AdminFolderTemplate::findOrFail($request->folder_id);

            $main->delete();
        return response()->json(['message' => 'Folder deleted successfully'], 200);
    }
}
