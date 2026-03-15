<?php
session_start();
include '../db.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'student' || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id']; 
$query = "SELECT s.subject_code, s.subject_name, g.prelim, g.midterm, g.finals 
          FROM subjects s 
          LEFT JOIN grades g ON s.id = g.subject_id 
          WHERE s.student_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grades - Nexus Portal</title>
    <link rel="stylesheet" href="designs/home.css">
    <link rel="stylesheet" href="designs/view_grades.css">
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
            <h3>NEXUS <span>AcademicPortal</span></h3>
        </div>
        <ul class="nav-links">
            <li><a href="home.php"><i class="fas fa-th-large"></i> <span>Dashboard</span></a></li>
            <li><a href="subjects.php"><i class="fas fa-book"></i> <span>Subjects</span></a></li>
            <li class="active"><a href="view_grades.php"><i class="fas fa-file-invoice"></i> <span>View Grades</span></a></li>
            <li><a href="announcements.php"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a></li>
            <li><a href="about.php"><i class="fas fa-users"></i> <span>About Us</span></a></li>
            <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <main class="main-content">
        <header class="top-header">
            <div class="header-left">
                <h1>Academic Records</h1>
            </div>
        </header>

        <section class="content-body">
            <div class="table-container">
                <table class="grades-table">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th class="text-center">Prelim</th>
                            <th class="text-center">Midterm</th>
                            <th class="text-center">Finals</th>
                            <th class="text-center">Average</th>
                            <th class="text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($result->num_rows > 0):
                            while($row = $result->fetch_assoc()): 
                                $p = $row['prelim']; $m = $row['midterm']; $f = $row['finals'];
                                if ($p !== null && $m !== null && $f !== null) {
                                    $average = ($p + $m + $f) / 3;
                                    $display_avg = number_format($average, 2);
                                    if ($average <= 3.0 && $average > 0) { $remarks = "Passed"; $status_class = "passed"; } 
                                    else { $remarks = "Failed"; $status_class = "failed"; }
                                } else {
                                    $remarks = "N/A"; $status_class = "na"; $display_avg = "-";
                                }
                        ?>
                        <tr>
                            <td class="sub-code"><?= htmlspecialchars($row['subject_code']) ?></td>
                            <td><?= htmlspecialchars($row['subject_name']) ?></td>
                            <td class="text-center grade-val"><?= ($p !== null) ? $p : '-' ?></td>
                            <td class="text-center grade-val"><?= ($m !== null) ? $m : '-' ?></td>
                            <td class="text-center grade-val"><?= ($f !== null) ? $f : '-' ?></td>
                            <td class="text-center"><span class="avg-pill"><?= $display_avg ?></span></td>
                            <td class="text-center"><span class="badge <?= $status_class ?>"><?= $remarks ?></span></td>
                        </tr>
                        <?php endwhile; else: ?>
                        <tr><td colspan="7" class="empty-msg"><i class="fas fa-info-circle"></i> No grades recorded yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
            if (sidebar.classList.contains('active')) { icon.classList.replace('fa-bars', 'fa-times'); } 
            else { icon.classList.replace('fa-times', 'fa-bars'); }
        }
        btn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>

</body>
</html>