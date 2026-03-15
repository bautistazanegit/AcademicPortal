<?php
session_start();
include '../db.php'; 

if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

$subject_count = 0;
$sub_query = "SELECT COUNT(*) as total FROM subjects WHERE student_id = ?";
$stmt_sub = $conn->prepare($sub_query);
if ($stmt_sub) {
    $stmt_sub->bind_param("i", $user_id);
    $stmt_sub->execute();
    $subject_count = $stmt_sub->get_result()->fetch_assoc()['total'];
}

$display_gpa = "0.00";
$grade_query = "SELECT AVG((prelim + midterm + finals) / 3) as gpa 
                FROM grades 
                WHERE student_id = ? 
                AND prelim IS NOT NULL 
                AND midterm IS NOT NULL 
                AND finals IS NOT NULL";

$stmt_grade = $conn->prepare($grade_query);
if ($stmt_grade) {
    $stmt_grade->bind_param("i", $user_id);
    $stmt_grade->execute();
    $res = $stmt_grade->get_result()->fetch_assoc();
    $display_gpa = ($res && $res['gpa'] !== null) ? number_format($res['gpa'], 2) : "N/A";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus Portal - Dashboard</title>
    <link rel="stylesheet" href="designs/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Page Transition */
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
            <li class="active"><a href="home.php"><i class="fas fa-th-large"></i> <span>Dashboard</span></a></li>
            <li><a href="subjects.php"><i class="fas fa-book"></i> <span>Subjects</span></a></li>
            <li><a href="view_grades.php"><i class="fas fa-file-invoice"></i> <span>View Grades</span></a></li>
            <li><a href="announcements.php" class="<?= basename($_SERVER['PHP_SELF']) == 'announcements.php' ? 'active' : '' ?>"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a></li>
            <li><a href="about.php"><i class="fas fa-users"></i> <span>About Us</span></a></li>
            <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <main class="main-content">
        <header class="top-header">
            <div class="user-info">
                <i class="fas fa-user-circle profile-icon"></i>
                <span>Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</span>
            </div>
        </header>

        <section class="content-body">
            <h2>Student Dashboard</h2>
            <div class="cards-container">
                <a href="subjects.php" class="card-link">
                    <div class="card">
                        <i class="fas fa-layer-group"></i>
                        <h3>Subjects</h3>
                        <p><?php echo $subject_count; ?> Enrolled</p>
                    </div>
                </a>

                <a href="view_grades.php" class="card-link">
                    <div class="card">
                        <i class="fas fa-star"></i>
                        <h3>GPA</h3>
                        <p><?php echo $display_gpa; ?></p>
                    </div>
                </a>
            </div>
        </section>
    </main>

    <div id="chat-container" style="display:none;">
        <div id="chat-header">
            <span><i class="fas fa-robot"></i> Nexus AI Assistant</span>
            <button id="close-chat"><i class="fas fa-times"></i></button>
        </div>
        <div id="chat-box">
            <div class="bot-msg">Kumusta! Ako ang iyong Nexus Assistant. May maipaglilingkod ba ako sa iyo?</div>
        </div>
        <div id="chat-input-area">
            <input type="text" id="user-input" placeholder="Ask here...">
            <button id="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <button id="chat-toggle-btn">
        <i class="fas fa-comment-dots"></i>
    </button>


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

        const chatToggle = document.getElementById('chat-toggle-btn');
        const chatContainer = document.getElementById('chat-container');
        const closeChat = document.getElementById('close-chat');
        const sendBtn = document.getElementById('send-btn');
        const userInput = document.getElementById('user-input');
        const chatBox = document.getElementById('chat-box');

        chatToggle.onclick = () => { chatContainer.style.display = (chatContainer.style.display === 'none') ? 'flex' : 'none'; };
        closeChat.onclick = () => chatContainer.style.display = 'none';

        async function sendMessage() {
            const message = userInput.value.trim();
            if (!message) return;
            chatBox.innerHTML += `<div class="user-msg">${message}</div>`;
            userInput.value = '';
            chatBox.scrollTop = chatBox.scrollHeight;
            try {
                const response = await fetch('chat_handler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: message })
                });
                const text = await response.text();
                try {
                    const data = JSON.parse(text);
                    if (data.choices && data.choices[0]) {
                        chatBox.innerHTML += `<div class="bot-msg">${data.choices[0].message.content}</div>`;
                    }
                } catch (jsonErr) { chatBox.innerHTML += `<div class="bot-msg" style="color:red;">Server Error.</div>`; }
            } catch (netErr) { chatBox.innerHTML += `<div class="bot-msg" style="color:red;">Network Error.</div>`; }
            chatBox.scrollTop = chatBox.scrollHeight;
        }
        sendBtn.onclick = sendMessage;
        userInput.onkeypress = (e) => { if(e.key === 'Enter') sendMessage(); };
    </script>
</body>
</html>