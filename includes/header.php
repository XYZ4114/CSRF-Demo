<?php if (!isset($_SESSION)) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Manager</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="../assets/styles/style.css">
</head>
<body>
<div class="wrapper">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <?php if (isset($_SESSION['user_id'])): ?>
      <a class="navbar-brand fw-bold" href="dashboard.php">📬 Contact Manager</a>
      <?php else: ?>
        <a class="navbar-brand fw-bold" href="../index.php">📬 Contact Manager</a>
        <?php endif; ?>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <?php if (isset($_SESSION['user_id'])): ?>
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="btn btn-sm btn-outline-l" href="../auth/logout.php">Logout</a>
            </li>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </nav>
  <div class="content container mt-4">
