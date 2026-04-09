@extends('layouts.instructor')

@section('title', 'Grade Details')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('instructor.grades.index') }}" class="text-indigo-600 hover:text-indigo-700">
                        <i class="fa-solid fa-arrow-left"></i> Back to Grades
                    </a>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Grade Details
                </h1>
                <p class="text-gray-600 mt-1">View complete grade information for {{ $grade->student->first_name }} {{ $grade->student->last_name }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('instructor.grades.edit', $grade->id) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-pen"></i> Edit Grade
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Student Information Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fa-solid fa-user-graduate text-indigo-600 mr-2"></i>
                        Student Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($grade->student->first_name, 0, 1) }}{{ substr($grade->student->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">{{ $grade->student->first_name }} {{ $grade->student->last_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $grade->student->email }}</p>
                            <p class="text-xs text-indigo-600 mt-1">Student ID: {{ $grade->student->student_id ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Program</span>
                            <span class="text-sm font-medium text-gray-900">{{ $grade->student->program ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Year Level</span>
                            <span class="text-sm font-medium text-gray-900">{{ $grade->student->year_level ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Phone</span>
                            <span class="text-sm font-medium text-gray-900">{{ $grade->student->phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Information Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fa-solid fa-ranking-star text-indigo-600 mr-2"></i>
                        Grade Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">Course</p>
                            <p class="font-medium text-gray-900">{{ $grade->course->name }}</p>
                            <p class="text-xs text-gray-500">Code: {{ $grade->course->code }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Subject</p>
                            <p class="font-medium text-gray-900">{{ $grade->subject }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Class</p>
                            <p class="font-medium text-gray-900">{{ $grade->class_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Academic Year</p>
                            <p class="font-medium text-gray-900">{{ $grade->academic_year ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Semester</p>
                            <p class="font-medium text-gray-900">{{ $grade->semester ?? '1st Semester' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Graded By</p>
                            <p class="font-medium text-gray-900">{{ $grade->graded_by_name ?? 'Instructor' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Grade Components</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <p class="text-sm text-gray-500">Prelim</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $grade->prelim ? number_format($grade->prelim, 2) : 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <p class="text-sm text-gray-500">Midterm</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $grade->midterm ? number_format($grade->midterm, 2) : 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <p class="text-sm text-gray-500">Prefinal</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $grade->prefinal ? number_format($grade->prefinal, 2) : 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 text-center">
                                <p class="text-sm text-gray-500">Final Exam</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $grade->final_exam ? number_format($grade->final_exam, 2) : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Final Grade</p>
                                <p class="text-4xl font-bold {{ $grade->final_grade && $grade->final_grade <= 3.0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $grade->final_grade ? number_format($grade->final_grade, 2) : 'N/A' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Status</p>
                                @if($grade->status == 'passed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fa-solid fa-check-circle mr-1"></i> Passed
                                    </span>
                                @elseif($grade->status == 'failed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fa-solid fa-times-circle mr-1"></i> Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fa-solid fa-clock mr-1"></i> Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t border-indigo-200">
                            <p class="text-xs text-gray-500">Grade Date: {{ $grade->created_at ? $grade->created_at->format('F d, Y h:i A') : 'N/A' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Last Updated: {{ $grade->updated_at ? $grade->updated_at->format('F d, Y h:i A') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection