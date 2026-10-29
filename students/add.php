<?php
require "../includes/auth_check.php";
require "../config/db.php";

$errors = [];
$name = $email = $phone = $course = $gender = $address = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST["name"] ?? "");
    $email   = trim($_POST["email"] ?? "");
    $phone   = trim($_POST["phone"] ?? "");
    $course  = trim($_POST["course"] ?? "");
    $gender  = $_POST["gender"] ?? "";
    $address = trim($_POST["address"] ?? "");

    if ($name === "") $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if ($phone !== "" && !preg_match('/^[0-9+\-\s]{7,20}$/', $phone)) $errors[] = "Phone number is not valid.";
    if (!in_array($gender, ["Male", "Female", "Other"])) $errors[] = "Please select a gender.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[] = "A student with this email already exists.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, phone, course, gender, address) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $course, $gender, $address]);
        header("Location: list.php?added=1");
        exit;
    }
}

$pageTitle = "Add Student";
require "../includes/header.php";
?>

<div class="panel form-narrow">
    <h2>Add New Student</h2>

    <?php foreach ($errors as $e): ?>
        <div class="alert alert-error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <form method="POST">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>">
        </div>
        <div class="form-group">
            <label>Course</label>
            <input type="text" name="course" value="<?= htmlspecialchars($course) ?>">
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select name="gender" required>
                <option value="">-- Select --</option>
                <?php foreach (["Male", "Female", "Other"] as $g): ?>
                    <option value="<?= $g ?>" <?= $gender === $g ? "selected" : "" ?>><?= $g ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address"><?= htmlspecialchars($address) ?></textarea>
        </div>
        <button type="submit" class="btn">Save Student</button>
        <a href="list.php" class="btn btn-light">Cancel</a>
    </form>
</div>

<?php require "../includes/footer.php"; ?>