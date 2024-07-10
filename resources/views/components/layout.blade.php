<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-entrancy Scanner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            background-color: #f0f4ff;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            color: #3b82f6;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px;
            text-align: center;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #ddd;
            border-radius: 5px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .upload-container {
            background-color: #fff;
            border: 2px dashed #d1d5db;
            padding: 50px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .upload-container h3 {
            color: #6b7280;
        }

        .upload-container p {
            color: #9ca3af;
        }

        .upload-container a {
            color: #3b82f6;
            text-decoration: none;
        }

        .upload-container a:hover {
            text-decoration: underline;
        }

        .upload-container button {
            background-color: #3b82f6;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload-container button:hover {
            background-color: #2563eb;
        }

        .navbar {
            background-color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .navbar .bell {
            font-size: 20px;
            cursor: pointer;
        }

        .highlight {
            border-color: #2563eb;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Re-entrancy Scanner</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Report</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="navbar">
            <div></div>
            <div class="bell">
                <i class="fas fa-bell"></i>
            </div>
        </div>
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
    </div>
</body>

</html>
