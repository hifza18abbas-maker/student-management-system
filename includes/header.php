<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base = "/student-management";
$pageTitle = $pageTitle ?? "Student Management";
$current = basename($_SERVER["PHP_SELF"]);
$userName = $_SESSION["name"] ?? "";
$initial = strtoupper(mb_substr($userName !== "" ? $userName : "U", 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Student Management</title>
    <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar" id="sidebar">
        <div class="brand">🎓 StudentMS</div>
        <nav>
            <a href="<?= $base ?>/dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">📊&nbsp; Dashboard</a>
            <a href="<?= $base ?>/students/list.php" class="<?= in_array($current, ['list.php', 'view.php', 'edit.php']) ? 'active' : '' ?>">👨‍🎓&nbsp; Students</a>
            <a href="<?= $base ?>/students/add.php" class="<?= $current === 'add.php' ? 'active' : '' ?>">➕&nbsp; Add Student</a>
            <a href="<?= $base ?>/auth/logout.php">🚪&nbsp; Logout</a>
        </nav>
    </aside>
    <div class="main">
        <header class="topbar">
            <button id="menuBtn" type="button">☰</button>
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
            <div class="user">
                <span class="uname"><?= htmlspecialchars($userName) ?></span>
                <span class="role-badge"><?= htmlspecialchars($_SESSION["role"] ?? "") ?></span>
                <div class="avatar"><?= htmlspecialchars($initial) ?></div>
            </div>
        </header>
        <main class="content">