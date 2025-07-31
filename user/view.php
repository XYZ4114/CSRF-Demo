<?php
require_once("../auth/session.php");
require_once("../includes/db.php");
require_once("../includes/header.php");

if (!isset($_GET['id'])) {
  header("Location: dashboard.php");
  exit;
}

$request_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM contact_requests WHERE id = ? AND user_id = ?");
$stmt->execute([$request_id, $_SESSION['user_id']]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
  echo "<div class='container mt-5 alert alert-danger'>Request not found.</div>";
  include("../includes/footer.php");
  exit;
}

$replyStmt = $conn->prepare("SELECT * FROM contact_replies WHERE request_id = ?");
$replyStmt->execute([$request_id]);
$reply = $replyStmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container mt-4" style="max-width: 800px;">
  <div class="row mb-3">
    <div class="col-12 col-md-6 d-flex align-items-center mb-2 mb-md-0">
      <h4 class="m-0">🗂️ View Details</h4>
    </div>
    <div class="col-12 col-md-6 text-md-end">
      <a href="dashboard.php" class="btn btn-outline-p btn-sm">← Back to Dashboard</a>
    </div>
  </div>


  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h4><?= htmlspecialchars($request['subject']) ?></h4>
      <p class="text-muted small">Submitted on <?= date('d M Y, h:i A', strtotime($request['created_at'])) ?></p>
      <hr>
      <p><?= nl2br(htmlspecialchars($request['message'])) ?></p>
    </div>
  </div>

  <?php if ($reply): ?>
    <div class="card border-success shadow-sm">
      <div class="card-body">
        <h5 class="text-success">🟢 Admin Reply</h5>
        <p class="text-muted small">Replied on <?= date('d M Y, h:i A', strtotime($reply['replied_at'])) ?></p>
        <hr>
        <p><?= nl2br(htmlspecialchars($reply['message'])) ?></p>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">No reply yet. Please check back later.</div>
  <?php endif; ?>
</div>


<?php include("../includes/footer.php"); ?>