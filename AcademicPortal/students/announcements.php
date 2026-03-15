<?php
session_start();
include '../db.php'; 

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

// Kunin lahat ng announcements, pinakabago ang una (DESC)
$query = "SELECT title, message, created_at FROM announcements ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - NEXUS</title>
    <link rel="stylesheet" href="designs/home.css"> <link rel="stylesheet" href="designs/announcements_list.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <div class="hamburger-btn" id="hamburger-btn"><i class="fas fa-bars"></i></div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="../images/olshco.jpg" alt="Logo" class="sidebar-logo">
            <h3>NEXUS <span>Student</span></h3>
        </div>
        <ul class="nav-links">
            <li><a href="home.php"><i class="fas fa-chart-line"></i> <span>Dashboard</span></a></li>
            <li><a href="announcements.php" class="active"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a></li>
            <li><a href="subjects.php"><i class="fas fa-book"></i> <span>Subjects</span></a></li>
            <li><a href="view_grades.php"><i class="fas fa-graduation-cap"></i> <span>Grades</span></a></li>
            <li><a href="about.php"><i class="fas fa-info-circle"></i> <span>About Us</span></a></li>
            <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <main class="main-content">
        
        <section class="ann-page-container">
            <div class="page-title">
                <h2><i class="fas fa-bullhorn"></i> School Announcements</h2>
                <p>Stay updated with the latest news from the administration.</p>
            </div>

            <div class="ann-list-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="ann-item-card">
                            <div class="ann-item-header">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <span class="ann-date">
                                    <i class="far fa-calendar-alt"></i> 
                                    <?php echo date('M d, Y | h:i A', strtotime($row['created_at'])); ?>
                                </span>
                            </div>
                            <div class="ann-item-body">
                                <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-announcement">
                        <i class="fas fa-comment-slash"></i>
                        <p>No announcements posted yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        const btn = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        btn.onclick = () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        };
        overlay.onclick = btn.onclick;
    </script>

</body>
</html>