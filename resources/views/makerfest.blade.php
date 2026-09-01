<!DOCTYPE html>
<html lang="{{ session('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('messages.brand_name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            /* Premium Color Palette */
            --primary: #4F46E5; /* Indigo 600 */
            --primary-hover: #4338CA;
            --primary-light: #EEF2FF;
            --primary-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            --sidebar-bg: #0F172A; /* Slate 900 */
            --bg-main: #F8FAFC; /* Slate 50 */
            --card-bg: rgba(255, 255, 255, 0.85); /* Glassmorphism base */
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: rgba(226, 232, 240, 0.8);
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            
            /* Shadows & Radii */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-glass: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
            --radius-md: 12px;
            --radius-lg: 20px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', system-ui, sans-serif; }
        input::-ms-reveal, input::-ms-clear { display: none; }
        input::-webkit-contacts-auto-fill-button, input::-webkit-credentials-auto-fill-button { visibility: hidden; pointer-events: none; position: absolute; right: 0; }
        body { background: var(--bg-main); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; -webkit-font-smoothing: antialiased; }

        /* Glassmorphic Navbar */
        .top-navbar {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border); 
            padding: 16px 32px;
            display: flex; justify-content: space-between; align-items: center; 
            position: sticky; top: 0; z-index: 100;
            box-shadow: var(--shadow-sm);
        }
        .brand { display: flex; align-items: center; gap: 12px; text-decoration: none; font-weight: 800; color: var(--primary); font-size: 22px; letter-spacing: -0.5px; }
        .brand-icon { background: var(--primary-gradient); color: #fff; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        
        .controls { display: flex; align-items: center; gap: 20px; }

        .lang-group { display: flex; align-items: center; gap: 4px; background: #fff; padding: 4px; border-radius: 10px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); }
        .lang-btn { text-decoration: none; padding: 6px 12px; font-size: 13px; font-weight: 600; color: var(--text-muted); border-radius: 8px; transition: all 0.2s ease; }
        .lang-btn:hover { color: var(--text-main); background: var(--bg-main); }
        .lang-btn.active { background: var(--primary); color: #fff; box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25); }

        .hbm-btn {
            background: #fff; border: 1px solid var(--border); width: 44px; height: 44px; border-radius: 12px; cursor: pointer;
            display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 5px; transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }
        .hbm-btn:hover { background: var(--bg-main); transform: translateY(-1px); box-shadow: var(--shadow-md); }
        .hbm-btn span { display: block; width: 22px; height: 2px; background: var(--text-main); border-radius: 2px; transition: all 0.2s ease; }

        /* HBM Dropdown Menu (Glassmorphism) */
        .hbm-wrapper { position: relative; }
        .hbm-dropdown {
            position: absolute; top: calc(100% + 12px); right: 0; width: 240px; 
            background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px);
            border-radius: 16px; border: 1px solid var(--border); box-shadow: var(--shadow-lg);
            display: none; flex-direction: column; overflow: hidden; z-index: 200;
            padding: 8px;
        }
        .hbm-dropdown.active { display: flex; animation: slideDown 0.2s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        
        .hbm-item {
            padding: 12px 16px; color: var(--text-main); text-decoration: none; font-weight: 500; font-size: 14px;
            background: transparent; border: none; text-align: left; cursor: pointer; border-radius: 8px;
            transition: all 0.15s ease; margin-bottom: 2px;
        }
        .hbm-item:hover { background: var(--primary-light); color: var(--primary); transform: translateX(4px); }
        .hbm-item.logout { color: var(--danger); margin-top: 8px; border-top: 1px solid var(--border); border-radius: 0 0 8px 8px; }
        .hbm-item.logout:hover { background: #FEF2F2; color: var(--danger); }

        .app-body { flex: 1; display: flex; }
        
        /* Modern Sidebar */
        .sidebar { width: 280px; background: var(--sidebar-bg); color: #fff; padding: 32px 0; display: flex; flex-direction: column; box-shadow: 4px 0 24px rgba(0,0,0,0.05); z-index: 50; }
        .sidebar-title { font-size: 12px; text-transform: uppercase; color: #64748B; padding: 0 28px 16px; font-weight: 800; letter-spacing: 1px; }
        .nav-item { 
            display: flex; align-items: center; gap: 12px; padding: 14px 28px; color: #94A3B8; text-decoration: none; 
            font-size: 15px; font-weight: 500; transition: all 0.2s ease; border-left: 4px solid transparent;
        }
        .nav-item:hover { color: #fff; background: rgba(255,255,255,0.03); }
        .nav-item.active { background: rgba(255,255,255,0.06); color: #fff; border-left: 4px solid var(--primary); font-weight: 600; }

        .main-content { flex: 1; padding: 40px; overflow-y: auto; background: var(--bg-main); }
        
        /* Glassmorphic Cards */
        .card { 
            background: var(--card-bg); backdrop-filter: blur(10px); 
            border-radius: var(--radius-lg); border: 1px solid var(--border); 
            padding: 32px; box-shadow: var(--shadow-glass); margin-bottom: 32px; 
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px; }
        .stat-card { 
            background: #fff; border-radius: var(--radius-md); border: 1px solid var(--border); 
            padding: 24px; box-shadow: var(--shadow-sm); transition: all 0.3s ease; position: relative; overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
        .stat-num { font-size: 32px; font-weight: 800; color: var(--text-main); margin-top: 12px; letter-spacing: -1px; }
        
        .data-table { width: 100%; border-collapse: separate; border-spacing: 0; text-align: left; }
        .data-table th { background: rgba(248, 250, 252, 0.8); padding: 16px 20px; font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--border); }
        .data-table td { padding: 20px; border-bottom: 1px solid var(--border); font-size: 15px; color: var(--text-main); vertical-align: middle; }
        .data-table tr:hover td { background: rgba(248, 250, 252, 0.5); }

        .badge { display: inline-flex; padding: 6px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; letter-spacing: 0.3px; }
        .badge-Draft { background: #F1F5F9; color: #475569; }
        .badge-Submitted { background: #E0F2FE; color: #0284C7; }
        .badge-Approved { background: #D1FAE5; color: #059669; }
        .badge-Revision_Requested { background: #FEF3C7; color: #D97706; }

        .maker-container { max-width: 1000px; margin: 0 auto; }
        
        /* Modern Forms */
        .form-label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; color: var(--text-main); }
        .form-input, .form-select, .form-textarea { 
            width: 100%; padding: 14px 16px; border-radius: var(--radius-md); 
            border: 1px solid var(--border); font-size: 15px; margin-bottom: 20px; 
            background: #fff; transition: all 0.2s ease; box-shadow: var(--shadow-sm);
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { 
            outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); 
        }
        
        /* Premium Buttons */
        .btn { 
            display: inline-flex; align-items: center; justify-content: center; 
            padding: 12px 24px; border-radius: var(--radius-md); font-weight: 600; font-size: 15px;
            cursor: pointer; border: none; text-decoration: none; gap: 8px; transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }
        .btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .btn:active { transform: translateY(0); }
        .btn-primary { background: var(--primary-gradient); color: #fff; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25); }
        .btn-primary:hover { box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35); }
        .btn-success { background: var(--success); color: #fff; }
        .btn-warning { background: var(--warning); color: #fff; }
        .btn-outline { background: transparent; border: 2px solid var(--primary); color: var(--primary); box-shadow: none; }
        .btn-outline:hover { background: var(--primary-light); }

        /* Modal styling */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 300; display: none; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s ease; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-card { 
            background: #fff; border-radius: var(--radius-lg); padding: 40px; width: 100%; max-width: 520px; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            max-height: 90vh; overflow-y: auto;
        }
        .modal-overlay.active .modal-card { transform: translateY(0); }
    </style>
</head>
<body>

    <header class="top-navbar">
        <a href="/" class="brand">
            <span>{{ __('messages.brand_name') }}</span>
        </a>

        <div class="controls">
            <!-- Language Toggle -->
            <div class="lang-group">
                <a href="{{ route('setLocale', 'en') }}" class="lang-btn {{ App::getLocale() === 'en' ? 'active' : '' }}">English</a>
                <a href="{{ route('setLocale', 'gu') }}" class="lang-btn {{ App::getLocale() === 'gu' ? 'active' : '' }}">ગુજરાતી</a>
            </div>

            <!-- Hamburger Dropdown Container -->
            <div class="hbm-wrapper">
                <button class="hbm-btn" id="hbmBtn" onclick="toggleDropdown(event)">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div class="hbm-dropdown" id="hbmDropdown">
                    <a href="{{ route('profile') }}" class="hbm-item">Profile</a>
                    @if($role === 'admin')
                    <button type="button" class="hbm-item" onclick="toggleAddStaffModal()">+ Add Judge / Volunteer</button>
                    @endif
                    <a href="{{ route('logout') }}" class="hbm-item logout">{{ __('messages.logout') }}</a>
                </div>
            </div>
        </div>
    </header>

    <div class="app-body">

        @if($role === 'admin')
        <aside class="sidebar">
            <div class="sidebar-title">{{ __('messages.admin_management') }}</div>
            <a href="javascript:void(0)" class="nav-item active" onclick="switchAdminTab('tabDashboard')" id="sideNavDashboard">{{ __('messages.nav_dashboard') }}</a>
            <a href="javascript:void(0)" class="nav-item" onclick="switchAdminTab('tabEvents')" id="sideNavEvents">{{ __('messages.nav_events') }}</a>
            <a href="javascript:void(0)" class="nav-item" onclick="switchAdminTab('tabProjects')" id="sideNavProjects">All Projects ({{ count($projects) }})</a>
            <a href="javascript:void(0)" class="nav-item" onclick="switchAdminTab('tabUsers')" id="sideNavUsers">{{ __('messages.nav_users') }}</a>
            <a href="javascript:void(0)" class="nav-item" onclick="switchAdminTab('tabJudgeAssign')" id="sideNavJudge">{{ __('messages.nav_judge_assign') }}</a>
            <a href="javascript:void(0)" class="nav-item" onclick="switchAdminTab('tabTaskAssign')" id="sideNavTask">{{ __('messages.nav_task_assign') }}</a>
            <a href="javascript:void(0)" class="nav-item" onclick="switchAdminTab('tabBroadcast')" id="sideNavBroadcast">Broadcast Email</a>
            <a href="javascript:void(0)" class="nav-item" onclick="switchAdminTab('tabSettings')" id="sideNavSettings">{{ __('messages.nav_settings') }}</a>
        </aside>
        @endif

        @if($role === 'judge')
        <aside class="sidebar">
            <div class="sidebar-title">Judge Navigation</div>
            <a href="javascript:void(0)" class="nav-item active" onclick="switchJudgeTab('tabAssignedProjects')" id="sideJudgeAssigned">Assigned Projects ({{ count($judgeAssignedProjects) }})</a>
            <a href="javascript:void(0)" class="nav-item" onclick="switchJudgeTab('tabAllProjects')" id="sideJudgeAll">All Event Projects ({{ count($projects) }})</a>
        </aside>
        @endif

        <main class="main-content">

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
            @if($errors->any())
            <div style="background: #fee2e2; color: #b91c1c; padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($role === 'admin')
            <!-- ADMIN ANALYTICS GRAPHS (DASHBOARD TAB ONLY) -->
            <div id="tabDashboard" class="admin-tab-content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <div>
                        <h2>{{ __('messages.admin_dashboard_title') }}</h2>
                        <p style="color: var(--text-muted);">{{ __('messages.admin_dashboard_sub') }}</p>
                    </div>
                </div>
                <div class="stats-grid">
                    <div class="stat-card">
                        <span style="color: var(--text-muted); font-size: 13px; font-weight: 600;">{{ __('messages.total_projects') }}</span>
                        <div class="stat-num">{{ count($projects) }}</div>
                    </div>
                    <div class="stat-card">
                        <span style="color: var(--text-muted); font-size: 13px; font-weight: 600;">{{ __('messages.active_judges') }}</span>
                        <div class="stat-num">{{ $judgesCount ?? count($judges) }}</div>
                    </div>
                    <div class="stat-card">
                        <span style="color: var(--text-muted); font-size: 13px; font-weight: 600;">{{ __('messages.pending_reviews') }}</span>
                        <div class="stat-num">{{ $pendingReviewsCount ?? 0 }}</div>
                    </div>
                    <div class="stat-card" style="background: var(--primary); color: #fff;">
                        <span style="opacity: 0.9; font-size: 13px; font-weight: 600;">Active Edition</span>
                        <div class="stat-num" style="color: #fff; font-size: 18px; margin-top: 4px;">{{ $activeEvent->title ?? 'Vadodara 2026' }}</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 24px;">
                    <div class="card">
                        <h4 style="margin-bottom: 16px; color: #111827;">Platform Users & Staff Breakdown</h4>
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="chartUsersComparison"></canvas>
                        </div>
                    </div>
                    <div class="card">
                        <h4 style="margin-bottom: 16px; color: #111827;">Monthly Project Submissions Trend</h4>
                        <div style="position: relative; height: 260px; width: 100%;">
                            <canvas id="chartProjectsGrowth"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: EVENT MANAGEMENT -->
            <div id="tabEvents" class="admin-tab-content" style="display: none;">
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3>🎉 Event Management</h3>
                        <button class="btn btn-primary" onclick="toggleCreateEventModal()">+ Create New Event</button>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Event Title</th>
                                <th>Location</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allEvents as $ev)
                            <tr>
                                <td style="font-weight: 700;">{{ $ev->title ?? $ev->name ?? 'MakerFest Edition' }}</td>
                                <td>{{ $ev->location ?? 'Vadodara' }}</td>
                                <td>{{ $ev->registration_open ?? $ev->start_date ?? 'N/A' }}</td>
                                <td>{{ $ev->submission_deadline ?? $ev->end_date ?? 'N/A' }}</td>
                                <td>
                                    @if($ev->is_active)
                                        <span class="badge badge-Approved">Active Edition</span>
                                    @else
                                        <span class="badge badge-Draft">Completed / Upcoming</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: ALL PROJECTS -->
            <div id="tabProjects" class="admin-tab-content" style="display: none;">
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                        <h3>All Projects</h3>
                        <input type="text" id="projectSearchInput" onkeyup="filterProjectList()" placeholder="Search by ID, title, leader, category..." class="form-input" style="width: 300px; margin: 0;">
                    </div>
                    <table class="data-table" id="adminProjectsTable">
                        <thead>
                            <tr>
                                <th>Project ID</th>
                                <th>Project Name</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $p)
                            <tr class="project-row">
                                <td style="font-weight: 700; color: var(--primary);">{{ $p->project_code }}</td>
                                <td>
                                    <strong>{{ $p->title }}</strong><br>
                                    <span style="font-size: 12px; color: var(--text-muted);">{{ $p->leader_name }} <span style="background: #e0e7ff; color: #4338ca; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; margin-left: 4px;">{{ __('messages.leader_badge') }}</span></span>
                                </td>
                                <td>{{ $p->category_name ?? 'General Tech' }}</td>
                                <td><span class="badge badge-{{ $p->status }}">{{ __('messages.status_' . strtolower($p->status)) ?? $p->status }}</span></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; border: 1px solid var(--primary); color: var(--primary); background: transparent; border-radius: 6px; cursor: pointer;" onclick='openProjectDetailsModal(@json($p))'>
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 4: MANAGE USERS -->
            <div id="tabUsers" class="admin-tab-content" style="display: none;">
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                        <h3>👥 Manage Users & Staff</h3>
                        
                        <div style="display: flex; gap: 12px; align-items: center;">
                            <input type="text" id="userSearchInput" onkeyup="filterUserList()" placeholder="🔍 Search by name or email..." class="form-input" style="width: 240px; margin: 0;">
                            <div class="lang-group">
                                <button type="button" class="lang-btn active" id="userFilterAll" onclick="filterUserRole('all')">All</button>
                                <button type="button" class="lang-btn" id="userFilterMaker" onclick="filterUserRole('maker')">Makers</button>
                                <button type="button" class="lang-btn" id="userFilterJudge" onclick="filterUserRole('judge')">Judges</button>
                                <button type="button" class="lang-btn" id="userFilterVolunteer" onclick="filterUserRole('volunteer')">Volunteers</button>
                            </div>
                        </div>
                    </div>

                    <table class="data-table" id="usersTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allUsers as $u)
                            <tr class="user-row" data-role="{{ $u->role }}">
                                <td><strong>{{ $u->name }}</strong></td>
                                <td>{{ $u->email }}</td>
                                <td><span style="text-transform: capitalize; background: #f3f4f6; padding: 4px 8px; border-radius: 4px; font-weight: 600;">{{ $u->role }}</span></td>
                                <td><span class="badge badge-Approved">Active</span></td>
                                 <td style="text-align: center;">
                                    @if($u->role !== 'admin')
                                        <button type="button" class="btn" style="background: #fee2e2; color: #b91c1c; padding: 6px 12px; font-size: 12px; border: none; border-radius: 6px; cursor: pointer;" onclick="openRemoveUserModal('{{ $u->id }}', '{{ addslashes($u->name) }}')">Remove</button>
                                    @else
                                        <span style="color: var(--text-muted); font-size: 12px;">Primary Admin</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 5: JUDGE ASSIGNMENTS -->
            <div id="tabJudgeAssign" class="admin-tab-content" style="display: none;">
                <div class="card" style="margin-bottom: 24px;">
                    <h3>⚖️ Assign Judge to Project</h3>
                    <form action="{{ route('admin.assignJudge') }}" method="POST" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: flex-end; margin-top: 16px;">
                        @csrf
                        <div>
                            <label class="form-label">Select Project *</label>
                            <select name="project_id" class="form-select" required>
                                @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->project_code }} — {{ $p->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Select Judge *</label>
                            <select name="judge_id" class="form-select" required>
                                @foreach($judges as $j)
                                <option value="{{ $j->id }}">{{ $j->name }} ({{ $j->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="height: 42px;">Assign Judge</button>
                    </form>
                </div>

                <div class="card">
                    <h4>Current Judge Assignments</h4>
                    <table class="data-table" style="margin-top: 12px;">
                        <thead>
                            <tr>
                                <th>Project Code</th>
                                <th>Project Title</th>
                                <th>Assigned Judge</th>
                                <th>Assigned Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($judgeAssignments as $ja)
                            <tr>
                                <td style="font-weight: 700; color: var(--primary);">{{ $ja->project_code }}</td>
                                <td>{{ $ja->project_title }}</td>
                                <td><strong>{{ $ja->judge_name }}</strong></td>
                                <td>{{ date('d M Y', strtotime($ja->assigned_at)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 6: TASK ASSIGNMENTS (VOLUNTEERS) -->
            <div id="tabTaskAssign" class="admin-tab-content" style="display: none;">
                <div class="card" style="margin-bottom: 24px;">
                    <h3>Assign Task to Volunteer</h3>
                    <form action="{{ route('admin.assignTask') }}" method="POST" style="margin-top: 16px;">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                            <div>
                                <label class="form-label">Select Volunteer *</label>
                                <select name="volunteer_id" class="form-select" required>
                                    @foreach($volunteers as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }} ({{ $v->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Task Title *</label>
                                <input type="text" name="title" class="form-input" placeholder="e.g. Registration Desk Management" required>
                            </div>
                        </div>
                        <label class="form-label" style="margin-top: 12px;">Task Description *</label>
                        <textarea name="description" class="form-textarea" rows="2" placeholder="Provide instructions for the volunteer..." required></textarea>
                        <button type="submit" class="btn btn-primary" style="margin-top: 12px;">Assign Task</button>
                    </form>
                </div>
            </div>

            <!-- TAB 7: BROADCAST EMAIL -->
            <div id="tabBroadcast" class="admin-tab-content" style="display: none;">
                <div class="card">
                    <h3>Broadcast Email Announcement</h3>
                    <p style="color: var(--text-muted); margin-bottom: 16px;">Send official announcement emails directly to registered users in bulk.</p>
                    
                    <form action="{{ route('admin.broadcastMail') }}" method="POST">
                        @csrf
                        <label class="form-label">Target Audience Filter *</label>
                        <select name="target_role" class="form-select">
                            <option value="all">All Registered Users (Makers, Judges, Volunteers)</option>
                            <option value="maker">Only Makers</option>
                            <option value="judge">Only Judges</option>
                            <option value="volunteer">Only Volunteers</option>
                        </select>

                        <label class="form-label">Email Subject *</label>
                        <input type="text" name="subject" class="form-input" placeholder="e.g. Important Announcement: MakerFest Venue & Schedule Update" required>

                        <label class="form-label">Announcement Content / Message *</label>
                        <textarea name="content" class="form-textarea" rows="6" placeholder="Enter your detailed message content here..." required></textarea>

                        <button type="submit" class="btn btn-primary" style="margin-top: 16px;">Send Broadcast Email</button>
                    </form>
                </div>
            </div>

            <!-- TAB 8: ADMIN SETTINGS -->
            <div id="tabSettings" class="admin-tab-content" style="display: none;">
                <div class="card" style="max-width: 500px;">
                    <h3>Admin Account Password Settings</h3>
                    <p style="color: var(--text-muted); margin-bottom: 20px;">Update your administrator login password securely.</p>
                    
                    <form action="{{ route('settings.password') }}" method="POST">
                        @csrf
                        <label class="form-label">Current Password *</label>
                        <div style="position: relative;">
                            <input type="password" id="adminCurrentPass" name="current_password" class="form-input" required>
                            <button type="button" onclick="togglePasswordVisibility('adminCurrentPass', this)" style="position: absolute; right: 12px; top: 12px; background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 16px;">👁</button>
                        </div>

                        <label class="form-label" style="margin-top: 12px;">New Password *</label>
                        <div style="position: relative;">
                            <input type="password" id="adminNewPass" name="new_password" class="form-input" required>
                            <button type="button" onclick="togglePasswordVisibility('adminNewPass', this)" style="position: absolute; right: 12px; top: 12px; background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 16px;">👁</button>
                        </div>

                        <label class="form-label" style="margin-top: 12px;">Confirm New Password *</label>
                        <div style="position: relative;">
                            <input type="password" id="adminConfirmPass" name="new_password_confirmation" class="form-input" required>
                            <button type="button" onclick="togglePasswordVisibility('adminConfirmPass', this)" style="position: absolute; right: 12px; top: 12px; background: none; border: none; cursor: pointer; color: var(--text-muted); font-size: 16px;">👁</button>
                        </div>

                        <button type="submit" class="btn btn-primary" style="margin-top: 16px;">Update Admin Password</button>
                    </form>
                </div>
            </div>

            <script>
                function initAdminCharts() {
                    if (typeof Chart === 'undefined') return;
                    const c1 = document.getElementById('chartUsersComparison');
                    if (c1 && !c1.chartInstance) {
                        c1.chartInstance = new Chart(c1, {
                            type: 'bar',
                            data: {
                                labels: ['Makers', 'Volunteers', 'Judges'],
                                datasets: [
                                    {
                                        label: 'Active Platform Users',
                                        data: [{{ $makersCount ?? 0 }}, {{ $volunteersCount ?? 0 }}, {{ $judgesCount ?? 0 }}],
                                        backgroundColor: ['#6b38fb', '#10b981', '#f59e0b']
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } }
                            }
                        });
                    }

                    const c2 = document.getElementById('chartProjectsGrowth');
                    if (c2 && !c2.chartInstance) {
                        c2.chartInstance = new Chart(c2, {
                            type: 'line',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                datasets: [{
                                    label: 'Submitted Projects',
                                    data: @json($monthlyProjectsData ?? array_fill(0, 12, 0)),
                                    borderColor: '#10b981',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    fill: true,
                                    tension: 0.3
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false }
                        });
                    }
                }

                window.addEventListener('load', initAdminCharts);
                document.addEventListener('DOMContentLoaded', initAdminCharts);

                function switchAdminTab(tabId) {
                    document.querySelectorAll('.admin-tab-content').forEach(el => el.style.display = 'none');
                    document.querySelectorAll('.sidebar .nav-item').forEach(el => el.classList.remove('active'));

                    const target = document.getElementById(tabId);
                    if (target) target.style.display = 'block';

                    const navMap = {
                        'tabDashboard': 'sideNavDashboard',
                        'tabEvents': 'sideNavEvents',
                        'tabProjects': 'sideNavProjects',
                        'tabUsers': 'sideNavUsers',
                        'tabJudgeAssign': 'sideNavJudge',
                        'tabTaskAssign': 'sideNavTask',
                        'tabBroadcast': 'sideNavBroadcast',
                        'tabSettings': 'sideNavSettings',
                    };
                    const navEl = document.getElementById(navMap[tabId]);
                    if (navEl) navEl.classList.add('active');

                    localStorage.setItem('activeAdminTab', tabId);

                    if (tabId === 'tabDashboard') {
                        setTimeout(initAdminCharts, 50);
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const savedTab = localStorage.getItem('activeAdminTab');
                    if (savedTab && document.getElementById(savedTab)) {
                        switchAdminTab(savedTab);
                    }
                });

                let currentRoleFilter = 'all';
                function filterUserRole(role) {
                    currentRoleFilter = role;
                    ['userFilterAll', 'userFilterMaker', 'userFilterJudge', 'userFilterVolunteer'].forEach(id => {
                        const btn = document.getElementById(id);
                        if (btn) btn.classList.remove('active');
                    });
                    const activeBtnId = 'userFilter' + role.charAt(0).toUpperCase() + role.slice(1);
                    const activeBtn = document.getElementById(activeBtnId);
                    if (activeBtn) activeBtn.classList.add('active');
                    filterUserList();
                }

                function filterUserList() {
                    const query = document.getElementById('userSearchInput').value.toLowerCase();
                    const rows = document.querySelectorAll('#usersTable .user-row');
                    rows.forEach(row => {
                        const role = row.getAttribute('data-role');
                        const text = row.innerText.toLowerCase();
                        const matchesRole = (currentRoleFilter === 'all' || role === currentRoleFilter);
                        const matchesSearch = text.includes(query);
                        row.style.display = (matchesRole && matchesSearch) ? '' : 'none';
                    });
                }

                function filterProjectList() {
                    const query = document.getElementById('projectSearchInput').value.toLowerCase();
                    const rows = document.querySelectorAll('#adminProjectsTable .project-row');
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(query) ? '' : 'none';
                    });
                }
            </script>
            @endif

            @if($role === 'maker')
            <div class="maker-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                    <div>
                        <h2>{{ __('messages.maker_submission_portal') }}</h2>
                    </div>
                    <button class="btn btn-primary" onclick="toggleNewProjectModal()">{{ __('messages.submit_new_project') }}</button>
                </div>

                <div class="card">
                    <h3 style="margin-bottom: 16px;">{{ __('messages.your_projects_overview') }}</h3>
                    @if(count($makerProjects) > 0)
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.project_code') }}</th>
                                    <th>{{ __('messages.col_project_name') }}</th>
                                    <th>{{ __('messages.col_category') }}</th>
                                    <th>{{ __('messages.col_status') }}</th>
                                    <th>{{ __('messages.col_submitted_date') }}</th>
                                    <th style="text-align: center;">{{ __('messages.col_actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($makerProjects as $mp)
                                <tr>
                                    <td style="font-weight: 700; color: var(--primary);">{{ $mp->project_code }}</td>
                                    <td><strong>{{ $mp->title }}</strong></td>
                                    <td>{{ $mp->category_name ?? 'General' }}</td>
                                    <td><span class="badge badge-{{ $mp->status }}">{{ __('messages.status_' . strtolower($mp->status)) ?? $mp->status }}</span></td>
                                    <td>{{ $mp->submitted_at ? date('d M Y', strtotime($mp->submitted_at)) : __('messages.status_draft') }}</td>
                                    <td style="text-align: center; display: flex; justify-content: center; gap: 8px;">
                                        @if($mp->status === 'Draft')
                                            <button class="btn btn-primary" style="padding: 6px 12px; font-size: 13px; border-radius: 6px; cursor: pointer;" onclick='resumeDraftModal(@json($mp))'>
                                                Resume Draft
                                            </button>
                                        @endif

                                        <button class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; border: 1px solid var(--primary); color: var(--primary); background: transparent; border-radius: 6px; cursor: pointer;" onclick='openProjectDetailsModal(@json($mp))'>
                                            {{ __('messages.view_details') }}
                                        </button>

                                        @if($mp->status === 'Draft')
                                            <form action="{{ route('maker.deleteDraft', $mp->id) }}" method="POST" onsubmit="return confirm('Warning: All info in this draft are gonna be deleted! Are you sure you want to proceed?');" style="margin: 0;">
                                                @csrf
                                                <button type="submit" style="padding: 6px 8px; border-radius: 6px; cursor: pointer; background: transparent; color: #ef4444; border: 1px solid #ef4444; display: flex; align-items: center; justify-content: center;" title="Delete Draft">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="text-align: center; padding: 40px 20px;">
                            <p style="color: var(--text-muted); font-size: 15px;">{{ __('messages.no_projects_yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            @if($role === 'judge')
            <!-- TAB 1: ASSIGNED PROJECTS FOR EVALUATION -->
            <div id="tabAssignedProjects" class="judge-tab-content">
                <div class="card">
                    <h2>Assigned Evaluation Queue</h2>
                    <p style="color: var(--text-muted); margin-bottom: 20px;">Review and evaluate projects assigned specifically to you by administrators.</p>
                    
                    @if(count($judgeAssignedProjects) > 0)
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($judgeAssignedProjects as $jp)
                            <div style="background: #f9fafb; padding: 20px; border-radius: 12px; border: 1px solid var(--border);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                    <div>
                                        <span style="font-size: 12px; font-weight: 700; color: var(--primary);">{{ $jp->project_code }}</span>
                                        <h3 style="margin-top: 2px;">{{ $jp->title }}</h3>
                                        <span style="font-size: 13px; color: var(--text-muted);">Maker: <strong>{{ $jp->leader_name }}</strong> | Category: {{ $jp->category_name ?? 'General Tech' }}</span>
                                    </div>
                                    <span class="badge badge-{{ $jp->status }}">{{ $jp->status }}</span>
                                </div>
                                <p style="font-size: 14px; color: #4b5563; margin-bottom: 16px;">{{ $jp->description }}</p>

                                <form action="#" method="POST" style="background: #fff; padding: 16px; border-radius: 8px; border: 1px solid var(--border);">
                                    @csrf
                                    <div style="display: flex; gap: 16px; align-items: flex-end;">
                                        <div style="flex: 1;">
                                            <label class="form-label" style="margin-bottom: 4px;">Technical Score (0-10)</label>
                                            <input type="number" class="form-input" style="margin-bottom: 0;" max="10" min="0" value="8">
                                        </div>
                                        <div style="flex: 2;">
                                            <label class="form-label" style="margin-bottom: 4px;">Judge Evaluation Remarks</label>
                                            <input type="text" class="form-input" style="margin-bottom: 0;" placeholder="Enter assessment feedback...">
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="alert('Evaluation score submitted for {{ $jp->project_code }}!')">{{ __('messages.btn_submit_eval') }}</button>
                                    </div>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p style="color: var(--text-muted);">No projects assigned for evaluation yet.</p>
                    @endif
                </div>
            </div>

            <!-- TAB 2: ALL EVENT PROJECTS (READ ONLY) -->
            <div id="tabAllProjects" class="judge-tab-content" style="display: none;">
                <div class="card">
                    <h2>All Event Projects Showcase</h2>
                    <p style="color: var(--text-muted); margin-bottom: 20px;">Browse all projects participating in this edition. (Read Only)</p>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Project Code</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th style="text-align: center;">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $p)
                            <tr>
                                <td style="font-weight: 700; color: var(--primary);">{{ $p->project_code }}</td>
                                <td><strong>{{ $p->title }}</strong></td>
                                <td>{{ $p->category_name ?? 'General Tech' }}</td>
                                <td><span class="badge badge-{{ $p->status }}">{{ $p->status }}</span></td>
                                <td style="text-align: center;">
                                    <button class="btn btn-outline" style="padding: 6px 12px; font-size: 13px; border: 1px solid var(--primary); color: var(--primary); background: transparent; border-radius: 6px; cursor: pointer;" onclick='openProjectDetailsModal(@json($p))'>
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                function switchJudgeTab(tabId) {
                    document.querySelectorAll('.judge-tab-content').forEach(el => el.style.display = 'none');
                    document.querySelectorAll('.sidebar .nav-item').forEach(el => el.classList.remove('active'));

                    const target = document.getElementById(tabId);
                    if (target) target.style.display = 'block';

                    const navMap = {
                        'tabAssignedProjects': 'sideJudgeAssigned',
                        'tabAllProjects': 'sideJudgeAll'
                    };
                    const navEl = document.getElementById(navMap[tabId]);
                    if (navEl) navEl.classList.add('active');
                }
            </script>
            @endif

            @if($role === 'volunteer')
            <div class="card">
                <h2>{{ __('messages.volunteer_dashboard_title') }}</h2>
                <p style="color: var(--text-muted); margin-bottom: 20px;">{{ __('messages.volunteer_dashboard_sub') }}</p>
                @foreach($volunteerTasks as $task)
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding: 12px 0;">
                    <div>
                        <strong>{{ $task->title }}</strong><br>
                        <span style="font-size: 13px; color: var(--text-muted);">{{ $task->description }}</span>
                    </div>
                    <span class="badge badge-Draft">{{ $task->status }}</span>
                </div>
                @endforeach
            </div>
            @endif

        </main>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal-overlay" id="profileModal">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>Edit Profile</h3>
                <button onclick="toggleProfileModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-input" value="{{ $currentUser->name ?? $userName }}" required>

                <label class="form-label">Email (Read Only)</label>
                <input type="email" class="form-input" value="{{ $currentUser->email ?? '' }}" readonly style="background: #f3f4f6;">

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px;">
                    <button type="button" class="btn" style="background: #e5e7eb;" onclick="toggleProfileModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Profile</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Event Modal -->
    <div class="modal-overlay" id="createEventModal">
        <div class="modal-card" style="max-width: 480px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>Create New MakerFest Event</h3>
                <button onclick="toggleCreateEventModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form action="{{ route('admin.storeEvent') }}" method="POST">
                @csrf
                <label class="form-label">Event Title / Edition *</label>
                <input type="text" name="title" class="form-input" placeholder="e.g. MakerFest Vadodara 2027" required>

                <label class="form-label">Venue Location *</label>
                <input type="text" name="location" class="form-input" value="Vadodara" required>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label class="form-label">Start Date *</label>
                        <input type="date" name="start_date" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">End Date *</label>
                        <input type="date" name="end_date" class="form-input" required>
                    </div>
                </div>

                <div style="margin-top: 14px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked style="width: auto;"> Set as Active Event Edition
                    </label>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                    <button type="button" class="btn" style="background: #e5e7eb;" onclick="toggleCreateEventModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Publish Event</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal-overlay" id="passwordModal">
        <div class="modal-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>Change Password</h3>
                <button onclick="togglePasswordModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form action="{{ route('settings.password') }}" method="POST">
                @csrf
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-input" required>

                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-input" required>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px;">
                    <button type="button" class="btn" style="background: #e5e7eb;" onclick="togglePasswordModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Admin Add Judge / Volunteer Modal -->
    <div class="modal-overlay" id="addStaffModal">
        <div class="modal-card" style="max-width: 480px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3>Create Staff Account</h3>
                <button onclick="toggleAddStaffModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            <form action="{{ route('admin.createUser') }}" method="POST">
                @csrf
                <label class="form-label">Select Staff Role *</label>
                <input type="hidden" name="role" id="staffRoleInput" value="judge">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
                    <div id="roleCardJudge" onclick="selectStaffRole('judge')" style="border: 2px solid var(--primary); background: var(--primary-light); padding: 16px; border-radius: 12px; cursor: pointer; text-align: center; transition: all 0.2s;">
                        <strong style="color: var(--primary); font-size: 15px; display: block; margin-top: 4px;">Judge</strong>
                        <span style="font-size: 12px; color: var(--text-muted);">Evaluates Projects</span>
                    </div>

                    <div id="roleCardVolunteer" onclick="selectStaffRole('volunteer')" style="border: 2px solid #e5e7eb; background: #ffffff; padding: 16px; border-radius: 12px; cursor: pointer; text-align: center; transition: all 0.2s;">
                        <strong style="color: #374151; font-size: 15px; display: block; margin-top: 4px;">Volunteer</strong>
                        <span style="font-size: 12px; color: var(--text-muted);">Event Operations</span>
                    </div>
                </div>

                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-input" placeholder="Enter staff name" required>

                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-input" placeholder="e.g. judge@makerfest.org" required>

                <div style="background: #eef2ff; border: 1px solid #c7d2fe; padding: 12px; border-radius: 8px; margin-top: 14px; font-size: 13px; color: #3730a3;">
                    🔑 A random secure password will be generated automatically and sent to this email address.
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                    <button type="button" class="btn" style="background: #e5e7eb;" onclick="toggleAddStaffModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create & Send Credentials</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectStaffRole(role) {
            document.getElementById('staffRoleInput').value = role;
            const cardJ = document.getElementById('roleCardJudge');
            const cardV = document.getElementById('roleCardVolunteer');
            if (role === 'judge') {
                cardJ.style.border = '2px solid var(--primary)';
                cardJ.style.background = 'var(--primary-light)';
                cardJ.querySelector('strong').style.color = 'var(--primary)';
                
                cardV.style.border = '2px solid #e5e7eb';
                cardV.style.background = '#ffffff';
                cardV.querySelector('strong').style.color = '#374151';
            } else {
                cardV.style.border = '2px solid var(--primary)';
                cardV.style.background = 'var(--primary-light)';
                cardV.querySelector('strong').style.color = 'var(--primary)';
                
                cardJ.style.border = '2px solid #e5e7eb';
                cardJ.style.background = '#ffffff';
                cardJ.querySelector('strong').style.color = '#374151';
            }
        }
    </script>

    <!-- Create New Project Modal (for Makers Multi-Step Wizard) -->
    <div class="modal-overlay" id="newProjectModal">
        <div class="modal-card" style="max-width: 640px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 id="wizardModalTitle">Step 1: Project Details</h3>
                <button onclick="toggleNewProjectModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>
            
            <form action="{{ route('maker.saveProject') }}" method="POST" id="projectWizardForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="draft_project_id" id="draft_project_id" value="">
                
                <!-- STEP 0: Pre-requisite Notice -->
                <div id="step0_notice" style="display: none;">
                    <div style="background: #fffbe6; border: 1px solid #ffe58f; padding: 16px; border-radius: 8px; font-size: 14px; color: #873800; margin-bottom: 20px;">
                        <h4 style="margin-bottom: 12px; color: #d46b08;">Important Requirements Before You Start</h4>
                        <p style="margin-bottom: 8px;">Please ensure you have the following ready before registering your project:</p>
                        <ul style="padding-left: 20px; line-height: 1.6;">
                            <li><strong>5-6 Photos of your Project Model:</strong> We need clear photos of all sides (Front, Back, Left, Right, Top, Bottom) in JPG, JPEG, or PNG format.</li>
                            <li><strong>Project Demonstration Videos:</strong> You must upload 1 or 2 demonstration videos to YouTube and provide the links.</li>
                        </ul>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="button" class="btn btn-primary" onclick="goToStep(1)">I Understand, Next &rarr;</button>
                    </div>
                </div>

                <!-- STEP 1: Project Details & Team Count -->
                <div id="step1_projectDetails">
                    <label class="form-label">{{ __('messages.label_project_title') }} *</label>
                    <input type="text" id="w_title" name="title" class="form-input" placeholder="{{ __('messages.placeholder_title') }}" required>

                    <label class="form-label">{{ __('messages.label_select_category') }} *</label>
                    <select name="category_id" class="form-select" required>
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>

                    <label class="form-label">{{ __('messages.label_project_desc') }} *</label>
                    <textarea id="w_desc" name="description" class="form-textarea" rows="3" placeholder="{{ __('messages.placeholder_desc') }}" required></textarea>

                    <label class="form-label">{{ __('messages.label_participating_as') }}</label>
                    <select name="registration_type" class="form-select">
                        <option value="individual">{{ __('messages.opt_individual') }}</option>
                        <option value="institutional">{{ __('messages.opt_institutional') }}</option>
                    </select>

                    <label class="form-label">Number of Team Members *</label>
                    <select id="teamCountSelect" name="team_count" class="form-select" onchange="updateMemberSteps()">
                        <option value="1">1 (Leader Only)</option>
                        <option value="2">2 Members (Leader + 1 Member)</option>
                        <option value="3">3 Members (Leader + 2 Members)</option>
                        <option value="4">4 Members (Leader + 3 Members)</option>
                    </select>

                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(0)">&larr; Back</button>
                        <button type="button" class="btn btn-primary" onclick="goToStep(2)">Next: Leader Details &rarr;</button>
                    </div>
                </div>

                <!-- STEP 2: Team Leader Details -->
                <div id="step2_leaderDetails" style="display: none; padding-top: 10px;">
                    
                    <label class="form-label">Leader Name *</label>
                    <input type="text" name="leader_name" class="form-input" value="{{ $currentUser->name ?? $userName }}" required>

                    <label class="form-label">Leader Email *</label>
                    <input type="email" name="leader_email" class="form-input" value="{{ $currentUser->email ?? '' }}" required>

                    <label class="form-label">School / Institution Name</label>
                    <input type="text" name="school_name" class="form-input" placeholder="Enter school or organization">

                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(1)">&larr; Back</button>
                        <button type="button" class="btn btn-primary" id="step2NextBtn" onclick="handleStep2Next()">Next: Additional Members &rarr;</button>
                    </div>
                </div>

                <!-- STEP 3: Additional Team Members Details -->
                <div id="step3_memberDetails" style="display: none;">
                    <div id="dynamicMemberFields"></div>

                    <!-- SUBMISSION WARNING NOTICE -->
                    <div style="background: #fffbe6; border: 1px solid #ffe58f; padding: 12px; border-radius: 8px; margin-top: 16px; font-size: 13px; color: #873800;">
                        {{ __('messages.submission_notice') }}
                    </div>

                    <div style="display: flex; justify-content: space-between; gap: 12px; margin-top: 20px;">
                        <button type="button" class="btn btn-secondary" onclick="handleMemberBack()">&larr; Back</button>
                        <button type="button" class="btn btn-primary" id="step3NextBtn" onclick="handleMemberNext()">Next Member &rarr;</button>
                    </div>
                </div>

                <!-- STEP 4: Media Uploads -->
                <div id="step4_media" style="display: none;">
                    <h4 style="margin-bottom: 16px; color: var(--primary);">Media Uploads</h4>
                    
                    <p style="font-size: 14px; margin-bottom: 12px;"><strong>1. Project Photos (JPG, PNG)</strong><br><span style="font-size: 12px; color: var(--text-muted, #666);">Note: Each photo should be less than 5MB.</span></p>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                        <div><label class="form-label">Front View *</label><input type="file" name="project_photos[front]" class="form-input" accept=".jpg,.jpeg,.png" required><div id="error_img_front" style="color: #ef4444; font-size: 12px; display: none; margin-top: 4px;"></div><img id="draft_img_front" style="display: none; width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border); margin-top: 6px;"></div>
                        <div><label class="form-label">Back View *</label><input type="file" name="project_photos[back]" class="form-input" accept=".jpg,.jpeg,.png" required><div id="error_img_back" style="color: #ef4444; font-size: 12px; display: none; margin-top: 4px;"></div><img id="draft_img_back" style="display: none; width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border); margin-top: 6px;"></div>
                        <div><label class="form-label">Left View *</label><input type="file" name="project_photos[left]" class="form-input" accept=".jpg,.jpeg,.png" required><div id="error_img_left" style="color: #ef4444; font-size: 12px; display: none; margin-top: 4px;"></div><img id="draft_img_left" style="display: none; width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border); margin-top: 6px;"></div>
                        <div><label class="form-label">Right View *</label><input type="file" name="project_photos[right]" class="form-input" accept=".jpg,.jpeg,.png" required><div id="error_img_right" style="color: #ef4444; font-size: 12px; display: none; margin-top: 4px;"></div><img id="draft_img_right" style="display: none; width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border); margin-top: 6px;"></div>
                        <div><label class="form-label">Top View *</label><input type="file" name="project_photos[top]" class="form-input" accept=".jpg,.jpeg,.png" required><div id="error_img_top" style="color: #ef4444; font-size: 12px; display: none; margin-top: 4px;"></div><img id="draft_img_top" style="display: none; width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border); margin-top: 6px;"></div>
                        <div><label class="form-label">Bottom View *</label><input type="file" name="project_photos[bottom]" class="form-input" accept=".jpg,.jpeg,.png" required><div id="error_img_bottom" style="color: #ef4444; font-size: 12px; display: none; margin-top: 4px;"></div><img id="draft_img_bottom" style="display: none; width: 80px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border); margin-top: 6px;"></div>
                    </div>

                    <p style="font-size: 14px; margin-bottom: 12px;"><strong>2. YouTube Demonstration Videos</strong></p>
                    <label class="form-label">Video 1 Link *</label>
                    <input type="url" name="youtube_link_1" class="form-input" placeholder="https://youtube.com/watch?v=..." required>
                    
                    <label class="form-label">Video 2 Link (Optional)</label>
                    <input type="url" name="youtube_link_2" class="form-input" placeholder="https://youtube.com/watch?v=...">

                    <div style="display: flex; justify-content: space-between; gap: 12px; margin-top: 20px;">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(3)">&larr; Back</button>
                        <div style="display: flex; gap: 12px;">
                            <button type="submit" name="action" value="draft" class="btn btn-warning" formnovalidate>{{ __('messages.btn_save_draft') }}</button>
                            <button type="submit" name="action" value="submit" class="btn btn-primary">{{ __('messages.btn_final_submit') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentMemberIndex = 2;
        let lastTeamCount = 0;

        function toggleDropdown(event) {
            event.stopPropagation();
            document.getElementById('hbmDropdown').classList.toggle('active');
        }
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('hbmDropdown');
            if (dropdown && dropdown.classList.contains('active')) {
                dropdown.classList.remove('active');
            }
        });
        function toggleProfileModal() {
            document.getElementById('profileModal').classList.toggle('active');
        }
        function togglePasswordModal() {
            document.getElementById('passwordModal').classList.toggle('active');
        }
        function toggleAddStaffModal() {
            document.getElementById('addStaffModal').classList.toggle('active');
        }
        function toggleCreateEventModal() {
            document.getElementById('createEventModal').classList.toggle('active');
        }
        function toggleNewProjectModal() {
            const modal = document.getElementById('newProjectModal');
            modal.classList.toggle('active');
            if (modal.classList.contains('active')) {
                document.getElementById('projectWizardForm').reset();
                if(document.getElementById('draft_project_id')) document.getElementById('draft_project_id').value = '';
                // remove dynamic members logic if any were added
                document.getElementById('dynamicMembersContainer').innerHTML = '';
                const draftImgs = ['front', 'back', 'left', 'right', 'top', 'bottom'];
                draftImgs.forEach(s => {
                    const img = document.getElementById('draft_img_' + s);
                    if(img) img.style.display = 'none';
                    const input = document.querySelector(`input[name="project_photos[${s}]"]`);
                    if(input) input.setAttribute('required', 'required');
                });
                goToStep(0);
            }
        }

        function resumeDraftModal(project) {
            const modal = document.getElementById('newProjectModal');
            modal.classList.add('active');
            
            if(document.getElementById('draft_project_id')) document.getElementById('draft_project_id').value = project.id;
            
            document.getElementById('w_title').value = project.title || '';
            document.getElementById('w_desc').value = project.description || '';
            document.querySelector(`select[name="category_id"]`).value = project.category_id || '';
            document.querySelector(`select[name="registration_type"]`).value = project.registration_type || 'individual';
            
            const schoolInput = document.querySelector(`input[name="school_name"]`);
            if(schoolInput) schoolInput.value = project.school_organization_name || '';
            
            const yt1 = document.querySelector(`input[name="youtube_link_1"]`);
            if(yt1) yt1.value = project.youtube_link_1 || '';
            const yt2 = document.querySelector(`input[name="youtube_link_2"]`);
            if(yt2) yt2.value = project.youtube_link_2 || '';
            
            let count = (project.members && project.members.length > 0) ? project.members.length : 1;
            document.getElementById('teamCountSelect').value = count;
            
            if (count > 1) {
                lastTeamCount = count;
                renderDynamicMembers();
                
                setTimeout(() => {
                    const nameInputs = document.querySelectorAll(`input[name="member_names[]"]`);
                    const emailInputs = document.querySelectorAll(`input[name="member_emails[]"]`);
                    const mobileInputs = document.querySelectorAll(`input[name="member_mobiles[]"]`);
                    const schoolInputs = document.querySelectorAll(`input[name="member_schools[]"]`);
                    
                    for(let i=0; i<count-1; i++) {
                        if (project.members[i+1]) {
                            if (nameInputs[i]) nameInputs[i].value = project.members[i+1].name || '';
                            if (emailInputs[i]) emailInputs[i].value = project.members[i+1].email || '';
                            if (mobileInputs[i]) mobileInputs[i].value = project.members[i+1].mobile || '';
                            if (schoolInputs[i]) schoolInputs[i].value = project.members[i+1].school_name || '';
                        }
                    }
                }, 100);
            } else {
                document.getElementById('dynamicMembersContainer').innerHTML = '';
            }

            // Populate media
            const draftImgs = ['front', 'back', 'left', 'right', 'top', 'bottom'];
            draftImgs.forEach(s => {
                const img = document.getElementById('draft_img_' + s);
                if(img) img.style.display = 'none';
                const input = document.querySelector(`input[name="project_photos[${s}]"]`);
                if(input) input.setAttribute('required', 'required');
            });

            if (project.media && project.media.length > 0) {
                project.media.forEach(m => {
                    const img = document.getElementById('draft_img_' + m.side);
                    if (img) {
                        img.src = '/storage/' + m.file_path;
                        img.style.display = 'block';
                        const input = document.querySelector(`input[name="project_photos[${m.side}]"]`);
                        if(input) input.removeAttribute('required');
                    }
                });
            }
            
            goToStep(0);
        }

        function goToStep(step) {
            const title = document.getElementById('wizardModalTitle');
            const s0 = document.getElementById('step0_notice');
            const s1 = document.getElementById('step1_projectDetails');
            const s2 = document.getElementById('step2_leaderDetails');
            const s3 = document.getElementById('step3_memberDetails');
            const s4 = document.getElementById('step4_media');

            s0.style.display = 'none';
            s1.style.display = 'none';
            s2.style.display = 'none';
            s3.style.display = 'none';
            s4.style.display = 'none';

            if (step === 0) {
                title.innerText = 'Important Notice';
                s0.style.display = 'block';
            } else if (step === 1) {
                title.innerText = 'Step 1: Project Details';
                s1.style.display = 'block';
            } else if (step === 2) {
                const projTitle = document.getElementById('w_title').value.trim();
                const projDesc = document.getElementById('w_desc').value.trim();
                if (!projTitle || !projDesc) {
                    alert('Please fill in Project Title and Description before proceeding.');
                    s1.style.display = 'block';
                    return;
                }
                title.innerText = 'Step 2: Team Leader Details';
                s1.style.display = 'none';
                s2.style.display = 'block';
                s3.style.display = 'none';

                const count = parseInt(document.getElementById('teamCountSelect').value);
                const nextBtn = document.getElementById('step2NextBtn');
                if (count === 1) {
                    nextBtn.innerHTML = 'Next: Media Uploads &rarr;';
                } else {
                    nextBtn.innerHTML = 'Next: Additional Members &rarr;';
                }
            } else if (step === 3) {
                const count = parseInt(document.getElementById('teamCountSelect').value);
                if (lastTeamCount !== count) {
                    renderDynamicMembers();
                    lastTeamCount = count;
                }
                currentMemberIndex = 2;
                updateMemberView();
                s3.style.display = 'block';
            } else if (step === 4) {
                title.innerText = 'Step 4: Media Uploads';
                s4.style.display = 'block';
            }
        }

        function handleStep2Next() {
            const count = parseInt(document.getElementById('teamCountSelect').value);
            if (count === 1) {
                // If 1 member (Leader only), skip extra members and go to Media
                goToStep(4);
            } else {
                goToStep(3);
            }
        }

        function updateMemberView() {
            const count = parseInt(document.getElementById('teamCountSelect').value);
            const title = document.getElementById('wizardModalTitle');
            title.innerText = 'Step 3: Team Member ' + currentMemberIndex;
            
            for (let i = 2; i <= count; i++) {
                const el = document.getElementById('member_step_' + i);
                if (el) el.style.display = (i === currentMemberIndex) ? 'block' : 'none';
            }
            
            const nextBtn = document.getElementById('step3NextBtn');
            if (currentMemberIndex === count) {
                nextBtn.innerHTML = 'Next: Media Uploads &rarr;';
            } else {
                nextBtn.innerHTML = 'Next Member &rarr;';
            }
        }

        function handleMemberNext() {
            const count = parseInt(document.getElementById('teamCountSelect').value);
            if (currentMemberIndex < count) {
                const nameInput = document.querySelector(`#member_step_${currentMemberIndex} input[type="text"]`);
                if(nameInput && !nameInput.value.trim()) {
                    alert('Please enter a name for Member ' + currentMemberIndex);
                    return;
                }
                currentMemberIndex++;
                updateMemberView();
            } else {
                const nameInput = document.querySelector(`#member_step_${currentMemberIndex} input[type="text"]`);
                if(nameInput && !nameInput.value.trim()) {
                    alert('Please enter a name for Member ' + currentMemberIndex);
                    return;
                }
                goToStep(4);
            }
        }

        function handleMemberBack() {
            if (currentMemberIndex > 2) {
                currentMemberIndex--;
                updateMemberView();
            } else {
                goToStep(2);
            }
        }

        function renderDynamicMembers() {
            const count = parseInt(document.getElementById('teamCountSelect').value);
            const container = document.getElementById('dynamicMemberFields');
            container.innerHTML = '';

            if (count <= 1) {
                container.innerHTML = '<p style="color: var(--text-muted); font-size: 14px;">Individual Participation: No extra team members required.</p>';
                return;
            }

            for (let i = 2; i <= count; i++) {
                const memberHtml = `
                    <div id="member_step_${i}" style="display: none; background: #f9fafb; border: 1px solid var(--border); border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                        <h5 style="margin: 0 0 12px 0; color: var(--primary);">Member ${i} Information</h5>
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="member_names[]" class="form-input" placeholder="Member ${i} Name" required>
                        
                        <label class="form-label">Email Address</label>
                        <input type="email" name="member_emails[]" class="form-input" placeholder="Member ${i} Email">
                        
                        <label class="form-label">School / Organization Name</label>
                        <input type="text" name="member_schools[]" class="form-input" placeholder="Member ${i} School / Org">
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', memberHtml);
            }
        }

        function openProjectDetailsModal(project) {
            document.getElementById('modalProjCode').innerText = project.project_code || 'N/A';
            document.getElementById('modalProjTitle').innerText = project.title || 'N/A';
            document.getElementById('modalProjCategory').innerText = project.category_name || 'General';
            document.getElementById('modalProjStatus').innerText = project.status || 'Submitted';
            document.getElementById('modalProjDesc').innerText = project.description || 'No description provided.';
            document.getElementById('modalProjType').innerText = (project.registration_type || 'individual').toUpperCase();
            document.getElementById('modalProjSchool').innerText = project.school_organization_name || 'N/A';

            let membersHtml = '';
            if (project.members && project.members.length > 0) {
                project.members.forEach((m, idx) => {
                    const leaderTag = (idx === 0) ? `<span style="background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; margin-left: 8px;">{{ __('messages.leader_badge') }}</span>` : '';
                    membersHtml += `
                        <div style="background: #f9fafb; padding: 10px 14px; border-radius: 6px; border: 1px solid #e5e7eb; margin-bottom: 8px;">
                            <strong style="color: #111827;">${m.name}</strong> ${leaderTag}
                            <span style="font-size: 13px; color: #6b7280; display: block; margin-top: 2px;">Email: ${m.email || 'N/A'}</span>
                        </div>
                    `;
                });
            } else {
                membersHtml = `<div style="background: #f9fafb; padding: 10px; border-radius: 6px; color: #6b7280; font-size: 13px;">${project.leader_name || 'N/A'} <span style="background: #e0e7ff; color: #4338ca; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">{{ __('messages.leader_badge') }}</span> (${project.leader_email || 'N/A'})</div>`;
            }
            document.getElementById('modalTeamList').innerHTML = membersHtml;

            let mediaHtml = '';
            if (project.media && project.media.length > 0) {
                mediaHtml += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px;">';
                project.media.forEach(m => {
                    const fullUrl = '/storage/' + m.file_path;
                    mediaHtml += `
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; position: relative;">
                            <div style="background: #f3f4f6; padding: 4px 8px; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #374151; border-bottom: 1px solid #e5e7eb;">${m.side} View</div>
                            <a href="javascript:void(0)" onclick="openLightbox('${fullUrl}')">
                                <img src="${fullUrl}" style="width: 100%; height: 120px; object-fit: cover; display: block;">
                            </a>
                        </div>
                    `;
                });
                mediaHtml += '</div>';
            } else {
                mediaHtml += '<p style="color: #6b7280; font-size: 13px;">No photos uploaded.</p>';
            }

            if (project.youtube_link_1 || project.youtube_link_2) {
                mediaHtml += '<div style="margin-top: 16px;">';
                if (project.youtube_link_1) {
                    mediaHtml += `<a href="${project.youtube_link_1}" target="_blank" style="display: block; color: var(--primary); font-size: 14px; margin-bottom: 8px;">&rarr; Watch Video 1</a>`;
                }
                if (project.youtube_link_2) {
                    mediaHtml += `<a href="${project.youtube_link_2}" target="_blank" style="display: block; color: var(--primary); font-size: 14px;">&rarr; Watch Video 2</a>`;
                }
                mediaHtml += '</div>';
            }
            document.getElementById('modalMediaList').innerHTML = mediaHtml;

            const approveBtn = document.getElementById('modalApproveBtn');
            const approveForm = document.getElementById('modalApproveForm');
            const rejectBtn = document.getElementById('modalRejectBtn');
            const rejectForm = document.getElementById('modalRejectForm');

            // Admins can no longer review/approve projects directly here (they should assign to a judge instead)
            if (approveBtn && approveForm && rejectBtn && rejectForm && ('{{ $role }}' === 'judge')) {
                approveForm.action = `/admin/project/${project.id}/status`;
                rejectForm.action = `/admin/project/${project.id}/status`;
                approveBtn.style.display = 'inline-block';
                rejectBtn.style.display = 'inline-block';
            } else {
                if (approveBtn) approveBtn.style.display = 'none';
                if (rejectBtn) rejectBtn.style.display = 'none';
            }

            document.getElementById('projectDetailsModal').style.display = 'flex';
        }

        function closeProjectDetailsModal() {
            document.getElementById('projectDetailsModal').style.display = 'none';
        }

        function openRemoveUserModal(userId, userName) {
            document.getElementById('removeUserNameText').innerText = userName;
            document.getElementById('confirmRemoveUserForm').action = `/admin/user/${userId}/delete`;
            document.getElementById('removeUserModal').style.display = 'flex';
        }

        function closeRemoveUserModal() {
            document.getElementById('removeUserModal').style.display = 'none';
        }
        document.addEventListener('DOMContentLoaded', function() {
            const photoInputs = document.querySelectorAll('input[type="file"][name^="project_photos"]');
            const maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
            const allowedTypes = ['image/jpeg', 'image/png']; // .jpg evaluates to image/jpeg

            photoInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const match = this.name.match(/\[(.*?)\]/);
                    const side = match ? match[1] : null;
                    const errorSpan = side ? document.getElementById('error_img_' + side) : null;
                    
                    if (errorSpan) {
                        errorSpan.style.display = 'none';
                        errorSpan.innerText = '';
                    }

                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        let hasError = false;
                        let errorMsg = '';
                        
                        if (!allowedTypes.includes(file.type)) {
                            hasError = true;
                            errorMsg = 'File must be a JPG or PNG image.';
                        } else if (file.size > maxFileSize) {
                            hasError = true;
                            errorMsg = 'Image size should be less than 5MB.';
                        }

                        if (hasError) {
                            if (errorSpan) {
                                errorSpan.innerText = errorMsg;
                                errorSpan.style.display = 'block';
                            }
                            this.value = ''; // clear the input
                            
                            // Hide preview image if one exists
                            if (side) {
                                const imgElement = document.getElementById('draft_img_' + side);
                                if (imgElement) {
                                    imgElement.style.display = 'none';
                                    imgElement.src = '';
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>

    <!-- PROJECT DETAILS POPUP MODAL -->
    <div id="projectDetailsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
        <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 580px; max-height: 85vh; overflow-y: auto; padding: 28px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); position: relative;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb; padding-bottom: 14px; margin-bottom: 20px;">
                <div>
                    <span id="modalProjCode" style="background: #eef2ff; color: #4f46e5; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px; text-transform: uppercase;"></span>
                    <h3 id="modalProjTitle" style="margin: 8px 0 0 0; color: #111827; font-size: 20px;">Project Title</h3>
                </div>
                <button onclick="closeProjectDetailsModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280; font-weight: bold;">&times;</button>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; background: #f8f9fa; padding: 14px; border-radius: 10px;">
                <div>
                    <span style="font-size: 12px; color: #6b7280; font-weight: 600;">CATEGORY</span>
                    <div id="modalProjCategory" style="font-weight: 600; color: #111827; font-size: 14px;"></div>
                </div>
                <div>
                    <span style="font-size: 12px; color: #6b7280; font-weight: 600;">STATUS</span>
                    <div id="modalProjStatus" style="font-weight: 600; color: #166534; font-size: 14px;"></div>
                </div>
                <div>
                    <span style="font-size: 12px; color: #6b7280; font-weight: 600;">TYPE</span>
                    <div id="modalProjType" style="font-weight: 600; color: #111827; font-size: 14px;"></div>
                </div>
                <div>
                    <span style="font-size: 12px; color: #6b7280; font-weight: 600;">INSTITUTION / SCHOOL</span>
                    <div id="modalProjSchool" style="font-weight: 600; color: #111827; font-size: 14px;"></div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <h4 style="color: #374151; margin-bottom: 8px; font-size: 15px;">Project Description</h4>
                <p id="modalProjDesc" style="font-size: 14px; color: #4b5563; line-height: 1.6; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin: 0;"></p>
            </div>

            <div>
                <h4 style="color: #374151; margin-bottom: 10px; font-size: 15px;">Team Members</h4>
                <div id="modalTeamList"></div>
            </div>

            <div style="margin-top: 20px;">
                <h4 style="color: #374151; margin-bottom: 10px; font-size: 15px;">Project Media</h4>
                <div id="modalMediaList"></div>
            </div>

            <div style="margin-top: 24px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e5e7eb; padding-top: 16px;">
                <div style="display: flex; gap: 10px;">
                    <form id="modalApproveForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" id="modalApproveBtn" class="btn" style="background: #10b981; color: #fff; padding: 10px 18px; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; display: none;">Approve Project</button>
                    </form>
                    <form id="modalRejectForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" id="modalRejectBtn" class="btn" style="background: #ef4444; color: #fff; padding: 10px 18px; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; display: none;">Reject Project</button>
                    </form>
                </div>
                <button onclick="closeProjectDetailsModal()" class="btn" style="background: #e5e7eb; color: #374151; padding: 10px 22px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Close</button>
            </div>
        </div>
    </div>

    <!-- CUSTOM USER REMOVAL CONFIRMATION MODAL -->
    <div id="removeUserModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
        <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 440px; padding: 24px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); text-align: center;">
            <h3 style="color: #b91c1c; margin-top: 0;">Confirm User Removal</h3>
            <p style="color: #4b5563; font-size: 14px; margin-bottom: 24px;">Are you sure you want to remove <strong id="removeUserNameText"></strong> from the system? This action cannot be undone.</p>
            
            <form id="confirmRemoveUserForm" method="POST" action="">
                @csrf
                <div style="display: flex; justify-content: center; gap: 12px;">
                    <button type="button" onclick="closeRemoveUserModal()" class="btn" style="background: #e5e7eb; color: #374151; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer;">Cancel</button>
                    <button type="submit" class="btn" style="background: #ef4444; color: #fff; padding: 10px 20px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;">Remove User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- IMAGE LIGHTBOX MODAL -->
    <div id="imageLightbox" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 10000; justify-content: center; align-items: center; cursor: pointer;" onclick="closeLightbox()">
        <button onclick="closeLightbox()" style="position: absolute; top: 20px; right: 20px; background: none; border: none; color: white; font-size: 36px; cursor: pointer;">&times;</button>
        <img id="lightboxImg" src="" style="max-width: 90%; max-height: 90%; border-radius: 8px; cursor: default;" onclick="event.stopPropagation();">
    </div>

    <style>
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
    </style>
    <script>
        function openLightbox(url) {
            document.getElementById('lightboxImg').src = url;
            document.getElementById('imageLightbox').style.display = 'flex';
        }

        function closeLightbox() {
            document.getElementById('imageLightbox').style.display = 'none';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const lightbox = document.getElementById('imageLightbox');
                if (lightbox && lightbox.style.display === 'flex') {
                    closeLightbox();
                } else {
                    const detailsModal = document.getElementById('projectDetailsModal');
                    if (detailsModal && detailsModal.style.display === 'flex') {
                        closeProjectDetailsModal();
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const wizardForm = document.getElementById('projectWizardForm');
            if (wizardForm) {
                wizardForm.addEventListener('submit', function(e) {
                    const submitter = e.submitter || document.activeElement;
                    
                    // If the submit button doesn't bypass validation, and form is invalid, don't show loader
                    if (submitter && !submitter.hasAttribute('formnovalidate')) {
                        if (!this.checkValidity()) return;
                    }
                    
                    if (submitter && submitter.tagName === 'BUTTON') {
                        submitter.innerHTML = `<svg style="display:inline-block; margin-right:8px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg> Loading...`;
                        submitter.style.opacity = '0.7';
                        submitter.style.pointerEvents = 'none';
                        
                        // Disable other submit buttons
                        const buttons = this.querySelectorAll('button[type="submit"]');
                        buttons.forEach(btn => {
                            if (btn !== submitter) btn.disabled = true;
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>
