<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $faviconVersion = file_exists(public_path('App-logo.png')) ? filemtime(public_path('App-logo.png')) : time();
        $faviconUrl = asset('App-logo.png') . '?v=' . $faviconVersion;
    @endphp

    <title>{{ config('app.name', 'Online Exam System') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $faviconUrl }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $isExamPage = request()->routeIs('student.exams.start');
    $isHome = request()->routeIs('home');
    $isAdmin = request()->is('admin/*') || request()->routeIs('admin.*');
    $isAdminResults = request()->routeIs('admin.results.*');
    $isGuest = ! auth()->check();
    $isStudent = auth()->check()
        && auth()->user()->role === 'student'
        && (request()->routeIs('student.*') || request()->routeIs('profile.*'));
    $adminBackgroundImage = ($isAdminResults && file_exists(public_path('images/admin-results-bg.jpg')))
        ? asset('images/admin-results-bg.jpg')
        : asset('images/admin-bg.jpg');
    $isGuestDemoLikePage = $isGuest && ! $isHome;
    $showAmbientEffects = $isGuestDemoLikePage;
@endphp

<body
class="min-h-screen text-gray-100 relative overflow-x-hidden {{ $isAdmin ? 'admin-layout' : (($isGuest || $isHome) ? 'guest-layout bg-gray-900' : ($isStudent ? 'student-layout' : '')) }}"
@if($isAdmin)
style="
    margin: 0;
    padding-top: 0;
    background-image:
        linear-gradient(rgba(0,0,0,0.62), rgba(0,0,0,0.62)),
        url('{{ $adminBackgroundImage }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
"
@elseif($isStudent)
style="
    background-image:
        linear-gradient(rgba(0,0,0,0.62), rgba(0,0,0,0.62)),
        url('{{ asset('images/admin-bg.jpg') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
"
@elseif($isGuestDemoLikePage)
style="
    background-image:
        linear-gradient(rgba(0,0,0,0.62), rgba(0,0,0,0.62)),
        url('{{ asset('images/hero1.jpg') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
"
@elseif($isGuest)
style="background:#020617;"
@endif
>

@if($isAdmin)
<style>
html, body.admin-layout {
    margin: 0 !important;
    padding: 0 !important;
}
body.admin-layout {
    padding-top: 0 !important;
}
body.admin-layout > .flex.min-h-screen.relative,
body.admin-layout #main,
body.admin-layout #main > header {
    margin-top: -16 !important;
    padding-top: 12 !important;
}
</style>
@endif

@if($isStudent)
<style>
html,
body.student-layout {
    height: 100%;
    background-color: #020617 !important;
    margin: 0 !important;
    padding: 0 !important;
}

body.student-layout {
    overflow: hidden;
}

body.student-layout > .flex.min-h-screen.relative {
    min-height: 100vh;
    height: 100vh;
}

body.student-layout #main {
    min-height: 0;
}

body.student-layout main {
    overscroll-behavior-y: contain;
}
</style>
@endif

@if(!$isAdmin && ($isGuest || $isHome))
<nav class="guest-navbar">
    <a href="{{ route('home') }}#home" target="_self" class="nav-logo flex items-center gap-2 logo-glow">
        <x-application-logo class="w-14 h-14 fill-current text-white logo-glow-item" />
        <img src="{{ asset('images/app-name.png') }}" alt="Academix Text Logo" class="h-14 logo-glow-item">
    </a>

    <ul class="nav-links">
        <li><a href="{{ route('home') }}#home" target="_self">Home</a></li>
        <li><a href="{{ route('home') }}#features" target="_self">Features</a></li>
        <li><a href="{{ route('home') }}#about" target="_self">About</a></li>
        <li><a href="{{ route('home') }}#benefits" target="_self">Benefits</a></li>
        <li><a href="{{ route('home') }}#contact" target="_self">Contact</a></li>
        <li><a href="{{ route('home') }}#support" target="_self">Support</a></li>
    </ul>

    <div class="nav-actions flex items-center gap-2">
        @guest
        <a href="{{ route('login') }}" class="nav-btn bg-blue-600/70 text-black font-semibold hover:bg-blue-800/100 hover:text-white">Login</a>
        <a href="{{ route('register') }}" class="nav-btn bg-orange-600/70 hover:bg-orange-700/90">Sign Up</a>
        @else
            @if(auth()->user()->role === 'student')
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-lg border border-white/25 bg-black/25 px-3 py-2 text-white hover:bg-black/40 transition">
                    <img
                        src="{{ auth()->user()->profile_photo
                                ? asset('storage/' . auth()->user()->profile_photo)
                                : (auth()->user()->sex === 'female'
                                    ? asset('images/default-female.png')
                                    : asset('images/default-male.png'))
                            }}"
                        alt="{{ auth()->user()->name }}"
                        class="h-8 w-8 rounded-full object-cover border border-white/30"
                    >
                    <span class="text-sm font-semibold">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline-flex items-center m-0">
                    @csrf
                    <button type="submit" class="flex items-center justify-center rounded-lg border border-red-300/30 bg-red-900/40 px-2 py-2 text-red-100 hover:bg-red-800/60 transition" title="Logout">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H9m8 7v1a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2h9a2 2 0 012 2v1"/>
                        </svg>
                    </button>
                </form>
                </a>
                
            @elseif(auth()->user()->role === 'admin')
                <span class="flex items-center gap-2 rounded-lg border border-white/25 bg-black/25 px-3 py-2 text-white">
                    <svg class="h-5 w-5 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7.5 7.5 0 0112 14.5a7.5 7.5 0 016.879 3.304M15 8a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">Admin</span>
                </span>
            @endif
        @endguest
    </div>
