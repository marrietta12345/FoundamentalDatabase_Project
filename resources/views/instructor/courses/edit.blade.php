@extends('layouts.instructor')

@section('title', 'Edit Course - ' . $course->name)

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Course</h1>
                <p class="text-gray-600 mt-1">Update course details and class information</p>
            </div>
            <a href="{{ route('instructor.courses.show', $course) }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Course
            </a>
        </div>
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

    <form method="POST" action="{{ route('instructor.courses.update', $course) }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Class Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Class Code *</label>
                <input type="text" 
                       name="class_code" 
                       value="{{ old('class_code', $course->class_code) }}" 
                       placeholder="e.g., CS101-A, IT201-B"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('class_code') border-red-500 @enderror"
                       required>
                @error('class_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Unique class identifier (e.g., CS101-A, IT201-B)</p>
            </div>

            <!-- Class Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Class Name *</label>
                <input type="text" 
                       name="class_name" 
                       value="{{ old('class_name', $course->class_name) }}" 
                       placeholder="e.g., THEO 110, PATHFIT 1, NSTP 1"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('class_name') border-red-500 @enderror"
                       required>
                @error('class_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter the class/subject name (e.g., THEO 110, PATHFIT 1, NSTP 1)</p>
            </div>

            <!-- Course Code -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Course Code *</label>
                <input type="text" 
                       name="code" 
                       value="{{ old('code', $course->code) }}" 
                       placeholder="e.g., CS101"
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
                    <option value="Business Management" {{ old('name', $course->name) == 'Business Management' ? 'selected' : '' }}>Business Management</option>
                    <option value="BLISS (Bachelor of Library and Information Science)" {{ old('name', $course->name) == 'BLISS (Bachelor of Library and Information Science)' ? 'selected' : '' }}>BLISS (Bachelor of Library and Information Science)</option>
                    <option value="Information Technology" {{ old('name', $course->name) == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                    <option value="Computer Science" {{ old('name', $course->name) == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                    <option value="Accountancy" {{ old('name', $course->name) == 'Accountancy' ? 'selected' : '' }}>Accountancy</option>
                    <option value="Financial Management" {{ old('name', $course->name) == 'Financial Management' ? 'selected' : '' }}>Financial Management</option>
                    <option value="Nursing" {{ old('name', $course->name) == 'Nursing' ? 'selected' : '' }}>Nursing</option>
                    <option value="Criminology" {{ old('name', $course->name) == 'Criminology' ? 'selected' : '' }}>Criminology</option>
                    <option value="Lawyer" {{ old('name', $course->name) == 'Lawyer' ? 'selected' : '' }}>Lawyer</option>
                    <option value="Physical Education" {{ old('name', $course->name) == 'Physical Education' ? 'selected' : '' }}>Physical Education</option>
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
                       value="{{ old('schedule', $course->schedule) }}" 
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
                    <option value="active" {{ old('status', $course->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $course->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                          placeholder="Course description, objectives, prerequisites...">{{ old('description', $course->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Provide a detailed description of the course</p>
            </div>
        </div>

        <div class="flex gap-3 mt-8">
            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-save"></i>
                Update Course
            </button>
            <a href="{{ route('instructor.courses.show', $course) }}" 
               class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-semibold transition text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // Add loading state to update button
    const updateForm = document.querySelector('form');
    const updateButton = updateForm?.querySelector('button[type="submit"]');
    if (updateButton) {
        updateForm.addEventListener('submit', function() {
            updateButton.disabled = true;
            updateButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Updating...';
        });
    }
    
    // Debug info
    console.log('Edit page loaded');
    console.log('Course ID: {{ $course->id }}');
    console.log('Class Code: {{ $course->class_code }}');
    console.log('Class Name: {{ $course->class_name }}');
    console.log('Course Code: {{ $course->code }}');
    console.log('Course Name: {{ $course->name }}');
    console.log('Current Status: {{ $course->status }}');
    console.log('Current Schedule: {{ $course->schedule }}');
</script>
@endsection