<?php
$page_title = "Login";
$base_path  = "../";

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/db.php';

// If already logged in, send them to dashboard
if (!empty($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$errors = [];
$old    = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']    ?? '');
    $password =      $_POST['password'] ?? '';

    $old['email'] = $email;

    // ---- Validate ----
    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    }

    // ---- Check credentials against database ----
    if (empty($errors)) {
        $stmt = $conn->prepare(
            "SELECT id, full_name, password FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify the password against the stored hash
            if (password_verify($password, $user['password'])) {
                // ✅ Login success → store user info in session
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];

                header("Location: dashboard.php");
                exit;
            } else {
                $errors[] = "Incorrect email or password.";
            }
        } else {
            // Security tip: same message for "wrong email" and "wrong password"
            // so attackers can't tell which one exists.
            $errors[] = "Incorrect email or password.";
        }

        $stmt->close();
    }
}
?>

<div class="form-wrapper">
    <div class="form-card">

        <h1 class="form-title">Welcome back</h1>
        <p class="form-subtitle">Login to your account to continue.</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $err): ?>
                    • <?php echo htmlspecialchars($err); ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" novalidate>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="you@example.com"
                       value="<?php echo htmlspecialchars($old['email']); ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>

        </form>

        <p class="form-footer-text">
            Don't have an account? <a href="register.php">Register here</a>
        </p>

    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>