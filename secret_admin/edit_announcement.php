<?php
session_start();
include '../db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); exit();
}

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM announcements WHERE id = $id");
$data = $res->fetch_assoc();

if (!$data) {
    header("Location: announcement.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    $conn->query("UPDATE announcements SET title='$title', message='$message' WHERE id=$id");
    header("Location: announcement.php?msg=updated");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Announcement - NEXUS</title>
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <link rel="stylesheet" href="designs_admin/edit_announcement.css">
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

    <div class="edit-wrapper">
        <div class="edit-card">
            <h2><i class="fas fa-edit"></i> Edit Announcement</h2>
            
            <form method="POST">
                <div class="form-group">
                    <label>Subject Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Message Content</label>
                    <textarea name="message" rows="8" required><?= htmlspecialchars($data['message']) ?></textarea>
                </div>

                <button type="submit" class="btn-update">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                
                <a href="announcement.php" class="btn-cancel">Cancel and Go Back</a>
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