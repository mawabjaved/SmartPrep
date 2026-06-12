<?php
require_once 'config.php';
require_once 'includes/auth.php';

// Perform secure logout
logoutUser();

// Redirect to login page
header("Location: " . base_url('login.php'));
exit();