<?php
session_start();
include '../db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$get_user = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $get_user->fetch_assoc();

if(isset($_POST['update'])) {
    $username = $_POST['username'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $block = $_POST['block'];

    $stmt = $conn->prepare("UPDATE users SET username=?, course=?, year=?, block=? WHERE id=?");
    $stmt->bind_param("ssssi", $username, $course, $year, $block, $id);

    if($stmt->execute()) {
        header("Location: manage_students.php?msg=updated");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student | NEXUS</title>
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <link rel="stylesheet" href="designs_admin/edit_student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <div class="hamburger-btn" id="hamburger-btn">
        <i class="fas fa-bars"></i>
    </div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../images/olshco.jpg" alt="Logo" class="sidebar-logo">
            <h3>NEXUS <span>Admin</span></h3>
        </div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="add_student.php" class="<?= basename($_SERVER['PHP_SELF']) == 'add_student.php' ? 'active' : '' ?>">
                    <i class="fas fa-user-plus"></i> <span>Add Student</span>
                </a>
            </li>
            <li>
                <a href="view_students.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage_students.php' ? 'active' : '' ?>">
                    <i class="fas fa-user-graduate"></i> <span>Students</span>
                </a>
            </li>
            <li>
                <a href="manage_students.php" class="<?= basename($_SERVER['PHP_SELF']) == 'manage_students.php' ? 'active' : '' ?>">
                    <i class="fas fa-users-cog"></i> <span>Manage Students</span>
                </a>
            </li>
            <li>
                <a href="subject_select_student.php" class="<?= basename($_SERVER['PHP_SELF']) == 'subject_select_student.php' ? 'active' : '' ?>">
                    <i class="fas fa-book"></i> <span>Subjects</span>
                </a>
            </li>
            <li>
                <a href="grade_select_student.php" class="<?= basename($_SERVER['PHP_SELF']) == 'grade_select_student.php' ? 'active' : '' ?>">
                    <i class="fas fa-graduation-cap"></i> <span>Grades</span>
                </a>
            </li>
            <li>
                <a href="announcement.php" class="<?= basename($_SERVER['PHP_SELF']) == 'announcement.php' ? 'active' : '' ?>">
                    <i class="fas fa-bullhorn"></i> <span>Announcements</span>
                </a>
            </li>
            <li class="logout">
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="form-card">
        <a href="manage_students.php" class="top-right-back">
            <i class="fas fa-times"></i> Cancel
        </a>

        <h2>Edit Student</h2>
        <span class="subtitle">Update the account information below.</span>

        <form method="POST">
            <div class="input-group">
                <label><i class="fas fa-user"></i> Full Name</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="input-group">
                <label><i class="fas fa-graduation-cap"></i> Program / Course</label>
                <select name="course">
                    <option value="BSIT" <?= $user['course'] == 'BSIT' ? 'selected' : '' ?>>BSIT</option>
                    <option value="BSHM" <?= $user['course'] == 'BSHM' ? 'selected' : '' ?>>BSHM</option>
                    <option value="BSOAD" <?= $user['course'] == 'BSOAD' ? 'selected' : '' ?>>BSOAD</option>
                    <option value="BSEDUC" <?= $user['course'] == 'BSEDUC' ? 'selected' : '' ?>>BSEDUC</option>
                </select>
            </div>

            <div class="flex-row">
                <div class="input-group">
                    <label>Year Level</label>
                    <select name="year">
                        <option value="1" <?= $user['year'] == '1' ? 'selected' : '' ?>>1st Year</option>
                        <option value="2" <?= $user['year'] == '2' ? 'selected' : '' ?>>2nd Year</option>
                        <option value="3" <?= $user['year'] == '3' ? 'selected' : '' ?>>3rd Year</option>
                        <option value="4" <?= $user['year'] == '4' ? 'selected' : '' ?>>4th Year</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Section</label>
                    <select name="block">
                        <option value="A" <?= $user['block'] == 'A' ? 'selected' : '' ?>>Block A</option>
                        <option value="B" <?= $user['block'] == 'B' ? 'selected' : '' ?>>Block B</option>
                        <option value="C" <?= $user['block'] == 'C' ? 'selected' : '' ?>>Block C</option>
                        <option value="D" <?= $user['block'] == 'D' ? 'selected' : '' ?>>Block D</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="update" class="btn-update">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>
    <script>
        const btn = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        if(btn) btn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>