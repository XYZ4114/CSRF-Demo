<?php
session_start();
require_once("../includes/db.php");
require_once("../includes/functions.php");

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Basic validation
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = "Email is already registered.";
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashed]);
        $success = true;
    }
}
?>

<?php include("../includes/header.php"); ?>
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center">Create an Account</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Registration successful! <a href="login.php">Login here</a>.</div>
    <?php elseif ($errors): ?>
        <div class="alert alert-danger">
            <?= implode("<br>", $errors) ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="confirm" class="form-control" required>
        </div>
        <button class="btn btn-p w-100">Register</button>
    </form>
    <div class="mt-3 text-center">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
