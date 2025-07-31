<?php
require_once("../auth/session_admin.php");
require_once("../includes/db.php");
require_once("../includes/functions.php");
require_once("../includes/header.php");


if (!isset($_GET['id'])) {
	echo "<div class='container mt-4'><div class='alert alert-danger'>No request ID provided.</div></div>";
	include("../includes/footer.php");
	exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("
  SELECT cr.*, u.name, u.email FROM contact_requests cr
  JOIN users u ON cr.user_id = u.id
  WHERE cr.id = ?
");
$stmt->execute([$id]);
$request = $stmt->fetch();

if (!$request) {
	echo "<div class='container mt-4'><div class='alert alert-danger'>Request not found.</div></div>";
	include("../includes/footer.php");
	exit;
}

$replies = $conn->prepare("
  SELECT * FROM contact_replies WHERE request_id = ? ORDER BY replied_at ASC
");
$replies->execute([$id]);
$replyList = $replies->fetchAll();
?>

<div class="container mt-4" style="max-width: 700px;">
	<div class="card shadow-sm">
		<div class="card-body">
			<div class="row mb-3">
				<div class="col-12 col-md-6 d-flex align-items-center mb-2 mb-md-0">
					<h4 class="m-0">🗂️ Request Details</h4>
				</div>
				<div class="col-12 col-md-6 text-md-end">
					<a href="dashboard.php" class="btn btn-outline-p btn-sm">← Back to Admin Panel</a>
				</div>
			</div>


			<p><strong>User:</strong> <?= htmlspecialchars($request['name']) ?>
				(<?= htmlspecialchars($request['email']) ?>)</p>
			<p><strong>Subject:</strong> <?= htmlspecialchars($request['subject']) ?></p>
			<p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($request['message'])) ?></p>
			<p><strong>Status:</strong>
				<span class="badge <?= $request['status'] === 'new' ? 'bg-secondary' : 'bg-success' ?>">
					<?= ucfirst($request['status']) ?>
				</span>
			</p>

			<hr>
			<h5>📝 Replies</h5>
			<?php if (!$replyList): ?>
				<p class="text-muted">No replies yet.</p>
			<?php else: ?>
				<?php foreach ($replyList as $r): ?>
					<div class="border rounded p-2 mb-2">
						<small class="text-muted">Replied on <?= date("d M Y, H:i", strtotime($r['replied_at'])) ?></small>
						<p class="mb-0"><?= nl2br(htmlspecialchars($r['message'])) ?></p>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<a href="reply.php?id=<?= $request['id'] ?>" class="btn btn-p mt-3">Send Reply</a>
		</div>
	</div>
</div>

<?php include("../includes/footer.php"); ?>