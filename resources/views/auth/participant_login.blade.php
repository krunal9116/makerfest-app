<!DOCTYPE html>
<html lang="{{ session('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <title>MakerFest Login Portal</title>
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
            color: #0F172A; font-family: 'Inter', system-ui, sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; 
        }
        .login-card { 
            background: var(--card-bg); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-radius: 20px; padding: 48px 40px; width: 100%; max-width: 440px; 
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1); border: 1px solid var(--border-color); 
            transition: transform 0.3s ease;
        }
        .login-card:hover { transform: translateY(-4px); box-shadow: 0 15px 50px -10px rgba(0,0,0,0.12); }
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
            cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }
        .btn:hover { box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3); transform: translateY(-2px); }
        .btn:active { transform: translateY(0); }
        
        .link-footer { text-align: center; margin-top: 32px; font-size: 15px; color: #64748B; }
        .link-footer a { color: var(--primary); text-decoration: none; font-weight: 600; transition: color 0.2s ease; }
        .link-footer a:hover { color: #3730A3; }
        
        .error-msg { background: #FEF2F2; color: #DC2626; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 24px; text-align: center; border: 1px solid #FCA5A5; font-weight: 500; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">MakerFest <span>Platform</span></div>
        <div class="sub-title">Sign in to your account</div>
        
        @if(session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your registered email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div style="position: relative;">
                    <input type="password" id="userPassInput" name="password" placeholder="Enter password" required style="padding-right: 40px;">
                    <button type="button" onclick="togglePass('userPassInput', this)" style="position: absolute; right: 12px; top: 12px; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 16px;">👁</button>
                </div>
                <div style="text-align: right; margin-top: 6px;">
                    <a href="{{ route('password.forgot') }}" style="font-size: 13px; color: #6b38fb; text-decoration: none; font-weight: 600;">Forgot Password?</a>
                </div>
            </div>
            <button type="submit" class="btn">Sign In</button>
        </form>

        <div class="link-footer">
            New Maker? <a href="{{ route('maker.register') }}">Create an Account</a>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>
