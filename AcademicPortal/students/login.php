<?php
include '../db.php';
session_start();

// Kunin ang selection data mula sa URL (Course, Year, Block)
$course = $_GET['course'] ?? '';
$year = $_GET['year'] ?? '';
$block = $_GET['block'] ?? '';

// Kung manual na pinalitan ang URL o walang selection, balik sa pre-login
if(empty($course) || empty($year) || empty($block)) {
    header("Location: pre-login.php");
    exit();
}

$message = "";
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // I-check ang credentials kasama ang course, year, at block
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND course=? AND year=? AND block=? AND role='student'");
    $stmt->bind_param("ssss", $username, $course, $year, $block);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // I-verify ang hashed password
        if(password_verify($password, $user['password'])) {
            
            // SUCCESS: I-set ang sessions
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'student'; 
            header("Location: home.php");
            exit();
        } else { $message = "Incorrect password."; }
    } else {
        $message = "No account found in this section.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NEXUS Portal</title>
    <link rel="stylesheet" href="designs/studentlogin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <ul class="bubbles">
        <li class="bubble" style="left: 25%; width: 80px; height: 80px;"></li>
        <li class="bubble" style="left: 10%; width: 20px; height: 20px; animation-delay: 2s;"></li>
        <li class="bubble" style="left: 70%; width: 20px; height: 20px; animation-delay: 4s;"></li>
        <li class="bubble" style="left: 40%; width: 60px; height: 60px; animation-delay: 0s;"></li>
        <li class="bubble" style="left: 65%; width: 20px; height: 20px; animation-delay: 7s;"></li>
        <li class="bubble" style="left: 35%; width: 150px; height: 150px; animation-delay: 7s;"></li>
        <li class="bubble" style="left: 50%; width: 25px; height: 25px; animation-delay: 15s;"></li>
        <li class="bubble" style="left: 20%; width: 15px; height: 15px; animation-delay: 2s;"></li>
        <li class="bubble" style="left: 85%; width: 150px; height: 150px; animation-delay: 11s;"></li>
        <li class="bubble" style="left: 60%; width: 20px; height: 20px; animation-delay: 9s;"></li>
        <li class="bubble" style="left: 75%; width: 40px; height: 40px; animation-delay: 5s;"></li>
        <li class="bubble" style="left: 90%; width: 15px; height: 15px; animation-delay: 6s;"></li>
        <li class="bubble" style="left: 80%; width: 80px; height: 80px; animation-delay: 8s;"></li>
        <li class="bubble" style="left: 95%; width: 20px; height: 20px; animation-delay: 14s;"></li>
        <li class="bubble" style="left: 5%; width: 20px; height: 20px; animation-delay: 3s;"></li>
        <li class="bubble" style="left: 30%; width: 15px; height: 15px; animation-delay: 12s;"></li>
        <li class="bubble" style="left: 55%; width: 40px; height: 40px; animation-delay: 10s;"></li>
        <li class="bubble" style="left: 75%; width: 20px; height: 20px; animation-delay: 13s;"></li>
        <li class="bubble" style="left: 90%; width: 25px; height: 25px; animation-delay: 16s;"></li>
        <li class="bubble" style="left: 10%; width: 60px; height: 60px; animation-delay: 5s;"></li>
        <li class="bubble" style="left: 20%; width: 20px; height: 20px; animation-delay: 8s;"></li>
        <li class="bubble" style="left: 35%; width: 15px; height: 15px; animation-delay: 9s;"></li>
        <li class="bubble" style="left: 50%; width: 40px; height: 40px; animation-delay: 10s;"></li>
        <li class="bubble" style="left: 65%; width: 20px; height: 20px; animation-delay: 11s;"></li>
    </ul>

    <div class="main-container">
        <div class="left-side">
            <div class="side-content">
                <h1 class="portal-title-left">NEXUS <span>Academic Portal</span></h1>
                <img src="../images/olshco.jpg" class="side-logo" alt="Logo">
            </div>
        </div>

        <div class="right-side">
            <div class="form-wrapper">
                <div class="login-box">
                    <h2>Sign In</h2>
                    <p class="sub-text">Logging in for <strong><?php echo htmlspecialchars("$course $year-$block"); ?></strong></p>
                    
                    <?php if($message != ""): ?>
                        <div class="error-msg">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $message; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="input-group">
                            <div class="input-wrapper">
                                <i class="fas fa-user"></i>
                                <input type="text" name="username" placeholder="Username" required>
                            </div>
                        </div>

                        <div class="input-group">
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" placeholder="Password" required>
                            </div>
                        </div>

                        <button type="submit" name="login" class="login-btn">
                            Sign In  <i class="fas fa-sign-in-alt"></i>
                        </button>
                    </form>
                    
                    <div class="footer-links">
                        <a href="pre-login.php"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>