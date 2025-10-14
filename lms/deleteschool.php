<?php
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id > 0) {
        
        $stmt = mysqli_prepare($connect, "DELETE FROM schools WHERE id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $affected = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);

            
            header("Location: index.php?deleted=" . ($affected > 0 ? "1" : "0"));
            exit();
        } else {
            
            die("Failed to prepare statement: " . mysqli_error($connect));
        }
    } else {
        header("Location: index.php?deleted=0");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
