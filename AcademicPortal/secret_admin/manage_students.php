<?php
session_start();
include '../db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Logic para sa Delete
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='student'");
    header("Location: manage_students.php?msg=deleted");
    exit();
}

$result = $conn->query("SELECT * FROM users WHERE role='student' ORDER BY course ASC, username ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students | NEXUS</title>
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <link rel="stylesheet" href="designs_admin/manage_students.css">
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

    <div class="container">
        <div class="header">
            <div>
                <h2><i class="fas fa-users-cog"></i> Student Management</h2>
                <p>Add, edit, or remove student accounts from the system.</p>
            </div>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>

        <?php if(isset($_GET['msg'])): ?>
            <div class="alert-box <?= $_GET['msg'] == 'updated' ? 'alert-success' : 'alert-danger' ?>">
                <i class="fas <?= $_GET['msg'] == 'updated' ? 'fa-check-circle' : 'fa-trash-alt' ?>"></i>
                <?= $_GET['msg'] == 'updated' ? 'Student record updated successfully!' : 'Student record has been deleted.' ?>
            </div>
        <?php endif; ?>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Program</th>
                    <th>Year & Block</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar"><?= strtoupper(substr($row['username'], 0, 1)) ?></div>
                            <strong><?= htmlspecialchars($row['username']) ?></strong>
                        </div>
                    </td>
                    <td><span class="badge-program"><?= htmlspecialchars($row['course']) ?></span></td>
                    <td><?= $row['year'] ?> - <?= $row['block'] ?></td>
                    <td style="text-align: center;">
                        <div class="action-buttons">
                            <a href="edit_student.php?id=<?= $row['id'] ?>" class="btn-edit" title="Edit Student">
                                <i class="fas fa-user-edit"></i> Edit
                            </a>
                            <a href="manage_students.php?delete=<?= $row['id'] ?>" class="btn-delete" title="Delete Student" onclick="return confirm('Are you sure you want to delete this student?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>

                <?php if($result->num_rows == 0): ?>
                    <tr>
                        <td colspan="4" class="empty-state">
                            <i class="fas fa-user-slash"></i>
                            <p>No students found in the database.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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