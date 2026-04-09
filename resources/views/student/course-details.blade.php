@extends('layouts.app')

@section('title', $course->name)

@section('content')
<div class="p-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('student.courses') }}" class="text-indigo-600 hover:text-indigo-700">
                        <i class="fa-solid fa-arrow-left"></i> Back to Courses
                    </a>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    {{ $course->name }}
                </h1>
                <p class="text-gray-500 mt-1">{{ $course->code }} | {{ $course->class_name ?? 'No Class' }}</p>
            </div>
        </div>
    </div>

    <!-- Course Info & Grade Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Course Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fa-solid fa-info-circle text-indigo-600 mr-2"></i>
                    Course Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Student Name - Added -->
                    <div>
                        <p class="text-sm text-gray-500">Student Name</p>
                        <p class="font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</p>
                    </div>
                    <!-- Student ID - Added -->
                    <div>
                        <p class="text-sm text-gray-500">Student ID</p>
                        <p class="font-medium text-gray-900">{{ $user->student_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Course Code</p>
                        <p class="font-medium text-gray-900">{{ $course->code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Class Code</p>
                        <p class="font-medium text-gray-900">{{ $course->class_code ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Class Name</p>
                        <p class="font-medium text-gray-900">{{ $course->class_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Schedule</p>
                        <p class="font-medium text-gray-900">{{ $course->schedule ?? 'Not scheduled' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Instructor</p>
                        <p class="font-medium text-gray-900">{{ $course->instructor->first_name ?? 'N/A' }} {{ $course->instructor->last_name ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $course->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($course->status) }}
                        </span>
                    </div>
                </div>
                @if($course->description)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-sm text-gray-500 mb-2">Description</p>
                        <p class="text-gray-700">{{ $course->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Grade Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fa-solid fa-ranking-star text-indigo-600 mr-2"></i>
                    Your Grade
                </h3>
            </div>
            <div class="p-6">
                @if($grade && $grade->final_grade)
                    <div class="text-center">
                        <div class="text-5xl font-bold {{ $grade->final_grade <= 3.0 ? 'text-green-600' : 'text-red-600' }} mb-2">
                            {{ $grade->final_grade }}
                        </div>
                        <p class="text-gray-500">Final Grade</p>
                        <div class="mt-3 inline-block px-4 py-1 rounded-full text-sm font-semibold {{ $grade->final_grade <= 3.0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $grade->final_grade <= 3.0 ? 'PASSED' : 'FAILED' }}
                        </div>
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        @if($grade->prelim)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Prelim</span>
                                <span class="font-medium">{{ $grade->prelim }}</span>
                            </div>
                        @endif
                        @if($grade->midterm)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Midterm</span>
                                <span class="font-medium">{{ $grade->midterm }}</span>
                            </div>
                        @endif
                        @if($grade->prefinal)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm text-gray-600">Prefinal</span>
                                <span class="font-medium">{{ $grade->prefinal }}</span>
                            </div>
                        @endif
                        @if($grade->final_exam)
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-600">Final Exam</span>
                                <span class="font-medium">{{ $grade->final_exam }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fa-solid fa-clock text-gray-400 text-5xl mb-3"></i>
                        <p class="text-gray-500">No grade available yet</p>
                        <p class="text-xs text-gray-400 mt-2">Your grade will appear here once available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Attendance Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fa-solid fa-calendar-check text-indigo-600 mr-2"></i>
                Attendance Records
            </h3>
        </div>
        <div class="p-6">
            @if($attendances->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-green-600">Present</p>
                        <p class="text-2xl font-bold text-green-700">{{ $attendanceStats['present'] }}</p>
                    </div>
                    <div class="bg-red-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-red-600">Absent</p>
                        <p class="text-2xl font-bold text-red-700">{{ $attendanceStats['absent'] }}</p>
                    </div>
                    <div class="bg-yellow-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-yellow-600">Late</p>
                        <p class="text-2xl font-bold text-yellow-700">{{ $attendanceStats['late'] }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-xl p-4 text-center">
                        <p class="text-sm text-purple-600">Attendance Rate</p>
                        <p class="text-2xl font-bold text-purple-700">{{ $attendanceRate }}%</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 rounded-lg">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Time In</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($attendances as $attendance)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                        @if($attendance->status == 'present') bg-green-100 text-green-700
                                        @elseif($attendance->status == 'absent') bg-red-100 text-red-700
                                        @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-700
                                        @else bg-blue-100 text-blue-700 @endif">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $attendance->time_in ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $attendance->remarks ?? '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fa-solid fa-calendar text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-500">No attendance records found</p>
                    <p class="text-xs text-gray-400 mt-2">Attendance records will appear here once taken</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection