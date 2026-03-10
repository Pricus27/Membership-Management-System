<?php
require 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && hash('sha256', $password) === $user['password_hash']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['company_id'] = $user['company_id'];

        if ($user['role'] === 'admin') {
            header('Location: admin_dashboard.php');
        } else {
            header('Location: company_dashboard.php');
        }
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MMS - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background:#f5f5f7;
            min-height:100vh;
        }
        .top-bar {
            height:64px;
            display:flex;
            align-items:center;
            padding:0 3rem;
        }
        .brand-logo {
            width:34px;
            height:34px;
            border-radius:50%;
            background:#0f172a;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-weight:600;
            margin-right:.6rem;
        }
        .brand-text {
            font-weight:600;
            letter-spacing:.04em;
        }
        .login-wrapper {
            display:flex;
            justify-content:center;
            align-items:flex-start;
            padding:3rem 1rem 4rem;
        }
        .login-card {
            max-width:520px;
            width:100%;
            padding:48px 60px;
            background:#ffffff;
            border-radius:12px;
            box-shadow:0 24px 60px rgba(15,23,42,.12);
        }
        .login-title {
            font-size:1.8rem;
            letter-spacing:.12em;
        }
        .login-btn {
            background:#111827;
            border-color:#111827;
            padding:.75rem 1rem;
            font-weight:500;
        }
        .login-btn:hover {
            background:#020617;
            border-color:#020617;
        }
        .small-link {
            font-size:.86rem;
        }
    </style>
</head>
<body>
<header class="top-bar">
    <div class="d-flex align-items-center">
        <div class="brand-logo">M</div>
        <span class="brand-text">MMS</span>
    </div>
</header>

<main class="login-wrapper">
    <div class="login-card">
        <div class="mb-4 text-center">
            <h1 class="login-title mb-3">MEMBERSHIP MANAGEMENT SYSTEM</h1>
            <p class="text-muted mb-0">Welcome back! Please enter your details.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control border-0 border-bottom rounded-0 ps-0" required>
            </div>
            <div class="mb-2">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control border-0 border-bottom rounded-0 ps-0" required>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="terms">
                    <label class="form-check-label small-link" for="terms">Terms &amp; Conditions</label>
                </div>
                <a href="#" class="small-link text-decoration-none">Forgot Password</a>
            </div>
            <div class="d-grid mb-3">
                <button class="btn login-btn btn-lg" type="submit">Log in</button>
            </div>
            <div class="text-center small-link">
                Don’t have an account?
                <a href="#" class="text-decoration-none">Sign up for free</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
