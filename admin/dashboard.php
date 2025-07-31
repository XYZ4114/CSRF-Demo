<?php
require_once("../auth/session_admin.php");
require_once("../includes/db.php");
require_once("../includes/functions.php");
require_once("../includes/csrf.php");
require_once("../includes/header.php");

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search setup
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchSql = "";
$params = [];

if (!empty($search)) {
  $searchSql = "WHERE cr.subject LIKE ? OR u.name LIKE ?";
  $params[] = "%$search%";
  $params[] = "%$search%";
}

// Count total rows
$countStmt = $conn->prepare("
    SELECT COUNT(*) FROM contact_requests cr
    JOIN users u ON cr.user_id = u.id
    $searchSql
");
$countStmt->execute($params);
$totalRequests = $countStmt->fetchColumn();
$totalPages = ceil($totalRequests / $limit);

// Fetch paginated rows
$sql = "
    SELECT cr.*, u.name FROM contact_requests cr
    JOIN users u ON cr.user_id = u.id
    $searchSql
    ORDER BY cr.created_at DESC
    LIMIT $limit OFFSET $offset
";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$requests = $stmt->fetchAll();
?>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-12 col-md-6 d-flex align-items-center mb-2 mb-md-0">
          <h4 class="card-title m-0">📋 Contact Requests</h4>
        </div>
        <div class="col-12 col-md-6 mt-3">
          <form class="d-flex" method="GET">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
              class="form-control form-control-sm me-2" placeholder="Search...">
            <button class="btn btn-outline-p btn-sm">Search</button>
          </form>
        </div>
      </div>

      <?php if (count($requests) === 0): ?>
        <p class="text-muted">No contact requests found.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>User</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($requests as $r): ?>
                <tr>
                  <td><?= htmlspecialchars($r['name']) ?></td>
                  <td><?= htmlspecialchars($r['subject']) ?></td>
                  <td>
                    <span class="badge <?= $r['status'] === 'new' ? 'bg-secondary' : 'bg-success' ?>">
                      <?= ucfirst($r['status']) ?>
                    </span>
                  </td>
                  <td><?= date("d M Y", strtotime($r['created_at'])) ?></td>
                  <td>
                    <a href="view.php?id=<?= $r['id'] ?>" class="btn btn-outline-p btn-sm">View</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <nav>
          <ul class="pagination justify-content-center mt-3">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
          </ul>
        </nav>

      <?php endif; ?>
    </div>
  </div>
</div>

<?php include("../includes/footer.php"); ?>