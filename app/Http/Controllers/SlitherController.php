<?php

namespace App\Http\Controllers;

use console;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SlitherController extends Controller
{
    public function showUploadForm()
    {
        return view('scanner1');
    }

    public function uploadContract(Request $request)
    {
        // Validate the request
        // $request->validate([
        //     'contract' => 'required|file|mimes:sol',
        //     'solc_version' => 'required|string',
        // ]);
        $request->session()->forget('slither_result');
        
        $file = $request->file('contract');
        $fileName = $file->getClientOriginalName();
        Log::info('Uploaded File Name: ' . $fileName); 
        $extension = $file->getClientOriginalExtension();

        if ($extension != 'sol') {
            return back()->with('error', 'Only .sol files are supported');
        }

        $fileContents = file_get_contents($file->getRealPath());

        // Run the Python script to analyze the contract
        $result = $this->runSlitherAnalysis($fileContents);

        // if ($result === null) {
        //     return redirect()->route('upload.form')->with('error', 'Failed to analyze the contract (SlitherController).');
        // }

        if ($result === null || isset($result['error'])) {
            $errorMessage = $result['error'] ?? 'Failed to analyze the contract.';
            $errorDetails = $result['details'] ?? '';
            return redirect()->route('upload.form')->with('error', $errorMessage)->with('details', $errorDetails);
        }
        

        // Store the result in the session
        session(['slither_result' => $result,'uploaded_file_name' => $fileName]);

        return redirect()->route('results');
    }

    private function runSlitherAnalysis($fileContents)
    {
        // try {
            $python = env('PYTHON_PATH', 'python'); // Path to Python executable
            $scriptPath = base_path('scripts/run_slither.py');

            $process = new Process([$python, $scriptPath]);
            $process->setInput($fileContents);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('Python script error: ' . $process->getErrorOutput());
                return null;
            }
        
            $output = $process->getOutput();
            Log::info('Python script output: ' . $output);

            //filter and format the result
            $slitherResult = json_decode($output,true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON decode error: ' . json_last_error_msg());
                // return null;
            }

            // Log::info("The filtered result pass from the python: ". $slitherResult);
            // echo($slitherResult);
            if (isset($slitherResult['reentrancy_issues'])) {
                $filteredResults = [];
                foreach ($slitherResult['reentrancy_issues']as $detector) {
                    $filteredResults[] = [
                        'contract_name' => $detector['contract_name'],
                        'function_name' => $detector['function_name'],
                        'start_line' => $detector['start_line'],
                        'end_line' => $detector['end_line'],
                        'impact' => $detector['impact'],
                        'code_block' => $detector['code_block']
                    ];
                }
                return ['reentrancy_issues' => $filteredResults];
            }
        return null;
    }

    public function showResults()
    {
        $result = session('slither_result', []);
        $fileName = session('uploaded_file_name', '');

        return view('results', compact('result','fileName'));
    }
}
