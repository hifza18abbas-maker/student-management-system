<?php
require "../includes/auth_check.php";
require "../config/db.php";

$search  = trim($_GET["search"] ?? "");
$perPage = 5;
$page    = max(1, (int)($_GET["page"] ?? 1));
$like    = "%" . $search . "%";

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE name LIKE ? OR email LIKE ?");
$countStmt->execute([$like, $like]);
$total      = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));
if ($page > $totalPages) $page = $totalPages;
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare("SELECT * FROM students WHERE name LIKE ? OR email LIKE ? ORDER BY id DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $like);
$stmt->bindValue(2, $like);
$stmt->bindValue(3, $perPage, PDO::PARAM_INT);
$stmt->bindValue(4, $offset, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

function pageLink($p, $search) {
    return "list.php?" . http_build_query(["search" => $search, "page" => $p]);
}

$pageTitle = "Students";
require "../includes/header.php";
?>

<?php if (isset($_GET["added"])): ?><div class="alert alert-success">Student added successfully.</div><?php endif; ?>
<?php if (isset($_GET["updated"])): ?><div class="alert alert-success">Student updated successfully.</div><?php endif; ?>
<?php if (isset($_GET["deleted"])): ?><div class="alert alert-success">Student deleted.</div><?php endif; ?>

<div class="panel">
    <div class="page-head">
        <h2>All Students (<?= $total ?>)</h2>
        <a href="add.php" class="btn">+ Add Student</a>
    </div>

    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search by name or email" value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn">Search</button>
        <?php if ($search !== ""): ?><a href="list.php" class="btn btn-light">Clear</a><?php endif; ?>
    </form>

    <div class="table-wrap">
        <table>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Course</th><th>Actions</th>
            </tr>
            <?php if (empty($students)): ?>
                <tr><td colspan="6">No students found.</td></tr>
            <?php endif; ?>
            <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= $s["id"] ?></td>
                    <td><?= htmlspecialchars($s["name"]) ?></td>
                    <td><?= htmlspecialchars($s["email"]) ?></td>
                    <td><?= htmlspecialchars($s["phone"]) ?></td>
                    <td><?= htmlspecialchars($s["course"]) ?></td>
                    <td>
                        <a href="view.php?id=<?= $s["id"] ?>" class="btn btn-light btn-sm">View</a>
                        <a href="edit.php?id=<?= $s["id"] ?>" class="btn btn-sm">Edit</a>
                        <?php if ($_SESSION["role"] === "admin"): ?>
                            <form method="POST" action="delete.php" class="inline-form" onsubmit="return confirm('Delete this student?');">
                                <input type="hidden" name="id" value="<?= $s["id"] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?= pageLink($page - 1, $search) ?>">&laquo; Prev</a>
            <?php else: ?>
                <span class="disabled">&laquo; Prev</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= pageLink($i, $search) ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="<?= pageLink($page + 1, $search) ?>">Next &raquo;</a>
            <?php else: ?>
                <span class="disabled">Next &raquo;</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require "../includes/footer.php"; ?>