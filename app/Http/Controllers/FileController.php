<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\File;
use App\Models\Download;

class FileController extends Controller
{
    public function show($id)
    {
        $file = File::findOrFail($id);
        return response()->json([
            'name' => $file->file_name,
            'url' => Storage::url($file->file_path),
        ]);
    }

    // Method to update file (for Update Modal)
    public function update(Request $request, $id)
    {
        $file = File::findOrFail($id);
        $file->file_name = $request->input('name');
        $file->save();

        return redirect()->route('superadmin.folders.show', $file->folder)->with('success', 'File updated successfully.');
    }

    public function download($folder, $file)
    {
        // Get the file path including the folder
        $filePath = storage_path('app/private/' . $folder . '/' . $file);

        // Check if the file exists
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Retrieve the file record from the database
        $fileRecord = File::where('file_name', $file)->where('folder_name', $folder)->first();

        // Log the download event
        if (Auth::check()) {
            $userEmail = Auth::user()->email; // Get the authenticated user's email
        } else {
            $userEmail = 'Guest'; // Handle non-authenticated users
        }

        // Create a new record in the downloads table to track the download
        if ($fileRecord) {
            Download::create([
                'file_id' => $fileRecord->id,
                'file_name' => $fileRecord->file_name,
                'user_email' => $userEmail,    // User's email
                'downloaded_at' => now(),      // Timestamp of download
            ]);
        }

        // Return the file for download
        return response()->download($filePath);
    }

    public function upload(Request $request, $folder)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Get the original file name
        $fileName = $file->getClientOriginalName();

        // Get the file's size and type
        $fileSize = $file->getSize(); // Size in bytes
        $fileType = $file->getMimeType(); // File MIME type (e.g., pdf, jpeg, etc.)

        // Define the file storage path
        $filePath = $file->storeAs('private/' . $folder, $fileName);

