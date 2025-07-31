<?php
require_once("../auth/session.php");
require_once("../includes/db.php");
require_once("../includes/functions.php");
require_once("../includes/csrf.php");
require_once("../includes/header.php");

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!validateToken($_POST['csrf_token'])) {
    $errors[] = "Invalid CSRF token.";
  } else {
    $subject = sanitize($_POST['subject']);
    $message = sanitize($_POST['message']);

    if (empty($subject) || empty($message)) {
      $errors[] = "All fields are required.";
    } else {
      $stmt = $conn->prepare("INSERT INTO contact_requests (user_id, subject, message) VALUES (?, ?, ?)");
      $stmt->execute([$_SESSION['user_id'], $subject, $message]);
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
          <h4 class="m-0">📩 Contact Form</h4>
        </div>
        <div class="col-12 col-md-6 text-md-end">
          <a href="dashboard.php" class="btn btn-outline-p btn-sm">← Back to Dashboard</a>
        </div>
      </div>

      <!-- Success/Error alerts here -->
      <?php if ($success): ?>
        <div class="alert alert-success">Request sent successfully.</div>
      <?php elseif ($errors): ?>
        <div class="alert alert-danger">
          <?= implode("<br>", $errors) ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= generateToken() ?>">
        <div class="mb-3">
          <label>Subject</label>
          <input type="text" name="subject" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Message</label>
          <textarea name="message" rows="5" class="form-control" required></textarea>
        </div>
        <button class="btn btn-success w-100">Submit</button>
      </form>
    </div>
  </div>
</div>


<?php include("../includes/footer.php"); ?>