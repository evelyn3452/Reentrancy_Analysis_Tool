<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    // stores the uploaded file
    public function upload(Request $request)
    {
        //debugging
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Log::info('Uploaded file MIME type: ' . $file->getMimeType());
            Log::info('Uploaded file MIME type (Client): ' . $file->getClientMimeType());
        }

        // Validate the incoming file. Refuses anything bigger than 2048 kilobyes (=2MB)
        $request->validate([
            'file' => 'required|mimes:application/octet-stream|max:2048',
        ]);

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $path = $file->store('uploads','local');

            // Push a success notification
            Session::flash('success', 'File uploaded successfully to ' .$path);

            return redirect()->back();
        }
        return redirect()->back()->withErrors(['file' => 'There was an issue with the file upload.']);
    }

    public function display()
    {

        // $uploadedFiles = UploadedFile::all();
        return view('report');
    }
}
