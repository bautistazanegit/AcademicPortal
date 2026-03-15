<?php
session_start();
include '../db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NEXUS Portal</title>
    <link rel="stylesheet" href="designs_admin/dashboard.css">
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
                <a href="logout_admin.php">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <main class="main-content">
        <header class="top-header">
            <div class="profile-icon"><i class="fas fa-user-circle"></i></div>
            <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
        </header>

        <section class="dashboard-section">
            <div class="welcome-banner">
                <h2>Admin Management</h2>
                <p>Manage student records, subjects, and academic performance.</p>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'posted'): ?>
                <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
                    <i class="fas fa-check-circle"></i> Announcement has been published successfully!
                </div>
            <?php endif; ?>

            <div class="admin-grid">
                <div class="admin-card" onclick="location.href='add_student.php'">
                    <div class="icon-circle bg-blue"><i class="fas fa-user-plus"></i></div>
                    <h3>Add New Student</h3>
                    <p>Create a new student account with course and block details.</p>
                </div>

                <div class="admin-card" onclick="location.href='announcement.php'">
                    <div class="icon-circle" style="background: #8b5cf6;"><i class="fas fa-bullhorn"></i></div>
                    <h3>Add Announcement</h3>
                    <p>Post school updates, deadlines, or holidays for students.</p>
                </div>

                <div class="admin-card" onclick="location.href='manage_students.php'">
                    <div class="icon-circle bg-blue"><i class="fas fa-users-cog"></i></div>
                    <h3>Manage Students</h3>
                    <p>Add, edit, or remove student accounts and manage personal details.</p>
                </div>

                <div class="admin-card" onclick="location.href='subject_select_student.php'">
                    <div class="icon-circle bg-orange" style="background: #f97316;"><i class="fas fa-book"></i></div>
                    <h3>Manage Subjects</h3>
                    <p>Assign and manage academic subjects for specific student accounts.</p>
                </div>

                <div class="admin-card" onclick="location.href='grade_select_student.php'">
                    <div class="icon-circle bg-green"><i class="fas fa-graduation-cap"></i></div>
                    <h3>Manage Grades</h3>
                    <p>Encode, update, or clear student grades for Prelim, Midterm, and Finals.</p>
                </div>
            </div>
        </section>
    </main>


    <script>
        const btn = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            const icon = btn.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.classList.replace('fa-bars', 'fa-times');
            } else {
                icon.classList.replace('fa-times', 'fa-bars');
            }
        }
        btn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>