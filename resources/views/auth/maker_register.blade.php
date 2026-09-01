<!DOCTYPE html>
<html lang="{{ session('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <title>Maker Registration — MakerFest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4F46E5;
            --primary-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            --bg-color: #F8FAFC;
            --card-bg: rgba(255, 255, 255, 0.9);
            --border-color: rgba(226, 232, 240, 0.8);
        }
        body { 
            background: var(--bg-color); 
            background-image: radial-gradient(circle at top right, rgba(79, 70, 229, 0.08), transparent 40%),
                              radial-gradient(circle at bottom left, rgba(124, 58, 237, 0.08), transparent 40%);
            color: #0F172A; font-family: 'Inter', system-ui, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; padding: 40px 20px; 
        }
        .register-card { 
            background: var(--card-bg); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-radius: 20px; padding: 48px 40px; width: 100%; max-width: 500px; 
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1); border: 1px solid var(--border-color); 
            transition: transform 0.3s ease;
        }
        .register-card:hover { transform: translateY(-4px); box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12); }
        .brand { text-align: center; font-size: 26px; font-weight: 800; color: var(--primary); margin-bottom: 8px; letter-spacing: -0.5px; }
        .brand span { background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .sub-title { text-align: center; color: #64748B; font-size: 15px; margin-bottom: 32px; font-weight: 500; }
        
        .form-group { margin-bottom: 24px; }
        label { display: block; font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 8px; }
        input { 
            width: 100%; padding: 14px 16px; border-radius: 12px; 
            border: 1px solid #CBD5E1; background: #fff; 
            color: #0F172A; font-size: 15px; box-sizing: border-box; transition: all 0.2s ease;
        }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15); }
        input::-ms-reveal, input::-ms-clear { display: none; }
        input::-webkit-contacts-auto-fill-button, input::-webkit-credentials-auto-fill-button { visibility: hidden; pointer-events: none; position: absolute; right: 0; }
        
        .btn { 
            width: 100%; padding: 14px; background: var(--primary-gradient); 
            border: none; border-radius: 12px; color: #fff; font-weight: 700; font-size: 16px; 
            cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); margin-top: 12px;
        }
        .btn:hover:not(:disabled) { box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3); transform: translateY(-2px); }
        .btn:active:not(:disabled) { transform: translateY(0); }
        .btn:disabled { opacity: 0.7; cursor: not-allowed; }
        
        .link-footer { text-align: center; margin-top: 32px; font-size: 15px; color: #64748B; }
        .link-footer a { color: var(--primary); text-decoration: none; font-weight: 600; transition: color 0.2s ease; }
        .link-footer a:hover { color: #3730A3; }
        
        .error-msg { background: #FEF2F2; color: #DC2626; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 24px; text-align: center; border: 1px solid #FCA5A5; font-weight: 500; }
        
        #passReqBox { background: rgba(241, 245, 249, 0.7); border: 1px solid #E2E8F0; padding: 12px; border-radius: 8px; margin-top: 10px; font-size: 13px; font-weight: 500; }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="brand">MakerFest <span>Platform</span></div>
        <div class="sub-title">Create a new Maker Account</div>
        
        @if(session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif

        <form action="{{ route('maker.register.post') }}" method="POST" id="regForm">
            @csrf
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" id="nameInput" name="name" value="{{ old('name') }}" placeholder="Enter full name (English or ગુજરાતી)" required>
            </div>
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" id="emailInput" name="email" value="{{ old('email') }}" placeholder="e.g. maker@gmail.com" required>
            </div>
            <div class="form-group">
                <label>Password *</label>
                <div style="position: relative;">
                    <input type="password" id="passwordInput" name="password" placeholder="Create password" oninput="checkPasswordRequirements()" required style="padding-right: 40px;">
                    <button type="button" onclick="togglePass('passwordInput', this)" style="position: absolute; right: 12px; top: 12px; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 16px;">👁</button>
                </div>
                
                <div id="passReqBox" style="margin-top: 8px; font-size: 12px; background: #f9fafb; padding: 10px; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <div id="reqMinLen" style="color: #ef4444; transition: color 0.2s;">At least 8 characters</div>
                    <div id="reqUpper" style="color: #ef4444; transition: color 0.2s;">At least 1 uppercase letter (A-Z)</div>
                    <div id="reqLower" style="color: #ef4444; transition: color 0.2s;">At least 1 lowercase letter (a-z)</div>
                    <div id="reqNumSym" style="color: #ef4444; transition: color 0.2s;">At least 1 number or special character</div>
                </div>
            </div>
            <div class="form-group">
                <label>Confirm Password *</label>
                <div style="position: relative;">
                    <input type="password" id="confirmPasswordInput" name="password_confirmation" placeholder="Confirm password" oninput="checkPasswordRequirements()" required style="padding-right: 40px;">
                    <button type="button" onclick="togglePass('confirmPasswordInput', this)" style="position: absolute; right: 12px; top: 12px; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 16px;">👁</button>
                </div>
                <div id="reqMatch" style="font-size: 12px; margin-top: 4px; color: #ef4444; display: none;">Passwords do not match</div>
            </div>

            <div style="margin-bottom: 20px;">
                <button type="button" id="sendOtpBtn" onclick="validateAndSendOtp()" class="btn" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe;">Send Verification OTP</button>
                <div id="otpStatus" style="font-size: 13px; margin-top: 6px; text-align: center; font-weight: 500;"></div>
            </div>

            <div class="form-group" id="otpGroup" style="display: none;">
                <label>Enter 6-Digit Email OTP *</label>
                <input type="text" id="otpInput" name="otp" placeholder="Enter 6-digit OTP code">
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 6px; font-size: 12px;">
                    <span id="resendLimitText" style="color: #6b7280;">Resends left: <strong id="resendsLeft">4</strong></span>
                    <button type="button" id="resendOtpBtn" onclick="validateAndSendOtp()" disabled style="background: none; border: none; color: #9ca3af; font-size: 12px; font-weight: 700; cursor: not-allowed; text-decoration: underline;">Resend OTP in <span id="countdown">40</span>s</button>
                </div>
            </div>

            <button type="submit" id="submitBtn" class="btn" style="display: none;">Register Maker Account</button>
        </form>

        <div class="link-footer">
            Already have an account? <a href="{{ route('login') }}">Sign In</a>
        </div>
    </div>

    <script>
        let resendsRemaining = 4;
        let countdownTimer = null;

        function togglePass(id, btn) {
            const inp = document.getElementById(id);
            if (inp.type === 'password') {
                inp.type = 'text';
                btn.style.color = '#6b38fb';
            } else {
                inp.type = 'password';
                btn.style.color = '#6b7280';
            }
        }

        function startCountdown() {
            let left = 40;
            const btn = document.getElementById('resendOtpBtn');
            const cdSpan = document.getElementById('countdown');
            btn.disabled = true;
            btn.style.cursor = 'not-allowed';
            btn.style.color = '#9ca3af';
            cdSpan.innerText = left;

            if (countdownTimer) clearInterval(countdownTimer);

            countdownTimer = setInterval(() => {
                left--;
                cdSpan.innerText = left;
                if (left <= 0) {
                    clearInterval(countdownTimer);
                    if (resendsRemaining > 0) {
                        btn.disabled = false;
                        btn.style.cursor = 'pointer';
                        btn.style.color = '#6b38fb';
                        btn.innerHTML = `Resend OTP (<span id="resendsLeft">${resendsRemaining}</span> left)`;
                    } else {
                        btn.disabled = true;
                        btn.innerText = 'Max Resends Reached';
                    }
                }
            }, 1000);
        }

        function checkPasswordRequirements() {
            const pass = document.getElementById('passwordInput').value;
            const confirm = document.getElementById('confirmPasswordInput').value;
            const reqMatch = document.getElementById('reqMatch');

            // Min len
            const elLen = document.getElementById('reqMinLen');
            if (pass.length >= 8) {
                elLen.style.color = '#10b981';
                elLen.innerText = 'At least 8 characters';
            } else {
                elLen.style.color = '#ef4444';
                elLen.innerText = 'At least 8 characters';
            }

            // Uppercase
            const elUpper = document.getElementById('reqUpper');
            if (/[A-Z]/.test(pass)) {
                elUpper.style.color = '#10b981';
                elUpper.innerText = 'At least 1 uppercase letter (A-Z)';
            } else {
                elUpper.style.color = '#ef4444';
                elUpper.innerText = 'At least 1 uppercase letter (A-Z)';
            }

            // Lowercase
            const elLower = document.getElementById('reqLower');
            if (/[a-z]/.test(pass)) {
                elLower.style.color = '#10b981';
                elLower.innerText = 'At least 1 lowercase letter (a-z)';
            } else {
                elLower.style.color = '#ef4444';
                elLower.innerText = 'At least 1 lowercase letter (a-z)';
            }

            // Number or Special
            const elNumSym = document.getElementById('reqNumSym');
            if (/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(pass)) {
                elNumSym.style.color = '#10b981';
                elNumSym.innerText = 'At least 1 number or special character';
            } else {
                elNumSym.style.color = '#ef4444';
                elNumSym.innerText = 'At least 1 number or special character';
            }

            // Match check
            if (confirm.length > 0) {
                reqMatch.style.display = 'block';
                if (pass === confirm) {
                    reqMatch.style.color = '#10b981';
                    reqMatch.innerText = 'Passwords match';
                } else {
                    reqMatch.style.color = '#ef4444';
                    reqMatch.innerText = 'Passwords do not match';
                }
            } else {
                reqMatch.style.display = 'none';
            }
        }

        function validatePassword(pass) {
            if (pass.length < 8) return "Password must be at least 8 characters long.";
            if (!/[A-Z]/.test(pass)) return "Password must contain at least 1 uppercase letter.";
            if (!/[a-z]/.test(pass)) return "Password must contain at least 1 lowercase letter.";
            if (!/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(pass)) return "Password must contain at least 1 number or special symbol.";
            return null;
        }

        function validateAndSendOtp() {
            if (resendsRemaining <= 0) {
                alert('Maximum OTP resend limit reached. Please try registering later.');
                return;
            }

            const name = document.getElementById('nameInput').value.trim();
            const email = document.getElementById('emailInput').value.trim();
            const password = document.getElementById('passwordInput').value.trim();
            const confirmPassword = document.getElementById('confirmPasswordInput').value.trim();
            const statusDiv = document.getElementById('otpStatus');
            const sendBtn = document.getElementById('sendOtpBtn');

            if (!name || !email || !password || !confirmPassword) {
                alert('Please fill in all details (Full Name, Email, Password, and Confirm Password) before requesting OTP.');
                return;
            }

            const passError = validatePassword(password);
            if (passError) {
                alert(passError);
                return;
            }

            if (password !== confirmPassword) {
                alert('Password and Confirm Password do not match.');
                return;
            }

            sendBtn.disabled = true;
            statusDiv.style.color = '#4338ca';
            statusDiv.innerText = 'Sending OTP...';

            fetch("{{ route('otp.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(res => res.json())
            .then(data => {
                sendBtn.disabled = false;
                if (data.success) {
                    resendsRemaining--;
                    document.getElementById('resendsLeft').innerText = resendsRemaining;
                    document.getElementById('otpGroup').style.display = 'block';
                    document.getElementById('otpInput').required = true;
                    document.getElementById('submitBtn').style.display = 'block';
                    sendBtn.style.display = 'none';
                    statusDiv.style.color = '#047857';
                    statusDiv.innerText = data.message;
                    startCountdown();
                } else {
                    statusDiv.style.color = '#b91c1c';
                    statusDiv.innerText = data.message;
                }
            })
            .catch(err => {
                sendBtn.disabled = false;
                statusDiv.style.color = '#b91c1c';
                statusDiv.innerText = 'Failed to send OTP. Please try again.';
            });
        }
    </script>
</body>
</html>
