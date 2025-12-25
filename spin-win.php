<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Spin & Win - College Planning</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link href="css/aos.css" rel="stylesheet">
<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/aos.js"></script>
<style>
/* ============================
   GLOBAL STYLES & FONTS
============================ */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Oswald:wght@500;700&display=swap');
body { font-family: 'Poppins', sans-serif; }
h1 { font-family: 'Oswald', sans-serif; }

/* Category Boxes with 3D Lift Effect */
.category-box {
  transition: all 0.3s ease-in-out;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.06);
}
.category-box:hover {
  box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2), 0 4px 6px -4px rgba(0,0,0,0.1);
  transform: translateY(-3px);
}

/* Spin Button Pulsing Glow Animation */
#spinBtn:not(:disabled) {
  animation: pulseGlow 1.5s infinite;
}
@keyframes pulseGlow {
  0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.7); }
  50% { box-shadow: 0 0 0 15px rgba(16,185,129,0); }
}

/* Modal Base Styling */
.modal { 
  display: none; 
  position: fixed; inset:0; 
  background: rgba(0,0,0,0.6); 
  justify-content: center; align-items: center;
  z-index: 50; backdrop-filter: blur(3px);
}
.modal.active { display: flex; }

/* ============================
   SPINNER WHEEL STYLES
============================ */
.spinner {
  width: 320px;
  height: 320px;
  border-radius: 50%;
  border: 20px solid #7c3aed;
  position: relative;
  margin: auto;
  overflow: hidden;
  box-shadow: 0 15px 30px rgba(0,0,0,0.4), inset 0 0 20px rgba(255,255,255,0.5);
  background: #f3f4f6;
}

/* Inner Hub Circle */
.spinner::after {
  content: '';
  position: absolute;
  width: 70px; height: 70px;
  background: white;
  border: 5px solid #a855f7;
  border-radius: 50%;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  z-index: 5;
  box-shadow: 0 0 10px rgba(0,0,0,0.5);
}

/* Prize Text Overlay in Spinner */
.spinner .prize-text-overlay {
  position: absolute;
  width: 50%; height: 50%;
  top: 0; left: 50%;
  transform-origin: 0% 100%;
  display: flex; align-items: center; justify-content: center;
  z-index: 10; pointer-events: none;
}
.spinner .prize-text-overlay p {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    text-shadow: 0 0 4px #ffffffaa;
    text-align: center;
    width: 80px;
    line-height: 1.2;
    white-space: normal;
    pointer-events: none;
}

/* Pointer Arrow at Top of Spinner */
.pointer {
    width: 0;
    height: 0;
    border-left: 20px solid transparent;
    border-right: 20px solid transparent;
    border-bottom: 36px solid #ef4444;
    filter: drop-shadow(0 6px 6px rgba(0, 0, 0, 0.5));
    position: relative;
    margin: auto;
    z-index: 20;
    animation: bounce-pointer 1.2s infinite;
}

@keyframes bounce-pointer {
    0%,100% { transform: rotate(180deg) translateY(0); }
    50% { transform: rotate(180deg) translateY(-6px); }
}


/* ============================
   ROLLER MACHINE STYLES
============================ */
.roller-container {
  display: grid;
  grid-template-columns: repeat(3,1fr);
  gap: 12px;
  width: 380px; 
  height: auto;
  border: 18px solid #475569;
  border-radius: 15px;
  background: #1f2937;
  padding: 10px;
  box-shadow: inset 0 0 30px rgba(0,0,0,0.8), 0 15px 30px rgba(0,0,0,0.5);
  position: relative;
  transform: perspective(1000px) rotateX(5deg);
  perspective: 1200px;
  transition: transform 0.6s ease;
}
.reel-strip {
  transition: transform 4s cubic-bezier(0.25,0.1,0.25,1);
}
/* Individual Reel Styling */
.reel {
  overflow: hidden;
  height: 160px;
  border-radius: 8px;
  background: #0f172a;
  border: 3px solid #fde047;
  box-shadow: inset 0 0 10px rgba(255,255,255,0.1), 0 5px 10px rgba(0,0,0,0.5);
}

