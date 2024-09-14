<!-- resources/views/play-reel.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play Reel</title>
</head>
<body>
    <h1>Play Reel</h1>

    <!-- Video player -->
    <video width="640" height="360" controls>
        <source src="{{ url('/api/reels/' . $fileId . '/play') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</body>
</html>