</nav>
@endif

@if(auth()->check() && !$isExamPage && !$isHome)
<style>
#sidebar {
    transition: transform 0.2s ease-in-out, width 0.2s ease-in-out;
}

#sidebar .sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    white-space: nowrap;
    position: relative;
    overflow: hidden;
    border-radius: 0.95rem;
    border: 1px solid transparent;
    padding: 0.85rem 0.95rem;
    color: rgb(226 232 240);
    transition: transform 0.24s ease, border-color 0.24s ease, background-color 0.24s ease, box-shadow 0.24s ease, color 0.24s ease;
}

#sidebar .sidebar-icon {
    width: 18px !important;
    height: 18px !important;
    min-width: 18px !important;
    min-height: 18px !important;
    max-width: 18px !important;
    max-height: 18px !important;
    flex: 0 0 18px !important;
    display: inline-block !important;
    overflow: visible;
    stroke-width: 2;
}

#sidebar .sidebar-text {
    transition: opacity 0.15s ease-in-out;
}

#sidebar .sidebar-link::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(115deg, rgba(255,255,255,0.12), transparent 36%);
    opacity: 0;
    transition: opacity 0.24s ease;
    pointer-events: none;
}

#sidebar .sidebar-link::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent 20%, rgba(255,255,255,0.14) 45%, transparent 70%);
    transform: translateX(-135%);
    opacity: 0;
    pointer-events: none;
}

#sidebar .sidebar-link:hover,
#sidebar .sidebar-link.is-active {
    transform: translateY(-2px);
    border-color: rgba(255, 255, 255, 0.14);
    background: linear-gradient(180deg, rgba(255,255,255,0.1), rgba(255,255,255,0.04));
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.12), 0 12px 24px rgba(2, 6, 23, 0.22);
    color: white;
}

#sidebar .sidebar-link:hover::before,
#sidebar .sidebar-link.is-active::before {
    opacity: 1;
}

body.student-layout #sidebar .sidebar-link:hover::after,
body.student-layout #sidebar .sidebar-link.is-active::after,
body.admin-layout #sidebar .sidebar-link:hover::after,
body.admin-layout #sidebar .sidebar-link.is-active::after {
    opacity: 1;
    animation: sidebarLinkShimmer 4.6s ease-in-out infinite;
}

