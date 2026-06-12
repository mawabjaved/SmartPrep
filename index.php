<?php
require_once 'config.php';
require_once 'includes/auth.php';

// If logged in → redirect to dashboard
if (isLoggedIn()) {
    redirectByRole();
}

// Page title
$title = "Welcome to " . (defined('APP_NAME') ? APP_NAME : 'SmartPrep');
require_once 'includes/header.php';
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

.landing-wrapper {
    font-family: 'Outfit', sans-serif;
    flex: 1;
    width: 100%;
}

.hero {
    background: radial-gradient(circle at top left, rgba(59, 130, 246, 0.25), transparent 38%),
                radial-gradient(circle at bottom right, rgba(168, 85, 247, 0.2), transparent 35%),
                linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #111827 100%);
    color: #f8fafc;
    padding: 88px 20px 72px;
}

.hero-content {
    max-width: 980px;
    margin: 0 auto;
    text-align: center;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1px solid rgba(148, 163, 184, 0.35);
    background: rgba(15, 23, 42, 0.45);
    color: #cbd5e1;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 0.85rem;
    margin-bottom: 22px;
}

.hero-title {
    font-size: clamp(2rem, 5vw, 3.7rem);
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -0.02em;
    margin: 0 0 16px;
}

.hero-subtitle {
    max-width: 760px;
    margin: 0 auto 30px;
    color: #cbd5e1;
    font-size: clamp(1rem, 2.2vw, 1.15rem);
    line-height: 1.65;
}

.hero-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-hero-primary,
.btn-hero-secondary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 22px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-hero-primary {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    color: #fff;
    border: 1px solid transparent;
}

.btn-hero-primary:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 10px 24px rgba(37, 99, 235, 0.35);
}

.btn-hero-secondary {
    border: 1px solid rgba(148, 163, 184, 0.4);
    color: #e2e8f0;
    background: rgba(15, 23, 42, 0.28);
}

.btn-hero-secondary:hover {
    color: #fff;
    border-color: rgba(191, 219, 254, 0.6);
}

.quick-stats {
    margin-top: 28px;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
}

.stat-box {
    border: 1px solid rgba(148, 163, 184, 0.25);
    background: rgba(15, 23, 42, 0.35);
    border-radius: 12px;
    padding: 14px;
}

.stat-box .value {
    font-size: 1.3rem;
    font-weight: 800;
}

.stat-box .label {
    font-size: 0.82rem;
    color: #cbd5e1;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.features-section {
    padding: 72px 20px;
    background: #f8fafc;
}

.section-heading {
    max-width: 760px;
    margin: 0 auto 32px;
    text-align: center;
}

.section-heading h2 {
    margin: 0 0 10px;
    color: #0f172a;
    font-weight: 800;
}

.section-heading p {
    margin: 0;
    color: #64748b;
}

.feature-card {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #e2e8f0;
    height: 100%;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.04);
}

.feature-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    font-size: 1.3rem;
    background: #eff6ff;
    color: #2563eb;
}

.feature-card h3 {
    margin: 0 0 8px;
    color: #0f172a;
    font-size: 1.15rem;
    font-weight: 700;
}

.feature-card p {
    margin: 0;
    color: #64748b;
    line-height: 1.6;
}

@media (max-width: 767.98px) {
    .hero {
        padding: 62px 14px 52px;
    }
    .quick-stats {
        grid-template-columns: 1fr;
    }
    .hero-actions a {
        width: 100%;
    }
    .features-section {
        padding: 56px 14px;
    }
}
</style>

<div class="landing-wrapper">
    <section class="hero">
        <div class="hero-content">
            <span class="hero-badge">
                <i class="bi bi-shield-check"></i> Secure Academic Platform
            </span>
            <h1 class="hero-title">Manage Campus Workflows in One Place</h1>
            <p class="hero-subtitle">
                <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?> helps admins, teachers, and students work faster with streamlined courses, attendance, assignments, and results.
            </p>
            <div class="hero-actions">
                <a href="<?= base_url('login.php') ?>" class="btn-hero-primary">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
                <a href="<?= base_url('register.php') ?>" class="btn-hero-secondary">
                    <i class="bi bi-person-plus"></i> Register
                </a>
            </div>
            <div class="quick-stats">
                <div class="stat-box">
                    <div class="value">24/7</div>
                    <div class="label">Availability</div>
                </div>
                <div class="stat-box">
                    <div class="value">Role-Based</div>
                    <div class="label">Access Control</div>
                </div>
                <div class="stat-box">
                    <div class="value">Responsive</div>
                    <div class="label">All Devices</div>
                </div>
            </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <div class="section-heading">
                <h2>Why choose <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?>?</h2>
                <p>Everything needed for daily academic operations, from classroom planning to grading and communication.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <span class="feature-icon"><i class="bi bi-journal-bookmark-fill"></i></span>
                        <h3>Course and Department Management</h3>
                        <p>Create structured departments, courses, and subjects for clear academic organization.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <span class="feature-icon"><i class="bi bi-clipboard-data-fill"></i></span>
                        <h3>Attendance and Results</h3>
                        <p>Record attendance, upload marks, and review academic progress with role-based control.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <span class="feature-icon"><i class="bi bi-megaphone-fill"></i></span>
                        <h3>Assignments and Announcements</h3>
                        <p>Share lectures, publish assignments, and deliver important updates instantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once 'includes/footer.php'; ?>