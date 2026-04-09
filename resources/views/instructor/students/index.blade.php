@extends('layouts.instructor')

@section('title', 'Manage Students')

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Students</h1>
                <p class="text-gray-600 mt-1">Manage all students in the system</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('instructor.students.create') }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    Add New Student
                </a>
                <a href="{{ route('instructor.students.enroll') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i>
                    Enroll Student
                </a>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form method="GET" action="{{ route('instructor.students.index') }}" class="relative">
            <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Search by name, email, or student ID..." 
                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalStudents ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Active Students</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $activeStudents ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-user-check text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Inactive Students</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $inactiveStudents ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-user-xmark text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student ID</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Program</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Year Level</th>
                        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($studentData ?? [] as $student)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm font-semibold text-indigo-600">{{ $student['student_id'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-600 font-bold text-sm">
                                        {{ $student['initial'] }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $student['fullname'] }}</p>
                                    @if($student['middle_name'])
                                        <p class="text-xs text-gray-500">{{ $student['middle_name'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $student['email'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $student['program'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $student['year_level'] }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($student['status'] == 'Active')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                            @elseif($student['status'] == 'Inactive')
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Inactive</span>
                            @else
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">{{ $student['status'] }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('instructor.students.show', $student['id']) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 transition p-1" title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('instructor.students.edit', $student['id']) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition p-1" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('instructor.students.destroy', $student['id']) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this student?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition p-1" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-user-graduate text-gray-400 text-3xl"></i>
                            </div>
                            <p class="text-gray-500">No students found</p>
                            <a href="{{ route('instructor.students.create') }}" class="text-indigo-600 hover:underline mt-2 inline-block">
                                Add your first student →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection