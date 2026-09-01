<!DOCTYPE html>
<html lang="{{ session('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <title>My Profile — {{ __('messages.brand_name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6b38fb;
            --primary-hover: #5422e1;
            --primary-light: #f0ebff;
            --bg-main: #f8f9fa;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: var(--bg-main); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }

        .top-navbar {
            background: #fff; border-bottom: 1px solid var(--border); padding: 12px 24px;
            display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100;
        }
        .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; font-weight: 700; color: var(--primary); font-size: 20px; }
        .brand-icon { background: var(--primary); color: #fff; width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; }
        
        .controls { display: flex; align-items: center; gap: 16px; }
        .lang-group { display: flex; align-items: center; gap: 4px; background: var(--primary-light); padding: 4px 8px; border-radius: 8px; border: 1px solid rgba(107,56,251,0.2); }
        .lang-btn { text-decoration: none; padding: 4px 10px; font-size: 13px; font-weight: 700; color: var(--primary); border-radius: 6px; }
        .lang-btn.active { background: var(--primary); color: #fff; }

        .hbm-wrapper { position: relative; }
        .hbm-btn {
            background: #f3f4f6; border: none; width: 40px; height: 40px; border-radius: 8px; cursor: pointer;
            display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 4px;
        }
        .hbm-btn span { display: block; width: 20px; height: 2px; background: #374151; border-radius: 2px; }
        .hbm-dropdown {
            position: absolute; top: calc(100% + 8px); right: 0; width: 220px; background: #fff;
            border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: none; flex-direction: column; overflow: hidden; z-index: 200;
        }
        .hbm-dropdown.active { display: flex; }
        .hbm-item {
            padding: 12px 18px; color: #374151; text-decoration: none; font-weight: 500; font-size: 14px;
            border-bottom: 1px solid #f3f4f6; background: none; text-align: left; cursor: pointer;
        }
        .hbm-item:last-child { border-bottom: none; }
        .hbm-item:hover { background: #f9fafb; color: var(--primary); }
        .hbm-item.logout { color: var(--danger); font-weight: 600; }

        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; width: 100%; }
        .card { background: #fff; border-radius: 16px; border: 1px solid var(--border); padding: 32px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); margin-bottom: 24px; }
        
        .form-label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; color: #374151; }
        .form-input { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border); font-size: 15px; margin-bottom: 16px; }
        .form-input[readonly] { background: #f9fafb; color: #6b7280; }
        .btn { display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; gap: 8px; }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-secondary { background: #f3f4f6; color: #374151; }
    </style>
</head>
<body>

    <header class="top-navbar">
        <a href="/" class="brand">
            <div class="brand-icon">M</div>
            <span>{{ __('messages.brand_name') }}</span>
        </a>

        <div class="controls">
            <div class="lang-group">
                <a href="{{ route('setLocale', 'en') }}" class="lang-btn {{ App::getLocale() === 'en' ? 'active' : '' }}">English</a>
                <a href="{{ route('setLocale', 'gu') }}" class="lang-btn {{ App::getLocale() === 'gu' ? 'active' : '' }}">ગુજરાતી</a>
            </div>

            <div class="hbm-wrapper">
                <button class="hbm-btn" onclick="toggleDropdown(event)">
                    <span></span><span></span><span></span>
                </button>
                <div class="hbm-dropdown" id="hbmDropdown">
                    <a href="{{ route('profile') }}" class="hbm-item">Profile</a>
                    <a href="{{ route('logout') }}" class="hbm-item logout">{{ __('messages.logout') }}</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <a href="/" style="text-decoration: none; color: var(--primary); font-weight: 600; display: inline-block; margin-bottom: 20px;">&larr; Back to Dashboard</a>

        @if(session('success'))
        <div style="background: #d1fae5; color: #047857; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div style="background: #fee2e2; color: #b91c1c; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
            {{ session('error') }}
        </div>
        @endif

        <div class="card">
            <h2 style="margin-bottom: 24px;">User Profile</h2>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label class="form-label" style="margin-bottom: 0;">Full Name</label>
                    <button type="button" class="btn btn-secondary" id="editNameBtn" style="padding: 4px 12px; font-size: 13px;" onclick="enableNameEdit()">Edit Name</button>
                </div>
                <input type="text" id="nameInput" name="name" class="form-input" value="{{ $currentUser->name ?? $userName }}" readonly required>

                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" value="{{ $currentUser->email ?? '' }}" readonly>

                <div id="saveActions" style="display: none; justify-content: flex-end; gap: 12px; margin-top: 10px;">
                    <button type="button" class="btn btn-secondary" onclick="cancelNameEdit()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Name</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="margin-bottom: 0;">Security & Password</h2>
                <button type="button" class="btn btn-secondary" id="changePassBtn" style="padding: 4px 12px; font-size: 13px;" onclick="enablePasswordChange()">Change Password</button>
            </div>
            
            <form action="{{ route('settings.password') }}" method="POST" id="passwordForm" style="display: none;">
                @csrf
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-input" required>

                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-input" required>

                <label class="form-label">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-input" required>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px;">
                    <a href="{{ route('password.forgot') }}" style="font-size: 13px; color: #6b38fb; text-decoration: none; font-weight: 600;">Forgot Password?</a>
                    <div style="display: flex; gap: 12px;">
                        <button type="button" class="btn btn-secondary" onclick="cancelPasswordChange()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDropdown(event) {
            event.stopPropagation();
            document.getElementById('hbmDropdown').classList.toggle('active');
        }
        document.addEventListener('click', function() {
            const dropdown = document.getElementById('hbmDropdown');
            if (dropdown && dropdown.classList.contains('active')) {
                dropdown.classList.remove('active');
            }
        });

        const initialName = "{{ $currentUser->name ?? $userName }}";
        function enableNameEdit() {
            const input = document.getElementById('nameInput');
            input.removeAttribute('readonly');
            input.focus();
            input.style.background = "#fff";
            document.getElementById('saveActions').style.display = 'flex';
            document.getElementById('editNameBtn').style.display = 'none';
        }
        function cancelNameEdit() {
            const input = document.getElementById('nameInput');
            input.value = initialName;
            input.setAttribute('readonly', 'readonly');
            input.style.background = "#f9fafb";
            document.getElementById('saveActions').style.display = 'none';
            document.getElementById('editNameBtn').style.display = 'inline-flex';
        }
        function enablePasswordChange() {
            document.getElementById('passwordForm').style.display = 'block';
            document.getElementById('changePassBtn').style.display = 'none';
        }
        function cancelPasswordChange() {
            document.getElementById('passwordForm').reset();
            document.getElementById('passwordForm').style.display = 'none';
            document.getElementById('changePassBtn').style.display = 'inline-flex';
        }
    </script>
</body>
</html>
