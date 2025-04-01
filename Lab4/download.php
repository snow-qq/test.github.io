<?php
session_start();

$results_dir = 'results';
$file = isset($_GET['file']) ? $_GET['file'] : '';

if (empty($file)) {
    header('Location: index.php');
    exit;
}

$file_path = $results_dir . '/' . $file;

// Security check to prevent directory traversal
if (strpos($file, '..') !== false || !file_exists($file_path)) {
    header('Location: index.php');
    exit;
}

// Set appropriate headers for file download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Content-Length: ' . filesize($file_path));
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Output file content
readfile($file_path);
exit; 