<?php
$page_title = "Report Item";
$base_path  = "../";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/db.php';

// Only logged-in users can report
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$errors  = [];
$success = '';
$old = [
    'item_type'   => 'lost',
    'item_name'   => '',
    'description' => '',
    'location'    => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Collect input
    $old['item_type']   = $_POST['item_type']   ?? 'lost';
    $old['item_name']   = trim($_POST['item_name']   ?? '');
    $old['description'] = trim($_POST['description'] ?? '');
    $old['location']    = trim($_POST['location']    ?? '');

    $item_type   = $old['item_type'];
    $item_name   = $old['item_name'];
    $description = $old['description'];
    $location    = $old['location'];

    // 2. Validate
    if (!in_array($item_type, ['lost', 'found'])) {
        $errors[] = "Invalid item type.";
    }
    if ($item_name === '') {
        $errors[] = "Item name is required.";
    } elseif (strlen($item_name) < 3) {
        $errors[] = "Item name must be at least 3 characters.";
    }

    // 3. Handle image upload (optional)
    $image_path = null;
    if (!empty($_FILES['image']['name'])) {

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file    = $_FILES['image'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Image upload failed (error code: {$file['error']}).";
        } else {
            // Size limit: 3 MB
            if ($file['size'] > 3 * 1024 * 1024) {
                $errors[] = "Image must be smaller than 3 MB.";
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $errors[] = "Only JPG, PNG, GIF, or WEBP images are allowed.";
            }

            // Verify it is truly an image
            if (empty($errors) && @getimagesize($file['tmp_name']) === false) {
                $errors[] = "The uploaded file is not a valid image.";
            }

            // Save the file
            if (empty($errors)) {
                $new_name = 'item_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest     = __DIR__ . '/../uploads/items/' . $new_name;

                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    // Store relative path from project root for easy display
                    $image_path = 'uploads/items/' . $new_name;
                } else {
                    $errors[] = "Could not save the uploaded image.";
                }
            }
        }
    }

    // 4. Insert into DB
    if (empty($errors)) {
        $user_id = (int)$_SESSION['user_id'];

        $stmt = $conn->prepare(
            "INSERT INTO items (user_id, item_type, item_name, description, image, location)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "isssss",
            $user_id, $item_type, $item_name, $description, $image_path, $location
        );

        if ($stmt->execute()) {
            // Redirect to the appropriate list page
            header("Location: " . ($item_type === 'lost' ? 'lost_items.php' : 'found_items.php'));
            exit;
        } else {
            $errors[] = "Could not save item. Please try again.";
        }
        $stmt->close();
    }
}
?>

<div class="form-wrapper">
    <div class="form-card">

        <h1 class="form-title">Report an Item</h1>
        <p class="form-subtitle">Fill in the details below. You can attach a photo.</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?>
                    • <?php echo htmlspecialchars($e); ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" novalidate>

            <div class="form-group">
                <label for="item_type">Type</label>
                <select id="item_type" name="item_type" required>
                    <option value="lost"  <?php if ($old['item_type'] === 'lost')  echo 'selected'; ?>>I Lost This Item</option>
                    <option value="found" <?php if ($old['item_type'] === 'found') echo 'selected'; ?>>I Found This Item</option>
                </select>
            </div>

            <div class="form-group">
                <label for="item_name">Item Name</label>
                <input type="text" id="item_name" name="item_name" placeholder="e.g. Black Wallet"
                       value="<?php echo htmlspecialchars($old['item_name']); ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Color, brand, contents, distinguishing marks..."><?php echo htmlspecialchars($old['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="Where was it lost / found?"
                       value="<?php echo htmlspecialchars($old['location']); ?>">
            </div>

            <div class="form-group">
                <label for="image">Photo (optional, max 3 MB)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Submit Report</button>

        </form>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>