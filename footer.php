   <?php
    include 'db_conn.php';
    $visiblePagesQuery = "SELECT page_name FROM page_contents WHERE is_visible = 1";
    $visiblePagesResult = mysqli_query($conn, $visiblePagesQuery);
?> 
    <!-- ==== Footer section ==== -->
    <footer  class="footer-section4 n5-bg position-relative cus-z1 overflow-hidden">
        <div class="container pb-120">
            <div class="row g-6 justify-content-between align-items-center">
                <div class="col-lg-6 col-md-7">
                    <div class="destination-cont-left">
                       <div class="box mb-xxl-15 mb-xl-10 mb-md-8 mb-sm-4 mb-2">
                            <h4 class="fw_700 nw1-clr mb-xxl-5 mb-3" data-aos="zoom-in-left" data-aos-duration="1200">
                                Your Prediction Score
                            </h4>
                            <span class="display-three nw1-clr" data-aos="zoom-in-right" data-aos-duration="1400">
                                Win The 2 Wheeler
                            </span>
                       </div>
                       <div class="d-flex align-items-center gap-xl-6 gap-lg-4 gap-sm-3 gap-2" data-aos="fade-up" data-aos-duration="1800">
                            <form class="box-address">
                                <input type="text" placeholder="Enter your email address">
                                <span class="nw4-clr worries d-block">No worries, we don’t spam your inbox.</span>
                            </form>
                            <a href="#0" class="cmn-60 d-center p1-bg radius-circle">
                                <i class="ph-bold ph-arrow-up-right n4-clr fs-four"></i>
                            </a>
                       </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5">
                    <div class="footer-v4cont-info">
                        <div class="fv4-cont-item d-flex align-items-center gap-xxl-4 gap-lg-3 gap-2" data-aos="zoom-in-up" data-aos-duration="1200">
                            <div class="icon cmn-60 radius-circle d-center">
                                <i class="ph-bold ph-map-pin fs-four p1-clr"></i>
                            </div>
                            <div class="cont">
                                <a href="javascript:void(0)" class="nw1-clr fs20 fw_600">
                                    5th Floor Samundra Complex FormsADDA Lalpur, 834001
                                </a>
                            </div>
                        </div>
                        <div class="fline" data-aos="zoom-in-up" data-aos-duration="1200"></div>
                        <div class="fv4-cont-item d-flex align-items-center gap-xxl-4 gap-lg-3 gap-2" data-aos="zoom-in-up" data-aos-duration="1200">
                            <div class="icon cmn-60 radius-circle d-center">
                                <i class="ph-bold ph-envelope-simple fs-four p1-clr"></i>
                            </div>
                            <div class="cont">
                                <span class="nw1-clr d-block fs-seven mb-2">
                                    Email Us Directly
                                </span>
                                <a href="javascript:void(0)" class="nw1-clr fs20 fw_600">
                                    formsadda@gmail.com
                                </a>
                            </div>
                        </div>
                        <div class="fline" data-aos="zoom-in-up" data-aos-duration="1200"></div>
                        <div class="fv4-cont-item d-flex align-items-center gap-xxl-4 gap-lg-3 gap-2" data-aos="zoom-in-up" data-aos-duration="1200">
                            <div class="icon cmn-60 radius-circle d-center">
                                <i class="ph-bold ph-phone-call fs-four p1-clr"></i>
                            </div>
                            <div class="cont">
                                <a href="javascript:void(0)" class="nw1-clr fs20 fw_600">
                                    <span class="nw1-clr d-block fs-seven mb-2">
                                        Call Us Directly
                                    </span>
                                    +91 7631-900-600
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottomv4 py-6 py-lg-0">
            <div class="container">
                <div class="row g-6 justify-content-between">
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="footer-bottom-cont1 d-flex justify-content-sm-start justify-content-center align-items-center h-100 py-xxl-8 py-xl-6 py-lg-4 py-sm-2 py-0">
                            <span class="copy nw4-clr">
                                Copyright &copy; 2024 <a href="#" class="nw4-clr">FormsADDA</a>
                                <span class="designed nw4-clr d-block mt-2">
                                    Designed By <a href="https://palaksys.in" class="p1-clr"> Palaksys</a>
                                </span>
                                <span class="d-block mt-2 small">
                                    <a href="admin/login.php" class="text-secondary text-decoration-none me-2"><i class="fas fa-user-shield me-1"></i>Admin</a>
                                    <span class="text-muted">|</span>
                                    <a href="counsellor/login.php" class="text-secondary text-decoration-none ms-2"><i class="fas fa-chalkboard-teacher me-1"></i>Counsellor</a>
                                </span>
                                <span class="d-block mt-2">
                                    <?php 
                                    $isFirst = true;
                                    while ($page = mysqli_fetch_assoc($visiblePagesResult)): 
                                        if (!$isFirst) echo " | ";  // Add separator only if not the first item
                                        $isFirst = false;
                                    ?>
                                        <a class="nw4-clr" href="page_template.php?page=<?php echo $page['page_name']; ?>">
                                            <?php echo ucwords(str_replace('-', ' ', $page['page_name'])); ?>
                                        </a>
                                    <?php endwhile; ?>
                                </span>

                            </span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="footer-bottom-cont1 d-flex align-items-center h-100 py-xxl-8 py-xl-6 py-lg-4 py-sm-2 py-0 px-4 b-one border-start border-end">
                            <a href="index.html" class="footer-logov4 text-center m-auto d-block">
                                <img src="image/formsadda.webp" alt="img">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-5">
                        <div class="footer-bottom-cont1 d-flex align-items-center justify-content-md-end justify-content-center h-100 py-xxl-8 py-xl-6 py-lg-4 py-sm-2 py-0">
                            <ul class="social-wrap social-wrap60 d-flex justify-content-center justify-content-sm-start align-items-center gap-xxl-3 gap-2 flex-wrap">
                                 <li>
                                     <a href="https://www.facebook.com/formsadda" class="fa-brands fa-facebook fa-2x soc-item soc-item-hover-black d-inline-flex radius-circle justify-content-center align-items-center n4-border" aria-label="Facebook"></a>
                                </li>
                                <li>
                                    <a href="https://x.com/formsadda" class="fa-brands fa-twitter fa-2x soc-item soc-item-hover-black d-inline-flex radius-circle justify-content-center align-items-center n4-border" aria-label="Twitter"></a>
                                </li>
                                <li>
                                    <a href="https://wa.me/917491871366?text=Hello%20there,%20I%20would%20like%20to%20get%20in%20touch!" target="_blank" class="fa-brands fa-whatsapp fa-2x soc-item soc-item-hover-black d-inline-flex radius-circle justify-content-center align-items-center n4-border" aria-label="GitHub"></a>
                                <li>
                                    <a href="https://www.linkedin.com/company/formsadda-com" class="fa-brands fa-linkedin fa-2x soc-item soc-item-hover-black d-inline-flex radius-circle justify-content-center align-items-center n4-border" aria-label="LinkedIn"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<!-- Modal Structure -->
