<!DOCTYPE html>
<html>
<head>
    <title>Reentrancy Scanner</title>
</head>
<body>
    <h1>Upload Solidity File</h1>
    <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".sol">
        <button type="submit">Upload</button>
    </form>
</body>
</html>
