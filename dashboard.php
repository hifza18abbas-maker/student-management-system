<?php
require "includes/auth_check.php";
require "config/db.php";

$totalStudents = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$totalUsers    = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$todayCount    = $pdo->query("SELECT COUNT(*) FROM students WHERE DATE(created_at) = CURDATE()")->fetchColumn();
$recent        = $pdo->query("SELECT id, name, email, course, created_at FROM students ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Dashboard";
require "includes/header.php";
?>

<div class="cards">
    <div class="stat-card">
        <span class="icon">👨‍🎓</span>
        <h3>Total Students</h3>
        <div class="num"><?= $totalStudents ?></div>
    </div>
    <div class="stat-card green">
        <span class="icon">🆕</span>
        <h3>Registered Today</h3>
        <div class="num"><?= $todayCount ?></div>
    </div>
    <div class="stat-card orange">
        <span class="icon">👥</span>
        <h3>Total Users</h3>
        <div class="num"><?= $totalUsers ?></div>
    </div>
</div>

<div class="panel">
    <h2>Search Students</h2>
    <form method="GET" action="students/list.php" class="search-bar">
        <input type="text" name="search" placeholder="Search by name or email">
        <button type="submit" class="btn">Search</button>
    </form>
</div>

<div class="panel">
    <h2>Recent Registrations</h2>
    <div class="table-wrap">
        <table>
            <tr><th>Name</th><th>Email</th><th>Course</th><th>Registered</th></tr>
            <?php if (empty($recent)): ?>
                <tr><td colspan="4">No students yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($recent as $r): ?>
                <tr>
                    <td><a href="students/view.php?id=<?= $r["id"] ?>"><?= htmlspecialchars($r["name"]) ?></a></td>
                    <td><?= htmlspecialchars($r["email"]) ?></td>
                    <td><?= htmlspecialchars($r["course"]) ?></td>
                    <td><?= $r["created_at"] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php require "includes/footer.php"; ?>