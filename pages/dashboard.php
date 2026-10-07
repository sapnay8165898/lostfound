<?php
$page_title = "Dashboard";
$base_path  = "../";

require_once __DIR__ . '/../includes/header.php';

// Temporary protection: only logged-in users should see this.
// (Real login check will use the DB on Day 3.)
if (empty($_SESSION['user_id'])) {
    // Not logged in? Send them to login page.
    header("Location: login.php");
    exit;
}
?>

<h1 class="page-title">Dashboard</h1>
<p class="page-subtitle">
    Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></strong>!
</p>

<div class="card">
    <h2>Quick Actions</h2>
    <p style="margin-top: 8px; color: #6b7280;">
        These will be functional from Day 4 onwards.
    </p>
    <div style="margin-top: 16px; display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="report.php" class="btn btn-primary">Report an Item</a>
        <a href="lost_items.php" class="btn btn-primary">View Lost Items</a>
        <a href="found_items.php" class="btn btn-primary">View Found Items</a>
    </div>
</div>

<div class="card">
    <h2>My Posts</h2>
    <p style="margin-top: 8px; color: #6b7280;">
        Your reported lost/found items will appear here once item management is built.
    </p>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>