<?php
// upload_profile_image.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!session_id()) session_start();
require_once __DIR__ . '/connect.php';

// Optional: require login
if (empty($_SESSION['user_id'])) {
  http_response_code(403);
  die('You must be logged in to upload an image.');
}

$userId = (int)$_SESSION['user_id'];

// Validate the incoming file
if (!isset($_FILES['image'])) {
  die('No file sent. Did you forget enctype="multipart/form-data"?');
}

$err = $_FILES['image']['error'];
if ($err !== UPLOAD_ERR_OK) {
  $map = [
    UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive.',
    UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive.',
    UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
    UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder on server.',
    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
    UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
  ];
  $msg = $map[$err] ?? ('Unknown upload error code: ' . $err);
  die('Upload error: ' . $msg);
}

$maxBytes = 2 * 1024 * 1024;
if ($_FILES['image']['size'] > $maxBytes) {
  die('File too large. Max 2 MB.');
}


$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
  if (!mkdir($uploadDir, 0775, true)) {
    die('Failed to create uploads directory.');
  }
}


$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime  = $finfo->file($_FILES['image']['tmp_name']) ?: '';

$allowed = [
  'image/jpeg' => 'jpg',
  'image/png'  => 'png',
  'image/gif'  => 'gif',
  'image/webp' => 'webp'
];

if (!isset($allowed[$mime])) {
  die('Unsupported file type. Allowed: JPG, PNG, GIF, WEBP.');
}

$ext = $allowed[$mime];


