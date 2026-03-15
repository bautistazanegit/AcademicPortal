<?php
session_start();
include '../db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}

$status_msg = "";

// LOGIC: DELETE ANNOUNCEMENT
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $del_sql = "DELETE FROM announcements WHERE id = $id";
    if (mysqli_query($conn, $del_sql)) {
        header("Location: announcement.php?msg=deleted");
        exit();
    }
}

// LOGIC: POST ANNOUNCEMENT
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_announcement'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO announcements (title, message) VALUES ('$title', '$message')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: announcement.php?msg=posted");
        exit();
    } else {
        $status_msg = "<div class='status-alert error'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Handling Success Messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'posted') $status_msg = "<div class='status-alert success'>Announcement published successfully!</div>";
    if ($_GET['msg'] == 'deleted') $status_msg = "<div class='status-alert success'>Announcement deleted successfully!</div>";
}

// Kunin lahat ng announcements
$all_ann = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - NEXUS</title>
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <link rel="stylesheet" href="designs_admin/announcement.css">
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

    <main class="main-content">
        <section class="ann-container-fixed">
            <div class="admin-announcement-container">
                <div class="ann-header-text">
                    <div>
                        <h2><i class="fas fa-bullhorn"></i> School Announcements</h2>
                        <p>Create, edit, or remove announcements for all students.</p>
                    </div>
                    <a href="dashboard.php" class="btn-dashboard">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                </div>

                <?php echo $status_msg; ?>

                <form method="POST" class="ann-form">
                    <div class="form-group">
                        <label>Subject Title</label>
                        <input type="text" name="title" placeholder="e.g. Schedule for Final Examinations" required>
                    </div>
                    <div class="form-group">
                        <label>Message Content</label>
                        <textarea name="message" rows="4" placeholder="Provide the details..." required></textarea>
                    </div>
                    <button type="submit" name="post_announcement" class="btn-post">
                        <i class="fas fa-paper-plane"></i> Publish Now
                    </button>
                </form>

                <hr class="divider">

                <div class="manage-section">
                    <h3>Recent Posts</h3>
                    <div class="table-scroll">
                        <table class="ann-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($all_ann->num_rows > 0): ?>
                                    <?php while($row = $all_ann->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= date('M d', strtotime($row['created_at'])) ?></td>
                                        <td class="title-text"><?= htmlspecialchars($row['title']) ?></td>
                                        <td class="action-btns">
                                            <a href="edit_announcement.php?id=<?= $row['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                                            <a href="announcement.php?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Delete this?')"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
        }

        if(btn) btn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>