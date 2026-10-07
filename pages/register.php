<?php
$page_title = "Register";
$base_path  = "../";

require_once __DIR__ . '/../includes/header.php';

// ---- Handle form submission ----
$errors = [];
$old    = ['name' => '', 'email' => ''];  // to refill the form after error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Collect and clean input
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';
    $confirm  =      $_POST['confirm']  ?? '';

    // Keep these so we can re-fill the form
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

    // 3. If no errors — for now, show a success message.
    //    (On Day 3, we will save the user to the database.)
    if (empty($errors)) {
        $success = "Validation passed! User registration will be saved on Day 3.";
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