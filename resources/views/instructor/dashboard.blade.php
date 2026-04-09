<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BrightSphere • Instructor Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Your existing styles remain the same */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f5f7fb;
        }

        .sidebar {
            background: linear-gradient(180deg, #1a1c2e 0%, #2d2f42 100%);
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

        .stat-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -10px rgba(99, 102, 241, 0.2);
            border-color: #6366f1;
        }

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
        
        .urian-value {
            transition: all 0.3s ease;
        }
        
        .urian-value:hover {
            transform: translateY(-3px);
        }
        
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
        
        .message-bubble {
            transition: all 0.2s ease;
        }
        
        .message-bubble:hover {
            transform: translateX(5px);
        }
        
        .student-card {
            transition: all 0.3s ease;
        }
        
        .student-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
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
        <div class="sidebar w-72 flex-shrink-0 hidden md:flex flex-col text-white shadow-2xl h-full">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                        <i class="fa-solid fa-graduation-cap text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight">BrightSphere</h1>
                        <p class="text-xs text-indigo-300 font-semibold uppercase tracking-wider mt-1">Instructor Portal</p>
                    </div>
                </div>
            </div>

            <div class="px-6 mb-6">
                <div class="bg-white/10 rounded-2xl p-3 backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                            {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-md">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            <p class="text-xs text-indigo-300 flex items-center gap-1">
                                <i class="fa-solid fa-chalkboard-user"></i>
                                {{ ucfirst(Auth::user()->role) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 px-4 space-y-1">
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider px-4 mb-2">Menu</p>
                
                <a href="{{ route('instructor.dashboard') }}" class="nav-item active flex items-center gap-4 px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-chart-pie w-5 text-base"></i>
                    <span class="font-medium text-sm">Dashboard</span>
                </a>

                <a href="{{ route('instructor.analytics.index') }}" class="nav-item flex items-center gap-4 px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-chart-line w-5 text-base"></i>
                    <span class="font-medium text-sm">Analytics</span>
                </a>

                <a href="{{ route('instructor.courses.index') }}" class="nav-item flex items-center gap-4 px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-book-open w-5 text-base"></i>
                    <span class="font-medium text-sm">Courses</span>
                </a>

                <a href="{{ route('instructor.students.index') }}" class="nav-item flex items-center gap-4 px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-user-graduate w-5 text-base"></i>
                    <span class="font-medium text-sm">Students</span>
                </a>

                <a href="{{ route('instructor.grades.index') }}" class="nav-item flex items-center gap-4 px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-ranking-star w-5 text-base"></i>
                    <span class="font-medium text-sm">Grades</span>
                </a>

                <a href="{{ route('instructor.attendance.index') }}" class="nav-item flex items-center gap-4 px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-calendar-check w-5 text-base"></i>
                    <span class="font-medium text-sm">Attendance</span>
                </a>

                <a href="{{ route('instructor.schedule') }}" class="nav-item flex items-center gap-4 px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-clock w-5 text-base"></i>
                    <span class="font-medium text-sm">Schedule</span>
                </a>

                <a href="#" onclick="switchTab('messages'); return false;" class="nav-item flex items-center gap-4 px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-envelope w-5 text-base"></i>
                    <span class="font-medium text-sm">Messages</span>
                    @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-0.5">{{ $unreadMessagesCount }}</span>
                    @endif
                </a>

                <div class="border-t border-white/10 my-3"></div>

                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider px-4 mb-2">Quick Links</p>

                <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-4 px-4 py-2.5 rounded-xl transition">
                    <i class="fa-solid fa-arrow-left w-5 text-base"></i>
                    <span class="font-medium text-sm">Back to Main</span>
                </a>
            </div>

            <div class="p-4 border-t border-white/10 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item flex items-center gap-4 px-4 py-2.5 w-full rounded-xl text-red-300 hover:text-red-200 transition">
                        <i class="fa-solid fa-right-from-bracket w-5 text-base"></i>
                        <span class="font-medium text-sm">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
            <div class="flex justify-around p-3">
                <a href="{{ route('instructor.dashboard') }}" class="text-indigo-600"><i class="fa-solid fa-chart-pie text-xl"></i></a>
                <a href="{{ route('instructor.analytics.index') }}" class="text-gray-400"><i class="fa-solid fa-chart-line text-xl"></i></a>
                <a href="{{ route('instructor.courses.index') }}" class="text-gray-400"><i class="fa-solid fa-book-open text-xl"></i></a>
                <a href="{{ route('instructor.students.index') }}" class="text-gray-400"><i class="fa-solid fa-user-graduate text-xl"></i></a>
                <a href="{{ route('instructor.grades.index') }}" class="text-gray-400"><i class="fa-solid fa-ranking-star text-xl"></i></a>
                <a href="{{ route('instructor.attendance.index') }}" class="text-gray-400"><i class="fa-solid fa-calendar-check text-xl"></i></a>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto">
            <!-- Top Header -->
            <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
                <div class="px-8 py-5">
                    <div class="mb-4">
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-university text-indigo-600"></i>
                            BrightSphere University
                        </h1>
                        <p class="text-xs text-gray-500 mt-1">Excellence • Service • Leadership</p>
                    </div>
                    
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex gap-8">
                            <button id="tabDashboard" onclick="switchTab('dashboard')" class="top-nav-link active text-gray-700 hover:text-indigo-600 font-medium pb-2 transition">
                                Dashboard
                            </button>
                            <button id="tabStudents" onclick="switchTab('students')" class="top-nav-link text-gray-500 hover:text-indigo-600 font-medium pb-2 transition">
                                Students
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
                        
                        <div class="flex items-center gap-4">
                            <button class="text-gray-400 hover:text-indigo-600 transition">
                                <i class="fa-solid fa-magnifying-glass text-lg"></i>
                            </button>
                            
                            <div class="relative dropdown" id="userDropdown">
                                <button onclick="toggleDropdown()" class="flex items-center gap-3 pl-4 border-l border-gray-200 cursor-pointer hover:bg-gray-50 rounded-lg transition px-3 py-2">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                                        {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <p class="font-semibold text-gray-800 text-sm">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-gray-400 text-xs transition-transform" id="dropdownIcon"></i>
                                </button>
                                
                                <div class="dropdown-menu absolute right-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
                                    <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                                                {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                                                <p class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                                    <i class="fa-solid fa-envelope text-xs"></i>
                                                    {{ Auth::user()->email }}
                                                </p>
                                                <p class="text-xs text-indigo-600 mt-1">
                                                    <i class="fa-solid fa-graduation-cap mr-1"></i>{{ ucfirst(Auth::user()->role) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-5 border-b border-gray-100">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Personal Information</h4>
                                            <button onclick="openProfileModal()" class="text-indigo-600 hover:text-indigo-700 text-xs font-medium flex items-center gap-1">
                                                <i class="fa-solid fa-pen"></i> Edit
                                            </button>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500">First Name</span>
                                                <span class="text-xs font-medium text-gray-900">{{ Auth::user()->first_name }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500">Middle Name</span>
                                                <span class="text-xs font-medium text-gray-900">{{ Auth::user()->middle_name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500">Last Name</span>
                                                <span class="text-xs font-medium text-gray-900">{{ Auth::user()->last_name }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500">Email Address</span>
                                                <span class="text-xs font-medium text-gray-900">{{ Auth::user()->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="p-5 border-b border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Security</h4>
                                        <button onclick="openPasswordModal()" class="w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2 text-sm">
                                            <i class="fa-solid fa-key"></i>
                                            Change Password
                                        </button>
                                    </div>
                                    
                                    <div class="p-5">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-semibold py-2.5 px-4 rounded-xl transition flex items-center justify-center gap-2 text-sm">
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

            <!-- Dashboard Content -->
            <div id="dashboardContent" class="p-6 pb-24 md:pb-6">
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
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 mb-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-36 h-36 bg-purple-500/20 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class="fa-solid fa-chalkboard-user text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-indigo-200 text-xs font-medium">Welcome back,</p>
                                <h2 class="text-2xl font-extrabold tracking-tight">
                                    Instructor {{ Auth::user()->first_name }}! 👋
                                </h2>
                            </div>
                        </div>
                        <p class="text-indigo-100 text-sm max-w-2xl mt-1">
                            Manage your courses, track student progress, and access teaching resources.
                        </p>
                        
                        <div class="flex flex-wrap gap-3 mt-4">
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-lg px-3 py-1.5">
                                <i class="fa-solid fa-fire text-yellow-300 text-sm"></i>
                                <span class="text-xs font-semibold">{{ $streakDays ?? 0 }} Day Streak</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-lg px-3 py-1.5">
                                <i class="fa-solid fa-book-open text-green-300 text-sm"></i>
                                <span class="text-xs font-semibold">{{ count($myCourses) }} Course{{ count($myCourses) != 1 ? 's' : '' }}</span>
                            </div>
                            <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-lg px-3 py-1.5">
                                <i class="fa-solid fa-users text-yellow-300 text-sm"></i>
                                <span class="text-xs font-semibold">{{ $totalAllStudents }} Total Student{{ $totalAllStudents != 1 ? 's' : '' }}</span>
                            </div>
                            @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                            <div class="flex items-center gap-2 bg-red-500/30 backdrop-blur-sm rounded-lg px-3 py-1.5">
                                <i class="fa-solid fa-envelope text-red-300 text-sm"></i>
                                <span class="text-xs font-semibold">{{ $unreadMessagesCount }} New Message{{ $unreadMessagesCount != 1 ? 's' : '' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                    <div class="stat-card bg-white rounded-xl p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-blue-100 rounded-lg flex items-center justify-center text-indigo-600">
                                <i class="fa-solid fa-book-open text-lg"></i>
                            </div>
                            <span class="text-xs px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full font-semibold">{{ count($myCourses) }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-0.5">My Courses</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($myCourses) }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            @if(count($myCourses) > 0)
                                {{ count($myCourses) }} active course{{ count($myCourses) != 1 ? 's' : '' }}
                            @else
                                No courses assigned yet
                            @endif
                        </p>
                        <a href="{{ route('instructor.courses.index') }}" class="text-indigo-600 text-xs font-medium mt-2 inline-block hover:underline">
                            Manage courses →
                        </a>
                    </div>

                    <div class="stat-card bg-white rounded-xl p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center text-green-600">
                                <i class="fa-solid fa-users text-lg"></i>
                            </div>
                            <span class="text-xs px-2 py-0.5 bg-green-50 text-green-600 rounded-full font-semibold">{{ $totalAllStudents }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-0.5">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalAllStudents }}</p>
                        <p class="text-xs text-gray-400 mt-1">All students in the system</p>
                        <a href="{{ route('instructor.students.index') }}" class="text-indigo-600 text-xs font-medium mt-2 inline-block hover:underline">
                            View all students →
                        </a>
                    </div>

                    <div class="stat-card bg-white rounded-xl p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg flex items-center justify-center text-purple-600">
                                <i class="fa-solid fa-graduation-cap text-lg"></i>
                            </div>
                            <span class="text-xs px-2 py-0.5 bg-purple-50 text-purple-600 rounded-full font-semibold">{{ $totalEnrolledStudents }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-0.5">Enrolled Students</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalEnrolledStudents }}</p>
                        <p class="text-xs text-gray-400 mt-1">Students enrolled in your courses</p>
                        <a href="{{ route('instructor.students.index') }}" class="text-indigo-600 text-xs font-medium mt-2 inline-block hover:underline">
                            View enrolled →
                        </a>
                    </div>

                    <div class="stat-card bg-white rounded-xl p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-100 to-amber-100 rounded-lg flex items-center justify-center text-yellow-600">
                                <i class="fa-solid fa-calendar-check text-lg"></i>
                            </div>
                            <span class="text-xs px-2 py-0.5 bg-yellow-50 text-yellow-600 rounded-full font-semibold">{{ count($upcomingClasses) }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-0.5">Today's Classes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ count($upcomingClasses) }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            @if(count($upcomingClasses) > 0)
                                Ready for today's lessons
                            @else
                                No classes scheduled today
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition border border-gray-100">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mb-3">
                            <i class="fa-solid fa-plus text-indigo-600 text-lg"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">Create Course</h3>
                        <p class="text-xs text-gray-500">Add new courses to your teaching schedule</p>
                        <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center gap-2 text-indigo-600 text-xs font-medium mt-3 hover:gap-3 transition-all">
                            Get Started <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition border border-gray-100">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                            <i class="fa-solid fa-chart-line text-green-600 text-lg"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">View Analytics</h3>
                        <p class="text-xs text-gray-500">Track student performance and progress</p>
                        <a href="{{ route('instructor.analytics.index') }}" class="inline-flex items-center gap-2 text-indigo-600 text-xs font-medium mt-3 hover:gap-3 transition-all">
                            View Reports <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition border border-gray-100">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                            <i class="fa-solid fa-download text-purple-600 text-lg"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 mb-1">Export Data</h3>
                        <p class="text-xs text-gray-500">Download student records and reports</p>
                        <a href="{{ route('instructor.analytics.export') }}" class="inline-flex items-center gap-2 text-indigo-600 text-xs font-medium mt-3 hover:gap-3 transition-all">
                            Export Now <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Students Tab Content -->
            <div id="studentsContent" class="p-6 pb-24 md:pb-6 hidden">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                        <i class="fa-solid fa-user-graduate text-indigo-600"></i>
                        My Students
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($myStudents ?? [] as $student)
                        <div class="student-card bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border border-gray-100">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-md">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $student->student_id ?? 'Student' }}</p>
                                    <p class="text-xs text-indigo-600 mt-0.5">{{ $student->program ?? 'No program' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-3">
                                <button onclick="openMessageModal({{ $student->id }}, '{{ $student->first_name }} {{ $student->last_name }}')" 
                                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg transition text-sm flex items-center justify-center gap-1">
                                    <i class="fa-solid fa-paper-plane text-xs"></i> Message
                                </button>
                                <a href="{{ route('instructor.students.show', $student->id) }}" 
                                   class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 py-2 rounded-lg transition text-sm text-center">
                                    View Profile
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full text-center py-12">
                            <i class="fa-solid fa-users-slash text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No students enrolled in your courses yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Messages Tab Content -->
            <div id="messagesContent" class="p-6 pb-24 md:pb-6 hidden">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-purple-50">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-indigo-600"></i>
                            Messages
                            @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                <span class="text-sm bg-red-500 text-white px-3 py-1 rounded-full">{{ $unreadMessagesCount }} unread</span>
                            @endif
                        </h2>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-3 min-h-[500px]">
                        <!-- Conversations List -->
                        <div class="border-r border-gray-200">
                            <div class="p-3 border-b border-gray-200 bg-gray-50">
                                <h3 class="font-semibold text-gray-700 text-sm">Conversations</h3>
                            </div>
                            <div class="divide-y divide-gray-100 max-h-[500px] overflow-y-auto">
                                @forelse($conversations ?? [] as $conversation)
                                <div class="p-3 hover:bg-gray-50 cursor-pointer transition message-item" data-conversation-id="{{ $conversation['id'] }}" data-student-name="{{ $conversation['student_name'] }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                            {{ substr($conversation['student_name'], 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <p class="font-semibold text-gray-900 text-sm">{{ $conversation['student_name'] }}</p>
                                                <p class="text-xs text-gray-400">{{ $conversation['last_message_time'] }}</p>
                                            </div>
                                            <p class="text-xs text-gray-600 truncate">{{ $conversation['last_message'] }}</p>
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
                                    <p class="text-xs">Start a conversation with a student</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Chat Area -->
                        <div class="lg:col-span-2 flex flex-col">
                            <div id="chatHeader" class="p-4 border-b border-gray-200 bg-gray-50">
                                <p class="text-gray-500 text-sm text-center">Select a conversation to start messaging</p>
                            </div>
                            <div id="chatMessages" class="flex-1 p-4 overflow-y-auto max-h-[400px]">
                                <div class="text-center text-gray-400 py-8">
                                    <i class="fa-solid fa-comments text-4xl mb-2"></i>
                                    <p>No conversation selected</p>
                                </div>
                            </div>
                            <div id="chatInput" class="p-4 border-t border-gray-200 hidden">
                                <form id="messageForm">
                                    @csrf
                                    <input type="hidden" name="receiver_id" id="receiverId">
                                    <div class="flex gap-2">
                                        <textarea name="message" id="messageText" rows="2" placeholder="Type your message..." 
                                                  class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none resize-none text-sm"></textarea>
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 rounded-xl transition">
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
            <div id="newsContent" class="p-6 pb-24 md:pb-6 hidden">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                        <i class="fa-solid fa-newspaper text-indigo-600"></i>
                        University News & Announcements
                    </h2>
                    <div class="space-y-4">
                        <div class="border-l-4 border-indigo-500 pl-3 py-1">
                            <p class="text-xs text-gray-500 mb-1">MAY 20, 2026</p>
                            <h3 class="font-semibold text-gray-900 text-sm">Enrollment for Second Semester Now Open</h3>
                            <p class="text-gray-600 text-xs mt-1">Enrollment for the second semester of Academic Year 2025-2026 is now open until March 30, 2026.</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-3 py-1">
                            <p class="text-xs text-gray-500 mb-1">JULY 10, 2026</p>
                            <h3 class="font-semibold text-gray-900 text-sm">BrightSphere University Founding Anniversary Celebration</h3>
                            <p class="text-gray-600 text-xs mt-1">Join us in celebrating the 127th Founding Anniversary of BrightSphere University.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-3 py-1">
                            <p class="text-xs text-gray-500 mb-1">MAY 10, 2026</p>
                            <h3 class="font-semibold text-gray-900 text-sm">Research Symposium 2026</h3>
                            <p class="text-gray-600 text-xs mt-1">Call for Papers: The annual Research Symposium will be held on April 15-16, 2026.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Welcome Tab Content -->
            <div id="welcomeContent" class="p-6 pb-24 md:pb-6 hidden">
                <div class="welcome-card bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-700 to-purple-700 p-6 text-white">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl">
                                <i class="fa-solid fa-university"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold">BrightSphere University</h1>
                                <p class="text-indigo-200 text-xs">Butuan City, Philippines</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-hand-wave text-indigo-600"></i> Welcome!
                            </h2>
                            <p class="text-gray-700 text-sm leading-relaxed">
                            Welcome to BrightSphere University, a values-driven academic institution committed to shaping competent, compassionate, and globally competitive individuals. Guided by the principles of integrity, unity, service, and faith, we provide quality and holistic education that empowers students with the knowledge, skills, and ethical responsibility needed for success.
                            </p>
                            <p class="text-gray-700 leading-relaxed mt-4">
                               At BrightSphere University, we take pride in our dedication to excellence in instruction, research, and community engagement. We cultivate innovative thinkers and future leaders who are ready to contribute meaningfully to society and national development.
                            </p>
                            <p class="text-gray-700 leading-relaxed mt-4">
                               With a dynamic and inclusive learning environment, we continue to inspire and prepare our students to face the challenges of an ever-evolving world.
                            </p>
                        </div>

                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-star text-yellow-500"></i> SPHERE Core Values
                            </h2>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                <div class="urian-value bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-3 text-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fa-solid fa-people-arrows text-blue-600 text-base"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900 text-sm">Unity</h3>
                                    <p class="text-xs text-gray-500">Bayanihan spirit</p>
                                </div>
                                <div class="urian-value bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-3 text-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fa-solid fa-church text-purple-600 text-base"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900 text-sm">Religiosity</h3>
                                    <p class="text-xs text-gray-500">Faith in God</p>
                                </div>
                                <div class="urian-value bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-3 text-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fa-solid fa-scale-balanced text-green-600 text-base"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900 text-sm">Integrity</h3>
                                    <p class="text-xs text-gray-500">Honesty & character</p>
                                </div>
                                <div class="urian-value bg-gradient-to-br from-red-50 to-rose-50 rounded-lg p-3 text-center">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fa-solid fa-heart text-red-600 text-base"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900 text-sm">Altruism</h3>
                                    <p class="text-xs text-gray-500">Service to others</p>
                                </div>
                                <div class="urian-value bg-gradient-to-br from-yellow-50 to-amber-50 rounded-lg p-3 text-center">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <i class="fa-solid fa-flag text-yellow-600 text-base"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900 text-sm">Nationalism</h3>
                                    <p class="text-xs text-gray-500">Love of country</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-indigo-50 rounded-xl p-4 border-l-4 border-indigo-600">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fa-solid fa-eye text-indigo-600 text-lg"></i>
                                    <h3 class="text-lg font-bold text-gray-900">Vision</h3>
                                </div>
                                <p class="text-gray-700 leading-relaxed">
                                   BrightSphere University aims to be a distinguished center of academic excellence, fostering innovation and adaptability in an ever-changing global landscape. It envisions producing empowered individuals who uphold strong values, contribute meaningfully to society, and lead with integrity, guided by faith and a commitment to national and global development
                                </p>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-4 border-l-4 border-purple-600">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fa-solid fa-bullseye text-purple-600 text-lg"></i>
                                    <h3 class="text-lg font-bold text-gray-900">Mission</h3>
                                </div>
                                <p class="text-gray-700 leading-relaxed">
                                   To deliver comprehensive and values-centered education that shapes skilled, ethical, and globally competent professionals. BrightSphere University is committed to nurturing individuals who contribute positively to their respective fields and communities, guided by principles of integrity, service, and excellence both locally and internationally.
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
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl modal-enter">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold text-gray-900">Send Message</h3>
                <button onclick="closeMessageModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <form id="modalMessageForm">
                @csrf
                <input type="hidden" name="receiver_id" id="modalReceiverId">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">To: <span id="receiverName" class="font-semibold text-indigo-600"></span></label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition" placeholder="Type your message here..."></textarea>
                </div>
                <div class="flex gap-3 mt-5">
                    <button type="button" onclick="closeMessageModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Send Message</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="profileModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold text-gray-900">Edit Profile</h3>
                <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" value="{{ Auth::user()->first_name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ Auth::user()->middle_name }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" value="{{ Auth::user()->last_name }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeProfileModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-2xl">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold text-gray-900">Change Password</h3>
                <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="new_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closePasswordModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition">Update Password</button>
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
        
        function openMessageModal(studentId, studentName) {
            document.getElementById('modalReceiverId').value = studentId;
            document.getElementById('receiverName').textContent = studentName;
            document.getElementById('messageModal').classList.remove('hidden');
        }
        
        function closeMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
            document.getElementById('modalMessageForm').reset();
        }
        
        function switchTab(tab) {
            const dashboardContent = document.getElementById('dashboardContent');
            const studentsContent = document.getElementById('studentsContent');
            const messagesContent = document.getElementById('messagesContent');
            const newsContent = document.getElementById('newsContent');
            const welcomeContent = document.getElementById('welcomeContent');
            
            const tabDashboard = document.getElementById('tabDashboard');
            const tabStudents = document.getElementById('tabStudents');
            const tabMessages = document.getElementById('tabMessages');
            const tabNews = document.getElementById('tabNews');
            const tabWelcomeNav = document.getElementById('tabWelcomeNav');
            
            dashboardContent.classList.add('hidden');
            studentsContent.classList.add('hidden');
            messagesContent.classList.add('hidden');
            newsContent.classList.add('hidden');
            welcomeContent.classList.add('hidden');
            
            tabDashboard.classList.remove('active', 'text-indigo-600');
            tabStudents.classList.remove('active', 'text-indigo-600');
            tabMessages.classList.remove('active', 'text-indigo-600');
            tabNews.classList.remove('active', 'text-indigo-600');
            tabWelcomeNav.classList.remove('active', 'text-indigo-600');
            
            if (tab === 'dashboard') {
                dashboardContent.classList.remove('hidden');
                tabDashboard.classList.add('active', 'text-indigo-600');
            } else if (tab === 'students') {
                studentsContent.classList.remove('hidden');
                tabStudents.classList.add('active', 'text-indigo-600');
            } else if (tab === 'messages') {
                messagesContent.classList.remove('hidden');
                tabMessages.classList.add('active', 'text-indigo-600');
            } else if (tab === 'news') {
                newsContent.classList.remove('hidden');
                tabNews.classList.add('active', 'text-indigo-600');
            } else if (tab === 'welcome') {
                welcomeContent.classList.remove('hidden');
                tabWelcomeNav.classList.add('active', 'text-indigo-600');
            }
        }
        
        function loadConversation(conversationId, studentName) {
            document.getElementById('chatHeader').innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                        ${studentName.charAt(0)}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">${studentName}</p>
                        <p class="text-xs text-gray-500">Student</p>
                    </div>
                </div>
            `;
            document.getElementById('receiverId').value = conversationId;
            document.getElementById('chatInput').classList.remove('hidden');
            
            fetch(`/instructor/messages/${conversationId}`)
                .then(response => response.json())
                .then(data => {
                    const chatMessages = document.getElementById('chatMessages');
                    chatMessages.innerHTML = '';
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(message => {
                            const isOwn = message.sender_id == {{ Auth::id() }};
                            chatMessages.innerHTML += `
                                <div class="flex ${isOwn ? 'justify-end' : 'justify-start'} mb-3">
                                    <div class="max-w-[70%] ${isOwn ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900'} rounded-2xl px-4 py-2 message-bubble">
                                        <p class="text-sm">${escapeHtml(message.message)}</p>
                                        <p class="text-xs ${isOwn ? 'text-indigo-200' : 'text-gray-400'} mt-1">${new Date(message.created_at).toLocaleTimeString()}</p>
                                    </div>
                                </div>
                            `;
                        });
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    } else {
                        chatMessages.innerHTML = '<div class="text-center text-gray-400 py-8"><i class="fa-solid fa-comment-dots text-4xl mb-2"></i><p>No messages yet</p><p class="text-xs">Send a message to start the conversation</p></div>';
                    }
                }).catch(error => {
                    console.error('Error loading messages:', error);
                });
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Add event listeners after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.message-item').forEach(item => {
                item.addEventListener('click', function() {
                    const conversationId = this.dataset.conversationId;
                    const studentName = this.dataset.studentName;
                    loadConversation(conversationId, studentName);
                });
            });
        });
        
        document.getElementById('messageForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('{{ url("/instructor/send-message") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    document.getElementById('messageText').value = '';
                    const receiverId = document.getElementById('receiverId').value;
                    const studentName = document.querySelector('#chatHeader .font-semibold')?.textContent || '';
                    loadConversation(receiverId, studentName);
                }
            }).catch(error => {
                console.error('Error sending message:', error);
            });
        });
        
        document.getElementById('modalMessageForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('{{ url("/instructor/send-message") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    closeMessageModal();
                    switchTab('messages');
                    setTimeout(() => {
                        const receiverId = document.getElementById('modalReceiverId').value;
                        const studentName = document.getElementById('receiverName').textContent;
                        loadConversation(receiverId, studentName);
                    }, 100);
                }
            }).catch(error => {
                console.error('Error sending message:', error);
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