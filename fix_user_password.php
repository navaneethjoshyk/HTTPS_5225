<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/connect.php';

$email   = isset($_GET['email']) ? strtolower(trim($_GET['email'])) : '';
$newPass = isset($_GET['pass'])  ? $_GET['pass'] : '';

if ($email === '' || $newPass === '') { 
  die('Usage: fix_user_password.php?email=you@example.com&pass=NewPassHere'); 
}

$hash = password_hash($newPass, PASSWORD_BCRYPT);
if (!$hash) die('Failed to hash password');

$sql = "UPDATE users SET password = ? WHERE email = ?";
$stmt = mysqli_prepare($connect, $sql) or die('Prepare failed: '.mysqli_error($connect));
mysqli_stmt_bind_param($stmt, "ss", $hash, $email);
mysqli_stmt_execute($stmt) or die('Execute failed: '.mysqli_error($connect));
$rows = mysqli_stmt_affected_rows($stmt);
mysqli_stmt_close($stmt);

echo "<pre>Target email: $email\nUpdated rows: $rows\nNew hash prefix: ".substr($hash,0,7)."</pre>";

