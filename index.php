<?php
// Start PHP session (needed later for login)
session_start();

// Set the page title for this page
$page_title = "Home";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found Management System</title>

    <!-- Our theme stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar">
        <a href="index.php" class="brand">Lost<span>&</span>Found</a>
        <ul>
    <li><a href="index.php" class="active">Home</a></li>
    <li><a href="pages/login.php">Login</a></li>
    <li><a href="pages/register.php">Register</a></li>
    <li><a href="pages/report.php">Report Item</a></li>
    <li><a href="pages/lost_items.php">Lost Items</a></li>
    <li><a href="pages/found_items.php">Found Items</a></li>
</ul>
    </nav>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="container">

        <h1 class="page-title">Welcome to Lost &amp; Found</h1>
        <p class="page-subtitle">
            A simple platform to report lost items and help reunite them with their owners.
        </p>

        <div class="card">
            <h2>What you can do here</h2>
            <ul style="margin-top: 12px; padding-left: 20px;">
                <li>Report an item you have lost</li>
                <li>Report an item you have found</li>
                <li>Browse lost and found items</li>
                <li>Submit a claim if an item belongs to you</li>
                <li>Verify ownership using photos and details</li>
            </ul>
        </div>

        <div class="card">
            <h2>Get started</h2>
            <p style="margin-top: 8px; color: #6b7280;">
                Login or Register to begin reporting and claiming items.
            </p>
        </div>

    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="footer">
        &copy; <?php echo date('Y'); ?> Lost <span>&amp;</span> Found Management System
    </footer>

</body>
</html>