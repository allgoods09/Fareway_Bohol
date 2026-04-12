<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Fareway Bohol')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        html {
            overflow-y: scroll;
        }
        :root {
            --navy:      #0c2340;
            --navy-mid:  #1a3a5c;
            --teal:      #0e8a6e;
            --teal-light:#e1f5ee;
            --sand:      #f5f7fa;
            --white:     #ffffff;
            --text-dark: #111827;
            --text-mid:  #374151;
            --text-muted:#6b7280;
            --border:    #e5e7eb;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--sand);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ─── HERO WITH INTEGRATED NAV ─── */
        .hero {
            background: var(--navy);
            position: relative;
            overflow: visible;
            padding-bottom: 60px;
        }
        .hero-dots {
            position: absolute; inset: 0; opacity: .05;
            background-image: radial-gradient(#fff 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .hero-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            position: relative;
            z-index: 1;
            overflow: visible;
        }
        
        /* Integrated Navigation inside Hero */
        .hero-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0 16px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            margin-bottom: 60px;
            gap: 20px;
            flex-wrap: wrap;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .nav-logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            backdrop-filter: blur(4px);
        }
        .nav-brand {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.3px;
        }
        .nav-brand span {
            color: #34d399;
        }
        
        /* Desktop Navigation Links */
        .nav-center {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-link {
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,.7);
            background: transparent;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .2s;
        }
        .nav-link:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
        }
        .nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background: rgba(255,255,255,.1);
            border: none;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            padding: 10px;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .mobile-menu-btn:hover {
            background: rgba(255,255,255,.2);
        }

        /* Mobile Navigation */
        .mobile-nav {
            display: none;
            width: 100%;
            flex-direction: column;
            gap: 8px;
            padding: 16px 0;
            border-top: 1px solid rgba(255,255,255,.1);
            margin-top: 8px;
        }
        .mobile-nav.active {
            display: flex;
        }
        .mobile-nav-link {
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255,255,255,.7);
            background: transparent;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all .2s;
            width: 100%;
            text-align: left;
        }
        .mobile-nav-link:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
        }
        .mobile-nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }

        /* Right side - Profile Dropdown Styles */
        .nav-right {
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1001;
        }
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }
        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 4px;
            border-radius: 40px;
            background: rgba(255,255,255,.08);
            transition: background 0.2s;
        }
        .profile-trigger:hover {
            background: rgba(255,255,255,.15);
        }
        .profile-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #34d399, #0e8a6e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
        }
        .profile-name {
            font-size: 14px;
            font-weight: 500;
            color: #fff;
        }
        .profile-chevron {
            color: rgba(255,255,255,.6);
            font-size: 12px;
            transition: transform 0.2s;
        }
        .profile-dropdown.active .profile-chevron {
            transform: rotate(180deg);
        }
        
        .profile-dropdown {
            position: relative;
            display: inline-block;
            z-index: 1001;
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 260px;
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s;
            z-index: 1002;
        }
        .profile-dropdown.active .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-header {
            padding: 16px;
            border-bottom: 1px solid var(--border);
        }
        .dropdown-user-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 2px;
        }
        .dropdown-user-email {
            font-size: 12px;
            color: var(--text-muted);
        }
        .dropdown-items {
            padding: 8px 0;
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: var(--text-mid);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.2s;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            font-family: 'Poppins', sans-serif;
        }
        .dropdown-item:hover {
            background: var(--sand);
        }
        .dropdown-item i {
            width: 18px;
            color: var(--teal);
            font-size: 14px;
        }
        .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 8px 0;
        }
        .dropdown-item.logout {
            color: #dc2626;
        }
        .dropdown-item.logout i {
            color: #dc2626;
        }

        .role-badge {
            display: inline-block;
            padding: 2px 8px;
            background: var(--teal-light);
            color: var(--teal);
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            margin-left: 8px;
        }

        /* Login & Register Buttons */
        .btn-nav-login {
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 500;
            color: #fff;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .2s;
        }
        .btn-nav-login:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.5);
        }
        .btn-nav-register {
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
            background: #34d399;
            color: #0c2340;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all .2s;
            box-shadow: 0 2px 8px rgba(52,211,153,0.3);
        }
        .btn-nav-register:hover {
            background: #2ebf8a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(52,211,153,0.4);
        }

        /* Hero Content */
        .hero-content {
            max-width: 600px;
        }
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            color: rgba(255,255,255,.75);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: 20px;
        }
        .hero-tag-dot {
            width: 5px;
            height: 5px;
            background: #34d399;
            border-radius: 50%;
        }
        .hero h1 {
            font-size: clamp(32px, 5vw, 52px);
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }
        .hero h1 span {
            color: #34d399;
        }
        .hero p {
            font-size: 15px;
            color: rgba(255,255,255,.6);
            font-weight: 300;
            max-width: 460px;
            line-height: 1.7;
            margin-bottom: 40px;
        }
        .hero-stats {
            display: flex;
            gap: 32px;
        }
        .hero-stat {
            border-left: 2px solid rgba(255,255,255,.1);
            padding-left: 18px;
        }
        .hero-stat-num {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
        }
        .hero-stat-label {
            font-size: 11px;
            color: rgba(255,255,255,.45);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-top: 4px;
        }

        /* Main Content Container */
        .main-content {
            width: 100%;
            padding: 100px 200px 60px;
            flex: 1;
        }

        /* Footer */
        footer {
            background: var(--navy);
            color: rgba(255,255,255,.4);
            text-align: center;
            padding: 28px 24px;
            font-size: 12px;
            border-top: 1px solid rgba(255,255,255,.05);
            flex-shrink: 0;
            margin-top: auto;
        }
        footer strong {
            color: rgba(255,255,255,.7);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-inner {
                padding: 0 20px;
            }
            .main-content {
                padding: 20px;
            }
            .hero-stats {
                gap: 20px;
            }
            .hero-stat {
                padding-left: 12px;
            }
            .hero-stat-num {
                font-size: 18px;
            }
            
            /* Hide desktop nav, show mobile menu button */
            .nav-center {
                display: none;
            }
            .mobile-menu-btn {
                display: block;
            }
            
            /* Adjust hero-nav for mobile */
            .hero-nav {
                flex-wrap: wrap;
                margin-bottom: 40px;
            }
            
            .profile-name {
                display: inline-block;
            }
            
            .dropdown-menu {
                width: 240px;
                right: -10px;
            }
        }

        @media (max-width: 480px) {
            .profile-name {
                display: none;
            }
            .btn-nav-login, .btn-nav-register {
                padding: 6px 12px;
                font-size: 12px;
            }
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        .animate-modalSlideIn {
            animation: modalSlideIn 0.2s ease-out;
        }
        .toast-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .fixed.inset-0.z-50 {
            z-index: 999999 !important;
        }

        .bg-white.rounded-2xl {
            z-index: 1000000 !important;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="hero">
    <div class="hero-dots"></div>
    <div class="hero-inner">
        {{-- Integrated Navigation --}}
        <div class="hero-nav">
            {{-- Left: Logo --}}
            <a href="/" class="nav-logo">
                <div class="nav-logo-icon"><i class="fas fa-bus"></i></div>
                <span class="nav-brand">Fareway <span>Bohol</span></span>
            </a>

            {{-- Desktop Navigation Links --}}
            <div class="nav-center">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="{{ route('user.recommended-places') }}" class="nav-link {{ request()->routeIs('user.recommended-places') ? 'active' : '' }}">
                    <i class="fas fa-map-marker-alt"></i> Places
                </a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i> About
                </a>
                <a href="{{ route('user.report.create') }}" class="nav-link {{ request()->routeIs('user.report.*') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i> Report
                </a>
            </div>

            {{-- Right: Auth Section & Mobile Menu Button --}}
            <div class="nav-right">
                @auth
                    {{-- Profile Dropdown --}}
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-trigger" onclick="toggleDropdown()">
                            <div class="profile-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="profile-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down profile-chevron"></i>
                        </div>
                        <div class="dropdown-menu">
                            <div class="dropdown-header">
                                <div class="dropdown-user-name">
                                    {{ Auth::user()->name }}
                                    @if(Auth::user()->role === 'admin')
                                        <span class="role-badge">Admin</span>
                                    @elseif(Auth::user()->role === 'moderator')
                                        <span class="role-badge">Moderator</span>
                                    @endif
                                </div>
                                <div class="dropdown-user-email">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="dropdown-items">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <i class="fas fa-user-circle"></i> Profile
                                </a>
                                <a href="{{ route('user.saved-routes') }}" class="dropdown-item">
                                    <i class="fas fa-bookmark"></i> Saved Items
                                </a>
                                <a href="{{ route('user.my-reports') }}" class="dropdown-item">
                                    <i class="fas fa-flag"></i> My Reports
                                </a>
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                        <i class="fas fa-shield-alt"></i> Admin Panel
                                    </a>
                                @elseif(Auth::user()->role === 'moderator')
                                    <a href="{{ route('moderator.dashboard') }}" class="dropdown-item">
                                        <i class="fas fa-user-check"></i> Moderator Panel
                                    </a>
                                @endif
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout">
                                        <i class="fas fa-sign-out-alt"></i> Log out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-nav-login">Login</a>
                    <a href="{{ route('register') }}" class="btn-nav-register">
                        <i class="fas fa-user-plus"></i> Sign Up
                    </a>
                @endauth
                
                {{-- Mobile Menu Button --}}
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            {{-- Mobile Navigation Links (hidden by default) --}}
            <div class="mobile-nav" id="mobileNav">
                <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="{{ route('user.recommended-places') }}" class="mobile-nav-link {{ request()->routeIs('user.recommended-places') ? 'active' : '' }}">
                    <i class="fas fa-map-marker-alt"></i> Places
                </a>
                <a href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    <i class="fas fa-info-circle"></i> About
                </a>
                <a href="{{ route('user.report.create') }}" class="mobile-nav-link {{ request()->routeIs('user.report.*') ? 'active' : '' }}">
                    <i class="fas fa-flag"></i> Report
                </a>
            </div>
        </div>

        {{-- Hero Content (only show on welcome page) --}}
        @yield('hero-content')
    </div>
</div>

{{-- Main Content - FULL WIDTH --}}
<main class="main-content">
    @yield('content')
</main>

<footer>
    &copy; {{ date('Y') }} <strong>Fareway Bohol</strong> — Helping you navigate Bohol with ease.
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

{{-- Global Toast Notification Container --}}
<div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"></div>

<script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNav = document.getElementById('mobileNav');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileNav.classList.toggle('active');
            const icon = mobileMenuBtn.querySelector('i');
            if (mobileNav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (mobileNav && mobileNav.classList.contains('active')) {
            if (!mobileNav.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                mobileNav.classList.remove('active');
                const icon = mobileMenuBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    });

    // Dropdown toggle function
    function toggleDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const trigger = dropdown?.querySelector('.profile-trigger');
        
        if (dropdown && trigger && !dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.classList.remove('active');
            }
            if (mobileNav && mobileNav.classList.contains('active')) {
                mobileNav.classList.remove('active');
                const icon = mobileMenuBtn.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    });

    // Global Toast Functions
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        const colors = {
            success: 'bg-emerald-500',
            error: 'bg-red-500',
            warning: 'bg-amber-500',
            info: 'bg-blue-500'
        };
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        toast.className = `toast-slide-in ${colors[type]} text-white px-5 py-3 rounded-lg shadow-lg text-sm font-medium flex items-center gap-2 min-w-[200px]`;
        toast.innerHTML = `<i class="fas ${icons[type]}"></i> ${message}`;
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    let confirmCallback = null;
</script>

@stack('scripts')

</body>
</html>