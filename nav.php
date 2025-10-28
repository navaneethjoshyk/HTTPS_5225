<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<nav>
    <ul style="display:flex; gap:16px; list-style:none; padding:0;">
        <li><a href="index.php">Schools</a></li>
        <li><a href="addschool.php">Add School</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="users_index.php">Users</a></li>
            <li>Hello, <?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
