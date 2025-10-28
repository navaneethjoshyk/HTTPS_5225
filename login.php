

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);//email=navaneeth@gmail.com&pass=Temp@1234


if (!session_id()) {
    session_start();
}

require_once __DIR__ . '/connect.php';

if (!$connect) {
    
    die('DB connection failed: ' . mysqli_connect_error());
}


$error = '';
$email_prefill = '';


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$csrf_token = $_SESSION['csrf_token'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email    = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $token    = $_POST['csrf_token'] ?? '';

    $email_prefill = $email; 

    
    if ($token !== $_SESSION['csrf_token']) {
        $error = 'Security check failed. Please reload the page and try again.';
    } elseif ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } else {
        
        $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($connect, $sql);
        if (!$stmt) {
            
            $error = 'Login error (prepare failed). Please verify the "users" table and columns (id, name, email, password).';
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            
            $user = null;

            if (function_exists('mysqli_stmt_get_result')) {
               
                $res = mysqli_stmt_get_result($stmt);
                if ($res) {
                    $user = mysqli_fetch_assoc($res);
                }
            } else {
               
                mysqli_stmt_bind_result($stmt, $id, $name, $em, $hash);
                if (mysqli_stmt_fetch($stmt)) {
                    $user = [
                        'id'       => $id,
                        'name'     => $name,
                        'email'    => $em,
                        'password' => $hash
                    ];
                }
            }
            mysqli_stmt_close($stmt);

            
            if ($user && is_string($user['password']) && password_verify($password, $user['password'])) {
                
                $_SESSION['user_id']   = (int)$user['id'];
                $_SESSION['user_name'] = (string)$user['name'];
                $_SESSION['user_email']= (string)$user['email'];

                
                unset($_SESSION['csrf_token']);

                
                $next = $_GET['next'] ?? 'index.php';
                if (!preg_match('/^[a-zA-Z0-9_\-\/\.]+$/', $next)) {
                    $next = 'index.php';
                }
                header('Location: ' . $next);
                exit;
            } else {
                
                $error = 'Invalid email or password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LMS Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <style>
    :root {
      --bg: #f6f7fb;
      --card: #ffffff;
      --text: #111827;
      --muted: #6b7280;
      --primary: #0ea5e9;
      --primary-hover: #0284c7;
      --error-bg: #fee2e2;
      --error-text: #b91c1c;
      --radius: 12px;
    }
    * { box-sizing: border-box; }
    html, body {
      height: 100%;
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
      background: var(--bg);
      color: var(--text);
    }
    .wrap {
      min-height: 100%;
      display: grid;
      place-items: center;
      padding: 24px;
    }
    .card {
      width: 100%;
      max-width: 420px;
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      padding: 28px;
    }
    h1 {
      margin: 0 0 8px 0;
      font-size: 1.5rem;
    }
    p.sub {
      margin: 0 0 20px 0;
      color: var(--muted);
      font-size: 0.95rem;
    }
    .error {
      background: var(--error-bg);
      color: var(--error-text);
      border-radius: 10px;
      padding: 10px 12px;
      margin-bottom: 14px;
      font-size: 0.95rem;
    }
    form { display: grid; gap: 12px; }
    label { font-size: 0.95rem; }
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px 14px;
      border-radius: 10px;
      border: 1px solid #e5e7eb;
      background: #fff;
      font-size: 1rem;
      outline: none;
    }
    input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(14,165,233,0.15);
    }
    button[type="submit"] {
      width: 100%;
      padding: 12px 14px;
      border: 0;
      border-radius: 10px;
      background: var(--primary);
      color: white;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
    }
    button[type="submit"]:hover {
      background: var(--primary-hover);
    }
    .meta {
      margin-top: 12px;
      font-size: 0.9rem;
      color: var(--muted);
      text-align: center;
    }
    .help {
      margin-top: 8px;
      font-size: 0.85rem;
      color: var(--muted);
      text-align: center;
      line-height: 1.4;
    }
    .help code {
      background: #f3f4f6;
      padding: 2px 6px;
      border-radius: 6px;
      font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
      font-size: 0.85rem;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Welcome back</h1>
      <p class="sub">Sign in to your LMS account</p>

      <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php<?php echo isset($_GET['next']) ? '?next=' . urlencode($_GET['next']) : ''; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">

        <div>
          <label for="email">Email</label>
          <input
            id="email"
            type="email"
            name="email"
            autocomplete="username"
            placeholder="you@example.com"
            value="<?php echo htmlspecialchars($email_prefill, ENT_QUOTES, 'UTF-8'); ?>"
            required
          >
        </div>

        <div>
          <label for="password">Password</label>
          <input
            id="password"
            type="password"
            name="password"
            autocomplete="current-password"
            placeholder="••••••••"
            required
          >
        </div>

        <button type="submit">Log in</button>
      </form>

      <div class="help">
        <p>Default seeded admin (if you imported <code>users.sql</code>):</p>
        <p><code>admin@example.com</code> / <code>admin123</code></p>
      </div>

      <div class="meta">
        Having trouble? Verify the <code>users</code> table and that passwords are bcrypt hashes.
      </div>
    </div>
  </div>
</body>
</html>
