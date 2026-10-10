<?php
$page_title = "Found Items";
$base_path  = "../";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/db.php';

$sql = "SELECT items.*, users.full_name AS reporter_name
        FROM items
        JOIN users ON users.id = items.user_id
        WHERE items.item_type = 'found'
        ORDER BY items.created_at DESC";
$result = $conn->query($sql);
?>

<h1 class="page-title">Found Items</h1>
<p class="page-subtitle">Browse all items reported as found.</p>

<?php if ($result->num_rows === 0): ?>
    <div class="card">
        <p>No found items reported yet. Be the first to <a href="report.php">report one</a>.</p>
    </div>
<?php else: ?>
    <div class="items-grid">
        <?php while ($item = $result->fetch_assoc()): ?>
            <div class="item-card">
                <?php if (!empty($item['image']) && file_exists(__DIR__ . '/../' . $item['image'])): ?>
                    <img src="<?php echo $base_path . htmlspecialchars($item['image']); ?>" alt="Item photo">
                <?php else: ?>
                    <div class="item-card-noimg">No Photo</div>
                <?php endif; ?>

                <div class="item-card-body">
                    <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>

                    <?php if (!empty($item['location'])): ?>
                        <p class="item-meta">📍 <?php echo htmlspecialchars($item['location']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($item['description'])): ?>
                        <p class="item-desc"><?php echo htmlspecialchars(substr($item['description'], 0, 90)); ?><?php echo strlen($item['description']) > 90 ? '…' : ''; ?></p>
                    <?php endif; ?>

                    <p class="item-meta">
                        Reported by <strong><?php echo htmlspecialchars($item['reporter_name']); ?></strong><br>
                        <small><?php echo date('d M Y', strtotime($item['created_at'])); ?></small>
                    </p>

                    <a href="item_detail.php?id=<?php echo (int)$item['id']; ?>" class="btn btn-primary btn-block" style="margin-top:10px;">View Details</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>