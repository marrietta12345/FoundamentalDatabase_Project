@extends('layouts.instructor')

@section('title', 'Add New Student')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Add New Student</h1>
        <p class="text-gray-600 mt-1">Create a new student account</p>
    </div>

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

    <form method="POST" action="{{ route('instructor.students.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Student ID - Added Field -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Student ID *</label>
                <input type="text" 
                       name="student_id" 
                       value="{{ old('student_id') }}" 
                       placeholder="e.g., 001, 002, 2024001"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('student_id') border-red-500 @enderror">
                @error('student_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Leave blank to auto-generate (e.g., 001, 002, etc.)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                <input type="text" 
                       name="first_name" 
                       value="{{ old('first_name') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('first_name') border-red-500 @enderror"
                       required>
                @error('first_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                <input type="text" 
                       name="middle_name" 
                       value="{{ old('middle_name') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                <input type="text" 
                       name="last_name" 
                       value="{{ old('last_name') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('last_name') border-red-500 @enderror"
                       required>
                @error('last_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                <input type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="text" 
                       name="phone" 
                       value="{{ old('phone') }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8">
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-info-circle text-blue-600 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Important Information:</p>
                        <p class="text-sm text-blue-800 mt-1">
                            • <strong>Student ID:</strong> If left blank, it will be auto-generated sequentially (e.g., 001, 002, etc.)
                        </p>
                        <p class="text-sm text-blue-800 mt-1">
                            • <strong>Default Password:</strong> Student ID + "123" (e.g., 001123)
                        </p>
                        <p class="text-sm text-blue-800 mt-1">
                            • <strong>Status:</strong> New students are automatically set to "Active"
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-8">
            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-save"></i>
                Create Student
            </button>
            <a href="{{ route('instructor.students.index') }}" 
               class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-semibold transition text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // Optional: Add validation for student ID format
    document.querySelector('form').addEventListener('submit', function(e) {
        const studentId = document.querySelector('input[name="student_id"]').value;
        if (studentId && !/^[a-zA-Z0-9\-]+$/.test(studentId)) {
            e.preventDefault();
            alert('Student ID can only contain letters, numbers, and hyphens.');
        }
    });
</script>
@endsection