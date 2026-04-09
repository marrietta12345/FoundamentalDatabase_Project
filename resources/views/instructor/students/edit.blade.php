@extends('layouts.instructor')

@section('title', 'Edit Student')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Edit Student
                    </h1>
                    <p class="text-gray-600 mt-2">Update student information and academic details</p>
                </div>
                <a href="{{ route('instructor.students.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-xl transition duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to List</span>
                </a>
            </div>
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

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <form action="{{ route('instructor.students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-8 space-y-8">
                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-user-circle text-indigo-600"></i>
                            Personal Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="first_name" 
                                       value="{{ old('first_name', $student->first_name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition @error('first_name') border-red-500 @enderror"
                                       required>
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                <input type="text" 
                                       name="middle_name" 
                                       value="{{ old('middle_name', $student->middle_name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="last_name" 
                                       value="{{ old('last_name', $student->last_name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                       required>
                            </div>

                            <!-- Student ID (Read Only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                                <input type="text" 
                                       name="student_id" 
                                       value="{{ old('student_id', $student->student_id) }}"
                                       class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-gray-600 cursor-not-allowed"
                                       readonly>
                                <p class="text-xs text-gray-400 mt-1">Student ID is auto-generated</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email', $student->email) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                       required>
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" 
                                       name="phone" 
                                       value="{{ old('phone', $student->phone) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                <input type="date" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $student->date_of_birth) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                                <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- Program -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Program / Course</label>
                                <select name="program" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                    <option value="">Select Program</option>
                                    <option value="Business Management" {{ old('program', $student->program) == 'Business Management' ? 'selected' : '' }}>Business Management</option>
                                    <option value="Information Technology" {{ old('program', $student->program) == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                    <option value="Computer Science" {{ old('program', $student->program) == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                    <option value="Accountancy" {{ old('program', $student->program) == 'Accountancy' ? 'selected' : '' }}>Accountancy</option>
                                    <option value="Financial Management" {{ old('program', $student->program) == 'Financial Management' ? 'selected' : '' }}>Financial Management</option>
                                    <option value="Nursing" {{ old('program', $student->program) == 'Nursing' ? 'selected' : '' }}>Nursing</option>
                                    <option value="Criminology" {{ old('program', $student->program) == 'Criminology' ? 'selected' : '' }}>Criminology</option>
                                    <option value="Physical Education" {{ old('program', $student->program) == 'Physical Education' ? 'selected' : '' }}>Physical Education</option>
                                    <option value="BLISS" {{ old('program', $student->program) == 'BLISS' ? 'selected' : '' }}>BLISS (Library and Information Science)</option>
                                </select>
                            </div>

                            <!-- Year Level -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                                <select name="year_level" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year" {{ old('year_level', $student->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ old('year_level', $student->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ old('year_level', $student->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ old('year_level', $student->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                    <option value="Active" {{ old('status', $student->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status', $student->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="Pending" {{ old('status', $student->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Graduated" {{ old('status', $student->status) == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                </select>
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea name="address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                    placeholder="Your complete address">{{ old('address', $student->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Guardian Information Section -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-users text-indigo-600"></i>
                            Guardian Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Guardian Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Guardian's Full Name</label>
                                <input type="text" 
                                       name="guardian_name" 
                                       value="{{ old('guardian_name', $student->guardian_name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            </div>

                            <!-- Guardian Relationship -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Relationship</label>
                                <select name="guardian_relationship" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                    <option value="">Select Relationship</option>
                                    <option value="Father" {{ old('guardian_relationship', $student->guardian_relationship) == 'Father' ? 'selected' : '' }}>Father</option>
                                    <option value="Mother" {{ old('guardian_relationship', $student->guardian_relationship) == 'Mother' ? 'selected' : '' }}>Mother</option>
                                    <option value="Guardian" {{ old('guardian_relationship', $student->guardian_relationship) == 'Guardian' ? 'selected' : '' }}>Legal Guardian</option>
                                    <option value="Sibling" {{ old('guardian_relationship', $student->guardian_relationship) == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                    <option value="Grandparent" {{ old('guardian_relationship', $student->guardian_relationship) == 'Grandparent' ? 'selected' : '' }}>Grandparent</option>
                                </select>
                            </div>

                            <!-- Guardian Contact -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Guardian's Contact Number</label>
                                <input type="tel" 
                                       name="guardian_contact" 
                                       value="{{ old('guardian_contact', $student->guardian_contact) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            </div>

                            <!-- Guardian Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Guardian's Email</label>
                                <input type="email" 
                                       name="guardian_email" 
                                       value="{{ old('guardian_email', $student->guardian_email) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            </div>

                            <!-- Guardian Address -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Guardian's Address</label>
                                <textarea name="guardian_address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                                    placeholder="Guardian's complete address">{{ old('guardian_address', $student->guardian_address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('instructor.students.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-100 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition duration-200 flex items-center gap-2 font-medium shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-save"></i>
                        Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection