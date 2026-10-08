<?php
$page_title = "Register";
$base_path  = "../";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/db.php';

// ---- Handle form submission ----
$errors  = [];
$success = '';
$old     = ['name' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Collect and clean input
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';
    $confirm  =      $_POST['confirm']  ?? '';

    $old['name']  = $name;
    $old['email'] = $email;

    // 2. Validate
    if ($name === '') {
        $errors[] = "Full name is required.";
    } elseif (strlen($name) < 3) {
        $errors[] = "Full name must be at least 3 characters.";
    }

    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // 3. Check if email already exists in the database
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "This email is already registered. Please login instead.";
        }
        $stmt->close();
    }

    // 4. If still no errors — save the user to the database
    if (empty($errors)) {
        // Hash the password securely (never store plain passwords!)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $success = "Registration successful! You can now login.";
            // Clear the form
            $old = ['name' => '', 'email' => ''];
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}
?>

<div class="form-wrapper">
    <div class="form-card">

        <h1 class="form-title">Create an account</h1>
        <p class="form-subtitle">Register to report and claim items.</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $err): ?>
                    • <?php echo htmlspecialchars($err); ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
                <br><a href="login.php" style="color:#166534;font-weight:600;">Go to Login →</a>
            </div>
        <?php endif; ?>

        <form action="" method="POST" novalidate>

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="John Doe"
                       value="<?php echo htmlspecialchars($old['name']); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="you@example.com"
                       value="<?php echo htmlspecialchars($old['email']); ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="At least 6 characters">
            </div>

            <div class="form-group">
                <label for="confirm">Confirm Password</label>
                <input type="password" id="confirm" name="confirm" placeholder="Re-enter password">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Create Account</button>

        </form>

        <p class="form-footer-text">
            Already have an account? <a href="login.php">Login here</a>
        </p>

    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>