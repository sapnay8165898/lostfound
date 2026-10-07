<?php
// Make sure the session is started (safe to call multiple times)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $page_title should be set by the page BEFORE including this header.
// If not set, use a default.
$page_title = $page_title ?? "Lost and Found";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Lost &amp; Found</title>

    <link rel="stylesheet" href="<?php echo $base_path ?? ''; ?>assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <a href="<?php echo $base_path ?? ''; ?>index.php" class="brand">
        Lost<span>&</span>Found
    </a>
        <ul>
        <li><a href="<?php echo $base_path ?? ''; ?>index.php">Home</a></li>
        <li><a href="<?php echo $base_path ?? ''; ?>pages/lost_items.php">Lost Items</a></li>
        <li><a href="<?php echo $base_path ?? ''; ?>pages/found_items.php">Found Items</a></li>
        <li><a href="<?php echo $base_path ?? ''; ?>pages/report.php">Report Item</a></li>

        <?php if (!empty($_SESSION['user_id'])): ?>
            <!-- Logged in -->
            <li><a href="<?php echo $base_path ?? ''; ?>pages/dashboard.php">Dashboard</a></li>
            <li><a href="<?php echo $base_path ?? ''; ?>pages/logout.php">Logout</a></li>
        <?php else: ?>
            <!-- Not logged in -->
            <li><a href="<?php echo $base_path ?? ''; ?>pages/login.php">Login</a></li>
            <li><a href="<?php echo $base_path ?? ''; ?>pages/register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main class="container">