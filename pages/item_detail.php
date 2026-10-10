<?php
$page_title = "Item Details";
$base_path  = "../";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/db.php';

// Get item ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo '<div class="card"><p>Invalid item.</p></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Fetch item + reporter
$stmt = $conn->prepare(
    "SELECT items.*, users.full_name AS reporter_name, users.email AS reporter_email
     FROM items
     JOIN users ON users.id = items.user_id
     WHERE items.id = ? LIMIT 1"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo '<div class="card"><p>Item not found.</p></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$item = $result->fetch_assoc();
$stmt->close();

$type_label = $item['item_type'] === 'lost' ? 'Lost Item' : 'Found Item';
?>

<h1 class="page-title"><?php echo htmlspecialchars($item['item_name']); ?></h1>
<p class="page-subtitle">
    <span class="badge badge-<?php echo $item['item_type']; ?>">
        <?php echo $type_label; ?>
    </span>
    &nbsp; Status: <strong><?php echo htmlspecialchars(ucfirst($item['status'])); ?></strong>
</p>

<div class="item-detail-wrap">

    <div class="item-detail-image">
        <?php if (!empty($item['image']) && file_exists(__DIR__ . '/../' . $item['image'])): ?>
            <img src="<?php echo $base_path . htmlspecialchars($item['image']); ?>" alt="Item photo">
        <?php else: ?>
            <div class="item-card-noimg">No Photo Available</div>
        <?php endif; ?>
    </div>

    <div class="item-detail-info">
        <div class="card">
            <h2>Description</h2>
            <p style="margin-top:8px;">
                <?php echo !empty($item['description'])
                    ? nl2br(htmlspecialchars($item['description']))
                    : '<em>No description provided.</em>'; ?>
            </p>
        </div>

        <div class="card">
            <h2>Details</h2>
            <p style="margin-top:8px;">
                <strong>Location:</strong> <?php echo !empty($item['location']) ? htmlspecialchars($item['location']) : '—'; ?><br>
                <strong>Reported by:</strong> <?php echo htmlspecialchars($item['reporter_name']); ?><br>
                <strong>Reported on:</strong> <?php echo date('d M Y, h:i A', strtotime($item['created_at'])); ?>
            </p>
        </div>

        <?php if (!empty($_SESSION['user_id'])): ?>
            <div class="card">
                <h2>Is this yours?</h2>
                <p style="margin-top:8px;color:#6b7280;">
                    Claim submission will be available on Day 5.
                </p>
            </div>
        <?php else: ?>
            <div class="card">
                <p>Please <a href="login.php">login</a> to submit a claim for this item.</p>
            </div>
        <?php endif; ?>

        <a href="javascript:history.back()" class="btn btn-primary" style="margin-top:6px;">← Back</a>
    </div>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>