/* Reel Prize Item */
.reel-prize {
  height: 160px;
  display: flex; align-items: center; justify-content: center;
  color: #fff;
  font-family: 'Oswald', sans-serif;
  font-size: 18px;
  font-weight: 700;
  padding: 0 5px; text-align: center; line-height: 1.2;
  text-shadow: 0 0 5px #fde047, 0 0 10px #fde047;
}

/* Highlight Bar for Winning Item */
.roller-indicator {
  position: absolute;
  top: 50%; left: 0; right: 0;
  height: 40px;
  transform: translateY(-50%);
  border-top: 6px solid #ef4444;
  border-bottom: 6px solid #ef4444;
  pointer-events: none;
  z-index: 10;
  /*box-shadow: 0 0 10px rgba(255,69,0,0.8);*/
  box-shadow: 0 0 15px rgba(16,185,129,0.7);
  border-color: #10b981;
}

/* Prize Modal Enhancement */
#prizeModal > div {
  border: 5px solid #fde047;
  box-shadow: 0 0 30px rgba(253,224,71,0.8);
}
</style>
</head>

<body class="bg-indigo-50 min-h-screen flex flex-col items-center p-5">

<!--<body class="bg-gray-50 text-gray-800 font-sans">-->
<!-- Page Header -->
<header class="text-center mb-12" data-aos="fade-down" data-aos-duration="1000">
  <h1 class="text-5xl font-extrabold text-indigo-800 mb-4 border-b-4 border-amber-400 inline-block pb-2 tracking-widest">
    <i class="fa-solid fa-graduation-cap text-amber-500 mr-2"></i>
    Plan Smart, Win Big – Spin Now!
  </h1>
  <p class="text-gray-600 mt-4 text-lg max-w-2xl mx-auto">
    Discover your educational path, spin the wheel, and unlock exciting rewards while planning your future.
  </p>
</header>

<!-- Category + Guide Section -->
<div id="categorySection" class="w-full max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-start px-4 md:px-0">
  
  <!-- LEFT: Category Selection -->
  <div data-aos="fade-right" data-aos-duration="1000">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6 flex items-center gap-2">
      <i class="fa-solid fa-bullseye text-indigo-600 text-3xl"></i>
      Choose Your Planning Focus
    </h2>
    <div id="categoryContainer" class="grid grid-cols-2 md:grid-cols-2 gap-6 mb-10">
      <!-- Dynamic categories loaded from database -->
    </div>
  </div>

    <!-- RIGHT: Step-by-Step Guide -->
    <div id="guideContainer" data-aos="fade-left" data-aos-duration="1000" class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
      <h3 class="text-2xl font-bold text-indigo-700 mb-4 flex items-center gap-2">
          <i class="fa-solid fa-book-open text-amber-500 text-3xl"></i>
          Step-by-Step Guide
        </h3>

      <ul id="guideList" class="list-decimal list-inside text-gray-700 space-y-2 leading-relaxed">
        <li>Select a category from the left panel.</li>
        <li>Read the guide and follow the instructions.</li>
        <li>Click on “Spin Now” to try your luck and win rewards!</li>
      </ul>

      <div id="guideImage" class="mt-6" data-aos="zoom-in" data-aos-duration="1200">
        <img src="uploads/SPINTOWIN_RGB-01.png" alt="Guide Illustration" class="rounded-xl shadow-md w-full">
      </div>
    </div>
    
</div>

 <!-- 🌟 Contact & Enquiry Section -->
