<?php
session_start();
include '../db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";
$status = "";

if(isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $course = $_POST['course'];
    $year = $_POST['year'];
    $block = $_POST['block'];
    $role = "student";

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, course, year, block) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $password, $role, $course, $year, $block);

    if($stmt->execute()) {
        $message = "Student registered successfully!";
        $status = "success";
    } else {
        $message = "Error: Username might already be taken.";
        $status = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student | NEXUS Admin</title>
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <link rel="stylesheet" href="designs_admin/add_students.css">
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

    <div class="main-wrapper">
        <div class="form-card">
            <div class="form-header">
                <div class="icon-circle">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2>Register New Student</h2>
                <p>Please fill out the details to create a student account.</p>
            </div>

            <?php if($message): ?>
                <div class="alert <?= $status == 'success' ? 'alert-success' : 'alert-error' ?>">
                    <i class="fas <?= $status == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="styled-form">
                <div class="section">
                    <h3 class="section-title">Login Credentials</h3>
                    <div class="input-field">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" placeholder="Full Name / Username" required>
                    </div>
                    <div class="input-field">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" placeholder="Temporary Password" required>
                    </div>
                </div>

                <div class="section">
                    <h3 class="section-title">Academic Details</h3>
                    <div class="input-field">
                        <i class="fas fa-graduation-cap"></i>
                        <select name="course" required>
                            <option value="" disabled selected>Select Course</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSHM">BSHM</option>
                            <option value="BSCRIM">BSCRIM</option>
                            <option value="BSEDUC">BSEDUC</option>
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="input-field">
                            <i class="fas fa-layer-group"></i>
                            <select name="year" required>
                                <option value="" disabled selected>Year</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <i class="fas fa-th-large"></i>
                            <select name="block" required>
                                <option value="" disabled selected>Block</option>
                                <option value="A">Block A</option>
                                <option value="B">Block B</option>
                                <option value="C">Block C</option>
                                <option value="D">Block D</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" name="add" class="btn-primary">
                        Save Student <i class="fas fa-arrow-right"></i>
                    </button>
                    <a href="dashboard.php" class="btn-secondary">Back to Dashboard</a>
                </div>
            </form>
        </div>
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