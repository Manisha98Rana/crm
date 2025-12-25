<?php 
require_once 'session_config.php'; // Persistent Session

// Auto-Redirect if Logged In
if (isset($_SESSION['student_id'])) {
    if (isset($_SESSION['is_parent_account']) && $_SESSION['is_parent_account'] == 1) {
        header("Location: parent/index.php");
    } else {
        header("Location: student/dashboard.php");
    }
    exit();
}

include_once 'db_conn.php';

// Logo Path (Hardcoded as per header.php)
$logo_path1 = 'image/favicon.png';

// Fetch Top Counsellors
// Note: 'rating' and 'total_sessions' columns do not exist in the table currently.
// We filter by status='Active' based on admin view.
// Fetch All Registered Counsellors (No Limit, All Status)
$sql = "SELECT * FROM counsellors ORDER BY is_online DESC, id DESC";
$result = $conn->query($sql);

// Fetch Upcoming Exams (Active & Future Application Date)
$exams_sql = "SELECT * FROM entrance_exams WHERE status='active' AND application_end_date >= CURDATE() ORDER BY exam_date ASC LIMIT 4";
$exams_result = $conn->query($exams_sql);

// Fetch Top Colleges (Ordered by Ranking)
// Note: Adjust ORDER BY if 'ranking' column is not numeric or has mixed data. Assuming standard setup.
$colleges_sql = "SELECT * FROM colleges ORDER BY ranking ASC LIMIT 4";
$colleges_result = $conn->query($colleges_sql);

