@extends('layouts.instructor')

@section('title', 'Enroll Student')

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Enroll Student in Courses</h1>
        <p class="text-gray-600 mt-1">Select a student and courses to enroll them in</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-check-circle text-green-600"></i>
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
        <div class="flex items-center gap-2 text-red-600 mb-2">
            <i class="fa-solid fa-exclamation-triangle"></i>
            <strong class="text-sm">Please fix the following errors:</strong>
        </div>
        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('instructor.students.enroll.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        @csrf

        <!-- Student Selection -->
        <div class="mb-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Student *</label>
            <select name="student_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                <option value="">Choose a student...</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                        {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id ?? $student->id }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Course Selection with Filter Dropdown -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Courses *</label>
                    <p class="text-xs text-gray-500">Choose courses to enroll the student in</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" id="selectAllCourses" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                        <i class="fa-solid fa-check-double mr-1"></i> Select All
                    </button>
                    <button type="button" id="deselectAllCourses" class="text-sm text-gray-500 hover:text-gray-700 font-medium">
                        <i class="fa-solid fa-times mr-1"></i> Deselect All
                    </button>
                </div>
            </div>

            <!-- Course Filter Dropdown -->
            <div class="mb-4">
                <div class="dropdown relative inline-block">
                    <button onclick="toggleFilterDropdown()" class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-5 py-2.5 rounded-xl transition flex items-center gap-2 shadow-md font-medium text-sm">
                        <i class="fa-solid fa-filter"></i>
                        <span id="courseFilterLabel">All Courses</span>
                        <i class="fa-solid fa-chevron-down text-sm ml-1 transition-transform" id="filterArrow"></i>
                    </button>
                    <div id="filterDropdownMenu" class="filter-dropdown-content absolute left-0 top-full mt-2 bg-white rounded-xl shadow-lg border border-gray-200 z-50 hidden min-w-[220px] overflow-hidden">
                        <div class="filter-dropdown-item active" onclick="filterCoursesByDepartment('all', 'All Courses', this)">
                            <i class="fa-solid fa-layer-group w-5"></i>
                            <span>All Courses</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('Business Management', 'Business Management', this)">
                            <i class="fa-solid fa-chart-line w-5"></i>
                            <span>Business Management</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('Information Technology', 'Information Technology', this)">
                            <i class="fa-solid fa-laptop-code w-5"></i>
                            <span>Information Technology</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('Computer Science', 'Computer Science', this)">
                            <i class="fa-solid fa-microchip w-5"></i>
                            <span>Computer Science</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('Accountancy', 'Accountancy', this)">
                            <i class="fa-solid fa-calculator w-5"></i>
                            <span>Accountancy</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('Financial Management', 'Financial Management', this)">
                            <i class="fa-solid fa-chart-simple w-5"></i>
                            <span>Financial Management</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('Nursing', 'Nursing', this)">
                            <i class="fa-solid fa-heartbeat w-5"></i>
                            <span>Nursing</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('Criminology', 'Criminology', this)">
                            <i class="fa-solid fa-gavel w-5"></i>
                            <span>Criminology</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('Lawyer', 'Lawyer', this)">
                            <i class="fa-solid fa-scale-balanced w-5"></i>
                            <span>Lawyer</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('Physical Education', 'Physical Education', this)">
                            <i class="fa-solid fa-person-running w-5"></i>
                            <span>Physical Education</span>
                        </div>
                        <div class="filter-dropdown-item" onclick="filterCoursesByDepartment('BLISS', 'BLISS', this)">
                            <i class="fa-solid fa-book w-5"></i>
                            <span>BLISS</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses List with Filtering -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="max-h-96 overflow-y-auto">
                    @php
                        $allCourses = $courses;
                    @endphp
                    
                    @if($allCourses->count() > 0)
                        <div class="divide-y divide-gray-200" id="coursesListContainer">
                            @foreach($allCourses as $course)
                            <label class="course-item flex items-center p-4 hover:bg-gray-50 transition cursor-pointer" 
                                   data-course-name="{{ $course->name }}"
                                   data-course-department="{{ $course->name }}">
                                <input type="checkbox" 
                                       name="course_ids[]" 
                                       value="{{ $course->id }}" 
                                       class="course-checkbox w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 mr-4">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-medium text-gray-900">{{ $course->name }}</p>
                                        @if($course->status === 'active')
                                            <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Active</span>
                                        @else
                                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">Inactive</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-code"></i>
                                            Code: {{ $course->code }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fa-solid fa-users"></i>
                                            {{ $course->students_count ?? 0 }} students
                                        </span>
                                    </div>
                                    @if($course->schedule)
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i class="fa-solid fa-clock mr-1"></i>
                                            {{ $course->schedule }}
                                        </p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-book-open text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">No courses available. Please create courses first.</p>
                            <a href="{{ route('instructor.courses.create') }}" class="text-indigo-600 text-sm hover:underline mt-2 inline-block">
                                Create New Course →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 flex justify-between items-center">
                <p class="text-sm text-gray-500" id="selectedCoursesCount">0 course(s) selected</p>
                <p class="text-sm text-gray-500">Select the courses you want to enroll the student in</p>
            </div>
        </div>

        <div class="flex gap-3 mt-8">
            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-user-plus"></i>
                Enroll Student
            </button>
            <a href="{{ route('instructor.students.index') }}" 
               class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-semibold transition text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>

<style>
    /* Filter Dropdown Styles */
    .filter-dropdown-content {
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
    .filter-dropdown-item {
        padding: 10px 16px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
    }
    .filter-dropdown-item i {
        width: 18px;
        color: #6b7280;
        font-size: 14px;
    }
    .filter-dropdown-item:hover {
        background-color: #f9fafb;
        color: #4f46e5;
    }
    .filter-dropdown-item:hover i {
        color: #4f46e5;
    }
    .filter-dropdown-item.active {
        background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
        color: #4f46e5;
    }
    .filter-dropdown-item.active i {
        color: #4f46e5;
    }
    .filter-dropdown-content.show {
        display: block;
    }
</style>

<script>
    // Store all courses data
    const allCourseElements = document.querySelectorAll('.course-item');
    let currentCourseFilter = 'all';
    let currentCourseFilterLabel = 'All Courses';
    
    // Toggle filter dropdown
    function toggleFilterDropdown() {
        const dropdown = document.getElementById('filterDropdownMenu');
        const arrow = document.getElementById('filterArrow');
        dropdown.classList.toggle('show');
        if (dropdown.classList.contains('show')) {
            arrow.style.transform = 'rotate(180deg)';
        } else {
            arrow.style.transform = 'rotate(0deg)';
        }
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('filterDropdownMenu');
        const trigger = document.querySelector('.dropdown');
        if (trigger && !trigger.contains(event.target)) {
            dropdown.classList.remove('show');
            const arrow = document.getElementById('filterArrow');
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
    });
    
    // Filter courses by department
    function filterCoursesByDepartment(department, label, element) {
        currentCourseFilter = department;
        currentCourseFilterLabel = label;
        
        // Update button label
        document.getElementById('courseFilterLabel').innerText = label;
        
        // Update active state in dropdown
        document.querySelectorAll('.filter-dropdown-item').forEach(item => {
            item.classList.remove('active');
        });
        element.classList.add('active');
        
        // Close dropdown
        document.getElementById('filterDropdownMenu').classList.remove('show');
        document.getElementById('filterArrow').style.transform = 'rotate(0deg)';
        
        // Filter courses
        allCourseElements.forEach(courseItem => {
            const courseName = courseItem.getAttribute('data-course-department');
            if (department === 'all' || courseName === department) {
                courseItem.style.display = 'flex';
            } else {
                courseItem.style.display = 'none';
            }
        });
        
        updateSelectedCount();
    }
    
    // Update selected courses count
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.course-checkbox:checked');
        const countSpan = document.getElementById('selectedCoursesCount');
        if (countSpan) {
            countSpan.textContent = `${selected.length} course(s) selected`;
        }
    }
    
    // Select All functionality
    const selectAllBtn = document.getElementById('selectAllCourses');
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const visibleCheckboxes = document.querySelectorAll('.course-item:not([style*="display: none"]) .course-checkbox');
            visibleCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedCount();
        });
    }
    
    // Deselect All functionality
    const deselectAllBtn = document.getElementById('deselectAllCourses');
    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.course-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount();
        });
    }
    
    // Add event listeners to all checkboxes
    document.querySelectorAll('.course-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Initial count
    updateSelectedCount();
    
    // Auto-select student if passed in URL
    const urlParams = new URLSearchParams(window.location.search);
    const studentId = urlParams.get('student_id');
    if (studentId) {
        const studentSelect = document.querySelector('select[name="student_id"]');
        if (studentSelect) {
            studentSelect.value = studentId;
        }
    }
</script>
@endsection