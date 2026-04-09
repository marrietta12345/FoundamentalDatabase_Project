<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrightSphere • My Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f5f7fb;
        }
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
        .course-card {
            transition: all 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            left: 0;
            top: 100%;
            background-color: white;
            min-width: 260px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            border-radius: 16px;
            z-index: 50;
            margin-top: 8px;
            border: 1px solid #e9eef3;
            overflow: hidden;
            animation: fadeIn 0.2s ease-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .dropdown-content.show {
            display: block;
        }
        .dropdown-item {
            padding: 12px 18px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            border-left: 3px solid transparent;
        }
        .dropdown-item i {
            width: 20px;
            color: #6b7280;
            font-size: 16px;
        }
        .dropdown-item:hover {
            background-color: #f9fafb;
            color: #4f46e5;
        }
        .dropdown-item:hover i {
            color: #4f46e5;
        }
        .dropdown-item.active {
            background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
            color: #4f46e5;
            border-left-color: #4f46e5;
        }
        .dropdown-item.active i {
            color: #4f46e5;
        }
        .filter-trigger {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        .filter-trigger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }
        /* Scrollbar for dropdown if needed */
        .dropdown-content::-webkit-scrollbar {
            width: 4px;
        }
        .dropdown-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .dropdown-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="sidebar w-72 flex-shrink-0 hidden md:flex flex-col text-white shadow-2xl">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-tr from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-graduation-cap text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight">BrightSphere</h1>
                        <p class="text-xs text-indigo-300 font-semibold uppercase tracking-wider mt-1">Instructor Portal</p>
                    </div>
                </div>
            </div>

            <div class="px-6 mb-8">
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold">
                            {{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-lg">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                            <p class="text-xs text-indigo-300 flex items-center gap-1 mt-1">
                                <i class="fa-solid fa-chalkboard-user"></i>
                                {{ ucfirst(Auth::user()->role) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 px-4 space-y-2">
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider px-4 mb-2">Menu</p>
                <a href="{{ route('instructor.dashboard') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-chart-pie w-6 text-lg"></i>
                    <span class="font-medium">Dashboard</span>
                </a>

                 <!-- ANALYTICS MENU ITEM - ADDED -->
                <a href="{{ route('instructor.analytics.index') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition {{ request()->routeIs('instructor.analytics*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line w-6 text-lg"></i>
                    <span class="font-medium">Analytics</span>
                </a>

                <a href="{{ route('instructor.courses.index') }}" class="nav-item active flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-book-open w-6 text-lg"></i>
                    <span class="font-medium">Courses</span>
                </a>
                <a href="{{ route('instructor.students.index') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-user-graduate w-6 text-lg"></i>
                    <span class="font-medium">Students</span>
                </a>
                <a href="{{ route('instructor.grades.index') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-ranking-star w-6 text-lg"></i>
                    <span class="font-medium">Grades</span>
                </a>
                <a href="{{ route('instructor.attendance.index') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-calendar-check w-6 text-lg"></i>
                    <span class="font-medium">Attendance</span>
                </a>
                <a href="{{ route('instructor.schedule') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-clock w-6 text-lg"></i>
                    <span class="font-medium">Schedule</span>
                </a>
                <div class="border-t border-white/10 my-4"></div>
                <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-arrow-left w-6 text-lg"></i>
                    <span class="font-medium">Back to Main</span>
                </a>
            </div>

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

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
                <div class="px-8 py-6">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-book-open text-indigo-600"></i>
                            My Courses
                        </h1>
                        <p class="text-xs text-gray-500 mt-1">Manage your teaching courses and materials</p>
                    </div>
                    
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <!-- Course Filter Dropdown Only -->
                        <div class="dropdown">
                            <button onclick="toggleDropdown()" class="filter-trigger text-white px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-md font-medium">
                                <i class="fa-solid fa-filter"></i>
                                <span id="selectedFilterLabel">All Courses</span>
                                <i class="fa-solid fa-chevron-down text-sm ml-1 transition-transform" id="dropdownArrow"></i>
                            </button>
                            <div id="dropdownMenu" class="dropdown-content">
                                <div class="dropdown-item active" onclick="selectFilter('all', 'All Courses', this)">
                                    <i class="fa-solid fa-layer-group"></i>
                                    <span>All Courses</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('Business Management', 'Business Management', this)">
                                    <i class="fa-solid fa-chart-line"></i>
                                    <span>Business Management</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('Information Technology', 'Information Technology', this)">
                                    <i class="fa-solid fa-laptop-code"></i>
                                    <span>Information Technology</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('Computer Science', 'Computer Science', this)">
                                    <i class="fa-solid fa-microchip"></i>
                                    <span>Computer Science</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('Accountancy', 'Accountancy', this)">
                                    <i class="fa-solid fa-calculator"></i>
                                    <span>Accountancy</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('Financial Management', 'Financial Management', this)">
                                    <i class="fa-solid fa-chart-simple"></i>
                                    <span>Financial Management</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('Nursing', 'Nursing', this)">
                                    <i class="fa-solid fa-heartbeat"></i>
                                    <span>Nursing</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('Criminology', 'Criminology', this)">
                                    <i class="fa-solid fa-gavel"></i>
                                    <span>Criminology</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('Lawyer', 'Lawyer', this)">
                                    <i class="fa-solid fa-scale-balanced"></i>
                                    <span>Lawyer</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('Physical Education', 'Physical Education', this)">
                                    <i class="fa-solid fa-person-running"></i>
                                    <span>Physical Education</span>
                                </div>
                                <div class="dropdown-item" onclick="selectFilter('BLISS', 'BLISS (Bachelor of Library and Information Science)', this)">
                                    <i class="fa-solid fa-book"></i>
                                    <span>BLISS</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <a href="{{ route('instructor.courses.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
                                <i class="fa-solid fa-plus"></i>
                                Create New Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-check-circle text-green-600"></i>
                            <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Courses</p>
                                <p class="text-3xl font-bold text-gray-900" id="totalCoursesCount">{{ $stats['total_courses'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-book-open text-indigo-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Active Courses</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['active_courses'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Students</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_students'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-users text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Class</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $stats['total_courses'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-chalkboard-user text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courses Grid -->
                <div id="coursesGrid">
                    @if($courses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="coursesContainer">
                            @foreach($courses as $course)
                                <div class="course-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg" data-course-name="{{ $course->name }}">
                                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white">
                                        <div class="flex items-center justify-between">
                                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                                <i class="fa-solid fa-book text-xl"></i>
                                            </div>
                                            @if($course->status === 'active')
                                                <span class="bg-green-400 text-green-900 text-xs px-2 py-1 rounded-full">Active</span>
                                            @else
                                                <span class="bg-gray-400 text-gray-900 text-xs px-2 py-1 rounded-full">Inactive</span>
                                            @endif
                                        </div>
                                        <h3 class="text-xl font-bold mt-3">{{ $course->code }}</h3>
                                        <p class="text-indigo-200 text-sm mt-1">{{ $course->name }}</p>
                                        @if($course->class_name)
                                            <p class="text-indigo-300 text-xs mt-1">
                                                <i class="fa-solid fa-chalkboard-user mr-1"></i> {{ $course->class_name }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="p-5">
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="fa-solid fa-users text-sm w-5"></i>
                                                <span class="text-sm">{{ $course->students_count ?? 0 }} Students Enrolled</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="fa-solid fa-calendar text-sm w-5"></i>
                                                <span class="text-sm">Created: {{ $course->created_at->format('M d, Y') }}</span>
                                            </div>
                                            @if($course->schedule)
                                                <div class="flex items-center gap-2 text-gray-600">
                                                    <i class="fa-solid fa-clock text-sm w-5"></i>
                                                    <span class="text-sm">{{ $course->schedule }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="mt-5 flex gap-2">
                                            <a href="{{ route('instructor.courses.show', $course->id) }}" class="flex-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-center py-2 rounded-xl transition font-medium text-sm">
                                                View Details
                                            </a>
                                            <a href="{{ route('instructor.courses.edit', $course->id) }}" class="bg-gray-50 hover:bg-gray-100 text-gray-600 py-2 px-3 rounded-xl transition">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $courses->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
                            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-book-open text-indigo-600 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Courses Yet</h3>
                            <p class="text-gray-500 mb-6">Get started by creating your first course</p>
                            <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl transition">
                                <i class="fa-solid fa-plus"></i>
                                Create Your First Course
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store all courses data
        const allCourses = @json($courses->items());
        let currentFilter = 'all';
        let currentFilterLabel = 'All Courses';
        
        // Toggle dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            const arrow = document.getElementById('dropdownArrow');
            dropdown.classList.toggle('show');
            if (dropdown.classList.contains('show')) {
                arrow.style.transform = 'rotate(180deg)';
            } else {
                arrow.style.transform = 'rotate(0deg)';
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdownMenu');
            const trigger = document.querySelector('.dropdown');
            if (!trigger.contains(event.target)) {
                dropdown.classList.remove('show');
                document.getElementById('dropdownArrow').style.transform = 'rotate(0deg)';
            }
        });
        
        // Select filter from dropdown
        function selectFilter(filter, label, element) {
            currentFilter = filter;
            currentFilterLabel = label;
            
            // Update button label
            document.getElementById('selectedFilterLabel').innerText = label;
            
            // Update active state in dropdown - remove from all, add to clicked
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.classList.remove('active');
            });
            element.classList.add('active');
            
            // Close dropdown
            document.getElementById('dropdownMenu').classList.remove('show');
            document.getElementById('dropdownArrow').style.transform = 'rotate(0deg)';
            
            // Filter courses
            let filteredCourses = [];
            
            if (filter === 'all') {
                filteredCourses = allCourses;
                document.getElementById('totalCoursesCount').innerText = allCourses.length;
            } else {
                filteredCourses = allCourses.filter(course => course.name === filter);
                document.getElementById('totalCoursesCount').innerText = filteredCourses.length;
            }
            
            // Update the courses display
            updateCoursesDisplay(filteredCourses);
        }
        
        // Function to update the courses grid
        function updateCoursesDisplay(courses) {
            const container = document.getElementById('coursesContainer');
            if (!container) return;
            
            if (courses.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full">
                        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
                            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-book-open text-indigo-600 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Courses Found</h3>
                            <p class="text-gray-500 mb-6">No courses found for ${currentFilterLabel}</p>
                            <button onclick="selectFilter('all', 'All Courses', document.querySelector('.dropdown-item'))" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl transition">
                                <i class="fa-solid fa-layer-group"></i>
                                View All Courses
                            </button>
                        </div>
                    </div>
                `;
                return;
            }
            
            // Build HTML for filtered courses
            let html = '';
            courses.forEach(course => {
                html += `
                    <div class="course-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-book text-xl"></i>
                                </div>
                                ${course.status === 'active' 
                                    ? '<span class="bg-green-400 text-green-900 text-xs px-2 py-1 rounded-full">Active</span>'
                                    : '<span class="bg-gray-400 text-gray-900 text-xs px-2 py-1 rounded-full">Inactive</span>'
                                }
                            </div>
                            <h3 class="text-xl font-bold mt-3">${escapeHtml(course.code)}</h3>
                            <p class="text-indigo-200 text-sm mt-1">${escapeHtml(course.name)}</p>
                            ${course.class_name ? `
                            <p class="text-indigo-300 text-xs mt-1">
                                <i class="fa-solid fa-chalkboard-user mr-1"></i> ${escapeHtml(course.class_name)}
                            </p>
                            ` : ''}
                        </div>
                        
                        <div class="p-5">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="fa-solid fa-users text-sm w-5"></i>
                                    <span class="text-sm">${course.students_count || 0} Students Enrolled</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="fa-solid fa-calendar text-sm w-5"></i>
                                    <span class="text-sm">Created: ${new Date(course.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                                </div>
                                ${course.schedule ? `
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="fa-solid fa-clock text-sm w-5"></i>
                                    <span class="text-sm">${escapeHtml(course.schedule)}</span>
                                </div>
                                ` : ''}
                            </div>
                            
                            <div class="mt-5 flex gap-2">
                                <a href="/instructor/courses/${course.id}" class="flex-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-center py-2 rounded-xl transition font-medium text-sm">
                                    View Details
                                </a>
                                <a href="/instructor/courses/${course.id}/edit" class="bg-gray-50 hover:bg-gray-100 text-gray-600 py-2 px-3 rounded-xl transition">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        // Helper function to escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>