#sidebar .student-card {
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.14);
    background:
        linear-gradient(180deg, rgba(204, 164, 6, 0.11), rgba(202, 169, 5, 0.04)),
        rgba(15, 23, 42, 0.34);
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.14), 0 16px 32px rgba(2, 6, 23, 0.22);
    backdrop-filter: blur(3px) saturate(115%);
    -webkit-backdrop-filter: blur(3px) saturate(115%);
    animation: sidebarReveal 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

#sidebar .student-card::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.14), transparent 34%);
    pointer-events: none;
}

#sidebar .student-card::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(110deg, transparent 20%, rgba(255,255,255,0.16) 45%, transparent 68%);
    transform: translateX(-135%);
    animation: sidebarCardShimmer 6.8s ease-in-out infinite;
    pointer-events: none;
}

#sidebar .student-card .student-avatar-ring {
    position: relative;
    width: 5.9rem;
    height: 5.9rem;
    margin: 0 auto 0.7rem;
    border-radius: 9999px;
    display: grid;
    place-items: center;
    box-shadow:
        0 0 0 1px rgba(255, 255, 255, 0.08),
        0 0 18px rgba(245, 158, 11, 0.12),
        0 12px 24px rgba(2, 6, 23, 0.22);
}

#sidebar .student-card .student-avatar-ring::before {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: inherit;
    padding: 4px;
    background:
        conic-gradient(
            from 210deg,
            rgba(255, 243, 176, 0.98),
            rgba(251, 191, 36, 1),
            rgba(245, 158, 11, 0.98),
            rgba(253, 224, 71, 1),
            rgba(255, 243, 176, 0.98)
        );
    -webkit-mask:
        linear-gradient(#000 0 0) content-box,
        linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
    mask:
        linear-gradient(#000 0 0) content-box,
        linear-gradient(#000 0 0);
    mask-composite: exclude;
    animation: sidebarAvatarRingRotate 6.8s linear infinite, sidebarAvatarRingPulse 3.2s ease-in-out infinite;
    box-shadow:
        inset 0 0 0 1px rgba(255, 248, 220, 0.18),
        0 0 22px rgba(245, 158, 11, 0.2);
}

#sidebar .student-card .student-avatar-ring::after {
    content: "✦";
    position: absolute;
    z-index: 2;
    top: 50%;
    left: 50%;
    font-size: 0.78rem;
    line-height: 1;
    color: rgba(255, 251, 235, 0.95);
    text-shadow:
        0 0 8px rgba(255, 255, 255, 0.95),
        0 0 16px rgba(251, 191, 36, 0.6);
    animation: sidebarAvatarStarOrbit 6.8s linear infinite, sidebarAvatarStarPulse 1.8s ease-in-out infinite;
    transform-origin: center;
    pointer-events: none;
}

#sidebar .student-card .student-avatar-ring img {
    position: relative;
    z-index: 1;
    width: calc(100% - 10px);
    height: calc(100% - 10px);
    display: block;
    border-radius: 9999px;
    object-fit: cover;
    object-position: center 25%;
    background: rgba(15, 23, 42, 0.82);
    border: 3px solid rgba(15, 23, 42, 0.96);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
}

body.student-layout #sidebar .sidebar-link,
body.admin-layout #sidebar .sidebar-link {
    animation: sidebarReveal 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    opacity: 0;
    transform: translateY(14px);
}

body.student-layout #sidebar .sidebar-link:nth-of-type(1),
body.admin-layout #sidebar .sidebar-link:nth-of-type(1) { animation-delay: 0.08s; }
body.student-layout #sidebar .sidebar-link:nth-of-type(2),
body.admin-layout #sidebar .sidebar-link:nth-of-type(2) { animation-delay: 0.14s; }
body.student-layout #sidebar .sidebar-link:nth-of-type(3),
body.admin-layout #sidebar .sidebar-link:nth-of-type(3) { animation-delay: 0.20s; }
body.student-layout #sidebar .sidebar-link:nth-of-type(4),
body.admin-layout #sidebar .sidebar-link:nth-of-type(4) { animation-delay: 0.26s; }
body.admin-layout #sidebar .sidebar-link:nth-of-type(5) { animation-delay: 0.32s; }

