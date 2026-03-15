<?php
session_start();
include '../db.php'; 

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Nexus Portal</title>
    <link rel="stylesheet" href="designs/home.css">
    <link rel="stylesheet" href="designs/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
            <li><a href="subjects.php"><i class="fas fa-book"></i> <span>Subjects</span></a></li>
            <li><a href="view_grades.php"><i class="fas fa-file-invoice"></i> <span>View Grades</span></a></li>
            <li><a href="announcements.php"><i class="fas fa-bullhorn"></i> <span>Announcements</span></a></li>
            <li class="active"><a href="about.php"><i class="fas fa-users"></i> <span>About Us</span></a></li>
            <li><a href="../logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </nav>

    <main class="main-content">

        <section class="content-body">
            <div class="about-container">
                <div class="about-header-text">
                    <h2>Meet the Team</h2>
                    <p>The creative minds behind the Nexus Academic Portal.</p>
                </div>

                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        
                        <div class="swiper-slide">
                            <div class="dev-card">
                                <div class="img-wrapper">
                                    <img src="images/zane_profile.jpg" alt="Zane">
                                </div>
                                <h3>Zane Bautista</h3>
                                <p class="role">Full-Stack Developer</p>
                                <div class="dev-description">
                                    <p>Specializes in both frontend and backend development, creating seamless user experiences.</p>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="dev-card">
                                <div class="img-wrapper">
                                    <img src="images/emerson_profile.jpg" alt="Emerson">
                                </div>
                                <h3>Emerson James Bantillo</h3>
                                <p class="role">Frontend Specialist</p>
                                <div class="dev-description">
                                    <p>Expert in creating beautiful and responsive user interfaces with modern design principles.</p>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="dev-card">
                                <div class="img-wrapper">
                                    <img src="images/aeron_profile.jpg" alt="Aeron">
                                </div>
                                <h3>Aeron Clyde Canay</h3>
                                <p class="role">Frontend Specialist</p>
                                <div class="dev-description">
                                    <p>Focuses on interactive web components and optimizing user experience across all devices.</p>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="dev-card">
                                <div class="img-wrapper">
                                    <img src="images/scottie_profile.jpg" alt="Scottie">
                                </div>
                                <h3>Scottie De Geron</h3>
                                <p class="role">Database Manager</p>
                                <div class="dev-description">
                                    <p>Manages database architecture and ensures efficient data storage and retrieval systems.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const btn = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        btn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 'auto',
            spaceBetween: 0,
            centeredSlides: false,
            loop: true,
            autoplay: {
                delay: 1,
                disableOnInteraction: false,
                pauseOnMouseEnter: false,
                reverseDirection: false,
            },
            speed: 8000,
            allowTouchMove: false,
            simulateTouch: false,
            grabCursor: false,
            cssMode: false,
            freeMode: {
                enabled: true,
                sticky: false,
                momentum: false,
            },
        });

        // Working hover controls
        const swiperContainer = document.querySelector('.mySwiper');
        
        swiperContainer.addEventListener('mouseenter', function() {
            swiper.autoplay.pause();
        });
        
        swiperContainer.addEventListener('mouseleave', function() {
            swiper.autoplay.resume();
        });
    </script>

</body>
</html>