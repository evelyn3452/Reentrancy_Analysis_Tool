<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-entrancy Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Mulish' rel='stylesheet'>
    <style>
        body {
            font-family: 'Mulish';
            background-color:white;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            background-color: #f0f4ff;
            height: 100vh;
            position: fixed;
            font-size:medium;
        }

        .sidebar h2 {
            text-align: center;
            color: #3b82f6;
        }
        .sidebar h4 {
            text-align: left;
            padding-left: 30px;
            color: #676767;
        }

        .sidebar a {
            text-decoration: none;
            color: #333;
            margin: 10px;
            display: block;
            padding: 10px;
        }
        .sidebar a img {
            padding-inline: 15px;
            /* width: 15px;
            height: 15px; */
        }

        .sidebar a:hover {
            background-color: rgba(56, 78, 183, 0.1);
            border-radius: 5px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .upload-container {
            background-color: #fff;
            /* border: 2px dashed #d1d5db; */
            padding: 50px;
            margin-top: 40px;
            max-width: 50%;
            margin-left: 20%;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .upload-container h3 {
            color: #000000;
        }

        .upload-container p {
            color: #9ca3af;
        }

        .upload-container a {
            color: #483EA8;
            font-weight: bold;
            text-decoration: none;
        }

        .upload-container a:hover {
            text-decoration: underline;
        }

        .upload-container button {
            background-color: rgba(56, 78, 183, 0.2);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload-container button:hover {
            background-color: #483EA8;
        }
                
        .drop-zone {
            border: 2px dashed #ccc;
            padding: 20px;
            border-radius: 10px;
            background-color: rgba(56, 78, 183, 0.1);
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .cloud-icon {
            font-size: 50px;
            color: #ccc;
        }

        .drop-zone img{
            max-width: 65px;
            color: #483EA8;
        }

        #file-list {
            margin-bottom: 20px;
        }

        .file-item {
            margin-bottom: 10px;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #ccc;
            margin-top: 5px;
            position: relative;
        }

        .progress-bar-inner {
            height: 100%;
            width: 0;
            background-color: blue;
            transition: width 0.3s;
        }

        .progress-bar-inner.complete {
            background-color: green;
        }

        .highlight {
            border-color: #2563eb;
        }
        .result{
            max-width: 60%;
            color: #5A6ACF;
        }
        .func_name{
            margin: 5px;
            padding: 5px;
            border-radius: 10px;
        }
        .func_name:hover{
            margin: 5px;
            padding: 5px;
            border-radius: 10px;
            box-shadow: 0 5px rgba(0, 0, 0, 0.1);
        }
        .func_name span{
            float: right;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Re-entrancy Vulnerability Scanner</h2>
        <h4>Menu</h4>
        <ul>
            <a href="{{ route('upload.form') }}"><img src="pics/home.png" alt="">Main</a> {{-- {{ route('dashboard.form') }}  --}}
            <a href="{{ route('results') }}"><img src="pics/bar-chart.png" alt="">Report</a> {{-- {{ route('report') }} --}}
        </ul>
    </div>
    {{-- <div class="sidebar">
        <a href="#dashboard">Dashboard</a>{{-- {{ route('dashboard.form') }}
        <a href="#reports">Reports</a> {{-- {{ route('report') }}
    </div> --}}
    <div class="main-content">
        
        @if (Session::has('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; margin: 20px 0; border-radius: 5px; text-align: center;">
            {{ Session::get('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 20px 0; border-radius: 5px; text-align: center;">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif
        @yield('content')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    </div>
</body>

</html>