@keyframes sidebarReveal {
    from {
        opacity: 0;
        transform: translateY(14px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes sidebarCardShimmer {
    0%, 100% {
        transform: translateX(-135%);
    }
    46%, 58% {
        transform: translateX(135%);
    }
}

@keyframes sidebarLinkShimmer {
    0%, 100% {
        transform: translateX(-135%);
    }
    48%, 60% {
        transform: translateX(135%);
    }
}

@keyframes sidebarAvatarRingRotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

@keyframes sidebarAvatarRingPulse {
    0%, 100% {
        box-shadow:
            0 0 0 1px rgba(255, 255, 255, 0.08),
            0 0 20px rgba(245, 158, 11, 0.12),
            0 12px 24px rgba(2, 6, 23, 0.22);
    }
    50% {
        box-shadow:
            0 0 0 1px rgba(255, 255, 255, 0.12),
            0 0 34px rgba(245, 158, 11, 0.24),
            0 14px 28px rgba(2, 6, 23, 0.24);
    }
}

@keyframes sidebarAvatarStarOrbit {
    from {
        transform: rotate(0deg) translateY(calc(-50% - 2.95rem)) rotate(0deg);
    }
    to {
        transform: rotate(360deg) translateY(calc(-50% - 2.95rem)) rotate(-360deg);
    }
}

@keyframes sidebarAvatarStarPulse {
    0%, 100% {
        opacity: 0.72;
        text-shadow:
            0 0 7px rgba(255, 255, 255, 0.85),
            0 0 14px rgba(251, 191, 36, 0.48);
    }
    50% {
        opacity: 1;
        text-shadow:
            0 0 10px rgba(255, 255, 255, 1),
            0 0 20px rgba(251, 191, 36, 0.72);
    }
}

@media (prefers-reduced-motion: reduce) {
    #sidebar .student-card,
    body.student-layout #sidebar .sidebar-link,
    body.admin-layout #sidebar .sidebar-link {
        animation: none !important;
        opacity: 1;
        transform: none;
    }

    #sidebar .student-card::after,
    #sidebar .sidebar-link::after,
    #sidebar .student-card .student-avatar-ring,
    #sidebar .student-card .student-avatar-ring::before,
    #sidebar .student-card .student-avatar-ring::after {
        animation: none !important;
    }
}

#sidebar.sidebar-collapsed {
    width: 5rem;
}

#sidebar.sidebar-collapsed .sidebar-text {
    opacity: 0;
    width: 0;
    overflow: hidden;
}

#sidebar.sidebar-collapsed .student-card,
#sidebar.sidebar-collapsed hr {
    display: none;
}

#sidebar .sidebar-logo .logo-icon {
    display: none;
}

#sidebar.sidebar-collapsed .sidebar-logo .logo-full {
    display: none;
}

#sidebar.sidebar-collapsed .sidebar-logo .logo-icon {
    display: block;
    width: 42px;
    height: 42px;
    object-fit: contain;
}

#sidebar.sidebar-collapsed .sidebar-link {
    justify-content: center;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

#sidebar.sidebar-collapsed form button {
    width: 2.75rem;
    padding-left: 0;
    padding-right: 0;
}

#sidebar.sidebar-collapsed form button .logout-text {
    display: none;
}

.admin-gap-cover {
    margin-top: -56px;
    padding-top: 12px;
}
</style>
@endif

@if($showAmbientEffects)
<style>
#particles {
    position: fixed;
    width: 100%;
    height: 100%;
    z-index: 0;
}

