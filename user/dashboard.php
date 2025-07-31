<?php
require_once("../auth/session.php");
require_once("../includes/db.php");
require_once("../includes/header.php");

$stmt = $conn->prepare("SELECT * FROM contact_requests WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>My Contact Requests</h3>
<a href="contact.php" class="btn btn-p mb-3">+ New Contact Request</a>

<?php if (count($requests) == 0): ?>
  <div class="alert alert-info">You haven't submitted any contact requests yet.</div>
<?php else: ?>
  <div class="row">
    <?php foreach ($requests as $req): ?>
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($req['subject']) ?></h5>
            <p class="card-text small text-muted">Submitted on <?= date('d M Y, h:i A', strtotime($req['created_at'])) ?>
            </p>
            <span class="badge bg-<?= $req['status'] === 'new' ? 'secondary' : 'success' ?>">
              <?= ucfirst($req['status']) ?>
            </span>
          </div>
          <div class="card-footer bg-transparent border-top-0">
            <a href="view.php?id=<?= $req['id'] ?>" class="btn btn-outline-p w-100">View</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include("../includes/footer.php"); ?>