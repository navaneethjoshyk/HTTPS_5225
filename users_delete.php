<?php
require_once 'auth.php';
require_once 'connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
    // Delete user image if exists
    $res = mysqli_query($connect, "SELECT image FROM users WHERE id = " . $id);
    if ($row = mysqli_fetch_assoc($res)) {
        if (!empty($row['image'])) {
            $imgPath = __DIR__ . '/uploads/' . $row['image'];
            if (is_file($imgPath)) {
                @unlink($imgPath);
            }
        }
    }
    mysqli_query($connect, "DELETE FROM users WHERE id = " . $id);
}
header('Location: users_index.php');
exit;
?>
