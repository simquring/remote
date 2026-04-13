<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $videoUrl = $_POST['video_url'];
    $title = $_POST['title'];

    $tempFile = "temp_" . time() . ".mp4";

    // Download video dari URL
    file_put_contents($tempFile, fopen($videoUrl, 'r'));

    // Prepare CURL upload
    $cfile = new CURLFile($tempFile, 'video/mp4', basename($tempFile));

    $postData = [
        'file' => $cfile,
        'title' => $title
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://videy.co/upload"); // endpoint target
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch);
    } else {
        echo "<h3>Upload Result:</h3>";
        echo "<pre>$response</pre>";
    }

    curl_close($ch);

    // hapus file sementara
    unlink($tempFile);
}
?>
