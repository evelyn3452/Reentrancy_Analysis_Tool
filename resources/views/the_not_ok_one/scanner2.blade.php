@extends('components.layout')

@section('content')
<div class="container">
    <div class="sidebar">
        <h2>MENU</h2>
        <ul>
            <li class="active"><a href="#">Dashboard</a></li>
            <li><a href="#">Report</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Dashboard</h1>
        <div class="search-bar">
            <input type="text" placeholder="Search">
            <button><i class="fas fa-search"></i></button>
        </div>

        <div class="upload-section">
            <h2>Upload</h2>
            <div class="drop-zone">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Drag & drop files or <a href="#" id="browse-link">Browse</a></p>
                <p>Supported formats: .sol</p>
                <input type="file" id="file" name="file" style="display: none;">
                <div class="progress-bar">
                    <div class="progress"></div>
                    <span class="progress-text">Uploading - 0 files</span>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress"></div>
                <span class="progress-text">Uploading - 0 files</span>
            </div>
            <button id="upload-button">UPLOAD FILES</button>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/your-fontawesome-kit-id.js" crossorigin="anonymous"></script>
<script>
    const dropZone = document.querySelector('.drop-zone');
    const uploadButton = document.getElementById('upload-button');
    const progressBar = document.querySelector('.progress');
    const progressText = document.querySelector('.progress-text');

    // Drag & Drop Functionality
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        fileInput.files = e.dataTransfer.files;
        uploadFile(file);
    });

    // Browse Button Functionality
    document.getElementById('browse-link').addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        uploadFile(file);
    });

    function uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);

        axios.post('/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (progressEvent) => {
                    const percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    progressBar.style.width = `${percent}%`;
                    progressText.textContent = `Uploading - ${percent}%`;
                }
            })
            .then((response) => {
                console.log(response);
                progressBar.style.width = `100%`;
                progressText.textContent = `Uploaded successfully!`;
                progressBar.classList.add('success');
            })
            .catch((error) => {
                console.error(error);
            });
    }

    uploadButton.addEventListener('click', () => {
        const formData = new FormData();
        formData.append('file', document.getElementById('file').files[0]);

        axios.post('/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
                onUploadProgress: (progressEvent) => {
                    const percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    progressBar.style.width = `${percent}%`;
                    progressText.textContent = `Uploading - ${percent}%`;
                }
            })
            .then((response) => {
                console.log(response);
            })
            .catch((error) => {
                console.error(error);
            });
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