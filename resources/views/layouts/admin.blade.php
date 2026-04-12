{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal — @yield('title', 'Dashboard')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Toast Animation */
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
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        .toast-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        .toast-slide-out {
            animation: slideOut 0.3s ease-out;
        }
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-60 min-h-screen bg-[#1a2f4e] text-white flex flex-col fixed top-0 left-0 z-40">
        <div class="px-5 pt-5 pb-4 border-b border-[#243d5c]">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-gray-400 text-xs hover:text-white transition mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 5v6m0 0H5m4 0h4"/>
                </svg>
                Back to Home
            </a>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-teal-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bus text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Fareway Bohol</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Admin Portal</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard',           'label' => 'Dashboard',          'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['route' => 'admin.fare-rates.index',    'label' => 'Fare Rates',        'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['route' => 'admin.recommended-places.index', 'label' => 'Recommended Places', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route' => 'admin.users.index',         'label' => 'Users',             'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['route' => 'admin.reports.index',        'label' => 'User Reports',      'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                    ['route' => 'admin.activity-logs.index', 'label' => 'Activity Logs', 'icon' => 'M12 8v4l3 3M12 8V4m0 4h4M12 12l-3 3m3-3v4m0-4h-4M4 4h16v16H4z'],
                    ['route' => 'admin.analytics.index',      'label' => 'Analytics',         'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php 
                    $active = request()->routeIs($item['route']) || 
                              (str_contains($item['route'], 'index') && request()->routeIs(str_replace('.index', '', $item['route']) . '.*'));
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
                          {{ $active ? 'bg-teal-500 text-white shadow' : 'text-gray-300 hover:bg-[#243d5c] hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="px-4 py-4 border-t border-[#243d5c]">
            <div class="bg-[#243d5c] rounded-xl p-3">
                <p class="text-xs text-gray-400">Logged in as</p>
                <p class="text-sm font-semibold text-white mt-0.5">{{ auth()->user()->name }}</p>
                <p class="text-xs text-teal-400 mt-0.5">Administrator</p>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="text-xs text-red-400 hover:text-red-300 transition flex items-center gap-1">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="ml-60 flex-1 p-8 min-h-screen">
        @yield('content')
    </main>
</div>

{{-- Global Toast Container --}}
<div id="toast-container" class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"></div>

<script>
    // Global Toast Function
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) {
            console.error('Toast container not found');
            return;
        }
        
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
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('toast-slide-out');
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 300);
        }, 3000);
    }
    
    // Check for flash messages on page load
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    });
</script>

@stack('scripts')
</body>
</html>