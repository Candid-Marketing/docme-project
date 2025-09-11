<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Viewing: {{ $filename }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts + Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f2f4f8;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        header {
            background-color: #ffffff;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .file-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .file-info i {
            font-size: 24px;
            color: #555;
        }

        .file-name {
            font-weight: 600;
            font-size: 16px;
            color: #333;
        }

        .download-btn {
            padding: 8px 16px;
            background-color: #0077cc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .download-btn:hover {
            background-color: #005fa3;
        }

        .viewer-container {
            flex: 1;
            overflow: hidden;
            display: flex;
            padding: 16px;
        }

        iframe {
            flex: 1;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<header>
    <div class="file-info">
        <i class="fas fa-file-alt"></i>
        <span class="file-name">{{ $filename }}</span>
    </div>

    <!-- Optional: Download button -->
    {{-- <a href="{{ $signedUrl }}" class="download-btn" download>
        <i class="fas fa-download" style="margin-right: 6px;"></i> Download
    </a> --}}
</header>

<div class="viewer-container">
   <iframe src="https://docs.google.com/gview?url={{ urlencode($signedUrl) }}&embedded=true"></iframe>
</div>

</body>
</html>
