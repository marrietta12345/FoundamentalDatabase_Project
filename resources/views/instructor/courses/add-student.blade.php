@extends('layouts.instructor')

@section('title', 'Add Students to ' . $course->name)

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('instructor.courses.show', $course) }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 transition group">
            <i class="fa-solid fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Back to Course
        </a>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Add Students to <span class="text-indigo-600">{{ $course->name }}</span></h1>
        <p class="text-gray-600 mt-2">Select students to enroll in this course</p>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
            <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-indigo-600 font-medium">Course Code</p>
                        <p class="text-xl font-bold text-gray-900">{{ $course->code }}</p>
                    </div>
                    <i class="fa-solid fa-code-branch text-indigo-400 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-600 font-medium">Currently Enrolled</p>
                        <p class="text-xl font-bold text-gray-900">{{ $currentEnrollment ?? $course->students()->count() }}</p>
                    </div>
                    <i class="fa-solid fa-users text-green-400 text-2xl"></i>
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 font-medium">Available Slots</p>
                        <p class="text-xl font-bold text-gray-900">{{ $availableSlots ?? max(0, 30 - $course->students()->count()) }}</p>
                    </div>
                    <i class="fa-solid fa-chair text-blue-400 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-exclamation-triangle text-red-600 text-xl"></i>
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
        <div class="flex items-center gap-2 text-red-600 mb-2">
            <i class="fa-solid fa-exclamation-triangle"></i>
            <strong>Please fix the following errors:</strong>
        </div>
        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Search Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <form method="GET" action="{{ route('instructor.courses.add-students', $course) }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search students by name or email..." 
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition">
                Search
            </button>
            @if(request('search'))
            <a href="{{ route('instructor.courses.add-students', $course) }}" class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-xl font-semibold transition text-center">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Available Students List -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-user-plus text-indigo-600"></i>
                        Available Students
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Select students to enroll in this course</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" id="selectAll" class="px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 rounded-lg font-medium transition flex items-center gap-1">
                        <i class="fa-solid fa-check-double"></i>
                        Select All
                    </button>
                    <button type="button" id="deselectAll" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg font-medium transition flex items-center gap-1">
                        <i class="fa-solid fa-square"></i>
                        Deselect All
                    </button>
                </div>
            </div>
        </div>

        @if(isset($availableStudents) && $availableStudents->count() > 0)
        <form method="POST" action="{{ route('instructor.courses.enroll-students', $course) }}" id="enrollForm">
            @csrf
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @foreach($availableStudents as $student)
                <label class="flex items-center p-4 hover:bg-gray-50 transition cursor-pointer group">
                    <input type="checkbox" 
                           name="student_ids[]" 
                           value="{{ $student->id }}" 
                           class="student-checkbox w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 mr-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded-full">ID: {{ $student->id }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $student->email }}</p>
                    </div>
                    <div class="text-sm text-gray-400 opacity-0 group-hover:opacity-100 transition">
                        <i class="fa-regular fa-user"></i>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="border-t border-gray-100 px-6 py-4 bg-gray-50">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-600" id="selectedCount">
                        <i class="fa-regular fa-circle-check mr-1"></i>
                        0 student(s) selected
                    </p>
                    <div class="flex gap-3">
                        <a href="{{ route('instructor.courses.show', $course) }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition flex items-center gap-2 shadow-md hover:shadow-lg">
                            <i class="fa-solid fa-user-plus"></i>
                            Enroll Selected Students
                        </button>
                    </div>
                </div>
            </div>
        </form>
        @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                <i class="fa-solid fa-user-graduate text-gray-400 text-4xl"></i>
            </div>
            <h4 class="text-xl font-semibold text-gray-700 mb-2">No Students Available</h4>
            <p class="text-gray-500 mb-4">
                @if(request('search'))
                    No students found matching "{{ request('search') }}"
                @else
                    All students are already enrolled in this course
                @endif
            </p>
            @if(request('search'))
            <a href="{{ route('instructor.courses.add-students', $course) }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium">
                <i class="fa-solid fa-times"></i>
                Clear Search
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
    // Select All functionality
    const selectAllBtn = document.getElementById('selectAll');
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedCount();
        });
    }
    
    // Deselect All functionality
    const deselectAllBtn = document.getElementById('deselectAll');
    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectedCount();
        });
    }
    
    // Update selected count
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.student-checkbox:checked');
        const countSpan = document.getElementById('selectedCount');
        if (countSpan) {
            countSpan.innerHTML = `<i class="fa-regular fa-circle-check mr-1"></i>${selected.length} student(s) selected`;
        }
    }
    
    // Add event listeners to all checkboxes
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Initial count
    updateSelectedCount();
</script>
@endsection