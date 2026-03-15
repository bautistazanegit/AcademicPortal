<?php
session_start();
include '../db.php';

if(!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$student_id = $_GET['student_id'];
$student = $conn->query("SELECT username FROM users WHERE id = $student_id")->fetch_assoc();

// Add Subject Logic
if(isset($_POST['add_subject'])) {
    $sub_code = $_POST['subject_code'];
    $sub_name = $_POST['subject_name'];
    $sub_teacher = $_POST['subject_teacher']; 
    $stmt = $conn->prepare("INSERT INTO subjects (student_id, subject_code, subject_name, subject_teacher) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $student_id, $sub_code, $sub_name, $sub_teacher);
    $stmt->execute();
    header("Location: manage_subjects.php?student_id=$student_id&msg=added");
    exit();
}

// Update Subject Logic
if(isset($_POST['update_subject'])) {
    $sub_id = $_POST['edit_id'];
    $sub_code = $_POST['edit_code'];
    $sub_name = $_POST['edit_name'];
    $sub_teacher = $_POST['edit_teacher']; 
    $stmt = $conn->prepare("UPDATE subjects SET subject_code = ?, subject_name = ?, subject_teacher = ? WHERE id = ?");
    $stmt->bind_param("sssi", $sub_code, $sub_name, $sub_teacher, $sub_id);
    $stmt->execute();
    header("Location: manage_subjects.php?student_id=$student_id&msg=updated");
    exit();
}

// Delete Subject Logic
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM subjects WHERE id = $id");
    header("Location: manage_subjects.php?student_id=$student_id&msg=deleted");
    exit();
}

$subjects = $conn->query("SELECT * FROM subjects WHERE student_id = $student_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Subjects - Admin</title>
    <link rel="stylesheet" href="designs_admin/dashboard.css">
    <link rel="stylesheet" href="designs_admin/manage_subjects.css">
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
        <div class="container">
            <div class="header">
                <div>
                    <h2>Manage Subjects</h2>
                    <p>Student: <strong><?php echo $student['username']; ?></strong></p>
                </div>
                <a href="subject_select_student.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to List</a>
            </div>

            <div class="form-section">
                <form method="POST" class="add-form">
                    <input type="text" name="subject_code" placeholder="Code" required>
                    <input type="text" name="subject_name" placeholder="Subject Name" required>
                    <input type="text" name="subject_teacher" placeholder="Teacher Name" required>
                    <button type="submit" name="add_subject" class="btn-add-main">Add Subject</button>
                </form>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Subject Name</th>
                            <th>Instructor</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($subjects->num_rows > 0): ?>
                            <?php while($row = $subjects->fetch_assoc()): ?>
                            <tr>
                                <td><span class="code-badge"><?php echo $row['subject_code']; ?></span></td>
                                <td><?php echo $row['subject_name']; ?></td>
                                <td><i class="fas fa-user-tie"></i> <?php echo $row['subject_teacher']; ?></td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <button class="btn-edit" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['subject_code']; ?>', '<?php echo $row['subject_name']; ?>', '<?php echo $row['subject_teacher']; ?>')">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="?student_id=<?php echo $student_id; ?>&delete=<?php echo $row['id']; ?>" class="btn-del" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center" style="padding:20px; color:#64748b;">No subjects added yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Subject</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="edit_id" id="edit_id">
                <div class="input-group">
                    <label>Subject Code</label>
                    <input type="text" name="edit_code" id="edit_code" required>
                </div>
                <div class="input-group">
                    <label>Subject Name</label>
                    <input type="text" name="edit_name" id="edit_name" required>
                </div>
                <div class="input-group">
                    <label>Subject Teacher</label>
                    <input type="text" name="edit_teacher" id="edit_teacher" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" name="update_subject" class="btn-update">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id, code, name, teacher) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_teacher').value = teacher;
            document.getElementById('editModal').style.display = 'flex';
        }
        function closeModal() { document.getElementById('editModal').style.display = 'none'; }
        window.onclick = function(event) { if (event.target == document.getElementById('editModal')) closeModal(); }
        
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