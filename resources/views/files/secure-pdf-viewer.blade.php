<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $filename }} - Secure PDF Viewer</title>
    <style>
        body, html {
            margin: 0;
            height: 100%;
            overflow: hidden;
        }
        iframe {
            border: none;
            width: 100%;
            height: 100vh;
        }
    </style>
</head>
<body>
    <iframe src="{{ $signedUrl }}"></iframe>
</body>
</html>