<div id="contactEnquiry" class="bg-gradient-to-r from-indigo-50 via-white to-indigo-50 border border-indigo-100 rounded-2xl p-6 shadow-sm text-center" data-aos="fade-up" data-aos-duration="1200">
  <h3 class="text-xl font-bold text-indigo-700 mb-2 flex items-center justify-center gap-2">
    <i class="fa-solid fa-headset text-amber-500 text-2xl"></i>
    Need Help or Have a Question?
  </h3>
  <p class="text-gray-600 mb-4">
    Our student success team is here to guide you — whether you need admission advice, exam tips, or help with your prize claim.
  </p>

  <div class="flex flex-col sm:flex-row justify-center items-center gap-4 text-gray-700 font-medium">
    <div class="bg-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition duration-300 flex items-center gap-2">
      <i class="fa-solid fa-envelope text-indigo-600"></i>
      <span><span class="font-semibold text-indigo-600">Email:</span> formsadda@gmail.com</span>
    </div>
    <div class="bg-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition duration-300 flex items-center gap-2">
      <i class="fa-solid fa-phone text-indigo-600"></i>
      <span><span class="font-semibold text-indigo-600">Call:</span> +91 7631-900-600</span>
    </div>
  </div>

  <p class="mt-4 text-sm text-gray-500 flex justify-center items-center gap-2">
    <i class="fa-brands fa-whatsapp text-green-600 text-lg"></i>
    You can also <span class="text-indigo-600 font-semibold">chat with us</span> for instant guidance and updates!
  </p>
</div>

<!-- Spinner / Roller Section -->
<div id="spinnerContainer" class="hidden flex-col items-center w-full max-w-lg bg-white p-8 rounded-xl shadow-2xl">
  <div id="pointer" class="pointer"></div>  
  <div class="mb-8" id="wheelOrRoller"></div>
  
  <!-- Spin and Reset Buttons -->
  <div class="flex space-x-4">
    <button id="spinBtn" class="bg-green-500 hover:bg-green-600 text-white font-extrabold text-xl px-10 py-4 rounded-full shadow-lg transition duration-300 transform hover:scale-105 disabled:opacity-50 disabled:animate-none">
      Spin Now!
    </button>
    <button id="resetBtn" class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-3 rounded-full shadow transition duration-300">
      Select New Category
    </button>
  </div>
</div>

<!-- ===============================
     About This Site & Approach
================================= -->
<section id="aboutSection" class="w-full max-w-6xl mx-auto mt-20 bg-gradient-to-r from-indigo-50 via-white to-indigo-50 p-8 rounded-2xl shadow-md border border-gray-100 overflow-hidden relative">
  
  <!-- Decorative floating shapes -->
  <div class="absolute -top-8 -left-8 w-32 h-32 bg-indigo-200 rounded-full opacity-30 blur-3xl animate-pulse"></div>
  <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-amber-200 rounded-full opacity-30 blur-3xl animate-pulse"></div>

  <div class="text-center mb-12 relative" data-aos="fade-up" data-aos-duration="1000">
    <h2 class="text-3xl font-bold text-indigo-700 mb-2 flex items-center justify-center gap-2">
      <i class="fa-solid fa-lightbulb text-amber-500"></i>
      About Spin & Win for College Success
    </h2>
    <p class="text-gray-600 text-lg max-w-2xl mx-auto">
      Empowering students to make smarter college, exam, and career choices through fun engagement and interactive tools.
    </p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-gray-700 leading-relaxed relative z-10">

    <!-- 1️⃣ Our Mission -->
    <div data-aos="fade-up" data-aos-delay="100" class="p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition duration-300">
      <h3 class="text-xl font-semibold text-indigo-600 mb-3 flex items-center gap-2">
        <i class="fa-solid fa-bullseye text-amber-500"></i>
        Our Mission
      </h3>
      <p>We aim to simplify your education journey — from choosing the right college to preparing for exams — by turning learning into an exciting experience.</p>
    </div>

    <!-- 2️⃣ Our Approach -->
    <div data-aos="fade-up" data-aos-delay="200" class="p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition duration-300">
      <h3 class="text-xl font-semibold text-indigo-600 mb-3 flex items-center gap-2">
        <i class="fa-solid fa-gears text-amber-500"></i>
        Our Approach
      </h3>
      <p>Our “Spin & Win” approach blends gamified learning with valuable insights, helping students engage, explore, and earn exclusive resources while planning their careers.</p>
    </div>

    <!-- 3️⃣ Why Choose Us -->
    <div data-aos="fade-up" data-aos-delay="300" class="p-6 bg-white rounded-xl shadow-sm hover:shadow-md transition duration-300">
      <h3 class="text-xl font-semibold text-indigo-600 mb-3 flex items-center gap-2">
        <i class="fa-solid fa-star text-amber-500"></i>
        Why Choose Us
      </h3>
      <p>We combine verified academic data, career guidance, and fun rewards to make education planning easy, interactive, and rewarding — all in one trusted platform.</p>
    </div>
  </div>

  <div class="text-center mt-12 relative z-10" data-aos="zoom-in" data-aos-duration="1200">
    <p class="text-gray-600 font-medium">
      Ready to take the next step in your academic journey?<br>
      <span class="text-indigo-600 font-semibold">Spin today and unlock your path to success!</span>
    </p>
  </div>