<div class="modal fade" id="participateModal" tabindex="-1" aria-labelledby="participateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="login-flip-container" class="xl-size flip-container">
        		<div class="login  card-front">
        			<div class="content">
        					<h3>Log In</h3>
            				<form id="loginForm">
                                <div class="mb-3">
                                    <!--<label for="loginMobile" class="form-label">Mobile Number</label>-->
                                    <input type="text" class="form-control" id="loginMobile" name="mobile" placeholder="Enter your mobile number" maxlength="10" required>
                                </div>
                                <button type="button" class="btn w-100 text-white" onclick="sendOTP()" style="border: none; background-color: #418789;">Login</button>
                                
                                <div id="loginMessage" class="mt-3"></div> <!-- For showing messages -->
                            </form>

            				<!-- OTP Verification Form for Login -->
                            <form id="otpForm" method="POST" style="display:none;">
                                <label for="loginOtp" class="form-label">Verify OTP</label>
                                <div class="otp-input d-flex mb-3" id="otpContainer">
                                    <input type="tel" name="otp[]" class="form-control otp-box" maxlength="1" required>
                                    <input type="tel" name="otp[]" class="form-control otp-box" maxlength="1" required>
                                    <input type="tel" name="otp[]" class="form-control otp-box" maxlength="1" required>
                                    <input type="tel" name="otp[]" class="form-control otp-box" maxlength="1" required>
                                    <input type="tel" name="otp[]" class="form-control otp-box" maxlength="1" required>
                                    <input type="tel" name="otp[]" class="form-control otp-box" maxlength="1" required>
                                </div>
                                <input type="hidden" name="otp_entered" id="otp_entered">
                                <button type="submit" class="btn btn-success w-100">Verify OTP</button>
                                <div id="otpMessage" class="mt-3 text-danger"></div>
                                <div class="mt-3 send-mess">
                                    <div>Didn’t receive OTP? <a role="button" class="text-primary text-decoration-none" onclick="sendOTP()">Resend OTP</a></div>
                                    <div><a role="button" class="text-primary text-decoration-none" onclick="showLoginForm()">Change Number</a></div>
                                </div>
                            
                                
                            </form>
                            <span class="loginwith">Or Connect with</span>
                			<div class="social-icons">
                                <a href="https://www.facebook.com/formsadda" class="fa-brands fa-facebook fa-2x" aria-label="Facebook"></a>
                                <a href="https://x.com/formsadda" class="fa-brands fa-twitter fa-2x" aria-label="Twitter"></a>
                                <a href="https://wa.me/+917631900600?text=Hello%20there,%20I%20would%20like%20to%20get%20in%20touch!" target="_blank" class="fa-brands fa-whatsapp fa-2x" aria-label="GitHub"></a>
                                <a href="https://www.linkedin.com/company/formsadda-com" class="fa-brands fa-linkedin fa-2x" aria-label="LinkedIn"></a>
                            </div>
                            <button type="button" class="btn flip-button sm-btn text-white w-100" onclick="showRegisterForm()">Click Here to Register</button>
            			</div>
            		</div>
            		<div class="page front">
            			<div class="content">
            				 <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
        					<h1>Hello, friend!</h1>
        					<p>Enter your personal details and start journey with us</p>
        					<button type="" id="register">Register <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right-circle"><circle cx="12" cy="12" r="10"/><polyline points="12 16 16 12 12 8"/><line x1="8" y1="12" x2="16" y2="12"/></svg></button>
            			</div>
            		</div>
            		<div class="page back">
        				<div class="content">
        					<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        					<h3>Welcome Back!</h3>
        					<p>To keep connected with us please login with your personal info</p>
        					<button type="" id="login"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"/><polyline points="12 8 8 12 12 16"/><line x1="16" y1="12" x2="8" y2="12"/></svg> Log In</button>
            			</div>
            		</div>
            		<div class="register  card-back">
            			<div class="content">
            				<h3>Sign Up</h3>
            				<div class="social-icons">
                                <a href="https://www.facebook.com/formsadda" class="fa-brands fa-facebook fa-2x" aria-label="Facebook"></a>
                                <a href="https://x.com/formsadda" class="fa-brands fa-twitter fa-2x" aria-label="Twitter"></a>
                                <a href="https://wa.me/+917631900600?text=Hello%20there,%20I%20would%20like%20to%20get%20in%20touch!" target="_blank" class="fa-brands fa-whatsapp fa-2x" aria-label="GitHub"></a>
                                <a href="https://www.linkedin.com/company/formsadda-com" class="fa-brands fa-linkedin fa-2x" aria-label="LinkedIn"></a>
                            </div>


        					<span class="loginwith">Or</span>
        
        					<!-- Registration Form -->
                       <form id="registerForm">
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="registerName" name="name" placeholder="Your full name" required>
                            </div>
                        
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-phone-alt"></i></span>
                                <input type="text" class="form-control" id="registerMobile" name="mobile" placeholder="Your mobile number" maxlength="10" required>
                            </div>
                        
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="registerEmail" name="email" placeholder="Your email address" required>
                            </div>
                        
                            <div class="mb-3 input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" class="form-control" id="registerAddress" name="address" placeholder="Your address" required>
                            </div>
                        
                            <button type="button" class="btn w-100 text-white" onclick="registerUser()" style="border: none; background-color: #418789;">
                                <i class="fas fa-user-plus me-2"></i>REGISTER
                            </button>
                        
                            <div id="registerMessage" class="mt-3 text-success"></div>
                        </form>

                        <!-- OTP Verification Form -->
                        <form id="registerOtpForm" style="display: none;">
                            <label for="registerOtp" class="form-label">Verify OTP</label>
                            <div class="otp-input d-flex justify-content-center mb-3">
                                <input type="tel" class="form-control otp-box" maxlength="1" required>
                                <input type="tel" class="form-control otp-box" maxlength="1" required>
                                <input type="tel" class="form-control otp-box" maxlength="1" required>
                                <input type="tel" class="form-control otp-box" maxlength="1" required>
                                <input type="tel" class="form-control otp-box" maxlength="1" required>
                                <input type="tel" class="form-control otp-box" maxlength="1" required>
                            </div>
                            <input type="hidden" name="otp_entered" id="otp_entered">
                            <button type="submit" class="btn btn-success w-100">Verify OTP</button>
                            <div id="registerOtpMessage" class="mt-3 text-danger"></div> <!-- error message -->
                            <div class="mt-3 send-mess">
                                <div>Didn’t receive OTP? <a role="button" class="text-primary text-decoration-none" onclick="sendOTP()">Resend OTP</a></div>
                                <div><a role="button" class="text-primary text-decoration-none" onclick="showRegisterForm()">Change Number</a></div>
                            </div>
                            
                        </form>
                        
                        <script>
                        // Auto focus to next input
                        document.querySelectorAll('.otp-box').forEach((input, index, inputs) => {
                            input.addEventListener('input', () => {
                                if (input.value.length && index < inputs.length - 1) {
                                    inputs[index + 1].focus();
                                }
                            });
                        });
                        
                        document.getElementById('registerOtpForm').addEventListener('submit', function (e) {
                            e.preventDefault();
                        
                            let otpInputs = document.querySelectorAll('.otp-box');
                            let otpValue = '';
                            otpInputs.forEach(input => {
                                otpValue += input.value;
                            });
                        
                            if (otpValue.length !== 6) {
                                document.getElementById('registerOtpMessage').textContent = '❌ Please enter the 6-digit OTP.';
                                return;
                            }
                        
                            document.getElementById('otp_entered').value = otpValue;
                        
                            fetch('verify_otp.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'otp_entered=' + encodeURIComponent(otpValue)
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.location.href = 'student/index.php';
                                } else {
                                    document.getElementById('registerOtpMessage').textContent = '❌ ' + data.message;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                document.getElementById('registerOtpMessage').textContent = '❌ Something went wrong.';
                            });
                        });
                        </script>

                        <button type="button" class="btn flip-button sm-btn" onclick="showLoginForm()">Click Here to Log In</button>
        			</div>		
        		</div>
            </div>
            
        </div>
    </div>

