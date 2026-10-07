<?php
$page_title = "Login";
$base_path  = "../";

require_once __DIR__ . '/../includes/header.php';
?>

<div class="form-wrapper">
    <div class="form-card">

        <h1 class="form-title">Welcome back</h1>
        <p class="form-subtitle">Login to your account to continue.</p>

        <form action="" method="POST">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>

        </form>
        <!-- TEMPORARY: fake-login button for Day 2 testing -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <?php
                // Simulate a logged-in session (Day 3 will replace this with a real DB check)
                $_SESSION['user_id']   = 1;
                $_SESSION['user_name'] = 'John';
                header("Location: dashboard.php");
                exit;
            ?>
        <?php endif; ?>
        <p class="form-footer-text">
            Don't have an account? <a href="register.php">Register here</a>
        </p>

    </div>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>