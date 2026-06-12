<?php
require_once 'config.php';
require_once 'includes/db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role     = isset($_POST['role']) ? $_POST['role'] : '';

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required!";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    }
    elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    }
    elseif (!in_array($role, ['student', 'teacher'])) {
        $error = "Invalid role!";
    }
    else {
        $existing = fetch("SELECT id FROM users WHERE email = ?", [$email]);

        if ($existing) {
            $error = "Email already exists!";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            executeQuery(
                "INSERT INTO users (name, email, password, role, status) 
                 VALUES (?, ?, ?, ?, 'pending')",
                [$name, $email, $hashedPassword, $role]
            );

            $success = "Account created. Please wait for admin approval.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?></title>

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
    overflow-x: hidden;
}

@media (max-width: 576px) {
    .row-custom {
        flex-direction: column;
        gap: 0;
    }
}

/* Background blob effects */
body::before {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: rgba(16, 185, 129, 0.2);
    filter: blur(100px);
    border-radius: 50%;
    top: -100px;
    right: -100px;
}
body::after {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: rgba(59, 130, 246, 0.2);
    filter: blur(100px);
    border-radius: 50%;
    bottom: -100px;
    left: -100px;
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

.register-card {
    background: #ffffff;
    width: 100%;
    max-width: 550px; /* Made wider to accommodate 2 columns */
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
    padding: 35px 35px; /* Compacting vertical padding */
    position: relative;
    z-index: 1;
    animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUpFade {
    from { opacity: 0; transform: translateY(20px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.register-header {
    text-align: center;
    margin-bottom: 20px; /* Tightened */
}

.auth-logo {
    font-size: 2rem; /* Reduced */
    color: #10b981;
    margin-bottom: 5px;
}

.register-title {
    font-weight: 800;
    color: #0f172a;
    font-size: 1.8rem; /* Reduced */
    margin-bottom: 5px;
    letter-spacing: -0.5px;
}

.register-subtitle {
    color: #64748b;
    font-size: 0.95rem; /* Reduced */
    font-weight: 400;
    margin-bottom: 0;
}

.row-custom {
    display: flex;
    gap: 15px; /* Creates two columns beautifully */
}
.col-custom {
    flex: 1;
}

.form-control-custom {
    display: block;
    width: 100%;
    padding: 12px 14px; /* Tightened */
    font-size: 0.95rem;
    font-weight: 400;
    color: #334155;
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-control-custom::placeholder {
    color: #94a3b8;
}

.form-control-custom:focus {
    background-color: #fff;
    border-color: #3b82f6;
    outline: 0;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
}

select.form-control-custom {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23334155' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    padding-right: 2.2rem;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.form-group-custom {
    margin-bottom: 16px; /* Tightened */
}

.form-label-custom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 0.85rem;
    color: #475569;
}

.btn-register {
    width: 100%;
    background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
    color: white;
    font-weight: 600;
    border: none;
    padding: 12px; /* Tightened */
    border-radius: 10px;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
    margin-top: 5px;
    cursor: pointer;
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(16, 185, 129, 0.4);
}

.btn-register:active {
    transform: translateY(0);
}

.register-footer {
    text-align: center;
    margin-top: 15px; /* Tightened */
    font-size: 0.95rem;
    color: #64748b;
}

.register-footer a {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 700;
    transition: color 0.3s;
}

.register-footer a:hover {
    color: #1d4ed8;
}

.alert-custom {
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 0.9rem;
    border: none;
    margin-bottom: 20px;
}

.alert-custom-error {
    background-color: #fef2f2;
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

.alert-custom-success {
    background-color: #f0fdf4;
    color: #166534;
    border-left: 4px solid #22c55e;
}
</style>
</head>
<body>

    <a href="<?= base_url('index.php') ?>" class="home-link">
        <i class="bi bi-arrow-left"></i> Home
    </a>

    <div class="register-card">
        <div class="register-header">
            <div class="auth-logo"><i class="bi bi-person-fill-add"></i></div>
            <h3 class="register-title">Create Account</h3>
            <p class="register-subtitle">Join <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?> today</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-custom alert-custom-error d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div style="line-height: 1.2;"><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-custom alert-custom-success d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div style="line-height: 1.2;"><?= htmlspecialchars($success) ?></div>
            </div>
        <?php endif; ?>

        <form method="POST">
            
            <div class="row-custom">
                <div class="col-custom form-group-custom">
                    <label class="form-label-custom">Full Name</label>
                    <input type="text" name="name" class="form-control-custom" placeholder="John Doe" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
                </div>

                <div class="col-custom form-group-custom">
                    <label class="form-label-custom">Email Address</label>
                    <input type="email" name="email" class="form-control-custom" placeholder="you@mail.com" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
                </div>
            </div>
            
            <div class="row-custom">
                <div class="col-custom form-group-custom">
                    <label class="form-label-custom">Password</label>
                    <input type="password" name="password" class="form-control-custom" placeholder="At least 6 chars" required>
                </div>

                <div class="col-custom form-group-custom">
                    <label class="form-label-custom">Select Role</label>
                    <select name="role" class="form-control-custom" required>
                        <option value="" disabled <?= empty($_POST['role']) ? 'selected' : '' ?>>Role</option>
                        <option value="student" <?= (isset($_POST['role']) && $_POST['role'] === 'student') ? 'selected' : '' ?>>Student</option>
                        <option value="teacher" <?= (isset($_POST['role']) && $_POST['role'] === 'teacher') ? 'selected' : '' ?>>Teacher</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-register">
                Register Now
            </button>
        </form>

        <div class="register-footer">
            Already have an account? <a href="<?= base_url('login.php') ?>">Sign In</a>
        </div>
    </div>

</body>
</html>