function showLogin() {
    document.querySelector(".flip-container").classList.remove("flipped");
}


</script>
<!--------------------------------------------------------------------->
<script>

// Function to send OTP to WhatsApp and show OTP form
function sendOTP() {
    let mobile = document.getElementById("loginMobile").value;
    if (mobile.length === 10 && /^\d+$/.test(mobile)) {
        fetch('student/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mobile: mobile })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.message === "OTP sent via WhatsApp") {
                    document.getElementById("loginForm").style.display = "none";
                    document.getElementById("otpForm").style.display = "block";
                    document.getElementById("loginMessage").innerHTML = "OTP has been sent to your WhatsApp.";
                    // Ensure OTP message displays in otpForm
                    document.getElementById("otpMessage").innerText = "Please enter the OTP sent to your WhatsApp.";
                }
            } else {
                document.getElementById("loginMessage").innerHTML = data.message;
                document.getElementById("loginMessage").style.color = "red";
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("loginMessage").innerText = "Error occurred. Please try again.";
        });
    } else {
        document.getElementById("loginMessage").innerText = "Please enter a valid 10-digit mobile number.";
    }
}


// Function to show registration form
function showRegisterForm() {
    document.getElementById("loginForm").style.display = "none";
    document.getElementById("otpForm").style.display = "none";
    document.getElementById("registerForm").style.display = "block";
    document.getElementById("registerOtpForm").style.display = "none";
    
    const container = document.querySelector('.flip-container');
    container.classList.add('flipped');
}

