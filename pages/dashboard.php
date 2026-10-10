<?php
$page_title = "Dashboard";
$base_path  = "../";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/db.php';

// Only logged-in users
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id   = (int)$_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// Fetch this user's items
$stmt = $conn->prepare(
    "SELECT * FROM items WHERE user_id = ? ORDER BY created_at DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$my_items = $stmt->get_result();

// Count stats
$total_lost  = 0;
$total_found = 0;
$items_arr   = [];
while ($row = $my_items->fetch_assoc()) {
    $items_arr[] = $row;
    if ($row['item_type'] === 'lost')  $total_lost++;
    if ($row['item_type'] === 'found') $total_found++;
}
$stmt->close();
?>

<h1 class="page-title">Dashboard</h1>
<p class="page-subtitle">
    Welcome back, <strong><?php echo htmlspecialchars($user_name); ?></strong>!
</p>

<!-- Stat cards -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-num"><?php echo $total_lost; ?></div>
        <div class="stat-label">Lost Items Reported</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?php echo $total_found; ?></div>
        <div class="stat-label">Found Items Reported</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?php echo count($items_arr); ?></div>
        <div class="stat-label">Total Posts</div>
    </div>
</div>

<div class="card">
    <h2>Quick Actions</h2>
    <div style="margin-top: 16px; display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="report.php" class="btn btn-primary">+ Report an Item</a>
        <a href="lost_items.php" class="btn btn-primary">View Lost Items</a>
        <a href="found_items.php" class="btn btn-primary">View Found Items</a>
    </div>
</div>

<h2 style="margin-top: 32px; margin-bottom: 8px;">My Posts</h2>

<?php if (empty($items_arr)): ?>
    <div class="card">
        <p>You haven't reported any items yet. <a href="report.php">Report your first item →</a></p>
    </div>
<?php else: ?>
    <div class="items-grid">
        <?php foreach ($items_arr as $item): ?>
            <div class="item-card">
                <?php if (!empty($item['image']) && file_exists(__DIR__ . '/../' . $item['image'])): ?>
                    <img src="<?php echo $base_path . htmlspecialchars($item['image']); ?>" alt="Item photo">
                <?php else: ?>
                    <div class="item-card-noimg">No Photo</div>
                <?php endif; ?>

                <div class="item-card-body">
                    <span class="badge badge-<?php echo $item['item_type']; ?>" style="margin-bottom:6px;">
                        <?php echo strtoupper($item['item_type']); ?>
                    </span>
                    <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>

                    <?php if (!empty($item['location'])): ?>
                        <p class="item-meta">📍 <?php echo htmlspecialchars($item['location']); ?></p>
                    <?php endif; ?>

                    <p class="item-meta">
                        <small><?php echo date('d M Y', strtotime($item['created_at'])); ?></small>
                    </p>

                    <a href="item_detail.php?id=<?php echo (int)$item['id']; ?>" class="btn btn-primary btn-block" style="margin-top:10px;">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>