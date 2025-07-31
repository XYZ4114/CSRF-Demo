<?php
require_once("includes/header.php");
?>
<link rel="stylesheet" href="assets/styles/style.css">
<div class="container mt-5 text-center" style="max-width: 600px;">
  <div class="card shadow-sm">
    <div class="card-body py-5">
      <h2 class="mb-4">📬 Contact Request Manager</h2>
      <p class="mb-4 text-muted">
        A simple platform to send and manage contact messages between users and admins.
      </p>
      <div class="d-grid gap-3">
        <a href="auth/login.php" class="btn btn-p btn-lg">Login as User</a>
        <a href="auth/login.php?admin=1" class="btn btn-outline-p btn-lg">Login as Admin</a>
      </div>
    </div>
  </div>
</div>

<?php
require_once("includes/footer.php");
?>
