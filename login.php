<?php
require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

$error = "";

// Redirect if already logged in
if (isLoggedIn()) {
    redirectByRole();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "All fields are required!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    }
    else {
        $user = fetch("SELECT * FROM users WHERE email = ?", [$email]);

        if ($user && password_verify($password, $user['password'])) {
            // Check approval status
            if ($user['status'] !== 'approved') {
                $error = "Your account is pending admin approval!";
            } else {
                loginUser($user);
                redirectByRole();
            }
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?></title>

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

body {
    margin: 0;
    font-family: 'Outfit', sans-serif;
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    min-height: 100vh; /* Allow content to push height */
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    overflow-x: hidden; /* Only hide horizontal overflow */
}

/* Background blob effects */
body::before {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: rgba(99, 102, 241, 0.25);
    filter: blur(100px);
    border-radius: 50%;
    top: -100px;
    left: -100px;
}
body::after {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: rgba(236, 72, 153, 0.2);
    filter: blur(100px);
    border-radius: 50%;
    bottom: -100px;
    right: -100px;
}

.home-link {
    position: absolute;
    top: 25px;
    left: 25px;
    color: rgba(255, 255, 255, 0.6);
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
    z-index: 10;
    padding: 6px 14px;
    border-radius: 50px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
}

.home-link:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    transform: translateX(-3px);
}

.login-card {
    background: #ffffff;
    width: 100%;
    max-width: 400px; /* Slightly compacted */
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
    padding: 35px 30px; /* Reduced vertical padding */
    position: relative;
    z-index: 1;
    animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUpFade {
    from { opacity: 0; transform: translateY(20px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.login-header {
    text-align: center;
    margin-bottom: 25px; /* Tightened */
}

.auth-logo {
    font-size: 2rem; /* Reduced */
    color: #4f46e5;
    margin-bottom: 5px;
}

.login-title {
    font-weight: 800;
    color: #0f172a;
    font-size: 1.8rem; /* Reduced */
    margin-bottom: 5px;
    letter-spacing: -0.5px;
}

.login-subtitle {
    color: #64748b;
    font-size: 0.95rem; /* Reduced */
    font-weight: 400;
    margin-bottom: 0;
}

.form-control-custom {
    display: block;
    width: 100%;
    padding: 12px 16px; /* Tightened padding */
    font-size: 1rem;
    font-weight: 400;
    color: #334155;
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-control-custom::placeholder {
    color: #94a3b8;
}

.form-control-custom:focus {
    background-color: #fff;
    border-color: #6366f1;
    outline: 0;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}

.form-group-custom {
    margin-bottom: 18px; /* Tightened */
}

.form-label-custom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 0.9rem;
    color: #475569;
}

.btn-login {
    width: 100%;
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: white;
    font-weight: 600;
    border: none;
    padding: 12px; /* Tightened */
    border-radius: 12px;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    box-shadow: 0 6px 15px rgba(99, 102, 241, 0.3);
    margin-top: 10px;
    cursor: pointer;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(99, 102, 241, 0.4);
}

.btn-login:active {
    transform: translateY(0);
}

.login-footer {
    text-align: center;
    margin-top: 20px;
    font-size: 0.95rem;
    color: #64748b;
}

.login-footer a {
    color: #4f46e5;
    text-decoration: none;
    font-weight: 700;
    transition: color 0.3s;
}

.login-footer a:hover {
    color: #312e81;
}

.alert-custom {
    border-radius: 12px;
    padding: 12px 14px;
    font-size: 0.95rem;
    border: none;
    background-color: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
    margin-bottom: 20px;
}
</style>
</head>
<body>

    <a href="<?= base_url('index.php') ?>" class="home-link">
        <i class="bi bi-arrow-left"></i> Home
    </a>

    <div class="login-card">
        <div class="login-header">
            <div class="auth-logo"><i class="bi bi-mortarboard-fill"></i></div>
            <h3 class="login-title">Welcome Back</h3>
            <p class="login-subtitle">Sign in to <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?></p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-custom d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div style="line-height: 1.2;"><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group-custom">
                <label class="form-label-custom">Email Address</label>
                <div class="position-relative">
                    <input type="email" name="email" class="form-control-custom" placeholder="name@example.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                </div>
            </div>
            
            <div class="form-group-custom">
                <div class="form-label-custom">
                    <span>Password</span>
                </div>
                <div class="position-relative">
                    <input type="password" name="password" class="form-control-custom" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-login">
                Sign In
            </button>
        </form>

        <div class="login-footer">
            Don't have an account? <a href="<?= base_url('register.php') ?>">Register here</a>
        </div>
    </div>

</body>
</html>