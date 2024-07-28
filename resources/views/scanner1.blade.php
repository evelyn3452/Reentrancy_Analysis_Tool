@extends('components.layout')

@section('content')
<div class="upload-container">
    <h3>Upload</h3>
    <form action="/" method="POST" enctype="multipart/form-data">
        @csrf
        <p>Drag & drop files or <a href="#" id="browse-link">Browse</a></p>
        <p>Supported formats: .sol & Supported version: ^0.8.x</p>
        <input type="file" name="file" id="file" accept=".sol" style="display: none;">
        <div id="fileInfo" style="margin: 20px 0;"></div>
        <div class="progress-bar">
            <div class="progress"></div>
        </div>
        <button type="submit" id="uploadButton" style="display: none;">SUBMIT</button>
    </form>
</div>

<script>
    document.getElementById('browse-link').onclick = function() {
        document.getElementById('file').click();
    };

    document.getElementById('file').onchange = function() {
        displayFileName(this.files[0]);
    };

    // Prevent default drag behaviors
    window.addEventListener("dragover", function(e) {
        e.preventDefault();
    }, false);

    window.addEventListener("drop", function(e) {
        e.preventDefault();
    }, false);

    // Highlight the drop area when dragging a file over it
    let dropArea = document.querySelector('.upload-container');

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropArea.classList.add('highlight');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, (e) => {
            dropArea.classList.remove('highlight');
        }, false);
    });

    // Handle dropped files
    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        let files = e.dataTransfer.files;
        document.getElementById('file').files = files;
        displayFileName(files[0]);
    });

    function displayFileName(file) {
        if (file) {
            document.getElementById('fileInfo').innerText = 'Selected file: ' + file.name;
            document.getElementById('uploadButton').style.display = 'block';
        } else {
            document.getElementById('fileInfo').innerText = '';
            document.getElementById('uploadButton').style.display = 'none';
        }
    }
</script>
@endsection