        // Store file metadata in the database
        File::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'folder_name' => $folder, // Store the folder name
            'created_by' => Auth::user()->email, // Assuming you're using Laravel's Auth system
            'updated_by' =>  Auth::user()->email, // You can update this later when the file is updated
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully.');
    }

    public function rename(Request $request, $folder, $file)
    {
        // Get the path of the file (Including the folder)
        $oldFilePath = storage_path('app/private/' . $folder . '/' . $file);

        // Check if the file exists
        if (!file_exists($oldFilePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Get the new file name from the request (the file name without extension)
        $newFileName = $request->input('newFileName');

        // Extract the file extension of the old file
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        // Construct the new file path with the folder and new file name
        $newFilePath = storage_path('app/private/' . $folder . '/' . $newFileName . '.' . $fileExtension);

        // Rename the file on the filesystem
        if (rename($oldFilePath, $newFilePath)) {
            // Update the file record in the database
            $fileRecord = File::where('file_name', $file)->where('folder_name', $folder)->first();

            if ($fileRecord) {
                // Update the file record with the new file name
                $fileRecord->file_name = $newFileName . '.' . $fileExtension;
                $fileRecord->updated_by = Auth::check() ? Auth::user()->email : 'Guest';
                $fileRecord->updated_at = now();
                $fileRecord->save();
            }

            return redirect()->back()->with('success', 'File renamed successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to rename the file.');
        }
    }


    public function delete($folder, $file)
    {
        $filePath = storage_path('app/private/' . $folder . '/' . $file);
        if (!file_exists($filePath)) {
            return redirect()->route('superadmin.folders.index')->with('error', 'File not found.');
        }
        $fileRecord = File::where('file_name', $file)->where('folder_name', $folder)->first();

        if ($fileRecord) {
            // You may also want to remove the file record from the database if it's no longer needed
            $fileRecord->delete();
        }
        unlink($filePath);

        return redirect()->route('superadmin.folders.index')->with('success', 'File deleted successfully.');
    }


    //sub folder
    public function sub_upload(Request $request, $folder, $subfolder)
    {

        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Get the original file name
        $fileName = $file->getClientOriginalName();

        // Get the file's size and type
        $fileSize = $file->getSize(); // Size in bytes
        $fileType = $file->getMimeType(); // File MIME type (e.g., pdf, jpeg, etc.)

        // Define the storage path - this needs to be a folder path, not a subfolder name
        // Adjust the path to include the folder and subfolder
        $filePath = $file->storeAs('private/' . $folder . '/' . $subfolder, $fileName);

        // Store file metadata in the database
        File::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'folder_name' => $folder . '/' . $subfolder,
            'created_by' => Auth::user()->email, // Assuming you're using Laravel's Auth system
            'updated_by' => Auth::user()->email, // You can update this later when the file is updated
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully.');
    }

    public function sub_delete($folder, $file,$subfolder)
    {
        $filePath = storage_path('app/private/' . $folder . '/' . $subfolder . '/' . $file);
        if (!file_exists($filePath)) {
            return redirect()->route('superadmin.folders.index')->with('error', 'File not found.');
        }
        $fileRecord = File::where('file_name', $file)->where('folder_name', $folder . '/' . $subfolder)->first();

        if ($fileRecord) {
            // You may also want to remove the file record from the database if it's no longer needed
            $fileRecord->delete();
        }
        unlink($filePath);

        return redirect()->back()->with('success', 'File deleted successfully.');
    }


    public function sub_rename(Request $request, $folder,$subfolder, $file)
    {
        // Get the path of the file (Including the folder)
        $oldFilePath = storage_path('app/private/' . $folder . '/' . $subfolder . '/' . $file);

        // Check if the file exists
        if (!file_exists($oldFilePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Get the new file name from the request (the file name without extension)
        $newFileName = $request->input('newFileName');

        // Extract the file extension of the old file
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        // Construct the new file path with the folder and new file name
        $newFilePath = storage_path('app/private/' . $folder . '/' . $subfolder . '/' . $newFileName . '.' . $fileExtension);

        // Rename the file on the filesystem
        if (rename($oldFilePath, $newFilePath)) {
            // Update the file record in the database
            $fileRecord = File::where('file_name', $file)->where('folder_name', $folder . '/' . $subfolder)->first();

            if ($fileRecord) {
                // Update the file record with the new file name
                $fileRecord->file_name = $newFileName . '.' . $fileExtension;
                $fileRecord->updated_by = Auth::check() ? Auth::user()->email : 'Guest';
                $fileRecord->updated_at = now();
                $fileRecord->save();
            }

            return redirect()->back()->with('success', 'File renamed successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to rename the file.');
        }
    }


    public function sub_download($folder, $subfolder, $file)
    {
        // Get the file path including the folder
        $filePath = storage_path('app/private/' . $folder . '/' . $subfolder . '/' . $file);

        // Check if the file exists
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Retrieve the file record from the database
        $fileRecord = File::where('file_name', $file)->where('folder_name', $folder . '/' . $subfolder)->first();
        // Log the download event
        if (Auth::check()) {
            $userEmail = Auth::user()->email; // Get the authenticated user's email
        } else {
            $userEmail = 'Guest'; // Handle non-authenticated users
        }

        // Create a new record in the downloads table to track the download
        if ($fileRecord) {
            Download::create([
                'file_id' => $fileRecord->id,
                'file_name' => $fileRecord->file_name,
                'user_email' => $userEmail,    // User's email
                'downloaded_at' => now(),      // Timestamp of download
            ]);
        }

        // Return the file for download
        return response()->download($filePath);
    }


    //inner folder
    public function inner_upload(Request $request, $folder, $subfolder, $innerfolder)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:2048',
        ]);

        // Get the uploaded file
        $file = $request->file('file');

        // Get the original file name
        $fileName = $file->getClientOriginalName();

        // Get the file's size and type
        $fileSize = $file->getSize(); // Size in bytes
        $fileType = $file->getMimeType(); // File MIME type (e.g., pdf, jpeg, etc.)

        // Define the storage path - this needs to be a folder path, not a subfolder name
        // Adjust the path to include the folder and subfolder
        $filePath = $file->storeAs('private/' . $folder . '/' . $subfolder . '/' . $innerfolder, $fileName);
        // Store file metadata in the database
        File::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'folder_name' => $folder . '/' . $subfolder . '/' . $innerfolder,
            'created_by' => Auth::user()->email, // Assuming you're using Laravel's Auth system
            'updated_by' => Auth::user()->email, // You can update this later when the file is updated
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully.');
    }

    public function inner_delete($folder,$subfolder,$innerfolder,$file )
    {

        $filePath = storage_path('app/private/' . $folder . '/' . $subfolder . '/' . $innerfolder .'/'. $file);
        if (!file_exists($filePath)) {
            return redirect()->route('superadmin.folders.index')->with('error', 'File not found.');
        }
        $fileRecord = File::where('file_name', $file)->where('folder_name', $folder . '/' . $subfolder)->first();

        if ($fileRecord) {
            // You may also want to remove the file record from the database if it's no longer needed
            $fileRecord->delete();
        }
        unlink($filePath);

        return redirect()->back()->with('success', 'File deleted successfully.');
    }


    public function inner_rename(Request $request, $folder, $subfolder, $innerfolder, $file)
    {
        // Get the path of the file (Including the folder)
        $oldFilePath = storage_path('app/private/' . $folder . '/' . $subfolder . '/' . $innerfolder . '/' . $file);

        // Check if the file exists
        if (!file_exists($oldFilePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Get the new file name from the request
        $newFileName = $request->input('newFileName');

        // Extract the file extension of the old file
        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        // Check if the new file name already has an extension
        $newFileExtension = pathinfo($newFileName, PATHINFO_EXTENSION);

        if (!$newFileExtension) {
            // If no extension is provided, add the old file extension
            $newFileName .= '.' . $fileExtension;
        }

        // Construct the new file path with the folder and new file name
        $newFilePath = storage_path('app/private/' . $folder . '/' . $subfolder . '/' . $innerfolder . '/' . $newFileName);

        // Rename the file on the filesystem
        if (rename($oldFilePath, $newFilePath)) {
            // Update the file record in the database
            $fileRecord = File::where('file_name', $file)->where('folder_name', $folder . '/' . $subfolder . '/' . $innerfolder)->first();

            if ($fileRecord) {
                // Update the file record with the new file name
                $fileRecord->file_name = $newFileName;
                $fileRecord->updated_by = Auth::check() ? Auth::user()->email : 'Guest';
                $fileRecord->updated_at = now();
                $fileRecord->save();
            }

            return redirect()->back()->with('success', 'File renamed successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to rename the file.');
        }
    }



    public function inner_download($folder, $subfolder, $innerfolder, $file)
    {
        // Get the file path including the folder
        $filePath = storage_path('app/private/' . $folder . '/' . $subfolder . '/' .$innerfolder . '/' . $file);

        // Check if the file exists
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Retrieve the file record from the database
        $fileRecord = File::where('file_name', $file)->where('folder_name', $folder . '/' . $subfolder . '/' . $innerfolder)->first();
        // Log the download event
        if (Auth::check()) {
            $userEmail = Auth::user()->email; // Get the authenticated user's email
        } else {
            $userEmail = 'Guest'; // Handle non-authenticated users
        }

        // Create a new record in the downloads table to track the download
        if ($fileRecord) {
            Download::create([
                'file_id' => $fileRecord->id,
                'file_name' => $fileRecord->file_name,
                'user_email' => $userEmail,    // User's email
                'downloaded_at' => now(),      // Timestamp of download
            ]);
        }

        // Return the file for download
        return response()->download($filePath);
    }


}