// Fetch Company Settings (Favicon & Logo)
$settings_sql = "SELECT favicon, logo FROM company_settings WHERE id = 1";
$settings_result = $conn->query($settings_sql);
$favicon_path = 'image/favicon.png'; // Default
$logo_path = 'image/favicon.png'; // Default fallback for logo if needed, though previously it was separate.
// $logo_path is already set to 'image/fmadda.png' at top, but we can overwrite it if DB has one.
if ($settings_result && $settings_result->num_rows > 0) {
    $settings = $settings_result->fetch_assoc();
    if (!empty($settings['favicon'])) {
        $favicon_path = 'admin/' . $settings['favicon'];
    }
    if (!empty($settings['logo'])) {
        $logo_path = 'admin/' . $settings['logo'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Counsellors | FormsAdda</title>
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($favicon_path) ?>">
    
    <!-- PWA Configuration -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#008190">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="FormsAdda">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        :root {
            --primary: #008190;
            --primary-dark: #00606b;
            --secondary: #F38E3E;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative; 
        }

        /* ==== CUSTOM HEADER ==== */
        .custom-navbar {
            padding: 15px 0;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand img {
            height: 40px;
        }
        .nav-link {
            font-weight: 500;
            color: var(--text-dark) !important;
            margin: 0 10px;
        }
        .nav-link:hover {
            color: var(--primary) !important;
        }

        /* ==== HERO SECTION ==== */
        .hero-section {
            padding: 100px 0 80px;
            background: linear-gradient(135deg, #e0f2f1 0%, #ffffff 60%, #fff3e0 100%);
            position: relative;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 300px; height: 300px;
            background: rgba(0, 129, 144, 0.05);
            border-radius: 50%;
            z-index: 0;
        }

        /* ==== BUTTONS ==== */
        .btn-primary-custom {
            background-color: var(--primary);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }
        .btn-primary-custom:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            color: white;
        }

        .btn-secondary-custom {
            background-color: var(--secondary);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }
        .btn-secondary-custom:hover {
            background-color: #e67e22;
            transform: translateY(-2px);
            color: white;
        }

        /* ==== CARDS ==== */
        .section-title {
            font-weight: 700;
            margin-bottom: 10px;
        }
        .section-subtitle {
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 10px;
        }

        .service-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            border: 1px solid #eee;
            transition: 0.3s;
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.05);
        }
        .service-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }

        /* Counsellor Card */
        .profile-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #eee;
            transition: 0.3s;
            overflow: hidden;
            height: 100%;
        }
        .profile-card:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transform: translateY(-5px);
        }
        
        /* College Card Premium */
        .college-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #f0f0f0;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
            height: 100%;
            position: relative;
        }
        .college-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border-color: transparent;
        }
        .college-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: 0.3s;
        }
        .college-card:hover::before {
            opacity: 1;
        }
        .college-logo-box {
            width: 65px;
            height: 65px;
            padding: 8px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            border: 1px solid #f8f8f8;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .college-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 6px;
        }
        .profile-header {
            padding: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f5f5f5;
        }
        .card-stats {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            background: #fdfdfd;
        }
        .stat-val { font-weight: 700; display: block; }
        .card-actions {
            padding: 15px 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .action-btn {
            padding: 8px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 0.9rem;
        }
        .btn-chat { border: 1px solid var(--primary); color: var(--primary); }
        .btn-chat:hover { background: var(--primary); color: white; }
        .btn-call { background: var(--secondary); color: white; }
        .btn-call:hover { background: #e67e22; color: white; }

        /* Steps */
        /* Steps using White Theme on Dark Background */
        .step-card {
            background: white;
            border: none;
            border-radius: 20px;
            position: relative;
            z-index: 1;
            transition: 0.3s;
            color: var(--text-dark);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            background: white;
        }
        .step-number {
            font-size: 4rem;
            font-weight: 900;
            color: rgba(0, 129, 144, 0.15); /* Watermark style Teal */
            line-height: 1;
            margin-bottom: -20px;
            position: relative;
            z-index: -1;
        }


        /* Footer */
        .custom-footer {
            background: #1a1a1a;
            color: #ccc;
            padding: 60px 0 20px;
        }
        .footer-link {
            color: #999;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            transition: 0.3s;
        }
        .footer-link:hover { color: var(--secondary); padding-left: 5px; }
        .social-btn {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: #333;
            display: flex; align-items: center; justify-content: center;
            color: white; margin-right: 10px;
            text-decoration: none;
            transition: 0.3s;
        }
        .social-btn:hover { background: var(--primary); }

        /* ==== RESPONSIVE ==== */
        @media (max-width: 991px) {
            .navbar-collapse {
                text-align: center;
                padding-top: 20px;
                padding-bottom: 20px;
                background: white; /* Ensure visibility */
            }
            .navbar-nav .nav-item {
                margin-bottom: 25px; /* Increase space between text links */
            }
            .navbar-nav .nav-item:last-child {
                margin-bottom: 0;
            }
            /* Fix button overlapping and margins */
            .navbar-nav .btn {
                width: 100%; /* Make buttons wider on mobile for easier touch */
                max-width: 200px;
                display: block;
                margin: 0 auto;
            }
            .nav-item.ms-2 {
                margin-left: 0 !important; /* Remove horizontal margin */
                margin-top: 15px; /* Add vertical space clearly */
            }

            .hero-section {
                padding: 60px 0 40px;
                text-align: center;
            }
            .hero-section h1 {
                font-size: 2.5rem;
            }
            .hero-section .d-flex.flex-wrap {
                justify-content: center;
            }
            .hero-section .mt-5.d-flex.align-items-center {
                justify-content: center;
            }
            .about-img-box {
                margin-top: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .navbar-brand img {
                height: 35px;
            }
            .hero-section {
                padding: 40px 0;
            }
            .hero-section h1 {
                font-size: 2rem;
            }
            .section-title.h2 {
                font-size: 1.75rem;
            }
            .step-number {
                font-size: 3rem;
            }
            .custom-footer {
                text-align: center;
            }
            .custom-footer .d-flex {
                justify-content: center;
            }
            .container {
                padding-left: 20px;
                padding-right: 20px;
                overflow-x: hidden; /* Prevent inner scroll */
            }
            .row {
                margin-left: 0;
                margin-right: 0;
            }
            .col-lg-3, .col-md-6, .col-6 {
                padding-left: 10px;
                padding-right: 10px;
            }
        }

        @keyframes pulse-btn {
            0% { box-shadow: 0 0 0 0 rgba(243, 142, 62, 0.7); transform: scale(1); }
            70% { box-shadow: 0 0 0 10px rgba(243, 142, 62, 0); transform: scale(1.05); }
            100% { box-shadow: 0 0 0 0 rgba(243, 142, 62, 0); transform: scale(1); }
        }
        .attract-btn {
            background: linear-gradient(45deg, #F38E3E, #ffb74d);
            border: none;
            color: white !important;
            animation: pulse-btn 2s infinite;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(243, 142, 62, 0.4);
            font-weight: 700 !important;
            letter-spacing: 0.5px;
        }
        .attract-btn:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 25px rgba(243, 142, 62, 0.6);
            background: linear-gradient(45deg, #e68a00, #F38E3E);
        }
    </style>
</head>
<body>

<!-- 1. CUSTOM NAVBAR -->
<nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="<?= htmlspecialchars($logo_path) ?>" alt="Logo">
        </a>
        <div class="d-flex align-items-center gap-2">
            <button id="installAppBtnMobile" class="btn rounded-pill px-3 py-1 d-lg-none fw-bold small me-2 attract-btn" style="display: none; font-size: 0.85rem;"><i class="fas fa-download me-1"></i> Install App</button>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="navContent">
            <ul class="navbar-nav ms-auto align-items-center mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#counsellors">Counsellors</a></li>
                <li class="nav-item"><a class="nav-link" href="#howitworks">How it Works</a></li>
                <li class="nav-item d-lg-none">
                    <a class="nav-link" href="student/login.php">Login</a>
                </li>
                <li class="nav-item ms-lg-3 d-none d-lg-block">
                    <a href="student/login.php" class="btn btn-outline-dark rounded-pill px-4">Login</a>
                </li>
                <li class="nav-item ms-2">
                    <a href="student/register.php" class="btn-primary-custom">Get Started</a>
                </li>
                <li class="nav-item ms-2">
                    <button id="installAppBtnDesktop" class="btn rounded-pill px-4 attract-btn" style="display: none;"><i class="fas fa-download me-2"></i> Install App</button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- 2. HERO SECTION -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                <span class="section-subtitle"><i class="fas fa-certificate"></i> India's Trusted Platform</span>
                <h1 class="display-4 fw-bold mb-4" style="line-height: 1.2;">
                    Find the Right Path with <br>
                    <span style="color: var(--primary);">Expert Guidance</span>
                </h1>
                <p class="lead text-muted mb-5">
                    Connect with verified education counsellors instantly. Get personalized advice on colleges, exams, and career choices through Chat or Call.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#counsellors" class="btn-primary-custom text-decoration-none">
                        <i class="fas fa-comments me-2"></i> Chat Now
                    </a>
                    <a href="#counsellors" class="btn-secondary-custom text-decoration-none">
                        <i class="fas fa-phone-alt me-2"></i> Talk Now
                    </a>
                </div>
                <div class="mt-5 d-flex align-items-center gap-3">
                    <div class="d-flex">
                        <img src="https://randomuser.me/api/portraits/thumb/men/32.jpg" class="rounded-circle border border-2 border-white" width="40" alt="">
                        <img src="https://randomuser.me/api/portraits/thumb/women/44.jpg" class="rounded-circle border border-2 border-white" width="40" style="margin-left: -15px;" alt="">
                        <img src="https://randomuser.me/api/portraits/thumb/men/85.jpg" class="rounded-circle border border-2 border-white" width="40" style="margin-left: -15px;" alt="">
                    </div>
                    <div>
                        <div class="d-flex text-warning small">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <small class="fw-bold">4.8/5 Rated by Students</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center" data-aos="fade-left">
                <!-- Hero Image (Uploaded by User) -->
                <img src="image/studnet-banner.png" 
                     alt="Happy Student Chatting" class="img-fluid" style="max-height: 500px; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.1));">
            </div>
        </div>
    </div>
</section>

<!-- 3. SERVICES GRID -->
<section id="services" class="py-5" style="background-color: #fafafa;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-subtitle">What We Offer</span>
            <h2 class="section-title h2">Comprehensive Guidance</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="100">
                <div class="service-card">
                    <div class="service-icon" style="background: #e0f7fa; color: #008190;">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Chat Support</h5>
                    <small class="text-muted">Instant Replies</small>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="200">
                <div class="service-card">
                    <div class="service-icon" style="background: #fff3e0; color: #f57c00;">
                        <i class="fas fa-phone-volume"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Voice Call</h5>
                    <small class="text-muted">Deep Discussion</small>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="300">
                <div class="service-card">
                    <div class="service-icon" style="background: #e8f5e9; color: #2e7d32;">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Scholarships</h5>
                    <small class="text-muted">Financial Aid</small>
                </div>
            </div>
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="400">
                <div class="service-card">
                    <div class="service-icon" style="background: #f3e5f5; color: #7b1fa2;">
                        <i class="fas fa-university"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Predictor</h5>
                    <small class="text-muted">College AI</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. COUNSELLORS GRID -->
<section id="counsellors" class="py-5" style="background: linear-gradient(to right, #ffffff, #fff3e0);">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="section-subtitle">Our Experts</span>
                <h2 class="section-title h2">Connect with Counsellors</h2>
            </div>
             <a href="student/counsellors.php" class="btn btn-outline-dark rounded-pill px-4 fw-bold">View All <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): 
                    $is_online = ($row['is_online'] == '1');
                    $initials = strtoupper(substr($row['name'], 0, 1));
                    // Handle missing columns with defaults
                    $rating = '4.9'; // Default high rating for top experts
                    $sessions_count = '120+'; // Default session count
                    $experience = isset($row['experience']) ? $row['experience'] : '5'; // Default experience
                ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="profile-card">
                         <div class="profile-header">
                             <div class="position-relative me-3">
                                <div style="width:60px; height:60px; border-radius:50%; background:#f0f2f5; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:bold; color:var(--primary); border: 2px solid var(--primary);">
                                    <?= $initials ?>
                                </div>
                                 <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle" style="display: <?= $is_online ? 'block' : 'none' ?>;"></span>
                             </div>
                             <div>
                                 <h5 class="mb-0 fw-bold" style="font-size:1.1rem;"><?= htmlspecialchars(explode(' ', $row['name'])[0]) ?></h5>
                                 <div class="small text-warning">
                                     <span><?= $rating ?></span> <i class="fas fa-star"></i>
                                 </div>
                             </div>
                         </div>
                         <div class="card-stats border-bottom">
                             <div class="text-center">
                                 <span class="stat-val"><?= $experience ?>+</span>
                                 <small class="text-muted">Years</small>
                             </div>
                             <div class="text-center">
                                 <span class="stat-val"><?= $sessions_count ?></span>
                                 <small class="text-muted">Orders</small>
                             </div>
                             <div class="text-center">
                                 <span class="stat-val">₹<?= isset($row['chat_price']) ? (int)$row['chat_price'] : '20' ?></span>
                                 <small class="text-muted">/min</small>
                             </div>
                         </div>
                         <div class="card-actions">
                             <a href="student/start_session.php?counsellor_id=<?= $row['id'] ?>&type=chat" class="action-btn btn-chat">
                                 <i class="fas fa-comment-dots"></i> Chat
                             </a>
                             <a href="student/start_session.php?counsellor_id=<?= $row['id'] ?>&type=voice" class="action-btn btn-call">
                                 <i class="fas fa-phone-alt"></i> Call
                             </a>
                         </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 py-5 text-center text-muted">No counsellors found.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 4.1. UPCOMING EXAMS -->
<section id="exams" class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="section-subtitle">Stay Ahead</span>
                <h2 class="section-title h2">Upcoming Entrance Exams</h2>
            </div>
             <a href="student/entrance_exams.php" class="btn btn-outline-dark rounded-pill px-4 fw-bold">View All <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
        
        <div class="row g-4">
            <?php if ($exams_result && $exams_result->num_rows > 0): ?>
                <?php while ($exam = $exams_result->fetch_assoc()): 
                    $exam_date = date('d M Y', strtotime($exam['exam_date']));
                    $app_end_date = date('d M Y', strtotime($exam['application_end_date']));
                ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="service-card text-start p-4 h-100 d-flex flex-column">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($exam['exam_category']) ?></span>
                            <small class="text-danger fw-bold"><i class="far fa-clock me-1"></i> Apply by <?= $app_end_date ?></small>
                        </div>
                        <h5 class="fw-bold mb-3"><?= htmlspecialchars($exam['exam_name']) ?></h5>
                        <p class="text-muted small mb-4 flex-grow-1">
                            <?= substr(strip_tags($exam['syllabus_details']), 0, 80) ?>...
                        </p>
                        <div class="mt-auto pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center text-secondary small">
                                <span><i class="fas fa-calendar-alt me-2"></i>Exam Date</span>
                                <span class="fw-bold text-dark"><?= $exam_date ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 py-4 text-center text-muted border rounded bg-light">
                    No upcoming exams found. <a href="student/entrance_exams.php" class="fw-bold text-primary">Check Archive</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- 4.2. TOP COLLEGES (SLIDER) -->
<section id="colleges" class="py-5" style="background-color: #fdfbf7; overflow: hidden;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <span class="section-subtitle">Dream Big</span>
                <h2 class="section-title h2">Top Ranked Colleges</h2>
            </div>
             <div class="d-flex gap-2">
                 <!-- Custom Navigation -->
                 <button class="btn btn-outline-dark rounded-circle swiper-prev"><i class="fas fa-chevron-left"></i></button>
                 <button class="btn btn-outline-dark rounded-circle swiper-next"><i class="fas fa-chevron-right"></i></button>
             </div>
        </div>
        
        <!-- Swiper -->
        <!-- Swiper -->
        <div class="swiper collegeSwiper" style="padding: 20px 20px 50px 20px; margin: -20px -20px 0 -20px;">
            <div class="swiper-wrapper">
                <?php if ($colleges_result && $colleges_result->num_rows > 0): ?>
                    <?php while ($college = $colleges_result->fetch_assoc()): 
                         $default_logo = 'admin/uploads/university.png';
                         $logo_path = 'admin/uploads/university.png'; 
                         if (!empty($college['college_logo'])) {
                             $logo_path = $college['college_logo'];
                         }
                    ?>
                    <div class="swiper-slide h-auto">
                        <div class="college-card d-flex flex-column h-100">
                             <div class="p-4 flex-grow-1 d-flex flex-column">
                                 <div class="d-flex align-items-start mb-3">
                                    <div class="college-logo-box me-3 flex-shrink-0">
                                         <img src="<?= htmlspecialchars($logo_path) ?>" alt="Logo" style="width:100%; height:100%; object-fit:contain;" onerror="this.src='<?= $default_logo ?>';">
                                    </div>
                                     <div class="overflow-hidden">
                                         <h6 class="fw-bold mb-1 text-dark text-truncate-2" style="font-size: 1.05rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 2.8rem;"><?= htmlspecialchars($college['college_name']) ?></h6>
                                         <div class="text-muted small text-truncate"><i class="fas fa-map-marker-alt text-danger me-1"></i> <?= htmlspecialchars($college['college_location']) ?></div>
                                     </div>
                                 </div>
                                 
                                 <div class="d-flex flex-wrap gap-2 mb-3">
                                     <span class="college-badge bg-light text-secondary border">
                                         <i class="fas fa-trophy text-warning me-1"></i>NIRF #<?= htmlspecialchars($college['ranking']) ?>
                                     </span>
                                     <span class="college-badge bg-success bg-opacity-10 text-success">
                                         <?= htmlspecialchars($college['type']) ?>
                                     </span>
                                 </div>

                                 <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top border-light">
                                     <small class="text-muted fw-bold text-truncate" style="max-width: 150px;"><?= htmlspecialchars($college['accreditation']) ?></small>
                                     <small class="text-primary fw-bold" style="cursor: pointer;">Know More <i class="fas fa-arrow-right small ms-1"></i></small>
                                 </div>
                             </div>
                             <a href="student/college_details.php?id=<?= $college['id'] ?>" class="stretched-link"></a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center text-muted">No colleges found.</div>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination mt-4"></div>
        </div>
        
        <div class="text-center mt-5">
            <a href="student/colleges.php" class="btn btn-outline-dark rounded-pill px-4 fw-bold">Explore All <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper(".collegeSwiper", {
        slidesPerView: 1,
        spaceBetween: 25,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-next",
            prevEl: ".swiper-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 4,
            },
        },
    });
</script>

<!-- 5. HOW IT WORKS -->
<!-- 5. HOW IT WORKS -->
<section id="howitworks" class="py-5" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: white;">
    <div class="container text-center">
        <span class="section-subtitle" style="color: rgba(255,255,255,0.8);">Process</span>
        <h2 class="section-title h2 mb-5 text-white">How It Works</h2>
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up">
                <div class="step-card p-4 text-start">
                    <div class="step-number">01</div>
                    <h5 class="fw-bold mt-3">Select Counsellor</h5>
                    <p class="text-muted">Browse through profiles, check ratings and reviews to find your best match.</p>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="step-card p-4 text-start">
                    <div class="step-number" style="color: rgba(243, 142, 62, 0.2);">02</div> <!-- Watermark Orange -->
                    <h5 class="fw-bold mt-3">Recharge Wallet</h5>
                    <p class="text-muted">Add money to your wallet securely. First chat is often free for new users!</p>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="step-card p-4 text-start">
                    <div class="step-number" style="color: rgba(40, 167, 69, 0.2);">03</div> <!-- Watermark Green -->
                    <h5 class="fw-bold mt-3">Start Session</h5>
                    <p class="text-muted">Connect instantly via Chat or Call and get your career roadmap.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. TESTIMONIALS (EXPANDED) -->
<section class="py-5" style="background: linear-gradient(to left, #ffffff, #e0f2f1);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-subtitle">Testimonials</span>
            <h2 class="section-title h2">Student Stories</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <!-- T1 -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="p-4 bg-white rounded-4 h-100 shadow-sm border border-light">
                    <div class="d-flex text-warning mb-3"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="fst-italic text-muted mb-4">"The platform is super easy to use. I found a counsellor who helped me crack my entrance exam strategy."</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-2 rounded-circle me-3 fw-bold text-primary border">RK</div>
                        <div><h6 class="fw-bold m-0">Rahul Kumar</h6><small>Engineering</small></div>
                    </div>
                </div>
            </div>
            <!-- T2 -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 bg-white rounded-4 h-100 shadow-sm border border-light">
                    <div class="d-flex text-warning mb-3"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="fst-italic text-muted mb-4">"I love the wallet system. It's transparent and I only pay for the minutes I talk. Very professional."</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-2 rounded-circle me-3 fw-bold text-secondary border">AP</div>
                        <div><h6 class="fw-bold m-0">Anjali P.</h6><small>Medical</small></div>
                    </div>
                </div>
            </div>
            <!-- T3 -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 bg-white rounded-4 h-100 shadow-sm border border-light">
                     <div class="d-flex text-warning mb-3"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
                    <p class="fst-italic text-muted mb-4">"The video call quality was amazing. Felt like a real offline session sitting at home."</p>
                    <div class="d-flex align-items-center">
                         <div class="bg-light p-2 rounded-circle me-3 fw-bold text-success border">VS</div>
                        <div><h6 class="fw-bold m-0">Vikram S.</h6><small>MBA</small></div>
                    </div>
                </div>
            </div>
            <!-- T4 NEW -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="p-4 bg-white rounded-4 h-100 shadow-sm border border-light">
                    <div class="d-flex text-warning mb-3"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="fst-italic text-muted mb-4">"I was confused about abroad studies. The expert cleared all my doubts regarding visas."</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-2 rounded-circle me-3 fw-bold text-danger border">MD</div>
                        <div><h6 class="fw-bold m-0">Meera D.</h6><small>Study Abroad</small></div>
                    </div>
                </div>
            </div>
            <!-- T5 NEW -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="p-4 bg-white rounded-4 h-100 shadow-sm border border-light">
                    <div class="d-flex text-warning mb-3"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="fst-italic text-muted mb-4">"Great experience! The counsellor was polite and knew exactly which colleges fit my score."</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-2 rounded-circle me-3 fw-bold text-info border">SK</div>
                        <div><h6 class="fw-bold m-0">Suresh K.</h6><small>JEE Main</small></div>
                    </div>
                </div>
            </div>
            <!-- T6 NEW -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="p-4 bg-white rounded-4 h-100 shadow-sm border border-light">
                    <div class="d-flex text-warning mb-3"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="fst-italic text-muted mb-4">"Instant connection at midnight before my exam helped me calm down. Life saver!"</p>
                    <div class="d-flex align-items-center">
                        <div class="bg-light p-2 rounded-circle me-3 fw-bold text-dark border">NJ</div>
                        <div><h6 class="fw-bold m-0">Neha J.</h6><small>Board Exams</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 7. ABOUT US -->
<section class="py-5 bg-white" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                <span class="section-subtitle">About Us</span>
                <h2 class="display-5 fw-bold mb-4">Bridging the Gap Between <span style="color: var(--primary);">Dreams & Reality</span></h2>
                <p class="text-muted mb-4">
                    FormsAdda CRM was born from a simple mission: to democratize access to quality education guidance. We believe every student deserves the right mentorship.
                </p>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light p-3 rounded-circle text-primary"><i class="fas fa-bullseye fa-lg"></i></div>
                        <div><h6 class="fw-bold m-0">Our Mission</h6><small class="text-muted">Verify and connect 1 million+ students with experts.</small></div>
                    </div>
                     <div class="d-flex align-items-center gap-3">
                        <div class="bg-light p-3 rounded-circle text-secondary"><i class="fas fa-heart fa-lg"></i></div>
                        <div><h6 class="fw-bold m-0">Our Values</h6><small class="text-muted">Transparency, Trust, and Student-First approach.</small></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                <div class="about-img-box position-relative">
                    <img src="image/ws1.webp" class="img-fluid rounded-4 w-100" style="object-fit: cover; min-height: 400px;" alt="About Us">
                    <div class="position-absolute bottom-0 end-0 p-3 bg-white m-3 rounded shadow-sm">
                        <div class="text-success display-5 fw-bold">98%</div>
                        <small class="fw-bold text-dark">Success Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 8. FAQ (EXPANDED) -->
<section class="py-5" style="background-color: #fdfbf7;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <span class="section-subtitle">FAQ</span>
                    <h2 class="section-title h2">Common Questions</h2>
                </div>
                <div class="accordion" id="faqAccordion">
                    <!-- Q1 -->
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                                How do I choose the right counsellor?
                            </button>
                        </h2>
                        <div id="q1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                You can browse counsellor profiles, read their bios, check their expertise areas (e.g., Engineering, Medical, Study Abroad), and view ratings from other students to make an informed decision.
                            </div>
                        </div>
                    </div>
                    <!-- Q2 -->
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                                Is my personal data safe?
                            </button>
                        </h2>
                        <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Yes, we take privacy seriously. Your phone number and chat details are encrypted and never shared with verified counsellors without your explicit permission.
                            </div>
                        </div>
                    </div>
                    <!-- Q3 -->
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                                What if I am not satisfied with the session?
                            </button>
                        </h2>
                        <div id="q3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Transparency is key. If you face technical issues or rude behavior, report the session within 24 hours. Our quality team will review the chat/recording and process a refund if valid.
                            </div>
                        </div>
                    </div>
                    <!-- Q4 -->
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q4">
                                Can I request a refund for wallet recharge?
                            </button>
                        </h2>
                        <div id="q4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Unused wallet balance can be used anytime for any counsellor. However, direct bank refunds are only processed in case of accidental double payments or technical failures.
                            </div>
                        </div>
                    </div>
                    <!-- Q5 -->
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q5">
                                Are these counsellors verified?
                            </button>
                        </h2>
                        <div id="q5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                Absolutely. We have a 5-step verification process verifying their education, certifications, and past track record before onboarding them.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 9. CTA -->
<section class="py-5 text-center" style="background: var(--primary);">
    <div class="container">
        <h2 class="text-white fw-bold mb-4">Download the FormsAdda App</h2>
        <p class="text-white-50 mb-4">Get expert guidance anytime, anywhere.</p>
        <div class="d-flex justify-content-center gap-3">
             <button class="btn btn-dark px-4 py-2 rounded-4 d-flex align-items-center gap-2">
                 <i class="fab fa-google-play fa-lg"></i>
                 <div class="text-start" style="line-height:1.2"><small>GET IT ON</small><br><strong>Google Play</strong></div>
             </button>
             <button class="btn btn-light px-4 py-2 rounded-4 d-flex align-items-center gap-2">
                 <i class="fab fa-apple fa-lg"></i>
                 <div class="text-start" style="line-height:1.2"><small>Download on the</small><br><strong>App Store</strong></div>
             </button>
        </div>
    </div>
</section>

<!-- 10. CUSTOM FOOTER -->
<footer class="custom-footer">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
                 <img src="<?= htmlspecialchars($logo_path1) ?>" alt="Logo" height="50" class="mb-3 bg-white p-2 rounded">
                 <p class="small text-secondary">
                     FormsAdda CRM is India's leading education counselling marketplace, connecting students with verified experts for career guidance.
                 </p>
                 <div class="d-flex">
                     <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                     <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                     <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
                     <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                 </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white mb-3">Quick Links</h5>
                <a href="#about" class="footer-link">About Us</a>
                <a href="#howitworks" class="footer-link">How It Works</a>
                <a href="#counsellors" class="footer-link">Our Experts</a>
                <a href="#services" class="footer-link">Services</a>
                <a href="admin/login.php" class="footer-link">Admin Login</a>
                <a href="counsellor/login.php" class="footer-link">Counsellor Login</a>
            </div>
             <div class="col-lg-2 col-md-6">
                <h5 class="text-white mb-3">Support</h5>
                <a href="#" class="footer-link">Help Center</a>
                <a href="#" class="footer-link">Terms of Service</a>
                <a href="#" class="footer-link">Privacy Policy</a>
                <a href="#" class="footer-link">Refund Policy</a>
            </div>
             <div class="col-lg-4 col-md-6">
                <h5 class="text-white mb-3">Contact Us</h5>
                <p class="small text-secondary mb-2"><i class="fas fa-envelope me-2"></i> support@formsadda.com</p>
                <p class="small text-secondary mb-2"><i class="fas fa-phone me-2"></i> +91 999 888 7777</p>
                <p class="small text-secondary"><i class="fas fa-map-marker-alt me-2"></i> 123 Education Hub, Tech Park, Bangalore</p>
            </div>
        </div>
        <div class="border-top border-secondary mt-5 pt-4 text-center">
            <small class="text-secondary">&copy; <?= date('Y') ?> FormsAdda. All rights reserved.</small>
        </div>
    </div>
</footer>

<!-- App Updated Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1060;">
  <div id="updateToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body fw-bold">
        <i class="fas fa-check-circle me-2"></i> App Updated Successfully!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
<script>
    // Service Worker & Auto-Update
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
             navigator.serviceWorker.register('service-worker.js')
             .then(reg => {
                 // Auto-Update
                if (reg.waiting) reg.waiting.postMessage({ type: 'SKIP_WAITING' });
                
                reg.addEventListener('updatefound', () => {
                    const newWorker = reg.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            newWorker.postMessage({ type: 'SKIP_WAITING' });
                        }
                    });
                });
             });
             
             let refreshing;
             navigator.serviceWorker.addEventListener('controllerchange', () => {
                if (refreshing) return;
                localStorage.setItem('sw_updated', 'true');
                window.location.reload();
                refreshing = true;
            });
        });
    }

    // Show update toast
    document.addEventListener("DOMContentLoaded", function() {
        if (localStorage.getItem('sw_updated') === 'true') {
            localStorage.removeItem('sw_updated');
            const toastEl = document.getElementById('updateToast');
            if(toastEl) {
                const toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        }
    });
</script>
<!-- PWA Install Modal -->
<div class="modal fade" id="installAppModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg text-center" style="background: linear-gradient(135deg, #ffffff, #f9f9f9);">
            <div class="modal-body p-5">
                <div class="mb-4">
                    <div style="width: 80px; height: 80px; background: #fff3e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; box-shadow: 0 5px 15px rgba(243, 142, 62, 0.2);">
                        <img src="image/favicon.png" alt="App Icon" style="width: 50px; height: 50px;">
                    </div>
                </div>
                <h4 class="fw-bold mb-3">Install FormsAdda App</h4>
                <p class="text-muted mb-4">
                    Get a better experience with our app! Access chats, calls, and updates instantly from your home screen.
                </p>
                <div class="d-grid gap-2">
                    <button id="modalInstallBtn" class="btn btn-lg text-white attract-btn rounded-pill border-0 fw-bold">
                        <i class="fas fa-download me-2"></i> Install Now
                    </button>
                    <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Maybe Later</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // PWA Install Logic
    let deferredPrompt;
    const installBtns = [document.getElementById('installAppBtnMobile'), document.getElementById('installAppBtnDesktop')];
    const installModal = new bootstrap.Modal(document.getElementById('installAppModal'));
    const modalInstallBtn = document.getElementById('modalInstallBtn');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        
        // Show the prompt modal automatically after a delay (e.g., 3 seconds) for better UX
        // or just unhide buttons. The user asked for a "popup".
        setTimeout(() => {
            installModal.show();
        }, 3000);

        // Also update UI to notify the user they can add to home screen manually
        installBtns.forEach(btn => btn.style.display = 'inline-block');
    });

    // Handle Modal Install Button
    modalInstallBtn.addEventListener('click', async () => {
        installModal.hide();
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            console.log(`User response to the install prompt: ${outcome}`);
            deferredPrompt = null;
        }
    });

    // Handle Manual Header/Navbar Buttons
    installBtns.forEach(btn => {
        btn.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response to the install prompt: ${outcome}`);
                deferredPrompt = null;
            } else {
                // Keep buttons text as "App Installed" or hide?
                // For now, if no prompt, it might be already installed or not supported.
                alert('App is already installed or installation not supported on this device.');
            }
        });
    });

    window.addEventListener('appinstalled', () => {
        console.log('PWA was installed');
        installBtns.forEach(btn => btn.style.display = 'none');
        installModal.hide();
    });
</script>
</body>
</html>
