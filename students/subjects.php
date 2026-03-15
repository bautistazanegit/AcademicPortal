<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT subject_code, subject_name, subject_teacher FROM subjects WHERE student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Subjects - Nexus Portal</title>
    <link rel="stylesheet" href="designs/home.css"> 
    <link rel="stylesheet" href="designs/subjects.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .main-content {
            animation: pageFadeIn 0.8s ease-out;
        }
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    <div class="hamburger-btn" id="hamburger-btn">
        <i class="fas fa-bars"></i>
    </div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../images/olshco.jpg" alt="Logo" class="sidebar-logo">
            <h3>NEXUS <span>Academic Portal</span></h3>
        </div>
        <ul class="nav-links">
            <li><a href="home.php"><i class="fas fa-th-large"></i> <span>Dashboard</span></a></li>
            <li class="active"><a href="subjects.php"><i class="fas fa-book"></i> <span>Subjects</span></a></li>
            <li><a href="view_grades.php"><i class="fas fa-file-invoice"></i> <span>View Grades</span></a></li>
            <li><a href="announcements.php"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a></li>
            <li><a href="about.php"><i class="fas fa-users"></i> <span>About Us</span></a></li>
            <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <main class="main-content">
        <header class="top-header">
            <div class="subjects-header">
                <h2>My Enrolled Subjects</h2>
            </div>
        </header>

        <section class="content-body">
            <div class="subjects-container">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Description</th>
                                <th>Instructor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><span class="code-badge"><?php echo htmlspecialchars($row['subject_code']); ?></span></td>
                                    <td><strong><?php echo htmlspecialchars($row['subject_name']); ?></strong></td>
                                    <td>
                                        <div class="prof-info">
                                            <i class="fas fa-user-tie" style="color: #94a3b8;"></i>
                                            <span class="prof-badge"><?php echo htmlspecialchars($row['subject_teacher']); ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="no-data">No subjects enrolled yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        }
        btn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>

</body>
</html>