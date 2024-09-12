@extends('components.layout')

@section('content')
<h2>Dashboard</h2>
<div class="upload-container">
    <h3>Upload</h3>
    <div class="drop-zone" id="drop-zone">
        <img src="pics/upload.png"><br />
        <span class="cloud-icon"></span>
        Drag & Drop .sol file here or <a href="#" id="file-browse">Browse</a>
    </div>
    <form action="{{ route('upload.contract') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="file-list"></div>
        <input type="file" id="file-input"  name="contract" multiple style="display: none">
        <button id="upload-btn">UPLOAD</button>
    </form>
    <div class="progress-bar">
        <div class="progress-bar-inner"></div>
    </div>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        <script>
            // Show the alert or modal when there is an error
            window.onload = function() {
                let errorMessage = '{{ session('error') }}';
                alert(errorMessage); // This will show a simple alert

                // Alternatively, you can trigger a Bootstrap modal
                // $('#errorModal').modal('show');
            };
        </script>
    @endif
</div>


<script>
    document.getElementById('file-browse').addEventListener('click', () => {
    document.getElementById('file-input').click();
});

document.getElementById('file-input').addEventListener('change', handleFiles);
document.getElementById('drop-zone').addEventListener('drop', handleDrop);
document.getElementById('drop-zone').addEventListener('dragover', (e) => e.preventDefault());
// document.getElementById('upload-btn').addEventListener('click', uploadFiles);

form.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent the form from submitting the default way
    uploadFiles(); // Custom upload handling
});

function handleFiles(event) {
    const files = event.target.files;
    displayFiles(files);
    document.getElementById('upload-btn').disabled = false; // Enable the button when files are selected
}

function handleDrop(event) {
    event.preventDefault();
    const files = event.dataTransfer.files;
    displayFiles(files);
    document.getElementById('upload-btn').disabled = false; // Enable the button when files are dropped
}

function displayFiles(files) {
    const fileList = document.getElementById('file-list');
    fileList.innerHTML = '';
    Array.from(files).forEach(file => {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        fileItem.innerHTML = `<span>${file.name}</span>`;
        fileList.appendChild(fileItem);
    });
    // Enable the upload button only when a file is selected or dropped
    uploadButton.disabled = files.length === 0;
}

function uploadFiles() {
    const formData = new FormData(form);
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable) {
                const percentComplete = (event.loaded / event.total) * 100;
                progressBar.style.width = percentComplete + '%';
            }
        });

        xhr.addEventListener('load', () => {
            if (xhr.status === 200) {
                progressBar.classList.add('complete');
                window.location.href = "{{ route('results') }}"; // Redirect to the results page on success
            } else {
                alert('Upload failed. Please try again.');
            }
        });

        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}'); // Add CSRF token
        xhr.send(formData);
    // const progressBars = document.querySelectorAll('.progress-bar-inner');
    // progressBars.forEach(bar => {
    //     bar.style.width = '100%';
    //     bar.classList.add('complete');
    // });

    // // Simulate upload completion
    // setTimeout(() => {
    //     document.getElementById('upload-btn').disabled = true; // Disable the button after upload is complete
    // }, 1000); // Adjust the timeout as needed
}
// Initially disable the upload button
// uploadButton.disabled = true;
document.getElementById('upload-btn').disabled = true;

    </script>
    @endsection