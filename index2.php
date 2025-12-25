<?php
include 'db_conn.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch only active + spin-enabled colleges, limit to 8
    $stmt = $pdo->prepare("
        SELECT 
            id,
            college_name,
            college_location,
            established_year,
  
            status
        FROM colleges
        WHERE status = 'active' AND is_spin_win = 1
        ORDER BY college_name ASC
        LIMIT 8
    ");
    $stmt->execute();
    $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Array of border colors to alternate
$borderColors = ['#2563EB', '#DC2626', '#D97706', '#16A34A', '#8B5CF6', '#0EA5E9', '#F43F5E', '#FACC15'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FormsADDA - Spin & Win College Discounts</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="spin-style.css" rel="stylesheet">
    
    
</head>
<body class="font-sans">
    
    <!-- ==== Responsive Header ==== -->
<header class="fixed top-0 left-0 w-full bg-white shadow-sm z-50">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-20">
            
            <!-- Logo & Contest Link -->
            <div class="flex items-center space-x-2 sm:space-x-4 min-w-0">
                <a href="index.php" class="flex items-center flex-shrink-0">
                    <img src="image/fmadda.png" alt="logo" class="h-8 sm:h-10 w-auto">
                </a>
                <a href="https://course-details.formsadda.com" 
                   class="hidden sm:block text-gray-800 font-bold px-2 sm:px-3 py-1 rounded-3xl border-2 border-gray-300 hover:bg-gray-100 transition text-xs sm:text-sm">
                    Contest
                </a>
            </div>

            <!-- Right Side: Participate/Login & Menu -->
            <div class="items-center space-x-2 sm:space-x-4 flex">
                <!-- Participate / Login Button -->
               <button onclick="openModal()" class="inline-flex items-center border-2 border-[#f38e3e] text-[#f38e3e] font-semibold px-3 sm:px-4 py-2 rounded-3xl hover:bg-[#f38e3e] hover:text-white transition-colors duration-300 text-xs sm:text-sm whitespace-nowrap">
                    <span class="hidden xs:inline">Participate</span>
                    <span class="inline xs:hidden">Login</span>
                    <i class="fas fa-arrow-right ml-1 sm:ml-2"></i>
                </button>

                <!-- Mobile Menu Toggle -->
                <button id="mobileMenuBtn" class="flex flex-col space-y-1 sm:hidden">
                    <span class="block w-5 h-0.5 bg-gray-800"></span>
                    <span class="block w-5 h-0.5 bg-gray-800"></span>
                    <span class="block w-5 h-0.5 bg-gray-800"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden sm:hidden bg-white border-t border-gray-200">
        <nav class="flex flex-col p-3 space-y-2">
            <a href="index.php" class="text-gray-800 font-semibold hover:text-blue-600 p-2">Home</a>
            <a href="https://course-details.formsadda.com" class="text-gray-800 font-semibold hover:text-blue-600 p-2">Contest</a>
        </nav>
    </div>
</header>

<script>
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>


<!-- HERO SECTION - Dark with overlay -->
    <section class="hero-bg h-screen flex items-center justify-center relative bg-gray-900" style="background-image: url('uploads/student.jpg'); background-size: cover; background-position: center;">
        <div class="hero-overlay absolute inset-0 bg-black bg-opacity-70"></div>
        <div class="z-10 text-center p-4 md:p-8 max-w-4xl mx-auto">
            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-white mb-4">
                <span style="color: #008190;">Spin & Win</span> 
                <span style="color: #f38e3e;">College Discounts!</span>
            </h1>
            <p class="text-lg text-gray-200 mb-8">Get up to 80% discount on entrance exam applications including CAT, JEE, XAT and more!</p>
            <button style="background-color: #f38e3e;" class="hover:opacity-90 text-white font-bold py-3 px-8 rounded-lg shadow-xl text-lg transition duration-300" onclick="openModal()" id="startSpinningCTA">
                Start Spinning Now!
            </button>
        </div>
    </section>
    <!-- COLLEGE ROTATE SECTION - Teal -->
    <section style="background: linear-gradient(to bottom right, #008190, #005f6b);" class="text-white py-16 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
            <i class="fa-solid fa-graduation-cap mr-2"></i>
            Roll to Discover Your Dream College Application Form Free!
        </h2>
        <p class="text-blue-100 text-lg mb-6 max-w-3xl mx-auto">
            Spin the wheel and unlock exclusive offers for top colleges
        </p>
        <div class="bg-white p-6 md:p-10 mx-auto max-w-6xl rounded-2xl shadow-2xl">
            <iframe id="contentFrame" src="spin-win/college-rotate.php"></iframe>
        </div>
    </section>
    <!-- EXAM SPIN SECTION - White with orange accents -->
    <section class="py-16 md:py-24 bg-white min-h-screen flex items-center justify-center">
        <div class="max-w-4xl mx-auto px-4 md:px-6 text-center w-full">
            <div style="background: linear-gradient(to right, #f38e3e, #ff9955);" class="text-white py-3 px-6 rounded-full inline-block mb-6">
                <i class="fa-solid fa-star mr-2"></i>
                <span class="font-bold">Exclusive Exam Discounts</span>
            </div>
            <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Spin for Exam Application Discounts!
            </h2>
            <p class="text-lg text-gray-700 max-w-3xl mx-auto mb-10">
                Get instant discounts on entrance exam application forms. Spin the wheel to win a guaranteed discount!
            </p>
            <iframe id="examFrame" src="spin-win/exam-spin.php"></iframe>
        </div>
    </section>
    
    <!-- TOP COLLEGES & UNIVERSITIES SECTION -->
  <!-- top-colleges section (your existing code) -->
<section class="py-16 bg-gray-100">
  <div class="max-w-7xl mx-auto px-4 md:px-6 text-center">
    <div class="mb-10">
      <div class="text-3xl text-gray-800 mb-2">
        <i class="fas fa-university text-4xl text-gray-700"></i>
      </div>
      <h2 class="text-4xl font-extrabold text-gray-900 mb-2">Top Colleges & Universities</h2>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">
        Explore premium educational institutions and get exclusive discounts on their application forms.
        Click on any college to spin for discounts up to 80%!
      </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-4 gap-8">
      <?php if (!empty($colleges)): ?>
        <?php foreach ($colleges as $index => $college): ?>
          <?php $borderColor = $borderColors[$index % count($borderColors)]; ?>
          <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 transform hover:-translate-y-1"
               style="border-top: 4px solid <?= $borderColor ?>;">
            <div class="flex flex-col items-center">
              <div class="w-16 h-16 mb-4 flex items-center justify-center rounded-full border-2 border-gray-300 bg-blue-100 text-blue-700 font-bold text-2xl">
                <i class="fas fa-university text-2xl"></i>
              </div>

              <h4 class="font-bold text-xl mb-1 text-gray-900"><?= htmlspecialchars($college['college_name']) ?></h4>

              <div class="flex flex-col items-center text-sm text-gray-500 mb-4 space-y-1">
                <span class="flex items-center">
                  <i class="fas fa-map-marker-alt text-red-500 mr-1"></i>
                  <?= htmlspecialchars($college['college_location']) ?>
                </span>
                <span class="flex items-center">
                  <i class="fas fa-calendar-alt text-green-500 mr-1"></i>
                  Established: <?= htmlspecialchars($college['established_year']) ?>
                </span>
              </div>
            </div>

            <!-- Spin Button -->
            <button
              class="college-spin-btn w-full mt-4 py-2 px-6 rounded-full text-white font-extrabold text-lg
                     bg-gradient-to-r from-[#008190] via-[#388b8d7a] to-[#f38e3e]
                     shadow-lg hover:shadow-2xl transform transition-all duration-300
                     hover:-translate-y-0.5 hover:scale-[1.03] flex items-center justify-center gap-2"
              data-college="<?= htmlspecialchars($college['college_name']) ?>">
              <i class="fa-solid fa-rotate text-white text-xl animate-spin-slow"></i>
              <span>Spin for Discount</span>
            </button>

            <style>
            @keyframes spinSlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
            .animate-spin-slow { animation: spinSlow 3s linear infinite; }
            .college-spin-btn:hover { animation: glowPulse 1.5s infinite; }
            @keyframes glowPulse {
              0%,100% { box-shadow: 0 0 12px rgba(243,142,62,.4); }
              50% { box-shadow: 0 0 25px rgba(243,142,62,.8); }
            }
            </style>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-gray-600 col-span-full">No spin-enabled colleges available at the moment.</p>
      <?php endif; ?>
    </div>

    <button class="mt-12 inline-flex items-center bg-transparent border-2 border-[#008190] text-[#008190]
                hover:bg-[#008190] hover:text-white font-bold py-3 px-8 rounded-full shadow-lg
                transition duration-300 transform hover:scale-105">
      View More Colleges <i class="fas fa-arrow-right ml-2"></i>
    </button>
  </div>
</section>

<!-- IFRAME OVERLAY (place before closing body tag) -->
<div id="spinWinOverlay" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-[9999]">
  <div id="spinWinWrapper" class="relative w-[95%] md:w-[80%] lg:w-[70%] h-[95%] md:h-[90%] lg:h-[85%]">
    <iframe id="spinWinIframe"
            src=""
            title="Spin & Win"
            class="w-full h-full bg-transparent border-none rounded-2xl shadow-2xl overflow-hidden">
    </iframe>

    <!-- close top-right (in overlay, outside iframe) -->
    <button id="closeSpinWin" aria-label="Close spin modal"
            class="absolute top-3 right-3 text-white text-3xl font-bold bg-transparent border-0 cursor-pointer">&times;</button>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const overlay = document.getElementById('spinWinOverlay');
  const iframe = document.getElementById('spinWinIframe');
  const closeBtn = document.getElementById('closeSpinWin');

  // open iframe on each college button click
  document.querySelectorAll('.college-spin-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const college = btn.dataset.college || 'Selected College';
      // build iframe src - pass college name (URL encoded)
      iframe.src = `spin-win/spin-and-win.php?college=${encodeURIComponent(college)}`;

      // show overlay with fade-in
      overlay.classList.remove('hidden');
      overlay.style.opacity = 0;
      overlay.style.display = 'flex';
      requestAnimationFrame(() => { overlay.style.transition = 'opacity .28s ease'; overlay.style.opacity = 1; });
    });
  });

  // close handler: clears iframe src to stop animations running inside
  function hideOverlay() {
    overlay.style.opacity = 0;
    overlay.addEventListener('transitionend', function _once() {
      overlay.removeEventListener('transitionend', _once);
      overlay.style.display = 'none';
      overlay.classList.add('hidden');
      iframe.src = '';
    });
  }

  closeBtn.addEventListener('click', hideOverlay);

  // click outside iframe to close
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) hideOverlay();
  });

  // listen to postMessage from iframe so iframe can ask parent to close or to pass events
  window.addEventListener('message', (ev) => {
    // ensure same origin for safety (optional)
    // if (ev.origin !== window.location.origin) return;
    const data = ev.data || {};
    if (data && data.type === 'closeSpinWin') {
      hideOverlay();
    }
    if (data && data.type === 'resetIframe') {
      iframe.src = '';
    }
    // optionally handle more message types (e.g., open external link)
  }, false);
});
</script>


    <!-- HOW IT WORKS - Brand Color Gradient -->
    <section class="py-20 relative overflow-hidden" style="background: linear-gradient(to bottom right, #008190, #f38e3e);">
        <!-- Decorative elements -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-white opacity-10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full translate-x-1/2 translate-y-1/2"></div>
        
        <div class="max-w-7xl mx-auto px-4 md:px-6 relative z-10">
            
            <!-- Section Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-6 shadow-2xl">
                    <i class="fas fa-clipboard-check text-4xl" style="color: #008190;"></i>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4 drop-shadow-lg">
                    How to Claim Your Prize
                </h2>
                <p class="text-xl text-white/90 max-w-3xl mx-auto">
                    Follow these simple steps to spin, win, and claim your exclusive discount on college application forms
                </p>
            </div>

            <!-- Process Steps -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                
                <!-- Step 1 -->
                <div class="relative bg-white rounded-3xl shadow-2xl p-8 border-t-4 hover:shadow-3xl transition duration-300 transform hover:-translate-y-3" style="border-top-color: #008190;">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg" style="background: linear-gradient(135deg, #008190, #005f6b);">
                        1
                    </div>
                    <div class="mt-6 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, rgba(0, 129, 144, 0.1), rgba(0, 129, 144, 0.2));">
                            <i class="fas fa-user-plus text-5xl" style="color: #008190;"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Login or Register</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Create your free account or login with your mobile number. Quick OTP verification ensures secure access.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative bg-white rounded-3xl shadow-2xl p-8 border-t-4 hover:shadow-3xl transition duration-300 transform hover:-translate-y-3" style="border-top-color: #f38e3e;">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg" style="background: linear-gradient(135deg, #f38e3e, #ff9955);">
                        2
                    </div>
                    <div class="mt-6 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, rgba(243, 142, 62, 0.1), rgba(243, 142, 62, 0.2));">
                            <i class="fas fa-dharmachakra text-5xl" style="color: #f38e3e;"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Spin the Wheel</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Choose your desired college or exam and spin. Every spin guarantees a discount from 10% to 80% off!
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative bg-white rounded-3xl shadow-2xl p-8 border-t-4 hover:shadow-3xl transition duration-300 transform hover:-translate-y-3" style="border-top-color: #008190;">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg" style="background: linear-gradient(135deg, #008190, #005f6b);">
                        3
                    </div>
                    <div class="mt-6 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, rgba(0, 129, 144, 0.1), rgba(0, 129, 144, 0.2));">
                            <i class="fas fa-trophy text-5xl" style="color: #008190;"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Win Your Discount</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Celebrate your win! Get your unique coupon code instantly displayed on screen.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative bg-white rounded-3xl shadow-2xl p-8 border-t-4 hover:shadow-3xl transition duration-300 transform hover:-translate-y-3" style="border-top-color: #f38e3e;">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg" style="background: linear-gradient(135deg, #f38e3e, #ff9955);">
                        4
                    </div>
                    <div class="mt-6 text-center">
                        <div class="w-24 h-24 mx-auto mb-6 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, rgba(243, 142, 62, 0.1), rgba(243, 142, 62, 0.2));">
                            <i class="fas fa-gift text-5xl" style="color: #f38e3e;"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Claim & Apply</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Fill the quick enquiry form and our team will contact you immediately to help.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detailed Flow Diagram -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 border-2 border-white/20">
                <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                    <i class="fas fa-route mr-3" style="color: #f38e3e;"></i>
                    Your Complete Journey
                </h3>
                
                <div class="space-y-6">
                    <!-- Login Step -->
                    <div class="flex items-start space-x-4 p-6 rounded-2xl border-l-4 shadow-md" style="background: linear-gradient(to right, rgba(0, 129, 144, 0.05), rgba(0, 129, 144, 0.1)); border-left-color: #008190;">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #008190, #005f6b);">
                                <i class="fas fa-sign-in-alt text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-3">Student Login / Registration</h4>
                            <ul class="text-gray-700 space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Click "Participate" button on any college or exam card</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Enter your 10-digit mobile number</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Receive 6-digit OTP on your mobile</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>New users: Complete quick registration with name, mobile & email</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Spin Step -->
                    <div class="flex items-start space-x-4 p-6 rounded-2xl border-l-4 shadow-md" style="background: linear-gradient(to right, rgba(243, 142, 62, 0.05), rgba(243, 142, 62, 0.1)); border-left-color: #f38e3e;">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #f38e3e, #ff9955);">
                                <i class="fas fa-sync-alt text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-3">Spin for Your Discount</h4>
                            <ul class="text-gray-700 space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #f38e3e;"></i>
                                    <span>Access the interactive spin wheel (college or exam specific)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #f38e3e;"></i>
                                    <span>Click the "SPIN" button in the center</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #f38e3e;"></i>
                                    <span>Watch as the wheel spins and lands on your discount (10% to 80%)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #f38e3e;"></i>
                                    <span>100% guaranteed prize on every spin!</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Prize Step -->
                    <div class="flex items-start space-x-4 p-6 rounded-2xl border-l-4 shadow-md" style="background: linear-gradient(to right, rgba(0, 129, 144, 0.05), rgba(0, 129, 144, 0.1)); border-left-color: #008190;">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #008190, #005f6b);">
                                <i class="fas fa-award text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-3">Receive Your Prize Code</h4>
                            <ul class="text-gray-700 space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>View your winning discount percentage instantly</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Get unique coupon code displayed on screen</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Copy the code or take a screenshot for reference</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Code is automatically saved to your account</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Claim Step -->
                    <div class="flex items-start space-x-4 p-6 rounded-2xl border-l-4 shadow-md" style="background: linear-gradient(to right, rgba(243, 142, 62, 0.05), rgba(243, 142, 62, 0.1)); border-left-color: #f38e3e;">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #f38e3e, #ff9955);">
                                <i class="fas fa-clipboard-check text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-3">Claim Your Prize</h4>
                            <ul class="text-gray-700 space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #f38e3e;"></i>
                                    <span>Click "Claim Your Prize" button</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #f38e3e;"></i>
                                    <span>Fill quick enquiry form (name, phone, email already pre-filled)</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #f38e3e;"></i>
                                    <span>Submit the form to finalize your claim</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #f38e3e;"></i>
                                    <span>Receive confirmation via SMS and email with full details</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Verification Step -->
                    <div class="flex items-start space-x-4 p-6 rounded-2xl border-l-4 shadow-md" style="background: linear-gradient(to right, rgba(0, 129, 144, 0.05), rgba(0, 129, 144, 0.1)); border-left-color: #008190;">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #008190, #005f6b);">
                                <i class="fas fa-phone-volume text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-3">Get Expert Assistance</h4>
                            <ul class="text-gray-700 space-y-2 text-sm">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Our team contacts you within 2 hours</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Verify your discount and application details</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Receive guidance on application process</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle mt-1 mr-2 text-lg" style="color: #008190;"></i>
                                    <span>Complete your application with applied discount!</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white text-gray-900 p-8 rounded-3xl shadow-2xl border-2 transition" style="border-color: #008190;">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-shield-alt text-4xl mr-4" style="color: #008190;"></i>
                        <h4 class="text-xl font-bold">100% Secure</h4>
                    </div>
                    <p class="text-sm text-gray-600">
                        Your data is encrypted and protected. We never share your information with third parties.
                    </p>
                </div>
                
                <div class="bg-white text-gray-900 p-8 rounded-3xl shadow-2xl border-2 transition" style="border-color: #f38e3e;">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-clock text-4xl mr-4" style="color: #f38e3e;"></i>
                        <h4 class="text-xl font-bold">Instant Prizes</h4>
                    </div>
                    <p class="text-sm text-gray-600">
                        No waiting period! Get your discount code immediately after spinning the wheel.
                    </p>
                </div>
                
                <div class="bg-white text-gray-900 p-8 rounded-3xl shadow-2xl border-2 transition" style="border-color: #008190;">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-headset text-4xl mr-4" style="color: #008190;"></i>
                        <h4 class="text-xl font-bold">24/7 Support</h4>
                    </div>
                    <p class="text-sm text-gray-600">
                        Our support team is always available to help you claim and apply your discount.
                    </p>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="text-center mt-12">
                <button onclick="openModal()" class="inline-flex items-center text-white font-bold py-5 px-12 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-110 transition duration-300" style="background: linear-gradient(135deg, #008190, #f38e3e);">
                    <i class="fas fa-rocket mr-3 text-2xl"></i>
                    <span class="text-xl">Start Your Journey Now!</span>
                    <i class="fas fa-arrow-right ml-3 text-xl"></i>
                </button>
                <p class="text-white/90 mt-4 text-sm">No credit card required • Free to participate • Instant results</p>
            </div>
        </div>
    </section>


 <!-- TESTIMONIALS - Warm Gradient -->
    <section class="py-20 bg-gradient-to-br from-white-100 via-amber-50 to-yellow-100">
        <div class="max-w-7xl mx-auto px-4 md:px-6 text-center">
            <div class="mb-4">
                <span class="inline-block bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-2 rounded-full font-semibold text-sm">
                    <i class="fas fa-star mr-2"></i>5-Star Reviews
                </span>
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">What Our Students Say</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto mb-16">
                Thousands of students have saved money and achieved their dreams with FormsADDA
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-600 flex flex-col items-start text-left hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="flex items-center mb-4">
                        <img src="https://placehold.co/48x48/60a5fa/ffffff?text=P" alt="Priya Sharma" class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-blue-200">
                        <div>
                            <h4 class="font-bold text-lg text-gray-900">Priya Sharma</h4>
                            <p class="text-sm text-blue-600">IIT Delhi Student</p>
                        </div>
                    </div>
                    <div class="text-yellow-500 mb-3 text-xl">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 italic">
                        "FormsADDA helped me save 60% on my JEE application! The process was so simple and the discount was genuine. Highly recommended!"
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-red-600 flex flex-col items-start text-left hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="flex items-center mb-4">
                        <img src="https://placehold.co/48x48/f87171/ffffff?text=R" alt="Rahul Patel" class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-red-200">
                        <div>
                            <h4 class="font-bold text-lg text-gray-900">Rahul Patel</h4>
                            <p class="text-sm text-red-600">IIM Ahmedabad Graduate</p>
                        </div>
                    </div>
                    <div class="text-yellow-500 mb-3 text-xl">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 italic">
                        "Got 70% off on my CAT application form through their spin wheel. The team was very helpful and the entire process was transparent."
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-yellow-500 flex flex-col items-start text-left hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="flex items-center mb-4">
                        <img src="https://placehold.co/48x48/fbbf24/ffffff?text=S" alt="Sneha Reddy" class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-yellow-200">
                        <div>
                            <h4 class="font-bold text-lg text-gray-900">Sneha Reddy</h4>
                            <p class="text-sm text-yellow-700">BITS Pilani Student</p>
                        </div>
                    </div>
                    <div class="text-yellow-500 mb-3 text-xl">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 italic">
                        "Amazing platform! I won 80% discount on my BITSAT application. The customer support team guided me through every step."
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-green-600 flex flex-col items-start text-left hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="flex items-center mb-4">
                        <img src="https://placehold.co/48x48/34d399/ffffff?text=A" alt="Arjun Kumar" class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-green-200">
                        <div>
                            <h4 class="font-bold text-lg text-gray-900">Arjun Kumar</h4>
                            <p class="text-sm text-green-600">Amity University Student</p>
                        </div>
                    </div>
                    <div class="text-yellow-500 mb-3 text-xl">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 italic">
                        "FormsADDA is a game-changer! Saved money on multiple exam applications and got excellent guidance for college admissions."
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-purple-600 flex flex-col items-start text-left hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="flex items-center mb-4">
                        <img src="https://placehold.co/48x48/a78bfa/ffffff?text=K" alt="Kavya Nair" class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-purple-200">
                        <div>
                            <h4 class="font-bold text-lg text-gray-900">Kavya Nair</h4>
                            <p class="text-sm text-purple-600">VIT University Student</p>
                        </div>
                    </div>
                    <div class="text-yellow-500 mb-3 text-xl">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 italic">
                        "The spin and win feature is fantastic! Won 50% discount on VITEEE application and the process was completely hassle-free."
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-teal-600 flex flex-col items-start text-left hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="flex items-center mb-4">
                        <img src="https://placehold.co/48x48/2dd4bf/ffffff?text=V" alt="Vikram Singh" class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-teal-200">
                        <div>
                            <h4 class="font-bold text-lg text-gray-900">Vikram Singh</h4>
                            <p class="text-sm text-teal-600">Manipal University Student</p>
                        </div>
                    </div>
                    <div class="text-yellow-500 mb-3 text-xl">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 italic">
                        "Excellent service! Got discount on NEET application and received proper guidance for medical college admissions. Truly helpful!"
                    </p>
                </div>
                
            </div>
        </div>
    </section>

    <!-- ABOUT FORMSADDA - Teal to Orange Gradient -->
    <section class="py-20 relative overflow-hidden" style="background: linear-gradient(to bottom right, #008190, #f38e3e);">
        <!-- Decorative circles -->
        <div class="absolute top-10 right-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
        
        <div class="max-w-6xl mx-auto px-4 md:px-6 relative z-10">
            <div class="text-center mb-12">
                <div class="inline-block bg-white text-gray-900 px-6 py-2 rounded-full font-bold text-sm mb-4">
                    <i class="fas fa-info-circle mr-2"></i>About Us
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4">About FormsADDA</h2>
                <p class="text-xl text-white/90 max-w-3xl mx-auto">
                    Your trusted partner in college admissions. We help students save money and achieve their educational dreams.
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <div class="flex flex-col space-y-6">
                    
                    <div class="bg-white/10 backdrop-blur-sm p-6 rounded-2xl text-white space-y-4 text-left border border-white/20">
                        <p class="text-lg leading-relaxed">
                            <span class="font-bold text-yellow-300">FormsADDA</span> is India's leading platform for college admissions and entrance exam applications. We have helped over 50,000 students save money and secure admissions in top colleges across the country.
                        </p>
                        <p class="text-lg leading-relaxed">
                            Our mission is to make quality education accessible and affordable for every student. Through our innovative spin-and-win platform, students can get genuine discounts on application forms while receiving expert guidance throughout their admission journey.
                        </p>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-3xl font-bold text-white mb-6 text-left">
                            <i class="fas fa-chart-line mr-3"></i>Our Impact
                        </h3>
                        <div class="grid grid-cols-2 gap-6 p-8 bg-white rounded-2xl shadow-2xl">
                            <div class="text-center p-4 border-r-2 border-gray-200">
                                <h4 class="text-5xl font-bold bg-gradient-to-r text-transparent bg-clip-text" style="background-image: linear-gradient(to right, #008190, #f38e3e);">50,000+</h4>
                                <p class="text-gray-700 font-semibold mt-2">Students Helped</p>
                            </div>
                            <div class="text-center p-4">
                                <h4 class="text-5xl font-bold bg-gradient-to-r text-transparent bg-clip-text" style="background-image: linear-gradient(to right, #008190, #f38e3e);">₹2 Cr+</h4>
                                <p class="text-gray-700 font-semibold mt-2">Money Saved</p>
                            </div>
                            <div class="text-center p-4 border-r-2 border-t-2 border-gray-200">
                                <h4 class="text-5xl font-bold bg-gradient-to-r text-transparent bg-clip-text" style="background-image: linear-gradient(to right, #008190, #f38e3e);">500+</h4>
                                <p class="text-gray-700 font-semibold mt-2">Partner Colleges</p>
                            </div>
                            <div class="text-center p-4 border-t-2 border-gray-200">
                                <h4 class="text-5xl font-bold bg-gradient-to-r text-transparent bg-clip-text" style="background-image: linear-gradient(to right, #008190, #f38e3e);">95%</h4>
                                <p class="text-gray-700 font-semibold mt-2">Success Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <img 
                        src="uploads/f4488027f3cc254449a71f24db335c95.jpg" 
                        alt="FormsADDA team" 
                        class="rounded-3xl shadow-2xl w-full object-cover border-8 border-white/20"
                    >
                </div>
            </div>
        </div>
    </section>

    <!-- WHY CHOOSE - Light background with brand colors -->
    <section class="py-20" style="background: linear-gradient(to bottom, #f8fafb, #f0f4f8);">
        <div class="max-w-7xl mx-auto px-4 md:px-6 text-center">
            <div class="mb-4">
                <span class="inline-block text-white px-6 py-2 rounded-full font-semibold" style="background: linear-gradient(135deg, #008190, #f38e3e);">
                    <i class="fas fa-thumbs-up mr-2"></i>Why Choose Us
                </span>
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Why Choose FormsADDA?</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto mb-16">
                We are dedicated to simplifying the college application process, ensuring you save time, money, and stress.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="bg-white p-8 rounded-2xl shadow-xl border-2 hover:shadow-2xl transition duration-300 transform hover:-translate-y-2" style="border-color: #008190;">
                    <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full shadow-lg" style="background: linear-gradient(135deg, rgba(0, 129, 144, 0.1), rgba(0, 129, 144, 0.2));">
                        <i class="fas fa-check-circle text-4xl" style="color: #008190;"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Verified Discounts</h3>
                    <p class="text-gray-600">
                        All our discounts are genuine and verified with our partner institutions, giving you complete peace of mind.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-xl border-2 hover:shadow-2xl transition duration-300 transform hover:-translate-y-2" style="border-color: #f38e3e;">
                    <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full shadow-lg" style="background: linear-gradient(135deg, rgba(243, 142, 62, 0.1), rgba(243, 142, 62, 0.2));">
                        <i class="fas fa-trophy text-4xl" style="color: #f38e3e;"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Top Colleges</h3>
                    <p class="text-gray-600">
                        Access premium colleges and universities across India, all in one simplified platform.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-xl border-2 hover:shadow-2xl transition duration-300 transform hover:-translate-y-2" style="border-color: #008190;">
                    <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full shadow-lg" style="background: linear-gradient(135deg, rgba(0, 129, 144, 0.1), rgba(0, 129, 144, 0.2));">
                        <i class="fas fa-sack-dollar text-4xl" style="color: #008190;"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Save Money</h3>
                    <p class="text-gray-600">
                        Save up to 80% on entrance exam application forms through our unique Spin & Win feature.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-xl border-2 hover:shadow-2xl transition duration-300 transform hover:-translate-y-2" style="border-color: #f38e3e;">
                    <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full shadow-lg" style="background: linear-gradient(135deg, rgba(243, 142, 62, 0.1), rgba(243, 142, 62, 0.2));">
                        <i class="fas fa-headset text-4xl" style="color: #f38e3e;"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">24/7 Support</h3>
                    <p class="text-gray-600">
                        Get round-the-clock customer support for all your queries regarding applications and discounts.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-xl border-2 hover:shadow-2xl transition duration-300 transform hover:-translate-y-2" style="border-color: #008190;">
                    <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full shadow-lg" style="background: linear-gradient(135deg, rgba(0, 129, 144, 0.1), rgba(0, 129, 144, 0.2));">
                        <i class="fas fa-clipboard-list text-4xl" style="color: #008190;"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Easy Process</h3>
                    <p class="text-gray-600">
                        Our simple and hassle-free application process means less paperwork and more focus on your studies.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-xl border-2 hover:shadow-2xl transition duration-300 transform hover:-translate-y-2" style="border-color: #f38e3e;">
                    <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center rounded-full shadow-lg" style="background: linear-gradient(135deg, rgba(243, 142, 62, 0.1), rgba(243, 142, 62, 0.2));">
                        <i class="fas fa-user-graduate text-4xl" style="color: #f38e3e;"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Expert Guidance</h3>
                    <p class="text-gray-600">
                        Receive expert advice on college admissions, career counseling, and securing your dream seat.
                    </p>
                </div>
                
            </div>
        </div>
    </section>

    <!-- CTA BANNER - Brand color gradient -->
    <section class="py-16 md:py-20" style="background: linear-gradient(to right, #008190, #f38e3e);">
        <div class="max-w-6xl mx-auto px-4 md:px-6">
            <div class="p-10 md:p-16 rounded-3xl text-white text-center shadow-2xl bg-gradient-to-br from-black/20 to-black/10 backdrop-blur-sm border border-white/20">
                
                <h2 class="text-3xl md:text-5xl font-extrabold mb-6 flex items-center justify-center drop-shadow-lg">
                    <i class="fas fa-rocket text-yellow-300 mr-4 text-4xl"></i>
                    Ready to Start Your Journey?
                </h2>
                
                <p class="text-lg md:text-2xl font-medium mb-10">
                    Join thousands of successful students who have achieved their dreams with FormsADDA
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-6">
                    
                    <a href="tel:+917631900600" class="bg-white text-gray-900 font-bold py-4 px-10 rounded-full shadow-2xl hover:bg-gray-100 hover:shadow-3xl transition duration-300 transform hover:scale-110 flex items-center justify-center">
                      <i class="fas fa-phone-alt mr-3 text-xl"></i>
                      <span class="text-lg">Contact Us</span>
                    </a>
                    
                    <button class="bg-white text-gray-900 font-bold py-4 px-10 rounded-full shadow-2xl hover:bg-gray-100 hover:shadow-3xl transition duration-300 transform hover:scale-110 flex items-center justify-center" onclick="openModal()">
                        <i class="fas fa-magic mr-3 text-xl"></i>
                        <span class="text-lg">Start Spinning</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER - Dark with brand color accents -->
    <footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white pt-16 pb-8 relative overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 50px 50px;"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12 relative z-10">
            <div>
                <img src="http://formsadda.com/wp-content/uploads/2024/02/cropped-cropped-cropped-formsadda-1-1.webp" alt="FormsADDA Logo" class="mb-4 h-12">
                <p class="text-gray-400 text-sm mb-6 leading-relaxed">Your trusted partner for college admissions and entrance exam applications. Save money, get guidance, achieve dreams.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 hover:opacity-80 rounded-full flex items-center justify-center transition" style="background-color: #008190;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 hover:opacity-80 rounded-full flex items-center justify-center transition" style="background-color: #f38e3e;"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="w-10 h-10 hover:opacity-80 rounded-full flex items-center justify-center transition" style="background-color: #008190;"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div>
                <h4 class="text-xl font-bold text-white mb-6 border-b-2 pb-2 inline-block" style="border-bottom-color: #008190;">Quick Links</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-gray-400 hover:transition duration-200 flex items-center" style="color: #f38e3e; --hover-color: #f38e3e;"><i class="fas fa-chevron-right mr-2 text-xs"></i>Home</a></li>
                    <li><a href="#" class="text-gray-400 transition duration-200 flex items-center hover:text-current" style="--hover-color: #f38e3e;"><i class="fas fa-chevron-right mr-2 text-xs"></i>Colleges</a></li>
                    <li><a href="#" class="text-gray-400 transition duration-200 flex items-center hover:text-current" style="--hover-color: #f38e3e;"><i class="fas fa-chevron-right mr-2 text-xs"></i>Entrance Exams</a></li>
                    <li><a href="#" class="text-gray-400 transition duration-200 flex items-center hover:text-current" style="--hover-color: #f38e3e;"><i class="fas fa-chevron-right mr-2 text-xs"></i>About Us</a></li>
                    <li><a href="#" class="text-gray-400 transition duration-200 flex items-center hover:text-current" style="--hover-color: #f38e3e;"><i class="fas fa-chevron-right mr-2 text-xs"></i>Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xl font-bold text-white mb-6 border-b-2 pb-2 inline-block" style="border-bottom-color: #f38e3e;">Our Services</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="text-gray-400 transition duration-200 flex items-center hover:text-current" style="--hover-color: #008190;"><i class="fas fa-chevron-right mr-2 text-xs"></i>Application Forms</a></li>
                    <li><a href="#" class="text-gray-400 transition duration-200 flex items-center hover:text-current" style="--hover-color: #008190;"><i class="fas fa-chevron-right mr-2 text-xs"></i>College Guidance</a></li>
                    <li><a href="#" class="text-gray-400 transition duration-200 flex items-center hover:text-current" style="--hover-color: #008190;"><i class="fas fa-chevron-right mr-2 text-xs"></i>Exam Preparation</a></li>
                    <li><a href="#" class="text-gray-400 transition duration-200 flex items-center hover:text-current" style="--hover-color: #008190;"><i class="fas fa-chevron-right mr-2 text-xs"></i>Scholarship Info</a></li>
                    <li><a href="#" class="text-gray-400 transition duration-200 flex items-center hover:text-current" style="--hover-color: #008190;"><i class="fas fa-chevron-right mr-2 text-xs"></i>Career Counseling</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xl font-bold text-white mb-6 border-b-2 pb-2 inline-block" style="border-bottom-color: #f38e3e;">Contact Info</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start"><i class="fas fa-phone-alt mr-3 mt-1" style="color: #f38e3e;"></i><span class="text-gray-400">+917631900600</span></li>
                    <li class="flex items-start"><i class="fas fa-envelope mr-3 mt-1" style="color: #008190;"></i><span class="text-gray-400">info@formsadda.com</span></li>
                    <li class="flex items-start"><i class="fas fa-map-marker-alt mr-3 mt-1" style="color: #f38e3e;"></i><span class="text-gray-400">5th Floor, FormsADDA, Samudra Complex, Circular Road, Ranchi, Jharkhand, 834001</span></li>
                    <li class="flex items-start"><i class="fab fa-whatsapp mr-3 mt-1 text-lg" style="color: #008190;"></i><span class="text-gray-400">WhatsApp Support</span></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 border-t border-gray-700 pt-8 text-center relative z-10">
            <p class="text-sm text-gray-500">&copy; 2024 FormsADDA. All rights reserved. | <a href="#" class="transition" style="color: #f38e3e;">Privacy Policy</a> | <a href="#" class="transition" style="color: #008190;">Terms of Service</a></p>
        </div>
    </footer>
    
    <!-- SPIN MODAL -->
    
    <!-- SPIN MODAL -->
    <div id="collegeSpinModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
            <div class="">
                
                <div id="modalSpinView">
                    <!--<h3 class="text-2xl font-bold text-gray-800 mb-4" id="modalTitle">Spin for Discount!</h3>-->
                    <!--<p class="text-gray-600 mb-6" id="modalSubtitle">Get up to 80% off on application forms</p>-->
                    
                    <!-- Header -->
                    <div class="modal-header">
                        <button onclick="closeSpinModal()" class="close-btn">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="modal-header-content">
                            <h2 class="modal-title">Spin & Win</h2>
                            <p class="modal-subtitle">Get exclusive discounts on your application</p>
                            <div class="badge-group">
                                <span class="badge">
                                    <i class="fas fa-bolt"></i> Guaranteed Prize
                                </span>
                                <span class="badge">
                                    <i class="fas fa-check-circle"></i> Up to 80% OFF
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-center space-y-6 py-2">
                        <div class="relative w-64 h-64 border-4 border-green-500 rounded-full flex items-center justify-center overflow-hidden">
                            <div id="modalSpinWheel" class="absolute inset-0 rounded-full css-spin-wheel spin-wheel-base">
                                <div class="segment-text-wrapper" style="--angle: 22.5;"><div class="segment-text-label">80%</div></div>
                                <div class="segment-text-wrapper" style="--angle: 67.5;"><div class="segment-text-label">70%</div></div>
                                <div class="segment-text-wrapper" style="--angle: 112.5;"><div class="segment-text-label">60%</div></div>
                                <div class="segment-text-wrapper" style="--angle: 157.5;"><div class="segment-text-label">50%</div></div>
                                <div class="segment-text-wrapper" style="--angle: 202.5;"><div class="segment-text-label">40%</div></div>
                                <div class="segment-text-wrapper" style="--angle: 247.5;"><div class="segment-text-label">30%</div></div>
                                <div class="segment-text-wrapper" style="--angle: 292.5;"><div class="segment-text-label">20%</div></div>
                                <div class="segment-text-wrapper" style="--angle: 337.5;"><div class="segment-text-label">10%</div></div>
                            </div>
                            <div class="spin-wheel-pointer absolute left-1/2 transform -translate-x-1/2 z-50"></div>
                            
                            <button id="modalSpinButton" class="absolute z-30 w-24 h-24 bg-red-600 hover:bg-red-700 text-white font-extrabold rounded-full shadow-xl text-lg transition duration-300">
                                SPIN
                            </button>
                        </div>
                        
                        <p class="text-xl font-bold text-green-700 h-6" id="modalSpinResult">Spin to win!</p>
                    </div>
                    <!-- Info Text -->
                    <p class="text-lg font-bold text-slate-600 text-center">
                        Click the button above to spin the wheel and win your discount!
                    </p>

                    <!-- Feature Cards -->
                    <div class="grid grid-cols-3 gap-4 w-full p-3">
                        <div class="info-card">
                            <div class="text-3xl mb-2">⚡</div>
                            <div class="text-xs font-bold text-slate-700">Instant</div>
                        </div>
                        <div class="info-card">
                            <div class="text-3xl mb-2">🔒</div>
                            <div class="text-xs font-bold text-slate-700">Secure</div>
                        </div>
                        <div class="info-card">
                            <div class="text-3xl mb-2">🎯</div>
                            <div class="text-xs font-bold text-slate-700">Verified</div>
                        </div>
                    </div>
                </div>

                <div id="modalPrizeView" class="hidden text-center mt-4 p-3">
                    <i class="fas fa-trophy text-yellow-500 text-5xl mb-3"></i>
                    <h4 class="text-2xl font-bold text-gray-800 mb-2">Congratulations!</h4>
                    <p class="text-xl font-extrabold text-blue-600 mb-4">
                        You've won: <span id="prizeDiscountDisplay"></span> off <span id="prizeCollegeDisplay"></span> Application
                    </p>

                    <div class="bg-gray-100 p-4 rounded-lg shadow-inner mb-6">
                        <p class="text-sm font-semibold text-gray-700 mb-2"><i class="fas fa-ticket-alt mr-1"></i> Your Coupon Code:</p>
                        <div class="flex items-center justify-center space-x-2">
                            <span id="couponCodeDisplay" class="font-mono text-lg p-2 border-dashed border-2 border-blue-400 rounded bg-white select-all"></span>
                            <button class="text-blue-600 hover:text-blue-800" onclick="navigator.clipboard.writeText(document.getElementById('couponCodeDisplay').textContent)"><i class="fas fa-copy"></i></button>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-100 p-4 rounded-lg shadow-md mb-6 text-left">
                        <p class="font-bold text-gray-800 mb-2"><i class="fas fa-info-circle mr-1"></i> How to Claim:</p>
                        <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                            <li>Click "Claim Your Prize" below.</li>
                            <li>Fill out the quick enquiry form.</li>
                            <li>We'll contact you immediately with details.</li>
                        </ol>
                    </div>

                    <div class="flex justify-center space-x-3 mb-6">
                        <button id="claimPrizeButton" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-300">
                            <i class="fas fa-gift mr-2"></i> Claim Your Prize
                        </button>
                    </div>
                    
                    <button id="prizeViewCloseButton" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg transition duration-300">
                        Close
                    </button>
                </div>

                <div id="modalClaimFormView" class="hidden text-left mt-4 p-3">
                    <h4 class="text-xl font-bold text-blue-600 mb-4"><i class="fas fa-file-alt mr-2"></i> Final Step: Secure Your Discount!</h4>
                    <p class="text-gray-700 mb-4">Fill out the form to instantly secure your <span id="formDiscountDisplay" class="text-red-500 font-extrabold"></span> for <span id="formCollegeDisplay" class="font-extrabold"></span>.</p>

                    <form id="enquiryForm" action="#" method="POST">
                        <input type="hidden" id="formPrizeWon" name="prize_won" value="">

                        <div class="mb-3">
                            <label for="claim_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" id="claim_name" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-3">
                            <label for="claim_phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="tel" id="claim_phone" name="phone" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="mb-6">
                            <label for="claim_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" id="claim_email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300">
                            Submit & Finalize Claim
                        </button>
                    </form>
                </div>

                <div id="modalFinalView" class="hidden text-center mt-8">
                    <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                    <h4 class="text-2xl font-bold text-gray-800 mb-2">Claim Completed!</h4>
                    <p class="text-xl text-gray-700 font-semibold mb-6">
                        Check your phone and email! We've sent the details and your coupon code.
                    </p>
                    <button id="finalViewCloseButton" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg transition duration-300">
                        Close & View Colleges
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    
  <!-- Login Registration Modal Overlay -->
    <div id="modalOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-2 sm:p-4 z-50" onclick="closeModal()">
        <!-- Modal -->
        <div class="w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-4xl" onclick="event.stopPropagation()">
            
            <!-- Flip Container -->
            <div id="flipContainer" class="flip-container">
                <div class="flip-card">
                    
                    <!-- LOGIN CARD (Front) -->
                    <div class="card-face card-front">
                        
                        <!-- Left Side - Info -->
                        <div class="side-info">
                            <div class="info-icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <div class="info-title">Welcome Back!</div>
                            <div class="info-text">
                                Log in to access your account and participate in contests
                            </div>
                            <button type="button" 
                                    class="px-6 py-2 bg-white text-purple-600 font-semibold rounded-lg hover:bg-gray-100 transition btn-flip" 
                                    onclick="flipCard()">
                                Create Account
                            </button>
                        </div>

                        <!-- Right Side - Login Form -->
                        <div class="side-form">
                            <h3 class="text-2xl font-bold text-gray-800 mb-6">Log In</h3>
                            
                            <form id="loginForm" class="space-y-4">
                                <div>
                                    <input type="text" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-600 focus:border-transparent" 
                                           id="loginMobile" 
                                           name="mobile" 
                                           placeholder="Enter your mobile number" 
                                           maxlength="10" 
                                           required>
                                </div>
                                <button type="button" 
                                        class="w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 transition" 
                                        onclick="sendOTP()">
                                    Login
                                </button>
                                <div id="loginMessage" class="mt-3 text-center text-sm"></div>
                            </form>

                            <!-- OTP Form -->
                            <form id="otpForm" style="display:none;" class="space-y-4">
                                <label class="block text-sm font-semibold text-gray-700 text-center">Verify OTP</label>
                                <div id="otpContainer" class="flex justify-center gap-2 mb-4">
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                </div>
                                <input type="hidden" name="otp_entered" id="otp_entered">
                                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                                    Verify OTP
                                </button>
                                <div id="otpMessage" class="mt-3 text-center text-red-600 text-sm"></div>
                                <div class="mt-4 space-y-2 text-center text-sm">
                                    <div>Didn't receive OTP? <a role="button" class="text-teal-600 hover:underline cursor-pointer font-semibold" onclick="sendOTP()">Resend OTP</a></div>
                                    <div><a role="button" class="text-teal-600 hover:underline cursor-pointer font-semibold" onclick="showLoginForm()">Change Number</a></div>
                                </div>
                            </form>
                            
                            <!-- Add this inside .side-form of LOGIN CARD, just before the divider -->
                            <div class="text-center mt-4 block md:hidden">
                              <p class="text-sm text-gray-600">Don’t have an account?</p>
                              <button type="button"
                                      class="mt-2 px-4 py-2 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition"
                                      onclick="flipCard()">
                                Create Account
                              </button>
                            </div>


                            <div class="relative my-6">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-600">Or Connect with</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-center gap-6 mb-6">
                                <a href="https://www.facebook.com/formsadda" class="text-blue-600 text-3xl hover:text-blue-800 transition" aria-label="Facebook">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                <a href="https://x.com/formsadda" class="text-gray-800 text-3xl hover:text-gray-600 transition" aria-label="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://wa.me/917491871366?text=Hello%20there,%20I%20would%20like%20to%20get%20in%20touch!" target="_blank" class="text-green-600 text-3xl hover:text-green-800 transition" aria-label="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="https://www.linkedin.com/company/formsadda-com" class="text-blue-700 text-3xl hover:text-blue-900 transition" aria-label="LinkedIn">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- REGISTER CARD (Back) -->
                    <div class="card-face card-back">
                        
                        <!-- Left Side - Registration Form -->
                        <div class="side-form">
                            <h3 class="text-center text-2xl font-bold text-gray-800 mb-4">Sign Up</h3>
                            
                            <div class="flex justify-center gap-6 mb-6">
                                <a href="https://www.facebook.com/formsadda" class="text-blue-600 text-3xl hover:text-blue-800 transition" aria-label="Facebook">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                <a href="https://x.com/formsadda" class="text-gray-800 text-3xl hover:text-gray-600 transition" aria-label="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://wa.me/917491871366?text=Hello%20there,%20I%20would%20like%20to%20get%20in%20touch!" target="_blank" class="text-green-600 text-3xl hover:text-green-800 transition" aria-label="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="https://www.linkedin.com/company/formsadda-com" class="text-blue-700 text-3xl hover:text-blue-900 transition" aria-label="LinkedIn">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                            </div>

                            <div class="relative my-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-600">Or</span>
                                </div>
                            </div>

                            <!-- Registration Form -->
                            <form id="registerForm" class="space-y-3">
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-teal-600">
                                    <span class="bg-white px-3 py-3 text-gray-600"><i class="fas fa-user"></i></span>
                                    <input type="text" 
                                           class="flex-1 px-3 py-3 border-none focus:outline-none" 
                                           id="registerName" 
                                           name="name" 
                                           placeholder="Your full name" 
                                           required>
                                </div>
                            
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-teal-600">
                                    <span class="bg-white px-3 py-3 text-gray-600"><i class="fas fa-phone-alt"></i></span>
                                    <input type="text" 
                                           class="flex-1 px-3 py-3 border-none focus:outline-none" 
                                           id="registerMobile" 
                                           name="mobile" 
                                           placeholder="Your mobile number" 
                                           maxlength="10" 
                                           required>
                                </div>
                            
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-teal-600">
                                    <span class="bg-white px-3 py-3 text-gray-600"><i class="fas fa-envelope"></i></span>
                                    <input type="email" 
                                           class="flex-1 px-3 py-3 border-none focus:outline-none" 
                                           id="registerEmail" 
                                           name="email" 
                                           placeholder="Your email address" 
                                           required>
                                </div>
                            
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-teal-600">
                                    <span class="bg-white px-3 py-3 text-gray-600"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" 
                                           class="flex-1 px-3 py-3 border-none focus:outline-none" 
                                           id="registerAddress" 
                                           name="address" 
                                           placeholder="Your address" 
                                           required>
                                </div>
                            
                                <button type="button" 
                                        class="w-full bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 transition" 
                                        onclick="registerUser()">
                                    <i class="fas fa-user-plus mr-2"></i>REGISTER
                                </button>
                            
                                <div id="registerMessage" class="mt-3 text-center text-sm"></div>
                            </form>

                            <!-- Register OTP Form -->
                            <form id="registerOtpForm" style="display: none;" class="space-y-4">
                                <label class="block text-sm font-semibold text-gray-700 text-center">Verify OTP</label>
                                <div id="registerOtpContainer" class="flex justify-center gap-2 mb-4">
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                    <input type="tel" class="otp-box" maxlength="1" required>
                                </div>
                                <input type="hidden" name="otp_entered" id="register_otp_entered">
                                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                                    Verify OTP
                                </button>
                                <div id="registerOtpMessage" class="mt-3 text-center text-red-600 text-sm"></div>
                                <div class="mt-4 space-y-2 text-center text-sm">
                                    <div>Didn't receive OTP? <a role="button" class="text-teal-600 hover:underline cursor-pointer font-semibold" onclick="sendRegisterOTP()">Resend OTP</a></div>
                                    <div><a role="button" class="text-teal-600 hover:underline cursor-pointer font-semibold" onclick="flipCard()">Change Number</a></div>
                                </div>
                            </form>
                            
                            <!-- Add this inside .side-form of REGISTER CARD, after registerOtpForm -->
                            <div class="text-center mt-4 block md:hidden">
                              <p class="text-sm text-gray-600">Already have an account?</p>
                              <button type="button"
                                      class="mt-2 px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition"
                                      onclick="flipCard()">
                                Log In
                              </button>
                            </div>

                        </div>

                        <!-- Right Side - Info -->
                        <div class="side-back-info">
                            <div class="info-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="info-title">Hello, Friend!</div>
                            <div class="info-text">
                                Create an account to participate in exciting contests and challenges
                            </div>
                            <button type="button" 
                                    class="px-6 py-2 bg-white text-red-500 font-semibold rounded-lg hover:bg-gray-100 transition" 
                                    onclick="flipCard()">
                                Log In
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal sections remain the same -->
    <script src="spin-custom.js"></script>

</body>
</html>