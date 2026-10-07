<?php
// Set variables BEFORE including header
$page_title = "Home";
$base_path  = "";   // empty because index.php is in the root folder

// Load the shared header (opens <body>, shows navbar, opens <main class="container">)
require_once __DIR__ . '/includes/header.php';
?>

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

<?php
// Load the shared footer (closes </main>, shows footer, closes </body></html>)
require_once __DIR__ . '/includes/footer.php';
?>