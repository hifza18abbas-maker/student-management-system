<?php
require "../includes/auth_check.php";
require "../config/db.php";

if ($_SESSION["role"] !== "admin") {
    die("Access denied. Only admins can delete students.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int)($_POST["id"] ?? 0);
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: list.php?deleted=1");
exit;