#mouseGlow {
    position: fixed;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle,#3b82f6,transparent);
    pointer-events: none;
    border-radius: 50%;
    filter: blur(80px);
    opacity: .25;
    z-index: 1;
}
</style>

<canvas id="particles"></canvas>
<div id="mouseGlow"></div>
@endif

<div class="flex min-h-screen relative">

@auth
@if(!$isExamPage && !$isHome)
<div id="overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"></div>

<aside id="sidebar"
    style="
        background:
            linear-gradient(rgba(0,0,0,0.75), transparent),
            url('/images/sidebar.jpg') no-repeat center center;
        background-size: cover;
    "
    class="fixed top-0 left-0 z-40 w-64 h-full bg-black/40 backdrop-blur-sm border-r border-gray-700 transform -translate-x-full transition-transform duration-200 ease-in-out">

    <div class="p-4 border-b border-gray-700 flex justify-center sidebar-logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/app-name.png') }}" alt="Academix" class="h-14 object-contain logo-full">
            <img src="{{ asset('App-logo.png') }}" alt="Academix Icon" class="logo-icon">
        </a>
    </div>

    <nav class="p-4 space-y-2 text-sm">
        @php
            $role = auth()->user()->role;
        @endphp

        @if($role === 'student')
            <div class="student-card rounded-xl p-4 text-center mb-4">
                <div class="student-avatar-ring">
                    <img
                        src="{{ auth()->user()->profile_photo
                                ? asset('storage/' . auth()->user()->profile_photo)
                                : (auth()->user()->sex === 'female'
                                    ? asset('images/default-female.png')
                                    : asset('images/default-male.png'))
                            }}"
                        alt="{{ auth()->user()->name }}"
                    >
                </div>

                <h3 class="font-semibold text-base leading-tight">{{ auth()->user()->name }}</h3>
                <p class="text-xs text-gray-300 mt-1">Reg No: {{ auth()->user()->registration_no ?? 'N/A' }}</p>
                <p class="text-xs text-gray-400">Semester: {{ auth()->user()->semester ?? 'N/A' }}</p>
                @if(auth()->user()->college_name)
                <p class="text-[11px] text-gray-400 mt-1">{{ auth()->user()->college_name }}</p>
                @endif
            </div>
            <hr class="border-gray-600/40 mb-3">
        @endif

        @if($role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" title="Dashboard">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/></svg>
                <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="{{ route('admin.students.index') }}" class="sidebar-link {{ request()->routeIs('admin.students.*') ? 'is-active' : '' }}" title="Student Management">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-8 0v2m8 0H9m8-10a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span class="sidebar-text">Student Management</span>
            </a>
            <a href="{{ route('admin.exams.index') }}" class="sidebar-link {{ request()->routeIs('admin.exams.*') || request()->routeIs('admin.questions.*') || request()->routeIs('admin.options.*') ? 'is-active' : '' }}" title="Manage Exams">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6M5 7h14M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>
                <span class="sidebar-text">Manage Exams</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="sidebar-link {{ request()->routeIs('admin.analytics') ? 'is-active' : '' }}" title="Analytics">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v18m-6-6h12m-9 6V9m6 12V5"/></svg>
                <span class="sidebar-text">Analytics</span>
            </a>
            <a href="{{ route('admin.results.index') }}" class="sidebar-link {{ request()->routeIs('admin.results.*') ? 'is-active' : '' }}" title="Results">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="sidebar-text">Results</span>
            </a>
        @endif

        @if($role === 'student')
            <a href="{{ route('student.dashboard') }}" class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'is-active' : '' }}" title="Dashboard">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6v-6h4v6h6V10"/></svg>
                <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'is-active' : '' }}" title="My Profile">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7.5 7.5 0 0112 14.5a7.5 7.5 0 016.879 3.304M15 8a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="sidebar-text">My Profile</span>
            </a>
            <a href="{{ route('student.exams.index') }}" class="sidebar-link {{ request()->routeIs('student.exams.*') ? 'is-active' : '' }}" title="Exams">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                <span class="sidebar-text">Exams</span>
            </a>
            <a href="{{ route('student.results.index') }}" class="sidebar-link {{ request()->routeIs('student.results.*') ? 'is-active' : '' }}" title="My Results">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m3 6V7m3 10V4m5 16H4a1 1 0 01-1-1V4a1 1 0 011-1h16a1 1 0 011 1v15a1 1 0 01-1 1z"/></svg>
                <span class="sidebar-text">My Results</span>
            </a>
        @endif

        <center>
        <form method="POST" action="{{ route('logout') }}" class="pt-10">
            @csrf
            <button class="w-20 h-10 flex items-center justify-center rounded-lg bg-red-800/70 text-red-200 font-semibold hover:bg-red-600/100 hover:text-red-200 hover:underline transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H9m8 7v1a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2h9a2 2 0 012 2v1"/>
                </svg>
                <span class="logout-text ml-2">Logout</span>
            </button>
        </form>
        </center>
    </nav>
