<?php
require_once("../auth/session_admin.php");
require_once("../includes/db.php");
require_once("../includes/functions.php");
require_once("../includes/csrf.php");
require_once("../includes/header.php");


if (!isset($_GET['id'])) {
	echo "<div class='container mt-4'><div class='alert alert-danger'>No request ID provided.</div></div>";
	include("../includes/footer.php");
	exit;
}

$id = (int) $_GET['id'];
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!validateToken($_POST['csrf_token'])) {
		$errors[] = "Invalid CSRF token.";
	} else {
		$message = sanitize($_POST['message']);
		if (empty($message)) {
			$errors[] = "Reply message is required.";
		} else {
			$stmt = $conn->prepare("INSERT INTO contact_replies (request_id, admin_id, message) VALUES (?, ?, ?)");
			$stmt->execute([$id, $_SESSION['user_id'], $message]);

			$conn->prepare("UPDATE contact_requests SET status = 'replied' WHERE id = ?")->execute([$id]);
			clearToken();
			$success = true;
		}
	}
}
?>

<div class="container mt-4" style="max-width: 600px;">
	<div class="card shadow-sm">
		<div class="card-body">
			<div class="row mb-3">
				<div class="col-12 col-md-6 d-flex align-items-center mb-2 mb-md-0">
					<h4 class="card-title m-0">✉️ Reply to Request</h4>
				</div>
				<div class="col-12 col-md-6 text-md-end">
					<a href="dashboard.php" class="btn btn-outline-p btn-sm">← Back to Admin Panel</a>
				</div>
			</div>



			<?php if ($success): ?>
				<div class="alert alert-success">Reply sent successfully.</div>
			<?php elseif ($errors): ?>
				<div class="alert alert-danger">
					<?= implode("<br>", $errors) ?>
				</div>
			<?php endif; ?>

			<form method="POST">
				<input type="hidden" name="csrf_token" value="<?= generateToken() ?>">
				<div class="mb-3">
					<label>Reply Message</label>
					<textarea name="message" rows="5" class="form-control" required></textarea>
				</div>
				<button class="btn btn-p w-100">Send Reply</button>
			</form>
		</div>
	</div>
</div>

<?php include("../includes/footer.php"); ?>