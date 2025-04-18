<?php
// Simple script to create a default profile picture

// Check if directory exists, create if needed
$directory = 'uploads/profiles';
if (!file_exists($directory)) {
    if (!mkdir($directory, 0777, true)) {
        die("Failed to create directory: $directory");
    }
    echo "Created directory: $directory\n";
} else {
    echo "Directory exists: $directory\n";
}

// Create a 200x200 image
$img = imagecreatetruecolor(200, 200);
if (!$img) {
    die("Failed to create image resource");
}

// Define colors
$lightGray = imagecolorallocate($img, 240, 240, 240); // #f0f0f0
$darkGray = imagecolorallocate($img, 128, 128, 128);  // #808080

// Fill background with light gray
imagefill($img, 0, 0, $lightGray);

// Draw a simple avatar silhouette
// Head circle
imagefilledellipse($img, 100, 80, 80, 80, $darkGray);

// Body shape
imagefilledrectangle($img, 60, 120, 140, 200, $darkGray);

// Try to save the image
$filename = "$directory/default.png";
if (imagepng($img, $filename)) {
    echo "Successfully created default profile image: $filename\n";
} else {
    echo "Failed to save image to $filename\n";
}

// Free up memory
imagedestroy($img);

echo "Done!";