</aside>
@endif
@endauth

<div id="main" class="flex-1 flex flex-col transition-all duration-200 ease-in-out">
@auth
@if(!$isExamPage && !$isHome)
<header class="flex items-center gap-3 p-4 bg-black/40 backdrop-blur-sm shadow {{ $isAdmin ? 'admin-gap-cover' : '' }}">
    <button onclick="toggleSidebar()" class="p-2 rounded bg-gray-700 hover:bg-gray-600">
        &#9776;
    </button>
    <span class="font-semibold hidden sm:inline">
        {{ auth()->user()->role === 'admin' ? 'Admin Panel' : 'Student Panel' }}
    </span>
</header>
@endif
@endauth

<main class="flex-1 overflow-y-auto {{ ($isStudent || $isHome) ? 'p-0' : 'p-6' }} bg-transparent relative z-10">
    {{ $slot }}
</main>

</div>
</div>

<div id="appDialog" class="app-dialog hidden" aria-hidden="true">
    <div class="app-dialog-backdrop"></div>
    <div class="app-dialog-panel" role="dialog" aria-modal="true" aria-labelledby="appDialogTitle">
        <div class="app-dialog-accent"></div>
        <div class="app-dialog-body">
            <p id="appDialogEyebrow" class="app-dialog-eyebrow">Notice</p>
            <h3 id="appDialogTitle" class="app-dialog-title">Message</h3>
            <p id="appDialogMessage" class="app-dialog-message"></p>
        </div>
        <div class="app-dialog-actions">
            <button type="button" id="appDialogCancel" class="app-dialog-btn app-dialog-btn-muted hidden">Cancel</button>
            <button type="button" id="appDialogConfirm" class="app-dialog-btn app-dialog-btn-primary">OK</button>
        </div>
    </div>
</div>

<style>
.app-dialog {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.app-dialog.hidden {
    display: none;
}

.app-dialog-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.52);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    animation: appDialogBackdropIn 0.2s ease forwards;
}

.app-dialog-panel {
    position: relative;
    width: min(100%, 27rem);
    overflow: hidden;
    border-radius: 0.95rem;
    border: 1px solid rgba(148, 163, 184, 0.24);
    background: rgba(15, 23, 42, 0.96);
    box-shadow: 0 20px 48px rgba(2, 6, 23, 0.42);
    animation: appDialogPanelIn 0.28s cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

.app-dialog-accent {
    height: 1px;
    background: rgba(148, 163, 184, 0.18);
}

.app-dialog-body {
    padding: 1.1rem 1.1rem 0.75rem;
}

.app-dialog-eyebrow {
    margin: 0 0 0.45rem;
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: rgb(148 163 184);
}

.app-dialog.is-confirm .app-dialog-eyebrow {
    color: rgb(248 113 113);
}

.app-dialog-title {
    margin: 0;
    color: white;
    font-size: 1.02rem;
    font-weight: 600;
}

.app-dialog-message {
    margin: 0.65rem 0 0;
    color: rgb(203 213 225);
    font-size: 0.92rem;
    line-height: 1.55;
    white-space: pre-line;
}

.app-dialog-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
    padding: 0.85rem 1.1rem 1.1rem;
}