// Function to show login form
function showLoginForm() {
    document.getElementById("registerForm").style.display = "none";
    document.getElementById("otpForm").style.display = "none";
    document.getElementById("loginForm").style.display = "block";
    document.getElementById("registerOtpForm").style.display = "none";
    
    const container = document.querySelector('.flip-container');
    container.classList.remove('flipped');
}


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




// JavaScript for Auto-Focus
document.querySelectorAll('.otp-box').forEach((input, index, elements) => {
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

// Submitting OTP as a Full Value
document.getElementById('otpForm').addEventListener('submit', function(e) {
    const otpInputs = document.querySelectorAll('#otpForm .otp-box');
    const otpValue = Array.from(otpInputs).map(input => input.value).join('');
    
    // Append a hidden input with the full OTP value
    const hiddenOtpInput = document.createElement('input');
    hiddenOtpInput.type = 'hidden';
    hiddenOtpInput.name = 'otp_entered';
    hiddenOtpInput.value = otpValue;
    this.appendChild(hiddenOtpInput);
});

</script>

<script>
    // Script to concatenate OTP values into the hidden input
    const otpInputs = document.querySelectorAll('.otp-box');
    const otpEntered = document.getElementById('otp_entered');

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            // Move to the next input if user types a number
            if (input.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }

            // Collect all OTP values
            let otpValue = '';
            otpInputs.forEach(input => otpValue += input.value);
            otpEntered.value = otpValue;
        });
    });
