<?php
require "../includes/auth_check.php";
require "../config/db.php";

$id = (int)($_GET["id"] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$id]);
$s = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$s) {
    die("Student not found.");
}

$pageTitle = "Student Details";
require "../includes/header.php";
?>

<div class="panel form-narrow">
    <h2><?= htmlspecialchars($s["name"]) ?></h2>

    <div class="detail-row"><div class="k">ID</div><div><?= $s["id"] ?></div></div>
    <div class="detail-row"><div class="k">Email</div><div><?= htmlspecialchars($s["email"]) ?></div></div>
    <div class="detail-row"><div class="k">Phone</div><div><?= htmlspecialchars($s["phone"]) ?></div></div>
    <div class="detail-row"><div class="k">Course</div><div><?= htmlspecialchars($s["course"]) ?></div></div>
    <div class="detail-row"><div class="k">Gender</div><div><?= htmlspecialchars($s["gender"]) ?></div></div>
    <div class="detail-row"><div class="k">Address</div><div><?= nl2br(htmlspecialchars($s["address"])) ?></div></div>
    <div class="detail-row"><div class="k">Registered</div><div><?= $s["created_at"] ?></div></div>

    <br>
    <a href="edit.php?id=<?= $s["id"] ?>" class="btn">Edit</a>
    <a href="list.php" class="btn btn-light">Back to list</a>
</div>

<?php require "../includes/footer.php"; ?> 