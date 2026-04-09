@extends('layouts.instructor')

@section('title', 'Manage Grades')

@section('content')
<div class="p-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Grades Management</h1>
            <p class="text-gray-600 mt-1">Manage student grades across all your courses</p>
        </div>
        <a href="{{ route('instructor.grades.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-semibold transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Add New Grade
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" action="{{ route('instructor.grades.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                <select name="course_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                <select name="student_id" class="w-full px-4 py-2 border border-gray-300 rounded-xl">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->first_name }} {{ $student->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-xl">
                    <option value="">All Status</option>
                    <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl font-medium transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Grades</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $totalGrades ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Passed</p>
                    <p class="text-3xl font-bold text-green-600">{{ $passed ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Failed</p>
                    <p class="text-3xl font-bold text-red-600">{{ $failed ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $pending ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grades Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prelim</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Midterm</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Prefinal</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Final Exam</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Final Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($grades as $grade)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fa-solid fa-user text-indigo-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $grade->student->first_name }} {{ $grade->student->last_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $grade->student->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $grade->course->name }}</p>
                            <p class="text-sm text-gray-500">Code: {{ $grade->course->code }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-900">{{ $grade->subject }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">{{ $grade->prelim ? number_format($grade->prelim, 2) : 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">{{ $grade->midterm ? number_format($grade->midterm, 2) : 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">{{ $grade->prefinal ? number_format($grade->prefinal, 2) : 'N/A' }}</td>
                        <td class="px-6 py-4 text-center">{{ $grade->final_exam ? number_format($grade->final_exam, 2) : 'N/A' }}</td>
                        <td class="px-6 py-4 text-center font-semibold">{{ $grade->final_grade ? number_format($grade->final_grade, 2) : 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if($grade->status == 'passed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fa-solid fa-check-circle mr-1 text-xs"></i> Passed
                                </span>
                            @elseif($grade->status == 'failed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fa-solid fa-times-circle mr-1 text-xs"></i> Failed
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fa-solid fa-clock mr-1 text-xs"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <!-- VIEW BUTTON - ADDED -->
                                <a href="{{ route('instructor.grades.show', $grade->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 transition p-1" 
                                   title="View Details">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('instructor.grades.edit', $grade->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition p-1" 
                                   title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('instructor.grades.delete', $grade->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this grade?')"
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
                        <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 block"></i>
                            <p class="text-lg">No grades found</p>
                            <p class="text-sm mt-1">Click the "Add New Grade" button to create your first grade record.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection