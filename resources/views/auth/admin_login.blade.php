<!DOCTYPE html>
<html lang="{{ session('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <title>Admin Login — MakerFest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4F46E5;
            --primary-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            --bg-color: #0F172A;
            --card-bg: rgba(30, 41, 59, 0.7);
            --border-color: rgba(255, 255, 255, 0.1);
        }
        body { 
            background: var(--bg-color); 
            background-image: radial-gradient(circle at top right, rgba(79, 70, 229, 0.15), transparent 40%),
                              radial-gradient(circle at bottom left, rgba(124, 58, 237, 0.15), transparent 40%);
            color: #fff; font-family: 'Inter', system-ui, sans-serif; height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; 
        }
        .login-card { 
            background: var(--card-bg); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border-radius: 20px; padding: 48px 40px; width: 100%; max-width: 420px; 
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); border: 1px solid var(--border-color); 
            transition: transform 0.3s ease;
        }
        .login-card:hover { transform: translateY(-4px); }
        .brand { text-align: center; font-size: 26px; font-weight: 800; color: #fff; margin-bottom: 8px; letter-spacing: -0.5px; }
        .brand span { background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .subtitle { text-align: center; color: #94A3B8; font-size: 14px; margin-bottom: 32px; font-weight: 500; }
        
        .form-group { margin-bottom: 24px; }
        label { display: block; font-size: 13px; font-weight: 600; color: #CBD5E1; margin-bottom: 8px; }
        input { 
            width: 100%; padding: 14px 16px; border-radius: 12px; 
            border: 1px solid rgba(255,255,255,0.1); background: rgba(15,23,42,0.6); 
            color: #fff; font-size: 15px; box-sizing: border-box; transition: all 0.2s ease;
        }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25); background: rgba(15,23,42,0.8); }
        input::-ms-reveal, input::-ms-clear { display: none; }
        
        .btn { 
            width: 100%; padding: 14px; background: var(--primary-gradient); 
            border: none; border-radius: 12px; color: #fff; font-weight: 700; font-size: 15px; 
            cursor: pointer; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        .btn:hover { box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4); transform: translateY(-2px); }
        .btn:active { transform: translateY(0); }
        
        .error-msg { background: rgba(239, 68, 68, 0.1); color: #F87171; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 24px; text-align: center; border: 1px solid rgba(239, 68, 68, 0.2); font-weight: 500; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand">MakerFest <span>Admin</span></div>
        <div class="subtitle">Secure Portal Access</div>
        @if(session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif
        <form action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Admin Email Address</label>
                <input type="email" name="email" value="admin@gmail.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div style="position: relative;">
                    <input type="password" id="adminPassInput" name="password" value="Admin@123" required style="padding-right: 40px;">
                    <button type="button" onclick="togglePass('adminPassInput', this)" style="position: absolute; right: 12px; top: 12px; background: none; border: none; cursor: pointer; color: #9ca3af; font-size: 16px;">👁</button>
                </div>
            </div>
            <button type="submit" class="btn">Sign In to Admin Panel</button>
        </form>
    </div>

    <script>
        function togglePass(id, btn) {
            const inp = document.getElementById(id);
            if (inp.type === 'password') {
                inp.type = 'text';
                btn.style.color = '#6b38fb';
            } else {
                inp.type = 'password';
                btn.style.color = '#9ca3af';
            }
        }
    </script>
</body>
</html>