</section>

<!-- Prize Modal -->
<div id="prizeModal" class="modal">
  <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-sm text-center transform scale-100 transition-transform duration-500 relative overflow-hidden">
    
    <!-- Sparkles / Decorations -->
    <svg class="sparkle-blast" width="100%" height="100%" viewBox="0 0 400 300" preserveAspectRatio="none" style="position: absolute; top: 0; left: 0; z-index: 1;">
      <g fill="#fde047" opacity="0.8">
        <path d="M 50 50 L 55 45 L 60 50 L 55 55 Z" class="sparkle s1"></path>
        <path d="M 350 250 L 355 245 L 360 250 L 355 255 Z" class="sparkle s2"></path>
        <path d="M 150 20 L 155 15 L 160 20 L 155 25 Z" class="sparkle s3"></path>
        <path d="M 280 180 L 285 175 L 290 180 L 285 185 Z" class="sparkle s4"></path>
        <path d="M 10 200 L 15 195 L 20 200 L 15 205 Z" class="sparkle s5"></path>
      </g>
    </svg>

    <!-- Prize Details -->
    <div class="relative z-10">
      <h2 class="text-3xl font-bold mb-4 text-green-600"><i class="fa-solid fa-trophy text-amber-500 mr-2"></i> Congratulations!</h2>
      <p class="text-xl font-semibold mb-2 text-gray-700">You won a free resource:</p>
      <p class="text-4xl font-extrabold mb-6 text-indigo-700" id="prizeName"></p>
      <button id="claimBtn" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-10 py-3 rounded-full shadow-lg transition duration-300 transform hover:scale-105">
        Claim My Prize
      </button>
    </div>
  </div>
</div>

<!-- Lead Form Modal -->
<div id="leadFormModal" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
  <div id="leadFormContent"
    class="relative bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-500 scale-100">
    
    <!-- Header with Icon -->
    <div class="text-center mb-6">
      <div class="mx-auto bg-indigo-100 w-16 h-16 flex items-center justify-center rounded-full shadow-inner mb-3">
        <i class="fa-solid fa-lock text-indigo-600 text-2xl"></i>
      </div>
      <h2 class="text-2xl font-extrabold text-indigo-700">Secure Your Prize</h2>
      <p class="text-gray-500 text-sm mt-1">Fill in your details to receive your exclusive reward instantly</p>
    </div>

    <!-- Lead Form -->
    <form id="leadForm" class="space-y-4">
      <!-- Full Name -->
      <div class="relative">
        <i class="fa-solid fa-user absolute left-3 top-3.5 text-gray-400"></i>
        <input type="text" name="name" placeholder="Full Name" required
          class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
      </div>

      <!-- Email -->
      <div class="relative">
        <i class="fa-solid fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
        <input type="email" name="email" placeholder="Email Address" required
          class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
      </div>

      <!-- Mobile -->
      <div class="relative">
        <i class="fa-solid fa-phone absolute left-3 top-3.5 text-gray-400"></i>
        <input type="tel" name="mobile" placeholder="Mobile Number" required pattern="[0-9]{10}"
          title="Please enter a valid 10-digit mobile number"
          class="w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
      </div>

      <!-- Hidden Prize Fields -->
      <input type="hidden" name="prize_text" id="prizeText">
      <input type="hidden" name="prize_type" id="prizeType">
      <input type="hidden" name="prize_color" id="prizeColor">
      <input type="hidden" name="prize_category" id="prizeCategoryName">

      <!-- Submit Button -->
      <button type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold w-full py-3 rounded-lg shadow-md transition transform hover:scale-105 focus:ring-2 focus:ring-indigo-400">
        <i class="fa-solid fa-paper-plane mr-2"></i> Submit & Get Access
      </button>
    </form>

    <!-- Decorative Glow -->
    <div class="absolute -top-6 -right-6 w-20 h-20 bg-indigo-200 rounded-full blur-3xl opacity-40"></div>
    <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-amber-200 rounded-full blur-3xl opacity-40"></div>
  </div>