.app-dialog-btn {
    min-width: 5.5rem;
    border: 1px solid transparent;
    border-radius: 0.7rem;
    padding: 0.62rem 0.95rem;
    font-weight: 600;
    font-size: 0.9rem;
    transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
}

.app-dialog-btn:hover {
    transform: none;
    filter: none;
}

.app-dialog-btn-muted {
    background: rgba(51, 65, 85, 0.82);
    border-color: rgba(148, 163, 184, 0.16);
    color: rgb(226 232 240);
}

.app-dialog-btn-primary {
    background: rgb(37 99 235);
    border-color: rgba(96, 165, 250, 0.32);
    color: rgb(254 202 202);
}

.app-dialog-btn-muted:hover {
    background: rgba(71, 85, 105, 0.92);
}

.app-dialog-btn-primary:hover {
    background: rgb(29 78 216);
}

@keyframes appDialogBackdropIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes appDialogPanelIn {
    from {
        opacity: 0;
        transform: translateY(90px) scale(0.98);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>

@if(auth()->check() && !$isExamPage && !$isHome)
<script>
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const main = document.getElementById('main');
const DESKTOP_MIN_WIDTH = 1024;
const MAIN_OFFSET_EXPANDED = 'lg:ml-64';
const MAIN_OFFSET_COLLAPSED = 'lg:ml-20';
const SIDEBAR_COLLAPSED_STORAGE_KEY = 'sidebarCollapsed';
let isSidebarCollapsed = false;

function isDesktopView() {
    return window.innerWidth >= DESKTOP_MIN_WIDTH;
}

function applyMainOffset() {
    main?.classList.remove(MAIN_OFFSET_EXPANDED, MAIN_OFFSET_COLLAPSED);
    if (isDesktopView() && !sidebar?.classList.contains('-translate-x-full')) {
        main?.classList.add(isSidebarCollapsed ? MAIN_OFFSET_COLLAPSED : MAIN_OFFSET_EXPANDED);
    }
}

function setSidebarCollapsed(collapsed) {
    isSidebarCollapsed = collapsed;
    sidebar?.classList.toggle('sidebar-collapsed', collapsed);
    applyMainOffset();
    try {
        localStorage.setItem(SIDEBAR_COLLAPSED_STORAGE_KEY, collapsed ? '1' : '0');
    } catch {}
}

function openSidebar() {
    sidebar?.classList.remove('-translate-x-full');
    if (isDesktopView()) {
        applyMainOffset();
        overlay?.classList.add('hidden');
    } else {
        main?.classList.remove(MAIN_OFFSET_EXPANDED, MAIN_OFFSET_COLLAPSED);
        sidebar?.classList.remove('sidebar-collapsed');
        overlay?.classList.remove('hidden');
    }
}

function closeSidebar() {
    sidebar?.classList.add('-translate-x-full');
    overlay?.classList.add('hidden');
    main?.classList.remove(MAIN_OFFSET_EXPANDED, MAIN_OFFSET_COLLAPSED);
}

function toggleSidebar() {
    if (isDesktopView()) {
        if (sidebar?.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            setSidebarCollapsed(!isSidebarCollapsed);
        }
    } else {
        if (sidebar?.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    }
}

overlay?.addEventListener('click', () => {
    closeSidebar();
});

window.addEventListener('resize', () => {
    if (isDesktopView()) {
        sidebar?.classList.remove('-translate-x-full');
        setSidebarCollapsed(isSidebarCollapsed);
        overlay?.classList.add('hidden');
    } else {
        main?.classList.remove(MAIN_OFFSET_EXPANDED, MAIN_OFFSET_COLLAPSED);
    }
});

document.querySelectorAll('#sidebar a').forEach((link) => {
    link.addEventListener('click', () => {
        if (!isDesktopView()) {
            closeSidebar();
        }
    });
});

try {
    isSidebarCollapsed = localStorage.getItem(SIDEBAR_COLLAPSED_STORAGE_KEY) === '1';
} catch {}

if (isDesktopView()) {
    openSidebar();
    setSidebarCollapsed(isSidebarCollapsed);
} else {
    closeSidebar();
}
</script>
@endif

<script>
(() => {
    const dialog = document.getElementById('appDialog');
    const title = document.getElementById('appDialogTitle');
    const message = document.getElementById('appDialogMessage');
    const eyebrow = document.getElementById('appDialogEyebrow');
    const confirmBtn = document.getElementById('appDialogConfirm');
    const cancelBtn = document.getElementById('appDialogCancel');
    const backdrop = dialog?.querySelector('.app-dialog-backdrop');
    if (!dialog || !title || !message || !confirmBtn || !cancelBtn || !backdrop || window.appConfirm) {
        return;
    }

    let resolver = null;
    let rejectOnClose = false;

    function closeDialog(result = false) {
        dialog.classList.add('hidden');
        dialog.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('app-dialog-open');

        if (resolver) {
            resolver(result);
            resolver = null;
        }
    }

    function openDialog(options = {}) {
        title.textContent = options.title || 'Notice';
        message.textContent = options.message || '';
        eyebrow.textContent = options.eyebrow || 'Notice';
        confirmBtn.textContent = options.confirmText || 'OK';
        cancelBtn.textContent = options.cancelText || 'Cancel';
        cancelBtn.classList.toggle('hidden', !options.showCancel);
        dialog.classList.toggle('is-confirm', !!options.showCancel);
        dialog.classList.remove('hidden');
        dialog.setAttribute('aria-hidden', 'false');
        document.body.classList.add('app-dialog-open');
        window.setTimeout(() => confirmBtn.focus(), 20);

        return new Promise((resolve) => {
            resolver = resolve;
        });
    }

    confirmBtn.addEventListener('click', () => closeDialog(true));
    cancelBtn.addEventListener('click', () => closeDialog(false));
    backdrop.addEventListener('click', () => closeDialog(false));
    document.addEventListener('keydown', (event) => {
        if (dialog.classList.contains('hidden')) return;
        if (event.key === 'Escape') closeDialog(false);
    });

    window.appAlert = function (messageText, options = {}) {
        return openDialog({
            title: options.title || 'Warning',
            eyebrow: options.eyebrow || 'Notice',
            message: messageText,
            confirmText: options.confirmText || 'OK',
            showCancel: false,
        });
    };

    window.appConfirm = function (messageText, options = {}) {
        return openDialog({
            title: options.title || 'Please Confirm',
            eyebrow: options.eyebrow || 'Confirmation',
            message: messageText,
            confirmText: options.confirmText || 'Confirm',
            cancelText: options.cancelText || 'Cancel',
            showCancel: true,
        });
    };
})();
</script>

@if($showAmbientEffects)
<script>
const canvas = document.getElementById("particles");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particles = [];

for (let i = 0; i < 80; i++) {
    particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: Math.random() * 2 + 0.5,
        dx: (Math.random() - 0.5) * 0.5,
        dy: (Math.random() - 0.5) * 0.5
    });
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    particles.forEach((p) => {
        p.x += p.dx;
        p.y += p.dy;

        if (p.x < 0 || p.x > canvas.width) p.dx = -p.dx;
        if (p.y < 0 || p.y > canvas.height) p.dy = -p.dy;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = "rgba(255,255,255,0.6)";
        ctx.fill();
    });

    requestAnimationFrame(animate);
}

animate();

window.addEventListener('resize', () => {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
});

const mouseGlow = document.getElementById('mouseGlow');
document.addEventListener("mousemove", (e) => {
    mouseGlow.style.left = e.clientX - 150 + "px";
    mouseGlow.style.top = e.clientY - 150 + "px";
});
</script>
@endif

</body>
</html>
