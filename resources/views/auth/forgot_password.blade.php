<!DOCTYPE html>
<html lang="{{ session('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password — MakerFest</title>
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
            color: #0F172A; font-family: 'Inter', system-ui, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; padding: 20px; 
        }
        .card { 
            background: var(--card-bg); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-radius: 20px; padding: 48px 40px; width: 100%; max-width: 460px; 
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1); border: 1px solid var(--border-color); 
            transition: transform 0.3s ease;
        }
        .card:hover { transform: translateY(-4px); box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12); }
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
        
        .btn { 
            width: 100%; padding: 14px; background: var(--primary-gradient); 
            border: none; border-radius: 12px; color: #fff; font-weight: 700; font-size: 16px; 
            cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); margin-top: 12px;
        }
        .btn:hover:not(:disabled) { box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3); transform: translateY(-2px); }
        .btn:active:not(:disabled) { transform: translateY(0); }
        .btn:disabled { opacity: 0.7; cursor: not-allowed; }
        
        .btn-secondary { background: #EEF2FF; color: var(--primary); border: 1px solid #C7D2FE; padding: 12px 16px; border-radius: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
        .btn-secondary:hover { background: #E0E7FF; }
        
        .error-msg { background: #FEF2F2; color: #DC2626; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 24px; text-align: center; border: 1px solid #FCA5A5; font-weight: 500; }
        .success-msg { background: #ECFDF5; color: #059669; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 24px; text-align: center; border: 1px solid #6EE7B7; font-weight: 500; }
        
        .link-footer { text-align: center; margin-top: 32px; font-size: 15px; color: #64748B; }
        .link-footer a { color: var(--primary); text-decoration: none; font-weight: 600; transition: color 0.2s ease; }
        .link-footer a:hover { color: #3730A3; }
        
        #forgotPassReqBox { background: rgba(241, 245, 249, 0.7); border: 1px solid #E2E8F0; padding: 12px; border-radius: 8px; margin-top: 10px; font-size: 13px; font-weight: 500; }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">MakerFest <span>Platform</span></div>
        <div class="sub-title">Reset Password via Email OTP</div>

        @if(session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="success-msg">{{ session('success') }}</div>
        @endif

        <!-- Step 1: Send OTP -->
        <div id="step1">
            <div class="form-group">
                <label>Registered Email Address *</label>
                <input type="email" id="emailInput" placeholder="Enter your email" required>
            </div>
            <button type="button" class="btn" id="sendOtpBtn" onclick="sendForgotOtp()">Send OTP</button>
            <div id="statusDiv" style="font-size: 13px; margin-top: 8px; text-align: center;"></div>
        </div>

        <!-- Step 2: Reset Password with OTP -->
        <form action="{{ route('password.forgot.reset') }}" method="POST" id="resetForm" style="display: none;">
            @csrf
            <input type="hidden" name="email" id="formEmail">
            
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="margin-bottom: 0;">OTP Code *</label>
                    <button type="button" onclick="sendForgotOtp(true)" style="background: none; border: none; color: #6b38fb; font-size: 12px; font-weight: 700; cursor: pointer; text-decoration: underline;">Resend OTP</button>
                </div>
                <input type="text" name="otp" placeholder="Enter 6-digit OTP code" required>
            </div>
            
            <div class="form-group">
                <label>New Password *</label>
                <input type="password" id="newPass" name="new_password" placeholder="Enter new password" oninput="checkForgotPassReq()" required>
                
                <div id="forgotPassReqBox" style="margin-top: 8px; font-size: 12px; background: #f9fafb; padding: 10px; border-radius: 6px; border: 1px solid #e5e7eb;">
                    <div id="fReqMinLen" style="color: #ef4444; transition: color 0.2s;">✖ At least 8 characters</div>
                    <div id="fReqUpper" style="color: #ef4444; transition: color 0.2s;">✖ At least 1 uppercase letter (A-Z)</div>
                    <div id="fReqLower" style="color: #ef4444; transition: color 0.2s;">✖ At least 1 lowercase letter (a-z)</div>
                    <div id="fReqNumSym" style="color: #ef4444; transition: color 0.2s;">✖ At least 1 number or special character</div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Confirm New Password *</label>
                <input type="password" id="newPassConfirm" name="new_password_confirmation" placeholder="Confirm new password" oninput="checkForgotPassReq()" required>
                <div id="fReqMatch" style="font-size: 12px; margin-top: 4px; color: #ef4444; display: none;">✖ Passwords do not match</div>
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>

        <div class="link-footer">
            @if(session()->has('user_id'))
                <a href="{{ route('profile') }}" style="color: #6b7280; font-weight: 500;">Cancel & Return to Profile</a>
            @else
                <a href="{{ route('login') }}" style="color: #6b7280; font-weight: 500;">Cancel & Return to Login</a>
            @endif
        </div>
    </div>

    <script>
        function checkForgotPassReq() {
            const pass = document.getElementById('newPass').value;
            const confirm = document.getElementById('newPassConfirm').value;
            const reqMatch = document.getElementById('fReqMatch');

            document.getElementById('fReqMinLen').style.color = (pass.length >= 8) ? '#10b981' : '#ef4444';
            document.getElementById('fReqMinLen').innerText = (pass.length >= 8) ? '✔ At least 8 characters' : '✖ At least 8 characters';

            document.getElementById('fReqUpper').style.color = (/[A-Z]/.test(pass)) ? '#10b981' : '#ef4444';
            document.getElementById('fReqUpper').innerText = (/[A-Z]/.test(pass)) ? '✔ At least 1 uppercase letter (A-Z)' : '✖ At least 1 uppercase letter (A-Z)';

            document.getElementById('fReqLower').style.color = (/[a-z]/.test(pass)) ? '#10b981' : '#ef4444';
            document.getElementById('fReqLower').innerText = (/[a-z]/.test(pass)) ? '✔ At least 1 lowercase letter (a-z)' : '✖ At least 1 lowercase letter (a-z)';

            document.getElementById('fReqNumSym').style.color = (/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(pass)) ? '#10b981' : '#ef4444';
            document.getElementById('fReqNumSym').innerText = (/[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(pass)) ? '✔ At least 1 number or special character' : '✖ At least 1 number or special character';

            if (confirm.length > 0) {
                reqMatch.style.display = 'block';
                if (pass === confirm) {
                    reqMatch.style.color = '#10b981';
                    reqMatch.innerText = '✔ Passwords match';
                } else {
                    reqMatch.style.color = '#ef4444';
                    reqMatch.innerText = '✖ Passwords do not match';
                }
            } else {
                reqMatch.style.display = 'none';
            }
        }

        function sendForgotOtp(isResend = false) {
            const email = isResend ? document.getElementById('formEmail').value : document.getElementById('emailInput').value;
            const btn = document.getElementById('sendOtpBtn');
            const statusDiv = document.getElementById('statusDiv');

            if (!email) {
                alert('Please enter your registered email address.');
                return;
            }

            if (btn) {
                btn.disabled = true;
                btn.innerText = isResend ? 'Resending OTP...' : 'Sending OTP...';
            }

            fetch("{{ route('password.forgot.sendOtp') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(res => res.json())
            .then(data => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerText = 'Send OTP';
                }
                if (data.success) {
                    if (!isResend) {
                        document.getElementById('step1').style.display = 'none';
                        document.getElementById('formEmail').value = email;
                        document.getElementById('resetForm').style.display = 'block';
                    } else {
                        alert('A new OTP has been sent to your email.');
                    }
                } else {
                    if (statusDiv) {
                        statusDiv.style.color = '#b91c1c';
                        statusDiv.innerText = data.message;
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(err => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerText = 'Send OTP';
                }
                alert('Failed to send OTP. Try again.');
            });
        }
    </script>
</body>
</html>
