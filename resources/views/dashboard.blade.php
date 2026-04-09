<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bright Sphere • Father Saturnino Urios University</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f5f7fb;
        }

        /* Sidebar styles */
        .sidebar {
            background: linear-gradient(180deg, #1a1c2e 0%, #2d2f42 100%);
            transition: all 0.3s ease;
        }

        .nav-item {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #6366f1;
        }

        .nav-item.active {
            background: rgba(99, 102, 241, 0.15);
            border-left-color: #6366f1;
        }

        .nav-item.active i, .nav-item.active span {
            color: #6366f1;
        }

        /* Card hover effects */
        .stat-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -10px rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Progress bar animation */
        .progress-bar {
            transition: width 1s ease-in-out;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c7d2fe;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #818cf8;
        }
        
        /* Empty state styling */
        .empty-state {
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        
        .empty-state:hover {
            opacity: 1;
        }
        
        /* Urian Values styling */
        .urian-value {
            transition: all 0.3s ease;
        }
        
        .urian-value:hover {
            transform: translateY(-3px);
        }
        
        /* Welcome section animations */
        .welcome-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Top navigation styles */
        .top-nav-link {
            transition: all 0.3s ease;
            position: relative;
        }
        
        .top-nav-link.active {
            color: #4f46e5;
            font-weight: 600;
        }
        
        .top-nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 2px;
            background: #4f46e5;
            border-radius: 2px;
        }
        
        .top-nav-link:hover {
            color: #4f46e5;
        }
        
        /* Dropdown styles */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }
        
        .dropdown.open .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        /* Logo animation */
        .logo-icon {
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
            }
        }

        /* Chat/Messaging Styles */
        .message-bubble {
            transition: all 0.2s ease;
        }
        
        .message-bubble:hover {
            transform: translateX(5px);
        }
        
        .instructor-card {
            transition: all 0.3s ease;
        }
        
        .instructor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        /* Modal animation */
        .modal-enter {
            animation: modalFadeIn 0.3s ease-out;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar - Left Navigation -->
        <div class="sidebar w-72 flex-shrink-0 hidden md:flex flex-col text-white shadow-2xl">
            <!-- Logo Section -->
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-white shadow-lg logo-icon">
                        <i class="fa-solid fa-graduation-cap text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text text-transparent">Bright Sphere</h1>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-[0.2em] mt-1">BSU Portal</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="flex-1 px-4 space-y-2">
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider px-4 mb-2">Menu</p>
                
                <a href="{{ route('dashboard') }}" class="nav-item active flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-chart-pie w-6 text-lg"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('student.courses') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-chalkboard-user w-6 text-lg"></i>
                    <span class="font-medium">Courses</span>
                </a>

                <a href="{{ route('student.registration') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-clipboard-list w-6 text-lg"></i>
                    <span class="font-medium">Registration</span>
                </a>

                <a href="{{ route('student.notices') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-bullhorn w-6 text-lg"></i>
                    <span class="font-medium">Notices</span>
                </a>

                <!-- Messages Link -->
                <a href="#" onclick="switchTab('messages'); return false;" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-envelope w-6 text-lg"></i>
                    <span class="font-medium">Messages</span>
                    @if($unreadMessagesCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5">{{ $unreadMessagesCount }}</span>
                    @endif
                </a>

                <div class="border-t border-white/10 my-4"></div>

                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider px-4 mb-2">Management</p>

                <a href="{{ route('students.index') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-user-graduate w-6 text-lg"></i>
                    <span class="font-medium">Students</span>
                </a>
            </div>

            <!-- Logout Button -->
            <div class="p-6 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item flex items-center gap-4 px-4 py-3 w-full rounded-xl text-red-300 hover:text-red-200 transition">
                        <i class="fa-solid fa-right-from-bracket w-6 text-lg"></i>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
            <div class="flex justify-around p-3">
                <a href="{{ route('dashboard') }}" class="text-indigo-600"><i class="fa-solid fa-chart-pie text-xl"></i></a>
                <a href="{{ route('student.courses') }}" class="text-gray-400"><i class="fa-solid fa-chalkboard-user text-xl"></i></a>
                <a href="{{ route('student.registration') }}" class="text-gray-400"><i class="fa-solid fa-clipboard-list text-xl"></i></a>
                <a href="{{ route('student.notices') }}" class="text-gray-400"><i class="fa-solid fa-bullhorn text-xl"></i></a>
                <a href="#" onclick="switchTab('messages'); return false;" class="text-gray-400"><i class="fa-solid fa-envelope text-xl"></i></a>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto bg-gray-50">
            <!-- Top Header -->
            <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
                <div class="px-8 py-6">
                    <!-- University Name -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-graduation-cap text-indigo-600"></i>
                            Bright Sphere University
                        </h1>
                        <p class="text-xs text-gray-500 mt-1">Excellence • Service • Leadership</p>
                    </div>
                    
                    <!-- Navigation Tabs and User Profile Row -->
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <!-- Navigation Tabs -->
                        <div class="flex gap-8">
                            <button id="tabDashboard" onclick="switchTab('dashboard')" class="top-nav-link active text-gray-700 hover:text-indigo-600 font-medium pb-2 transition">
                                Dashboard
                            </button>
                            <button id="tabInstructors" onclick="switchTab('instructors')" class="top-nav-link text-gray-500 hover:text-indigo-600 font-medium pb-2 transition">
                                Instructors
                            </button>
                            <button id="tabMessages" onclick="switchTab('messages')" class="top-nav-link text-gray-500 hover:text-indigo-600 font-medium pb-2 transition">
                                Messages
                            </button>
                            <button id="tabNews" onclick="switchTab('news')" class="top-nav-link text-gray-500 hover:text-indigo-600 font-medium pb-2 transition">
                                News
                            </button>
                            <button id="tabWelcomeNav" onclick="switchTab('welcome')" class="top-nav-link text-gray-500 hover:text-indigo-600 font-medium pb-2 transition">
                                Welcome
                            </button>
                        </div>
                        
                        <!-- User Profile Dropdown -->
                        <div class="flex items-center gap-4">
                            <button class="text-gray-400 hover:text-indigo-600 transition">
                                <i class="fa-solid fa-magnifying-glass text-lg"></i>
                            </button>
                            
                            <div class="relative dropdown" id="userDropdown">
                                <button onclick="toggleDropdown()" class="flex items-center gap-3 pl-4 border-l border-gray-200 cursor-pointer hover:bg-gray-50 rounded-lg transition px-3 py-2">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                                        {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <p class="font-semibold text-gray-800 text-sm">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform" id="dropdownIcon"></i>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div class="dropdown-menu absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
                                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                                        <div class="flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                                                {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900 text-lg">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h3>
                                                <p class="text-sm text-gray-500 flex items-center gap-1 mt-1">
                                                    <i class="fa-solid fa-envelope text-xs"></i>
                                                    {{ auth()->user()->email }}
                                                </p>
                                                <p class="text-xs text-indigo-600 mt-1">
                                                    <i class="fa-solid fa-graduation-cap mr-1"></i>{{ ucfirst(auth()->user()->role) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6 border-b border-gray-100">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Personal Information</h4>
                                            <button onclick="openProfileModal()" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium flex items-center gap-1">
                                                <i class="fa-solid fa-pen"></i> Edit
                                            </button>
                                        </div>
                                        <div class="space-y-3">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-500">First Name</span>
                                                <span class="text-sm font-medium text-gray-900">{{ auth()->user()->first_name }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-500">Middle Name</span>
                                                <span class="text-sm font-medium text-gray-900">{{ auth()->user()->middle_name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-500">Last Name</span>
                                                <span class="text-sm font-medium text-gray-900">{{ auth()->user()->last_name }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-gray-500">Email Address</span>
                                                <span class="text-sm font-medium text-gray-900">{{ auth()->user()->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6 border-b border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Security</h4>
                                        <button onclick="openPasswordModal()" class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-key"></i>
                                            Change Password
                                        </button>
                                    </div>
                                    
                                    <div class="p-6">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-3 px-4 rounded-xl transition flex items-center justify-center gap-2">
                                                <i class="fa-solid fa-sign-out-alt"></i>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Dashboard Tab -->
            <div id="dashboardContent" class="p-8 pb-24 md:pb-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600"><i class="fa-solid fa-check"></i></div>
                            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600"><i class="fa-solid fa-exclamation-triangle"></i></div>
                            <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                @endif

                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-8 mb-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-500/20 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <h2 class="text-3xl font-extrabold mb-2">
                            Welcome back, {{ auth()->user()->first_name }}! 👋
                        </h2>
                        <p class="text-indigo-100 text-lg max-w-2xl">
                            @if($totalStudents > 0 || $totalInstructors > 0 || $totalCourses > 0)
                                Here's what's happening with your academic journey today.
                            @else
                                Get started by exploring your courses and connecting with your instructors.
                            @endif
                        </p>
                        
                        <!-- Quick Stats Row -->
                        <div class="flex flex-wrap gap-4 mt-6">
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
                                <i class="fa-solid fa-fire text-yellow-300"></i>
                                <span class="text-sm font-semibold">{{ $streakDays }} Day Streak</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
                                <i class="fa-solid fa-users text-green-300"></i>
                                <span class="text-sm font-semibold">{{ $totalStudents }} Student{{ $totalStudents != 1 ? 's' : '' }}</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
                                <i class="fa-solid fa-chalkboard-user text-yellow-300"></i>
                                <span class="text-sm font-semibold">{{ $totalInstructors }} Instructor{{ $totalInstructors != 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-blue-100 rounded-xl flex items-center justify-center text-indigo-600 shadow-sm">
                                <i class="fa-solid fa-book-open text-xl"></i>
                            </div>
                            <span class="text-xs px-2 py-1 bg-indigo-50 text-indigo-600 rounded-full font-semibold">{{ $totalCourses }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Enrolled Courses</p>
                        <p class="text-4xl font-bold text-gray-900">{{ $totalCourses }}</p>
                        <p class="text-xs text-gray-400 mt-2">
                            @if($totalCourses > 0)
                                {{ $courseProgress['completed'] ?? 0 }} completed
                            @else
                                No courses enrolled yet
                            @endif
                        </p>
                    </div>

                    <div class="stat-card bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl flex items-center justify-center text-green-600 shadow-sm">
                                <i class="fa-solid fa-users text-xl"></i>
                            </div>
                            <span class="text-xs px-2 py-1 bg-green-50 text-green-600 rounded-full font-semibold">{{ $totalStudents }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Total Students</p>
                        <p class="text-4xl font-bold text-gray-900">{{ $totalStudents }}</p>
                        <div class="mt-3 pt-2 border-t border-gray-100">
                            <div class="flex justify-between text-xs">
                                <span>Active Rate</span>
                                <span class="font-medium text-green-600">{{ $totalStudents > 0 ? round(($activeStudents ?? 0) / $totalStudents * 100) : 0 }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full mt-1 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" style="width: {{ $totalStudents > 0 ? round(($activeStudents ?? 0) / $totalStudents * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center text-purple-600 shadow-sm">
                                <i class="fa-solid fa-chalkboard-user text-xl"></i>
                            </div>
                            <span class="text-xs px-2 py-1 bg-purple-50 text-purple-600 rounded-full font-semibold">{{ $totalInstructors }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Total Instructors</p>
                        <p class="text-4xl font-bold text-gray-900">{{ $totalInstructors }}</p>
                    </div>

                    <div class="stat-card bg-white rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-100 to-amber-100 rounded-xl flex items-center justify-center text-yellow-600 shadow-sm">
                                <i class="fa-solid fa-layer-group text-xl"></i>
                            </div>
                            <span class="text-xs px-2 py-1 bg-yellow-50 text-yellow-600 rounded-full font-semibold">{{ $totalSubjects }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mb-1">Total Subjects</p>
                        <p class="text-4xl font-bold text-gray-900">{{ $totalSubjects }}</p>
                    </div>
                </div>

                <!-- Academic Summary Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl p-6 text-white">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-ranking-star"></i> Academic Summary
                        </h3>
                        <div class="text-center mb-6">
                            <p class="text-5xl font-bold mb-2">{{ $overallGrade['gpa'] }}</p>
                            <p class="text-indigo-100">Overall GPA</p>
                            <div class="mt-2 inline-block px-4 py-1 bg-white/20 rounded-full text-sm font-semibold">
                                {{ $overallGrade['letter'] }} Average
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-indigo-100">Total Credits</span>
                                <span class="font-bold">{{ $overallGrade['total_credits'] }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-indigo-100">Courses Completed</span>
                                <span class="font-bold">{{ $courseProgress['completed'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    @if(!empty($upcomingAssignments))
                    <div class="bg-white rounded-2xl p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-calendar-check text-indigo-600"></i> Upcoming Tasks
                        </h3>
                        <div class="space-y-3">
                            @foreach($upcomingAssignments as $assignment)
                            <div class="flex items-center justify-between p-3 bg-{{ $assignment['color'] }}-50 rounded-xl">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $assignment['task'] }}</p>
                                    <p class="text-xs text-gray-600">{{ $assignment['course'] }} • Due {{ $assignment['due'] }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 bg-{{ $assignment['color'] }}-200 text-{{ $assignment['color'] }}-700 rounded-full">{{ ucfirst($assignment['priority']) }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="bg-white rounded-2xl p-6 shadow-sm flex items-center justify-center">
                        <div class="text-center">
                            <i class="fa-solid fa-check-circle text-green-500 text-4xl mb-3"></i>
                            <p class="text-gray-500">No upcoming assignments</p>
                            <p class="text-xs text-gray-400 mt-1">You're all caught up!</p>
                        </div>
                    </div>
                    @endif
                </div>

                @if($totalCourses == 0 && $totalStudents == 0 && $totalInstructors == 0)
                <div class="bg-white rounded-2xl p-12 shadow-sm text-center">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-database text-4xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">No Data Available</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Start by adding students, instructors, and courses to populate your dashboard.</p>
                </div>
                @endif
            </div>

            <!-- Instructors Tab Content -->
            <div id="instructorsContent" class="p-8 pb-24 md:pb-8 hidden">
                <div class="bg-white rounded-3xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <i class="fa-solid fa-chalkboard-user text-indigo-600"></i>
                        Our Instructors
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($instructors as $instructor)
                        <div class="instructor-card bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-gray-100">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                                    {{ substr($instructor->first_name, 0, 1) }}{{ substr($instructor->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">{{ $instructor->first_name }} {{ $instructor->last_name }}</h3>
                                    <p class="text-sm text-indigo-600">{{ $instructor->department ?? 'Faculty Member' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fa-solid fa-envelope"></i> {{ $instructor->email }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                @if($instructor->specialization)
                                <p class="text-sm text-gray-600">
                                    <i class="fa-solid fa-tag text-indigo-500 mr-2"></i>
                                    {{ $instructor->specialization }}
                                </p>
                                @endif
                                @if($instructor->office_location)
                                <p class="text-sm text-gray-600">
                                    <i class="fa-solid fa-location-dot text-indigo-500 mr-2"></i>
                                    {{ $instructor->office_location }}
                                </p>
                                @endif
                            </div>
                            
                            <button onclick="openMessageModal({{ $instructor->id }}, '{{ $instructor->first_name }} {{ $instructor->last_name }}')" 
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-xl transition flex items-center justify-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i>
                                Send Message
                            </button>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-12">
                            <i class="fa-solid fa-users-slash text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No instructors found.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Messages Tab Content -->
            <div id="messagesContent" class="p-8 pb-24 md:pb-8 hidden">
                <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-indigo-600"></i>
                            Messages
                            @if($unreadMessagesCount > 0)
                                <span class="text-sm bg-red-500 text-white px-3 py-1 rounded-full">{{ $unreadMessagesCount }} unread</span>
                            @endif
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 min-h-[500px]">
                        <!-- Conversations List -->
                        <div class="border-r border-gray-200">
                            <div class="p-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="font-semibold text-gray-700">Conversations</h3>
                            </div>
                            <div class="divide-y divide-gray-100 max-h-[500px] overflow-y-auto">
                                @forelse($conversations as $conversation)
                                <div class="p-4 hover:bg-gray-50 cursor-pointer transition message-item" data-conversation-id="{{ $conversation['id'] }}" data-instructor-name="{{ $conversation['instructor_name'] }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                            {{ substr($conversation['instructor_name'], 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <p class="font-semibold text-gray-900">{{ $conversation['instructor_name'] }}</p>
                                                <p class="text-xs text-gray-400">{{ $conversation['last_message_time'] }}</p>
                                            </div>
                                            <p class="text-sm text-gray-600 truncate">{{ $conversation['last_message'] }}</p>
                                            @if($conversation['unread_count'] > 0)
                                                <span class="inline-block mt-1 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">{{ $conversation['unread_count'] }} new</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="p-8 text-center text-gray-500">
                                    <i class="fa-solid fa-inbox text-4xl mb-3"></i>
                                    <p>No messages yet</p>
                                    <p class="text-sm">Start a conversation with an instructor</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Chat Area -->
                        <div class="lg:col-span-2 flex flex-col">
                            <div id="chatHeader" class="p-4 border-b border-gray-200 bg-gray-50">
                                <p class="text-gray-500 text-center">Select a conversation to start messaging</p>
                            </div>
                            <div id="chatMessages" class="flex-1 p-4 overflow-y-auto max-h-[400px]">
                                <div class="text-center text-gray-400 py-8">
                                    <i class="fa-solid fa-comments text-4xl mb-2"></i>
                                    <p>No conversation selected</p>
                                </div>
                            </div>
                            <div id="chatInput" class="p-4 border-t border-gray-200 hidden">
                                <form id="messageForm" method="POST" action="{{ route('student.send-message') }}">
                                    @csrf
                                    <input type="hidden" name="receiver_id" id="receiverId">
                                    <div class="flex gap-2">
                                        <textarea name="message" id="messageText" rows="2" placeholder="Type your message..." 
                                                  class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none resize-none"></textarea>
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 rounded-xl transition">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Tab Content -->
            <div id="newsContent" class="p-8 pb-24 md:pb-8 hidden">
                <div class="bg-white rounded-3xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <i class="fa-solid fa-newspaper text-indigo-600"></i>
                        University News & Announcements
                    </h2>
                    <div class="space-y-6">
                        <div class="border-l-4 border-indigo-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 mb-1">MAY 20, 2026</p>
                            <h3 class="font-semibold text-gray-900">Enrollment for Second Semester Now Open</h3>
                            <p class="text-gray-600 mt-1">Enrollment for the second semester of Academic Year 2025-2026 is now open until March 30, 2026.</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 mb-1">JULY 10, 2026</p>
                            <h3 class="font-semibold text-gray-900">BrightSphere University Founding Anniversary Celebration</h3>
                            <p class="text-gray-600 mt-1">Join us in celebrating the 127th Founding Anniversary of BrightSphere University.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4 py-2">
                            <p class="text-sm text-gray-500 mb-1">MAY 10, 2026</p>
                            <h3 class="font-semibold text-gray-900">Research Symposium 2026</h3>
                            <p class="text-gray-600 mt-1">Call for Papers: The annual Research Symposium will be held on April 15-16, 2026.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Welcome Tab -->
            <div id="welcomeContent" class="p-8 pb-24 md:pb-8 hidden">
                <div class="welcome-card bg-white rounded-3xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-700 to-purple-700 p-8 text-white">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl">
                                <i class="fa-solid fa-university"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold">Bright Sphere University</h1>
                                <p class="text-indigo-200 mt-1">Butuan City, Philippines</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-hand-wave text-indigo-600"></i> Welcome!
                            </h2>
                            <p class="text-gray-700 leading-relaxed">
                                Welcome to BrightSphere University, a values-driven academic institution committed to shaping competent, compassionate, and globally competitive individuals.
                            </p>
                        </div>

                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-star text-yellow-500"></i> SPHERE Core Values
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                                <div class="urian-value bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 text-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-people-arrows text-blue-600 text-xl"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900">Unity</h3>
                                    <p class="text-xs text-gray-500 mt-1">Bayanihan spirit</p>
                                </div>
                                <div class="urian-value bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-4 text-center">
                                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-church text-purple-600 text-xl"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900">Religiosity</h3>
                                    <p class="text-xs text-gray-500 mt-1">Faith in God</p>
                                </div>
                                <div class="urian-value bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-4 text-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-scale-balanced text-green-600 text-xl"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900">Integrity</h3>
                                    <p class="text-xs text-gray-500 mt-1">Honesty & character</p>
                                </div>
                                <div class="urian-value bg-gradient-to-br from-red-50 to-rose-50 rounded-xl p-4 text-center">
                                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-heart text-red-600 text-xl"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900">Altruism</h3>
                                    <p class="text-xs text-gray-500 mt-1">Service to others</p>
                                </div>
                                <div class="urian-value bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl p-4 text-center">
                                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-flag text-yellow-600 text-xl"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900">Nationalism</h3>
                                    <p class="text-xs text-gray-500 mt-1">Love of country</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-indigo-50 rounded-2xl p-6 border-l-4 border-indigo-600">
                                <div class="flex items-center gap-3 mb-4">
                                    <i class="fa-solid fa-eye text-indigo-600 text-2xl"></i>
                                    <h3 class="text-xl font-bold text-gray-900">Vision</h3>
                                </div>
                                <p class="text-gray-700 leading-relaxed">
                                    BrightSphere University aims to be a distinguished center of academic excellence.
                                </p>
                            </div>
                            <div class="bg-purple-50 rounded-2xl p-6 border-l-4 border-purple-600">
                                <div class="flex items-center gap-3 mb-4">
                                    <i class="fa-solid fa-bullseye text-purple-600 text-2xl"></i>
                                    <h3 class="text-xl font-bold text-gray-900">Mission</h3>
                                </div>
                                <p class="text-gray-700 leading-relaxed">
                                    To deliver comprehensive and values-centered education that shapes skilled, ethical, and globally competent professionals.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl modal-enter">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Send Message</h3>
                <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('student.send-message') }}" id="modalMessageForm">
                @csrf
                <input type="hidden" name="receiver_id" id="modalReceiverId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To: <span id="receiverName" class="font-semibold text-indigo-600"></span></label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Type your message here..."></textarea>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeMessageModal()" class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition">Send Message</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="profileModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Edit Profile</h3>
                <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div><input type="text" name="first_name" value="{{ auth()->user()->first_name }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></div>
                    <div><input type="text" name="middle_name" value="{{ auth()->user()->middle_name }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></div>
                    <div><input type="text" name="last_name" value="{{ auth()->user()->last_name }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></div>
                    <div><input type="email" name="email" value="{{ auth()->user()->email }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closeProfileModal()" class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Change Password</h3>
                <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div><input type="password" name="current_password" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></div>
                    <div><input type="password" name="new_password" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></div>
                    <div><input type="password" name="new_password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closePasswordModal()" class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition">Update Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            const icon = document.getElementById('dropdownIcon');
            dropdown.classList.toggle('open');
            if (dropdown.classList.contains('open')) {
                icon.style.transform = 'rotate(180deg)';
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        }
        
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('open');
                const icon = document.getElementById('dropdownIcon');
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        });
        
        function openProfileModal() {
            document.getElementById('profileModal').classList.remove('hidden');
        }
        
        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
        }
        
        function openPasswordModal() {
            document.getElementById('passwordModal').classList.remove('hidden');
        }
        
        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
        }
        
        let currentReceiverId = null;
        
        function openMessageModal(instructorId, instructorName) {
            document.getElementById('modalReceiverId').value = instructorId;
            document.getElementById('receiverName').textContent = instructorName;
            document.getElementById('messageModal').classList.remove('hidden');
        }
        
        function closeMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
            document.getElementById('modalMessageForm').reset();
        }
        
        function switchTab(tab) {
            const dashboardContent = document.getElementById('dashboardContent');
            const instructorsContent = document.getElementById('instructorsContent');
            const messagesContent = document.getElementById('messagesContent');
            const newsContent = document.getElementById('newsContent');
            const welcomeContent = document.getElementById('welcomeContent');
            
            const tabDashboard = document.getElementById('tabDashboard');
            const tabInstructors = document.getElementById('tabInstructors');
            const tabMessages = document.getElementById('tabMessages');
            const tabNews = document.getElementById('tabNews');
            const tabWelcomeNav = document.getElementById('tabWelcomeNav');
            
            // Hide all content
            dashboardContent.classList.add('hidden');
            instructorsContent.classList.add('hidden');
            messagesContent.classList.add('hidden');
            newsContent.classList.add('hidden');
            welcomeContent.classList.add('hidden');
            
            // Remove active class from all tabs
            tabDashboard.classList.remove('active', 'text-indigo-600');
            tabInstructors.classList.remove('active', 'text-indigo-600');
            tabMessages.classList.remove('active', 'text-indigo-600');
            tabNews.classList.remove('active', 'text-indigo-600');
            tabWelcomeNav.classList.remove('active', 'text-indigo-600');
            
            // Show selected content
            if (tab === 'dashboard') {
                dashboardContent.classList.remove('hidden');
                tabDashboard.classList.add('active', 'text-indigo-600');
            } else if (tab === 'instructors') {
                instructorsContent.classList.remove('hidden');
                tabInstructors.classList.add('active', 'text-indigo-600');
            } else if (tab === 'messages') {
                messagesContent.classList.remove('hidden');
                tabMessages.classList.add('active', 'text-indigo-600');
                loadConversations();
            } else if (tab === 'news') {
                newsContent.classList.remove('hidden');
                tabNews.classList.add('active', 'text-indigo-600');
            } else if (tab === 'welcome') {
                welcomeContent.classList.remove('hidden');
                tabWelcomeNav.classList.add('active', 'text-indigo-600');
            }
        }
        
        function loadConversations() {
            // This would typically fetch conversations via AJAX
            // For now, the conversations are loaded from the server-side data
        }
        
        function loadConversation(conversationId, instructorName) {
            document.getElementById('chatHeader').innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                        ${instructorName.charAt(0)}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">${instructorName}</p>
                        <p class="text-xs text-gray-500">Instructor</p>
                    </div>
                </div>
            `;
            document.getElementById('receiverId').value = conversationId;
            document.getElementById('chatInput').classList.remove('hidden');
            
            // Load messages via AJAX
            fetch(`/student/messages/${conversationId}`)
                .then(response => response.json())
                .then(data => {
                    const chatMessages = document.getElementById('chatMessages');
                    chatMessages.innerHTML = '';
                    data.messages.forEach(message => {
                        const isOwn = message.sender_id == {{ auth()->id() }};
                        chatMessages.innerHTML += `
                            <div class="flex ${isOwn ? 'justify-end' : 'justify-start'} mb-3">
                                <div class="max-w-[70%] ${isOwn ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900'} rounded-2xl px-4 py-2 message-bubble">
                                    <p class="text-sm">${message.message}</p>
                                    <p class="text-xs ${isOwn ? 'text-indigo-200' : 'text-gray-400'} mt-1">${new Date(message.created_at).toLocaleTimeString()}</p>
                                </div>
                            </div>
                        `;
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                });
        }
        
        document.querySelectorAll('.message-item').forEach(item => {
            item.addEventListener('click', function() {
                const conversationId = this.dataset.conversationId;
                const instructorName = this.dataset.instructorName;
                loadConversation(conversationId, instructorName);
            });
        });
        
        document.getElementById('messageForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    document.getElementById('messageText').value = '';
                    loadConversation(document.getElementById('receiverId').value, '');
                }
            });
        });
        
        setTimeout(function() {
            document.querySelectorAll('.bg-green-50, .bg-red-50').forEach(function(el) {
                if(el.style) el.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>