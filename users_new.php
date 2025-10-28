<?php
require_once 'auth.php';
require_once 'connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Name, email, and password are required.';
    } else {
        // Handle image upload
        $imageFileName = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadsDir = __DIR__ . '/uploads';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $safeBase = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($_FILES['image']['name'], PATHINFO_FILENAME));
            $imageFileName = $safeBase . '_' . time() . '.' . $ext;
            $target = $uploadsDir . '/' . $imageFileName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $error = 'Failed to upload image.';
            }
        }

        if ($error === '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = mysqli_prepare($connect, "INSERT INTO users (name, email, password, image) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hash, $imageFileName);
            $ok = mysqli_stmt_execute($stmt);
            if ($ok) {
                $success = 'User created successfully.';
            } else {
                $error = 'Failed to create user. Perhaps email already exists.';
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>New User</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 680px; margin: 40px auto; }
        form { border: 1px solid #ddd; padding: 20px; border-radius: 6px; }
        .error { color: #b00020; margin: 10px 0; }
        .success { color: #006400; margin: 10px 0; }
        label { display:block; margin-top:10px; }
        input[type="text"], input[type="email"], input[type="password"] { width:100%; padding:8px; }
        button { margin-top: 16px; padding: 10px 16px; cursor: pointer; }
        a { text-decoration:none; }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>
    <h1>New User</h1>
    <?php if ($error): ?><div class="error"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Name</label>
        <input type="text" name="name" required />

        <label>Email</label>
        <input type="email" name="email" required />

        <label>Password</label>
        <input type="password" name="password" required />

        <label>Image (optional)</label>
        <input type="file" name="image" accept="image/*" />

        <button type="submit">Create User</button>
    </form>
</body>
</html>