</script>
<script>
document.getElementById("otpForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const otpBoxes = document.querySelectorAll(".otp-box");
    let otpValue = "";
    otpBoxes.forEach(box => otpValue += box.value);

    // Remove previous error styling
    otpBoxes.forEach(box => box.classList.remove("error"));

    fetch("student/verify_login_otp.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `verify_login_otp=1&otp_entered=${encodeURIComponent(otpValue)}`
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            window.location.href = 'student/index.php'; // success
        } else {
            document.getElementById("otpMessage").innerText = response.message;
            otpBoxes.forEach(box => box.classList.add("error"));
        }
    })
    .catch(err => {
        console.error("OTP verification failed", err);
    });
});
</script>

<style>
.otp-box.error {
    border: 2px solid red;
}
#errorMsg {
    color: red;
    margin-top: 10px;
}
.send-mess div, .send-mess a {
    font-size: 14px;
}

#registerForm .input-group {
    border-radius: 6px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
}

#registerForm .input-group-text {
    background-color: #fff;
    border: 1px solid #ccc;
    border-right: 0;
    margin: 0;
    padding: 4px 0 4px 5px;
}

#registerForm .form-control {
    border: 1px solid #ccc;
    border-left: 0;
    font-size: 1rem;
     width: 80%;
}

#registerForm .form-control:focus {
    border-color: #418789;
    box-shadow: 0 0 0 0.1rem rgba(65, 135, 137, 0.25);
}

#registerForm button {
    font-size: 1rem;
    padding: 10px;
    border-radius: 6px;
}

</style>
<!-------------------End Mobile Views-------------------------------------------------->
    </main>



    <!-- ==== js Jquery start ==== -->
    <script src="js/jquery.js"></script>
    <!-- ==== js Viewport js start ==== -->
    <script src="js/viewpot.js"></script>
    <!-- ==== js Aos Animation start ==== -->
    <script src="js/aos.js"></script>
    <!-- ==== js Bootstrap start ==== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ==== js Magnific start ==== -->
    <script src="js/magnific-popup.js"></script>
    <!-- ==== js Swiper start ==== -->
    <script src="js/swiper.js"></script>
    <!-- ==== js Odometer start ==== -->
    <script src="js/odometer.js"></script>
    <!-- ==== js Nice Select start ==== -->
    <script src="js/jquery.nice-select.min.js"></script>
    <!-- ==== js Phosphor Icon start ==== -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- ==== js Mian start ==== -->
    <script src="js/main.js"></script>

    <script src="js/pwa.js"></script>
</body>

</html>