<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TestingController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('Upload method called');
        // $request->validate([
        //     'file' => 'required|file|mimes:sol',
        // ]);

        Log::info('getting into the TRY');
        try {
            // Store the uploaded file
            $file = $request->file('file');
            $fileContents = $file->get();

            Log::info('File contents read');
 
            // Call Python script to analyze the file
            $python = env('PYTHON_PATH', 'python');//'venv/Scripts/python'
            $script = base_path('scripts/process.py');
            Log::info('Running Python script', ['python' => $python, 'script' => $script]);

            $process = new Process([$python, $script]);
            $process->setInput($fileContents);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('Process failed', ['error' => $process->getErrorOutput()]);
                throw new ProcessFailedException($process);
            }

            $output = $process->getOutput();
            $report = json_decode($output, true);
            Log::info('Script output', ['output' => $output]);

            // Debugging output
            Log::info('Script output', ['output' => $output]);
            Log::info('Report', ['report' => $report]);

            // Store the report in the session
            session(['report' => $report]);
            Log::info('Stored report in session');

            // Redirect to the report view
            return redirect()->route('report');
        } catch (\Exception $e) {
            Log::error('Exception caught', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while processing the file.');
        }
    }

    public function report()
    {
        $report = session('report', []);
        Log::info('Report data retrieved from session', ['report' => $report]);
        return view('report1', compact('report'));
    }
}
