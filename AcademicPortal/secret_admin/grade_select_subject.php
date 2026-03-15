<?php
session_start();
include '../db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$student_id = $_GET['student_id'];
$student = $conn->query("SELECT username FROM users WHERE id = $student_id")->fetch_assoc();

// Kuhanin ang subjects ng student at i-join ang grades table
$subjects = $conn->query("SELECT s.*, g.prelim, g.midterm, g.finals 
                          FROM subjects s 
                          LEFT JOIN grades g ON s.id = g.subject_id 
                          WHERE s.student_id = $student_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Subject | NEXUS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <link rel="stylesheet" href="designs_admin/grade_select_subject.css">
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

    <main class="main-content">
        <div class="container">
            <div class="header-section">
                <div class="title-info">
                    <div class="icon-box"><i class="fas fa-file-invoice"></i></div>
                    <div>
                        <h2>Manage Grades</h2>
                        <p>Student: <strong><?= htmlspecialchars($student['username']) ?></strong></p>
                    </div>
                </div>
                <a href="grade_select_student.php" class="btn-back">
                    <i class="fas fa-chevron-left"></i> Back to Students
                </a>
            </div>

            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Description</th>
                            <th class="text-center">Grading Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $subjects->fetch_assoc()): ?>
                        <tr>
                            <td class="code-cell"><?= htmlspecialchars($row['subject_code']) ?></td>
                            <td><?= htmlspecialchars($row['subject_name']) ?></td>
                            <td class="text-center">
                                <div class="status-container">
                                    <span class="grade-pill <?= $row['prelim'] !== null ? 'filled' : '' ?>">
                                        P: <?= $row['prelim'] ?? '-' ?>
                                    </span>
                                    <span class="grade-pill <?= $row['midterm'] !== null ? 'filled' : '' ?>">
                                        M: <?= $row['midterm'] ?? '-' ?>
                                    </span>
                                    <span class="grade-pill <?= $row['finals'] !== null ? 'filled' : '' ?>">
                                        F: <?= $row['finals'] ?? '-' ?>
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="manage_grades.php?student_id=<?= $student_id ?>&subject_id=<?= $row['id'] ?>" class="btn-edit-grade">
                                    <i class="fas fa-pen-to-square"></i> Edit
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        
                        <?php if($subjects->num_rows == 0): ?>
                            <tr>
                                <td colspan="4" class="empty-state">
                                    <i class="fas fa-folder-open"></i>
                                    <p>No subjects found for this student.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
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