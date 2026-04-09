@extends('layouts.app')

@section('title', 'Student Registration')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
            Student Registration
        </h1>
        <p class="text-gray-600 mt-1">Complete your personal and guardian information</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-exclamation-triangle text-red-600 text-xl"></i>
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">×</button>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
            <ul class="list-disc list-inside text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Registration Steps -->
    <div class="flex items-center justify-center mb-8">
        <div class="flex items-center gap-4">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">1</div>
                <div class="ml-2 text-sm font-medium text-gray-900">Personal Info</div>
            </div>
            <div class="w-16 h-0.5 bg-indigo-300"></div>
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">2</div>
                <div class="ml-2 text-sm font-medium text-gray-900">Guardian Info</div>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <form method="POST" action="{{ route('student.registration.submit') }}" class="space-y-8">
        @csrf

        <!-- SECTION 1: Personal Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fa-solid fa-user-circle text-indigo-600 mr-2"></i>
                    Personal Information
                </h3>
                <p class="text-sm text-gray-500 mt-1">Your basic personal details</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                        <input type="text" name="student_id" value="{{ old('student_id', $student->student_id ?? '') }}"
                               class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-gray-600 cursor-not-allowed" readonly>
                        <p class="text-xs text-gray-400 mt-1">Student ID is auto-generated</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $student->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Program / Course</label>
                        <select name="program" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            <option value="">Select Program</option>
                            <option value="Business Management" {{ old('program', $student->program ?? '') == 'Business Management' ? 'selected' : '' }}>Business Management</option>
                            <option value="Information Technology" {{ old('program', $student->program ?? '') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                            <option value="Computer Science" {{ old('program', $student->program ?? '') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                            <option value="Accountancy" {{ old('program', $student->program ?? '') == 'Accountancy' ? 'selected' : '' }}>Accountancy</option>
                            <option value="Financial Management" {{ old('program', $student->program ?? '') == 'Financial Management' ? 'selected' : '' }}>Financial Management</option>
                            <option value="Nursing" {{ old('program', $student->program ?? '') == 'Nursing' ? 'selected' : '' }}>Nursing</option>
                            <option value="Criminology" {{ old('program', $student->program ?? '') == 'Criminology' ? 'selected' : '' }}>Criminology</option>
                            <option value="Physical Education" {{ old('program', $student->program ?? '') == 'Physical Education' ? 'selected' : '' }}>Physical Education</option>
                            <option value="BLISS" {{ old('program', $student->program ?? '') == 'BLISS' ? 'selected' : '' }}>BLISS (Library and Information Science)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                        <select name="year_level" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            <option value="">Select Year Level</option>
                            <option value="1st Year" {{ old('year_level', $student->year_level ?? '') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year_level', $student->year_level ?? '') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year_level', $student->year_level ?? '') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year_level', $student->year_level ?? '') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            placeholder="Your complete address">{{ old('address', $student->address ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: Guardian Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fa-solid fa-users text-indigo-600 mr-2"></i>
                    Guardian Information
                </h3>
                <p class="text-sm text-gray-500 mt-1">Parent or guardian contact details</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Guardian's Full Name</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Relationship</label>
                        <select name="guardian_relationship" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            <option value="">Select Relationship</option>
                            <option value="Father" {{ old('guardian_relationship', $student->guardian_relationship ?? '') == 'Father' ? 'selected' : '' }}>Father</option>
                            <option value="Mother" {{ old('guardian_relationship', $student->guardian_relationship ?? '') == 'Mother' ? 'selected' : '' }}>Mother</option>
                            <option value="Guardian" {{ old('guardian_relationship', $student->guardian_relationship ?? '') == 'Guardian' ? 'selected' : '' }}>Legal Guardian</option>
                            <option value="Sibling" {{ old('guardian_relationship', $student->guardian_relationship ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                            <option value="Grandparent" {{ old('guardian_relationship', $student->guardian_relationship ?? '') == 'Grandparent' ? 'selected' : '' }}>Grandparent</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Guardian's Contact Number</label>
                        <input type="tel" name="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Guardian's Email</label>
                        <input type="email" name="guardian_email" value="{{ old('guardian_email', $student->guardian_email ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Guardian's Address</label>
                        <textarea name="guardian_address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                            placeholder="Guardian's complete address">{{ old('guardian_address', $student->guardian_address ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2 shadow-md">
                <i class="fa-solid fa-save"></i>
                Save Registration Information
            </button>
            <a href="{{ route('dashboard') }}" class="flex-1 border border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-xl font-semibold transition text-center flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i>
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection