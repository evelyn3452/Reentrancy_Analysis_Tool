@extends('components.layout')
    @section('content')
    <h1>Upload Solidity Contract</h1>
    
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    
    <form action="{{ route('upload.contract') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="contract">Select Solidity File:</label>
            <input type="file" name="contract" id="contract" required>
        </div>
        <div>
            <button type="submit">Upload and Analyze</button>
        </div>
    </form>
@endsection