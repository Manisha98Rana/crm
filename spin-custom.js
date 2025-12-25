  
// Open Modal
function openModal() {
    document.getElementById("modalOverlay").classList.remove("hidden");
    // Reset to login form on open
    document.getElementById("flipContainer").classList.remove("flipped");
    showLoginForm();
}

// Close Modal
function closeModal() {
    document.getElementById("modalOverlay").classList.add("hidden");
}

// Flip Card
function flipCard() {
    const container = document.getElementById("flipContainer");
    container.classList.toggle("flipped");
}

// Show Register Form
function showRegisterForm() {
    document.getElementById("registerForm").style.display = "block";
    document.getElementById("registerOtpForm").style.display = "none";
    flipCard();
}

// Show Login Form
function showLoginForm() {
    const loginForm = document.getElementById("loginForm");
    const otpForm = document.getElementById("otpForm");
    
    if (loginForm) loginForm.style.display = "block";
    if (otpForm) otpForm.style.display = "none";
    
    // Clear inputs
    const loginMobile = document.getElementById("loginMobile");
    if (loginMobile) loginMobile.value = "";
    
    const loginMessage = document.getElementById("loginMessage");
    if (loginMessage) loginMessage.innerHTML = "";
}

// Send OTP for Login
// Send OTP for Login
function sendOTP() {
    let mobile = document.getElementById("loginMobile").value;
    document.getElementById("loginMessage").innerHTML = '';

    // Validate mobile number
    if (mobile.length !== 10 || !/^\d+$/.test(mobile)) {
        document.getElementById("loginMessage").innerHTML = '<span class="text-red-600">Please enter a valid 10-digit mobile number.</span>';
        return;
    }

    document.getElementById("loginMessage").innerHTML = '<span class="text-blue-600">Sending OTP...</span>';

    // Actual API call
    fetch('student/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ mobile: mobile })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // OTP sent successfully
            document.getElementById("loginForm").style.display = "none";
            document.getElementById("otpForm").style.display = "block";
            document.getElementById("loginMessage").innerHTML = '';
            document.getElementById("otpMessage").innerText = "OTP sent to your WhatsApp";
            focusFirstOTPBox('otpContainer');
        } 
        else if (data.message === 'not_registered') {
            // User not registered
            document.getElementById("loginMessage").innerHTML = `
                <span class="text-red-600">User not registered. Please create an account.</span>
                <br>
                <button type="button" class="mt-2 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition"
                        onclick="flipCard()">
                    Register Now
                </button>
            `;
        } 
        else {
            // Any other error from backend
            document.getElementById("loginMessage").innerHTML = '<span class="text-red-600">' + data.message + '</span>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById("loginMessage").innerHTML = '<span class="text-red-600">Error occurred. Please try again.</span>';
    });
}


// Register User
function registerUser() {
    let name = document.getElementById("registerName").value;
    let mobile = document.getElementById("registerMobile").value;
    let email = document.getElementById("registerEmail").value;
    let address = document.getElementById("registerAddress").value;

    // Clear previous messages
    const messageBox = document.getElementById("registerMessage");
    const otpMessageBox = document.getElementById("registerOtpMessage");
    messageBox.innerHTML = '';
    otpMessageBox.innerHTML = '';

    fetch('register.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, mobile, email, address })
    })
   .then(response => response.text())
    .then(text => {
        console.log("Raw Response:", text);
        try {
            let data = JSON.parse(text);
            if (data.success) {
                document.getElementById("registerForm").style.display = "none";
                document.getElementById("registerOtpForm").style.display = "block";
                document.getElementById("registerOtpMessage").innerText = "OTP has been sent to your WhatsApp.";
            } else {
                document.getElementById("registerMessage").innerText = data.message || "Registration failed. Try again.";
            }
        } catch (err) {
            console.error("JSON Parse Error:", err);
            document.getElementById("registerMessage").innerText = "Server returned unexpected response: " + text;
        }
    })

    .catch(error => {
        console.error('Error:', error);
        messageBox.innerHTML = `<span style="color: red;">❌ Error: ${error.message}</span>`;
    });
}

function sendRegisterOTP() {
    // Resend OTP logic
    alert('OTP Resent to your WhatsApp');
}

function focusFirstOTPBox(containerId) {
    let container = document.getElementById(containerId);
    let firstBox = container.querySelector('.otp-box');
    if (firstBox) firstBox.focus();
}

// OTP Auto-focus setup
function setupOTPAutoFocus() {
    const otpBoxes = document.querySelectorAll('.otp-box');
    otpBoxes.forEach((input, index, elements) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < elements.length - 1) {
                elements[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === "Backspace" && index > 0 && !e.target.value) {
                elements[index - 1].focus();
            }
        });
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    setupOTPAutoFocus();

    // Login OTP Submit
    let otpForm = document.getElementById('otpForm');
    if (otpForm) {
        otpForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const otpBoxes = this.querySelectorAll('.otp-box');
            let otpValue = '';
            otpBoxes.forEach(box => otpValue += box.value);

            if (otpValue.length !== 6) {
                document.getElementById("otpMessage").innerText = 'Please enter all 6 digits';
                return;
            }

            /* Uncomment for actual API call*/
            fetch("student/verify_login_otp.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `verify_login_otp=1&otp_entered=${encodeURIComponent(otpValue)}`
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    window.location.href = 'student/index.php';
                } else {
                    document.getElementById("otpMessage").innerText = response.message;
                }
            });
            

            // Demo: show success
            // alert('Login OTP Verified! (Demo)');
            // showLoginForm();
        });
    }

    // Register OTP Submit
    let registerOtpForm = document.getElementById('registerOtpForm');
    if (registerOtpForm) {
        registerOtpForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const otpBoxes = this.querySelectorAll('.otp-box');
            let otpValue = '';
            otpBoxes.forEach(box => otpValue += box.value);

            if (otpValue.length !== 6) {
                document.getElementById("registerOtpMessage").innerText = 'Please enter all 6 digits';
                return;
            }

            /* Uncomment for actual API call*/
            fetch('verify_otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'otp_entered=' + encodeURIComponent(otpValue)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'student/index.php';
                } else {
                    document.getElementById('registerOtpMessage').innerText = '❌ ' + data.message;
                }
            });
            

            // Demo: show success
            // alert('Registration OTP Verified! (Demo)');
            // showLoginForm();
        });
    }
});
    


