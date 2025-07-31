<?php
session_start();
require_once("../includes/db.php");
require_once("../includes/functions.php");
$is_admin = isset($_GET['admin']) && $_GET['admin'] == 1;

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
       if ($user) {
    if ($is_admin && $user['role'] !== 'admin') {
        $errors[] = "Access denied. You are not an admin.";
    } else {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../user/dashboard.php");
        }
        exit;
    }
}
    } else {
        $errors[] = "Invalid email or password.";
    }
}
?>

<?php include("../includes/header.php"); ?>
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="mb-4 text-center"><?= $is_admin ? '🔒 Admin Login' : '👤 User Login' ?></h2>

    <?php if ($errors): ?>
        <div class="alert alert-danger"><?= implode("<br>", $errors) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required />
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required />
        </div>
        <button class="btn btn-success w-100">Login</button>
    </form>

    <div class="mt-3 text-center">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>
<?php include("../includes/footer.php"); ?>
