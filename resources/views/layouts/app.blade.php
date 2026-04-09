<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                
                <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie w-6 text-lg"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('student.courses') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition {{ request()->routeIs('student.courses*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chalkboard-user w-6 text-lg"></i>
                    <span class="font-medium">Courses</span>
                </a>

                <a href="{{ route('student.registration') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition {{ request()->routeIs('student.registration') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-list w-6 text-lg"></i>
                    <span class="font-medium">Registration</span>
                </a>

                <!-- NOTICE MENU ITEM - No "New" badge -->
                <a href="{{ route('student.notices') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition {{ request()->routeIs('student.notices') ? 'active' : '' }}">
                    <i class="fa-solid fa-bullhorn w-6 text-lg"></i>
                    <span class="font-medium">Notices</span>
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
                <a href="{{ route('students.index') }}" class="text-gray-400"><i class="fa-solid fa-user-graduate text-xl"></i></a>
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
                    
                    <!-- User Profile Row -->
                    <div class="flex items-center justify-end flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <button class="text-gray-400 hover:text-indigo-600 transition">
                                <i class="fa-solid fa-magnifying-glass text-lg"></i>
                            </button>
                            
                            <!-- User Profile Dropdown -->
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

            <!-- Content Area -->
            <div class="p-8 pb-24 md:pb-8">
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

                @yield('content')
            </div>
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" name="first_name" value="{{ auth()->user()->first_name }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ auth()->user()->middle_name }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" name="last_name" value="{{ auth()->user()->last_name }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                        <input type="password" name="new_password" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
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
    </script>
</body>
</html>