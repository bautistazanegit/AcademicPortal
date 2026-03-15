<?php
session_start();
include '../db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$student_id = $_GET['student_id'];
$subject_id = $_GET['subject_id'];

// Get Student and Subject names for the header
$info = $conn->query("SELECT u.username, s.subject_name, s.subject_code 
                      FROM users u, subjects s 
                      WHERE u.id = $student_id AND s.id = $subject_id")->fetch_assoc();

// Check kung may existing grade
$grade_query = $conn->query("SELECT * FROM grades WHERE student_id = $student_id AND subject_id = $subject_id");
$grade_data = $grade_query->fetch_assoc();

if(isset($_POST['save_grade'])) {
    $prelim = $_POST['prelim'] === "" ? NULL : $_POST['prelim'];
    $midterm = $_POST['midterm'] === "" ? NULL : $_POST['midterm'];
    $finals = $_POST['finals'] === "" ? NULL : $_POST['finals'];

    if($grade_data) {
        $stmt = $conn->prepare("UPDATE grades SET prelim=?, midterm=?, finals=? WHERE student_id=? AND subject_id=?");
        $stmt->bind_param("dddii", $prelim, $midterm, $finals, $student_id, $subject_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO grades (student_id, subject_id, prelim, midterm, finals) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiddd", $student_id, $subject_id, $prelim, $midterm, $finals);
    }
    $stmt->execute();
    header("Location: grade_select_subject.php?student_id=$student_id&msg=saved");
    exit();
}

if(isset($_POST['delete_grades'])) {
    $conn->query("DELETE FROM grades WHERE student_id = $student_id AND subject_id = $subject_id");
    header("Location: grade_select_subject.php?student_id=$student_id&msg=cleared");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Grades | NEXUS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <link rel="stylesheet" href="designs_admin/manage_grades.css">
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
        <div class="grade-card">
            <a href="grade_select_subject.php?student_id=<?= $student_id ?>" class="top-right-back">
                <i class="fas fa-times"></i>
            </a>

            <div class="card-header">
                <h2>Update Grades</h2>
                <p><?= htmlspecialchars($info['subject_code']) ?> - <?= htmlspecialchars($info['subject_name']) ?></p>
                <small>Student: <strong><?= htmlspecialchars($info['username']) ?></strong></small>
            </div>
            
            <form method="POST">
                <div class="input-group">
                    <label><i class="fas fa-pen-nib"></i> Preliminary Grade</label>
                    <input type="number" step="0.01" name="prelim" value="<?= $grade_data['prelim'] ?? '' ?>" placeholder="0.00" min="0" max="100">
                </div>
                
                <div class="input-group">
                    <label><i class="fas fa-pen-nib"></i> Midterm Grade</label>
                    <input type="number" step="0.01" name="midterm" value="<?= $grade_data['midterm'] ?? '' ?>" placeholder="0.00" min="0" max="100">
                </div>
                
                <div class="input-group">
                    <label><i class="fas fa-pen-nib"></i> Finals Grade</label>
                    <input type="number" step="0.01" name="finals" value="<?= $grade_data['finals'] ?? '' ?>" placeholder="0.00" min="0" max="100">
                </div>

                <div class="button-container">
                    <button type="submit" name="save_grade" class="btn-save">
                        <i class="fas fa-check-circle"></i> Save Grades
                    </button>
                    
                    <button type="submit" name="delete_grades" class="btn-clear" onclick="return confirm('Are you sure you want to clear these grades?')">
                        <i class="fas fa-trash-alt"></i> Clear All
                    </button>
                </div>
            </form>
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