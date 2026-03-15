<?php
session_start();
// Kung may session na, huwag nang pakitahin ang selection, diretso na sa home
if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'student') {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Program - NEXUS Portal</title>
    <link rel="stylesheet" href="designs/prelogin.css">
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
    
    <div class="selection-box">
        <div id="step1">
            <h2>Select Your Program</h2>
            <div class="course-grid">
                <div class="course-card" onclick="nextStep('BSOAD')">
                    <i class="fas fa-briefcase"></i>
                    <h3>BSOAD</h3>
                </div>
                <div class="course-card" onclick="nextStep('BSHM')">
                    <i class="fas fa-utensils"></i>
                    <h3>BSHM</h3>
                </div>
                <div class="course-card" onclick="nextStep('BSCRIM')">
                    <i class="fas fa-shield-alt"></i>
                    <h3>BSCRIM</h3>
                </div>
                <div class="course-card" onclick="nextStep('BSEDUC')">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>BSEDUC</h3>
                </div>
                <div class="course-card" onclick="nextStep('BSIT')">
                    <i class="fas fa-laptop-code"></i>
                    <h3>BSIT</h3>
                </div>
            </div>
        </div>

        <div id="step2" class="step-2">
            <button class="back-btn" onclick="prevStep()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <h2 id="displayCourse">Details</h2>
            <form action="login.php" method="GET">
                <input type="hidden" name="course" id="courseInput">
                
                <div class="input-group-select">
                    <label>Year Level</label>
                    <select name="year" required>
                        <option value="">-- Choose Year --</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>
                </div>

                <div class="input-group-select">
                    <label>Block / Section</label>
                    <select name="block" required>
                        <option value="">-- Choose Block --</option>
                        <option value="A">Block A</option>
                        <option value="B">Block B</option>
                        <option value="C">Block C</option>
                        <option value="D">Block D</option>
                    </select>
                </div>

                <button type="submit" class="proceed-btn">Proceed to Login</button>
            </form>
        </div>
    </div>

    <script>
        function nextStep(course) {
            document.getElementById('courseInput').value = course;
            document.getElementById('displayCourse').innerText = course + " Details";
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
        }
        function prevStep() {
            document.getElementById('step1').style.display = 'block';
            document.getElementById('step2').style.display = 'none';
        }
    </script>

</body>
</html>