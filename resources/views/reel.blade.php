<!-- resources/views/upload-reel.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and Play Reel</title>
</head>
<body>
    <h1>Upload Reel</h1>

    <!-- File Input for selecting the video file -->
    <input type="file" id="fileInput" accept="video/*">
    <button id="uploadButton">Upload Reel</button>

    <!-- Section to show the video player for playback -->
    <div id="videoPlayerContainer" style="display: none;">
        <h2>Play Uploaded Reel</h2>
        <video id="videoPlayer" width="640" height="360" controls>
            <source id="videoSource" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <script>
        // Function to generate a unique ID for the file
        function generateUniqueId() {
            return 'reel_' + Math.random().toString(36).substr(2, 9);
        }

        // Async function to handle the chunked file upload
        async function uploadFile(file) {
            const chunkSize = 5 * 1024 * 1024; // 5MB
            const totalChunks = Math.ceil(file.size / chunkSize);
            const fileId = generateUniqueId(); // Generate a unique ID for this file

            // Loop through each chunk and upload
            for (let chunkIndex = 1; chunkIndex <= totalChunks; chunkIndex++) {
                const start = (chunkIndex - 1) * chunkSize;
                const end = Math.min(start + chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file', chunk);
                formData.append('chunkIndex', chunkIndex);
                formData.append('totalChunks', totalChunks);
                formData.append('fileId', fileId);

                // Upload each chunk to the server
                await fetch('/api/reels/upload', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                  .then(data => console.log('Chunk uploaded:', data))
                  .catch(error => console.error('Upload error:', error));
            }

            alert('Reel uploaded successfully!');
            
            // After upload, set the video player source and display it
            document.getElementById('videoSource').src = `/api/reels/${fileId}/play`;
            document.getElementById('videoPlayerContainer').style.display = 'block';
            document.getElementById('videoPlayer').load(); // Refresh the video player
        }

        // Event listener for the upload button
        document.getElementById('uploadButton').addEventListener('click', () => {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];

            if (!file) {
                alert('Please select a file to upload.');
                return;
            }

            uploadFile(file);
        });
    </script>
</body>
</html>
