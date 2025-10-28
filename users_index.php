<?php
require_once 'auth.php';
require_once 'connect.php';

$result = mysqli_query($connect, "SELECT id, name, email, image FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Users</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 40px auto; }
        table { border-collapse: collapse; width:100%; }
        th, td { padding: 10px; border: 1px solid #ddd; }
        th { background: #f5f5f5; text-align:left; }
        img { max-height: 48px; border-radius: 4px; }
        .actions a { margin-right: 10px; }
        .top { display:flex; justify-content: space-between; align-items:center; margin-bottom: 16px; }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="top">
        <h1>Users</h1>
        <a href="users_new.php">+ New User</a>
    </div>
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Image</th><th>Actions</th></tr></thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo (int)$row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="user" />
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <a href="users_delete.php?id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
