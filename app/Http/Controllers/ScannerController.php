<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class ScannerController extends Controller
{
    public function index()
    {
        return view('reentrancy-scanner.dashboard');
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        if ($extension != 'sol') {
            return back()->with('error', 'Only .sol files are supported');
        }

        $path=Storage::putFileAs('untested_file', $file, $filename);
        
        //call python to scan the file
        $python = env('PYTHON_PATH', 'python3');
        $script = base_path('scripts/analyze.py');
        $file_path = storage_path('app/' . $path);

        $process = new Process([$python, $script, $file_path]);
        $process->run();

        Session::flash('success', 'File uploaded successfully!');
        return redirect()->back();
    }

    public function getUploadedFiles()
    {
        $files = Storage::files('uploads');
        return view('reentrancy-scanner.dashboard', compact('files'));
    }
}
