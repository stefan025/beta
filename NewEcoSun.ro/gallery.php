<?php
header('Content-Type: application/json');

$dir = __DIR__ . "/gallery/uploads/";
$files = glob($dir . "*.{jpg,jpeg,png,webp}", GLOB_BRACE);

$images = [];

foreach ($files as $file) {
    $images[] = [
        "file" => "gallery/uploads/" . basename($file),
        "time" => filemtime($file)
    ];
}

// sortare: cele mai noi primele
usort($images, function($a, $b) {
    return $b['time'] - $a['time'];
});

// scoatem doar path-ul pentru frontend
$result = array_map(function($img) {
    return $img['file'];
}, $images);

echo json_encode($result);