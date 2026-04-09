@extends('layouts.instructor')

@section('title', 'Create New Course')

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Create New Course</h1>
        <p class="text-gray-600 mt-1">Fill in the details to create a new course and add students</p>
    </div>

    <!-- Display Validation Errors -->
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

    <!-- Form -->
    <form method="POST" action="{{ route('instructor.courses.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Class Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Class Code *</label>
                <input type="text" 
                       name="class_code" 
                       value="{{ old('class_code') }}" 
                       placeholder="e.g., CS101-A, IT201-B"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('class_code') border-red-500 @enderror"
                       required>
                @error('class_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Unique class identifier (e.g., CS101-A, IT201-B)</p>
            </div>

            <!-- Class Name (Dropdown with all subjects) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Class Name *</label>
                <select name="class_name" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('class_name') border-red-500 @enderror"
                        required>
                    <option value="">Select a class...</option>
                    <option value="THEO 110" {{ old('class_name') == 'THEO 110' ? 'selected' : '' }}>THEO 110</option>
                    <option value="THEP 120" {{ old('class_name') == 'THEP 120' ? 'selected' : '' }}>THEP 120</option>
                    <option value="THEO 130" {{ old('class_name') == 'THEO 130' ? 'selected' : '' }}>THEO 130</option>
                    <option value="BRIDG" {{ old('class_name') == 'BRIDG' ? 'selected' : '' }}>BRIDG</option>
                    <option value="Mathematics in the Modern World" {{ old('class_name') == 'Mathematics in the Modern World' ? 'selected' : '' }}>Mathematics in the Modern World</option>
                    <option value="BRIG2" {{ old('class_name') == 'BRIG2' ? 'selected' : '' }}>BRIG2</option>
                    <option value="PATHFIT 1" {{ old('class_name') == 'PATHFIT 1' ? 'selected' : '' }}>PATHFIT 1</option>
                    <option value="PATHFIT 2" {{ old('class_name') == 'PATHFIT 2' ? 'selected' : '' }}>PATHFIT 2</option>
                    <option value="NSTP 1" {{ old('class_name') == 'NSTP 1' ? 'selected' : '' }}>NSTP 1</option>
                    <option value="NSTP 2" {{ old('class_name') == 'NSTP 2' ? 'selected' : '' }}>NSTP 2</option>
                    <option value="Philippine Literature" {{ old('class_name') == 'Philippine Literature' ? 'selected' : '' }}>Philippine Literature</option>
                    <option value="Purposive Communication" {{ old('class_name') == 'Purposive Communication' ? 'selected' : '' }}>Purposive Communication</option>
                    <option value="The Contemporary World" {{ old('class_name') == 'The Contemporary World' ? 'selected' : '' }}>The Contemporary World</option>
                    <option value="IC-JEEP 110" {{ old('class_name') == 'IC-JEEP 110' ? 'selected' : '' }}>IC-JEEP 110</option>
                    <option value="IC-JEEP 120" {{ old('class_name') == 'IC-JEEP 120' ? 'selected' : '' }}>IC-JEEP 120</option>
                </select>
                @error('class_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Select the class/subject name</p>
            </div>

            <!-- Course Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Code *</label>
                <input type="text" 
                       name="code" 
                       value="{{ old('code') }}" 
                       placeholder="e.g., CS101, MATH201"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('code') border-red-500 @enderror"
                       required>
                @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Unique course identifier (e.g., CS101, MATH201)</p>
            </div>

            <!-- Course Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Name *</label>
                <select name="name" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('name') border-red-500 @enderror"
                        required>
                    <option value="">Select a course...</option>
                    <option value="Business Management" {{ old('name') == 'Business Management' ? 'selected' : '' }}>Business Management</option>
                    <option value="BLISS (Bachelor of Library and Information Science)" {{ old('name') == 'BLISS (Bachelor of Library and Information Science)' ? 'selected' : '' }}>BLISS (Bachelor of Library and Information Science)</option>
                    <option value="Information Technology" {{ old('name') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                    <option value="Computer Science" {{ old('name') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                    <option value="Accountancy" {{ old('name') == 'Accountancy' ? 'selected' : '' }}>Accountancy</option>
                    <option value="Financial Management" {{ old('name') == 'Financial Management' ? 'selected' : '' }}>Financial Management</option>
                    <option value="Nursing" {{ old('name') == 'Nursing' ? 'selected' : '' }}>Nursing</option>
                    <option value="Criminology" {{ old('name') == 'Criminology' ? 'selected' : '' }}>Criminology</option>
                    <option value="Lawyer" {{ old('name') == 'Lawyer' ? 'selected' : '' }}>Lawyer</option>
                    <option value="Physical Education" {{ old('name') == 'Physical Education' ? 'selected' : '' }}>Physical Education</option>
                </select>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Select the course department/program</p>
            </div>

            <!-- Schedule -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Schedule</label>
                <input type="text" 
                       name="schedule" 
                       value="{{ old('schedule') }}" 
                       placeholder="e.g., MWF 10:00 AM - 11:30 AM"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('schedule') border-red-500 @enderror">
                @error('schedule')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Class schedule (optional)</p>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('status') border-red-500 @enderror">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Course availability status</p>
            </div>

            <!-- Description (Full Width) -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" 
                          rows="4" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('description') border-red-500 @enderror"
                          placeholder="Course description, objectives, prerequisites...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Provide a detailed description of the course</p>
            </div>
        </div>

        <!-- Students Section -->
        <div class="mt-8 border-t border-gray-200 pt-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Enroll Students</h3>
                    <p class="text-sm text-gray-500">Select students to enroll in this course</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" id="selectAllStudents" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                        Select All
                    </button>
                    <button type="button" id="deselectAllStudents" class="text-sm text-gray-500 hover:text-gray-700 font-medium">
                        Deselect All
                    </button>
                </div>
            </div>

            <!-- Search Students -->
            <div class="mb-4">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" 
                           id="searchStudents" 
                           placeholder="Search students by name or email..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
            </div>

            <!-- Students List -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="max-h-96 overflow-y-auto">
                    @php
                        $students = \App\Models\User::where('role', 'student')
                            ->orderBy('last_name')
                            ->orderBy('first_name')
                            ->get();
                    @endphp
                    
                    @if($students->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($students as $student)
                            <label class="student-item flex items-center p-4 hover:bg-gray-50 transition cursor-pointer" 
                                   data-name="{{ strtolower($student->first_name . ' ' . $student->last_name . ' ' . $student->email) }}">
                                <input type="checkbox" 
                                       name="student_ids[]" 
                                       value="{{ $student->id }}" 
                                       class="student-checkbox w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 mr-4">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                        <span class="text-xs text-gray-400">ID: {{ $student->student_id ?? $student->id }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $student->email }}</p>
                                </div>
                                <div class="text-sm text-gray-400 ml-2">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-user-graduate text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">No students found. Please add students first.</p>
                            <a href="{{ route('register.student') }}" class="text-indigo-600 text-sm hover:underline mt-2 inline-block">
                                Register New Student →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 flex justify-between items-center">
                <p class="text-sm text-gray-500" id="selectedCount">0 student(s) selected</p>
                <p class="text-sm text-gray-500">You can add more students later</p>
            </div>
        </div>

        <div class="flex gap-3 mt-8">
            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus"></i>
                Create Course & Enroll Students
            </button>
            <a href="{{ route('instructor.courses.index') }}" 
               class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-semibold transition text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // Search functionality
    const searchInput = document.getElementById('searchStudents');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const studentItems = document.querySelectorAll('.student-item');
            
            studentItems.forEach(item => {
                const name = item.dataset.name || '';
                if (name.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Select All functionality
    const selectAllBtn = document.getElementById('selectAllStudents');
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const visibleCheckboxes = document.querySelectorAll('.student-item:not([style*="display: none"]) .student-checkbox');
            visibleCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectedCount();
        });
    }
    
    // Deselect All functionality
    const deselectAllBtn = document.getElementById('deselectAllStudents');
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
            countSpan.textContent = `${selected.length} student(s) selected`;
        }
    }
    
    // Add event listeners to all checkboxes
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Store data-name attribute for search
    document.querySelectorAll('.student-item').forEach(item => {
        const nameElement = item.querySelector('.font-medium');
        const emailElement = item.querySelector('.text-gray-500');
        
        if (nameElement && emailElement) {
            let searchData = nameElement.textContent + ' ' + emailElement.textContent;
            item.dataset.name = searchData.toLowerCase();
        }
    });
    
    // Initial count
    updateSelectedCount();
</script>
@endsection