</div>


<script>
$(document).ready(function(){

  /* ---------------------------
     VARIABLE INITIALIZATION
  ---------------------------- */
  let prizes = [];                   // Prize array for selected category
  let selectedPrize = null;          // Stores prize after spin
  let selectedCategoryName = '';     // Name of selected category
  let isRoller = false;              // True if roller mode
  const wheelOrRoller = $('#wheelOrRoller'); // Spinner / roller container
  const pointerElement = $('#pointer');      // Pointer arrow
  const categoryContainer = $('#categoryContainer');
  const categorySection = $('#categorySection');
  const spinnerContainer = $('#spinnerContainer');
  const spinBtn = $('#spinBtn');
  const leadFormContent = $('#leadFormContent');
  const originalFormHTML = leadFormContent.html();
  const prizeHeight = 160;           // Height for roller prize div

/* ---------------------------
     1. LOAD CATEGORIES FROM API
  ---------------------------- */
$.getJSON('api/get_categories.php', function (data) {

  // Icon map (Font Awesome icons)
  function getCategoryIcon(name) {
    name = name.toLowerCase();
    if (name.includes("college")) return "fa-solid fa-graduation-cap";   // 🎓
    if (name.includes("exam")) return "fa-solid fa-pencil-ruler";        // 🧮
    if (name.includes("admission")) return "fa-solid fa-school";         // 🏫
    if (name.includes("counselling")) return "fa-solid fa-comments";     // 💬
    if (name.includes("career")) return "fa-solid fa-rocket";            // 🚀
    if (name.includes("loan")) return "fa-solid fa-sack-dollar";         // 💰
    return "fa-solid fa-bullseye";                                       // 🎯
  }

  // Loop through each category
  data.forEach(cat => {
    const iconClass = getCategoryIcon(cat.name);
    const box = $(`
      <div class="category-box group relative bg-gradient-to-br from-indigo-50 via-white to-indigo-100 p-6 rounded-2xl shadow-lg 
                  hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 cursor-pointer border border-transparent 
                  hover:border-indigo-400 overflow-hidden hover:scale-105 animate-float">

        <!-- Glow background -->
        <div class="absolute inset-0 bg-gradient-to-tr from-amber-100 via-transparent to-indigo-100 opacity-0 
                    group-hover:opacity-70 transition duration-500 rounded-2xl blur-md"></div>

        <!-- Icon with animation -->
        <div class="relative z-10 mb-3 flex justify-center">
          <div class="w-16 h-16 flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 
                      group-hover:from-indigo-200 group-hover:to-indigo-300 transition-all duration-300 
                      shadow-inner rotate-0 group-hover:rotate-12">
            <i class="${iconClass} text-4xl text-indigo-700 group-hover:text-indigo-800 transition-transform duration-300"></i>
          </div>
        </div>

        <!-- Category Title -->
        <p class="relative z-10 text-xl font-extrabold text-gray-800 group-hover:text-indigo-700 tracking-wide">
          ${cat.name}
        </p>

        <!-- Subtitle -->
        <p class="relative z-10 text-sm text-gray-500 mt-2 italic group-hover:text-gray-700 transition">
          <i class="fa-solid fa-star text-amber-400 mr-1"></i> Explore insights and expert guidance
        </p>


        <!-- Bottom decorative bar -->
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-400 to-amber-400 
                    opacity-0 group-hover:opacity-100 transition duration-500"></div>
      </div>
    `);

    // When user clicks category — show right-side details, no page leave
    box.click(function () {
      $('.category-box').removeClass('border-indigo-600 border-b-8 shadow-2xl').addClass('border-indigo-400 border-b-4');
      $(this).removeClass('border-indigo-400 border-b-4').addClass('border-indigo-600 border-b-8 shadow-2xl');

      // Update right side section dynamically
      selectedCategoryName = cat.name;
      selectCategory(cat.id); // Loads steps/guides dynamically on the same page

      // Scroll to right-side section smoothly
      $('html, body').animate({
        scrollTop: $("#guideSection").offset().top - 50
      }, 600);
    });

    categoryContainer.append(box);
  });
});

/* ---------------------------
   Extra Animations
---------------------------- */
const style = document.createElement('style');
style.innerHTML = `
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-5px); }
  100% { transform: translateY(0px); }
}
.animate-float {
  animation: float 3s ease-in-out infinite;
}

@keyframes pulse-slow {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.15); opacity: 0.85; }
}
.animate-pulse-slow {
  animation: pulse-slow 2.5s ease-in-out infinite;
}
`;
document.head.appendChild(style);

/* ---------------------------
   HANDLE CATEGORY SELECTION
---------------------------- */
function selectCategory(catId) {
  $.getJSON('api/get_prizes.php?category_id=' + catId, function (data) {
    prizes = data;
    if (prizes.length === 0) return;

    isRoller = selectedCategoryName.toLowerCase().includes('college');

    categorySection.slideUp(400);
    $("#contactEnquiry").slideUp(400);

    spinnerContainer.delay(400).fadeIn(400).css('display', 'flex');
    wheelOrRoller.empty();

    if (isRoller) initializeRoller();
    else initializeSpinner();

    spinBtn.prop('disabled', false).text(isRoller ? 'Pull Lever!' : 'Spin Now!');
  });
}

/* ---------------------------
   INITIALIZE SPINNER
---------------------------- */
function initializeSpinner() {
  pointerElement.show();
  wheelOrRoller.removeClass('roller-container').addClass('spinner').empty();

  const totalSlices = prizes.length;
  const degPerSlice = 360 / totalSlices;
  const colors = ['#facc15','#60a5fa','#34d399','#c084fc','#fb7185','#a3e635'];

  let gradientStops = [];
  let currentDeg = 0;

  prizes.forEach((p, i) => {
    const bgColor = colors[i % colors.length];
    const startDeg = currentDeg;
    const endDeg = currentDeg + degPerSlice;
    gradientStops.push(`${bgColor} ${startDeg}deg`, `${bgColor} ${endDeg}deg`);
    currentDeg = endDeg;

    const rotationAngle = (i * degPerSlice) + (degPerSlice / 2);
    const textDiv = $('<div>')
      .addClass('prize-text-overlay')
      .html(`<p>${p.text}</p>`)
      .css({ 'transform': `rotate(${rotationAngle}deg)` });
    wheelOrRoller.append(textDiv);
  });

  const gradient = `conic-gradient(from 0deg, ${gradientStops.join(', ')})`;
  wheelOrRoller.css({ 'background': gradient, 'transition': 'none', 'transform': 'rotate(0deg)' });
}

/* ---------------------------
   INITIALIZE ROLLER
---------------------------- */
function initializeRoller() {
  pointerElement.hide();
  wheelOrRoller.removeClass('spinner').addClass('roller-container').empty();
  wheelOrRoller.append('<div class="roller-indicator"></div>');

  const itemsPerReel = prizes.length;

  const reelDiv = $('<div>').addClass('reel');
  reelDiv.css({ 'grid-column': '1 / span 3' });

  const stripDiv = $('<div>').addClass('reel-strip').attr('id', 'reel-strip-0');
  for (let i = 0; i < itemsPerReel * 3; i++) {
    const prize = prizes[i % itemsPerReel];
    const prizeColor = prize.color || '#1f2937';

    stripDiv.append($('<div>')
      .addClass('reel-prize')
      .text(prize.text)
      .css({
        'background-color': prizeColor,
        'border-radius': '8px',
        'color': '#fff',
        'text-shadow': '0 0 5px rgba(0,0,0,0.5)'
      })
    );
  }

  reelDiv.append(stripDiv);
  wheelOrRoller.append(reelDiv);
}

/* ---------------------------
   SPIN BUTTON CLICK
---------------------------- */
$('#spinBtn').click(function () {
  $(this).prop('disabled', true).text(isRoller ? 'Rolling...' : 'Spinning...');
  const randIndex = Math.floor(Math.random() * prizes.length);
  selectedPrize = prizes[randIndex];
  if (isRoller) spinRoller(randIndex);
  else spinWheel(randIndex);
});

/* ---------------------------
   SPIN WHEEL LOGIC
---------------------------- */
function highlightWinnerSlice(randIndex) {
  const slice = wheelOrRoller.find('.prize-text-overlay').eq(randIndex);
  slice.css({ 'background': 'rgba(255,255,255,0.3)', 'border-radius': '12px', 'box-shadow': '0 0 15px #fde047' });
}

function spinWheel(randIndex) {
  const totalSlices = prizes.length;
  const degPerSlice = 360 / totalSlices;
  const targetAngle = 360 - (randIndex * degPerSlice + degPerSlice / 2);
  const rotation = 360 * 6 + targetAngle;

  wheelOrRoller.css({
    'transition': 'transform 5s cubic-bezier(0.25,0.1,0.25,1)',
    'transform': `rotate(${rotation}deg)`
  });

  setTimeout(() => {
    highlightWinnerSlice(randIndex);
    showPrizeModal();
  }, 5200);
}

/* ---------------------------
   SPIN ROLLER LOGIC
---------------------------- */
function spinRoller(randIndex) {
  const itemsPerReel = prizes.length;
  const targetItemIndex = itemsPerReel + randIndex;
  const targetOffset = -(targetItemIndex * prizeHeight);
  const reel = $('#reel-strip-0'); // define reel inside function
  const duration = 4000;

  const initialSpin = -(itemsPerReel * prizeHeight * 5) - Math.random() * prizeHeight * itemsPerReel;
  reel.css({ 'transition': 'none', 'transform': `translateY(${initialSpin}px)` });
  reel.get(0).offsetHeight; // force reflow

  reel.css({ 'transition': `transform ${duration}ms cubic-bezier(0.25,0.1,0.25,1)`, 'transform': `translateY(${targetOffset}px)` });
  setTimeout(showPrizeModal, duration + 200);
}

/* ---------------------------
   SHOW PRIZE MODAL
---------------------------- */
function showPrizeModal() {
  if (!selectedPrize) return;
  $('#prizeName').text(selectedPrize.text || 'Your Prize');
  $('#prizeModal').addClass('active');
}

  /* ---------------------------
     9. RESET BUTTON
  ---------------------------- */
  $('#resetBtn').click(function(){ window.location.reload(); });

  /* ---------------------------
     10. CLAIM PRIZE BUTTON
  ---------------------------- */
  $('#claimBtn').click(function(){
    $('#prizeModal').removeClass('active');
    $('#leadFormModal').addClass('active');

    $('#prizeText').val(selectedPrize ? selectedPrize.text : '');
    $('#prizeType').val(selectedPrize ? selectedPrize.type : '');
    $('#prizeColor').val(selectedPrize ? selectedPrize.color : '');
    $('#prizeCategoryName').val(selectedCategoryName || '');
  });

  /* ---------------------------
     11. LEAD FORM SUBMISSION
  ---------------------------- */
  function handleLeadFormSubmit(e){
    e.preventDefault();
    const $form = $(this);
    const postUrl = 'api/claim_prize.php';
    const $submitBtn = $form.find('button[type="submit"]');

    $submitBtn.prop('disabled',true).text('Submitting...');

    $.post(postUrl,$form.serialize(),function(res){
      $submitBtn.prop('disabled',false).text('Submit & Get Access');
      if(res && res.success){
        leadFormContent.html(`
        <div class="relative z-10 text-center">
            <h2 class="text-3xl font-bold mb-4 text-green-600">
              <i class="fa-solid fa-circle-check text-green-500 mr-2"></i> Prize Secured!
            </h2>
            <p class="text-gray-700 mb-2 font-medium">Your prize details have been sent to your registered email address.</p> <p class="text-xl font-extrabold text-indigo-700 mt-4 mb-4">Your Coupon Code: ${res.spin_id}</p>
          <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
              <div class="flex items-center justify-center space-x-3">
                <div class="flex items-center justify-center w-10 h-10 bg-green-500 text-white rounded-full shadow-md">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-6 h-6">
                    <path d="M12.04 2C6.51 2 2 6.33 2 11.65c0 2.03.67 3.92 1.8 5.47L2 22l5.12-1.61a10.27 10.27 0 0 0 4.92 1.26c5.53 0 10.04-4.33 10.04-9.65S17.57 2 12.04 2zm.02 17.31c-1.57 0-3.1-.41-4.44-1.18l-.32-.19-3.03.95.99-2.88-.21-.3a7.58 7.58 0 0 1-1.36-4.3c0-4.23 3.62-7.66 8.07-7.66s8.07 3.43 8.07 7.66c0 4.23-3.62 7.66-8.07 7.66zm4.56-5.74c-.25-.13-1.48-.73-1.71-.82-.23-.09-.4-.13-.57.13-.17.26-.65.82-.8.99-.15.17-.3.2-.55.07-.25-.13-1.07-.4-2.04-1.29-.75-.66-1.25-1.47-1.4-1.72-.15-.26-.02-.4.11-.53.11-.11.25-.3.38-.45.13-.15.17-.26.25-.43.08-.17.04-.32-.02-.45-.07-.13-.57-1.37-.79-1.87-.21-.5-.42-.43-.57-.44h-.49c-.17 0-.45.06-.69.32s-.9.88-.9 2.15.92 2.49 1.05 2.67c.13.17 1.82 2.87 4.42 4.03.62.27 1.1.43 1.47.55.62.2 1.19.17 1.64.1.5-.07 1.48-.6 1.69-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.17-.48-.3z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-sm text-gray-700 font-medium leading-snug">
                    Have questions or need help?
                  </p>
                  <a href="https://api.whatsapp.com/send/?phone=917479716703&text&type=phone_number&app_absent=0"
                     target="_blank"
                     class="inline-flex items-center mt-1 bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-full shadow-md transition-all duration-300">
                     <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-5 h-5 mr-2">
                       <path d="M12.04 2C6.51 2 2 6.33 2 11.65c0 2.03.67 3.92 1.8 5.47L2 22l5.12-1.61a10.27 10.27 0 0 0 4.92 1.26c5.53 0 10.04-4.33 10.04-9.65S17.57 2 12.04 2zm.02 17.31c-1.57 0-3.1-.41-4.44-1.18l-.32-.19-3.03.95.99-2.88-.21-.3a7.58 7.58 0 0 1-1.36-4.3c0-4.23 3.62-7.66 8.07-7.66s8.07 3.43 8.07 7.66c0 4.23-3.62 7.66-8.07 7.66z"/>
                     </svg>
                     Chat on WhatsApp
                  </a>
                </div>
              </div>
            </div>
        </div>
        `);
        $('#finishBtn').on('click',function(){
          $('#leadFormModal').removeClass('active');
          $('#resetBtn').click();
        });
      } else {
        alert((res && res.message)?res.message:'Error saving data. Please try again.');
      }
    },'json').fail(function(){
      $submitBtn.prop('disabled',false).text('Submit & Get Access');
      alert('Error submitting form. Please check your network and ensure api/claim_prize.php is working.');
    });
  }

  /* ---------------------------
     12. ATTACH LEAD FORM SUBMISSION
  ---------------------------- */
  $('#leadForm').on('submit', handleLeadFormSubmit);

}); // document.ready
</script>
<script>
    AOS.init({
      once: true,  // animation runs once
      duration: 1000,
      offset: 100
    });
  </script>
</body>
</html>
