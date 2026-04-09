@extends('layouts.app')

@section('title', 'My Courses')

@section('content')
<div class="p-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Courses</h1>
        <p class="text-gray-600 mt-1">View all your enrolled courses</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Courses</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_courses'] }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Completed</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['completed_courses'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">In Progress</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['in_progress'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-spinner text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    @if($courses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-4 text-white">
                    <h3 class="text-xl font-bold">{{ $course->code }}</h3>
                    <p class="text-indigo-200 text-sm">{{ $course->name }}</p>
                    @if($course->class_name)
                        <p class="text-indigo-300 text-xs mt-1">
                            <i class="fa-solid fa-chalkboard-user mr-1"></i> {{ $course->class_name }}
                        </p>
                    @endif
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fa-solid fa-chalkboard-user text-sm w-5"></i>
                            <span class="text-sm">{{ $course->instructor->first_name ?? 'N/A' }} {{ $course->instructor->last_name ?? '' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fa-solid fa-users text-sm w-5"></i>
                            <span class="text-sm">{{ $course->students_count ?? $course->students()->count() }} Students</span>
                        </div>
                        @if($course->schedule)
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fa-solid fa-clock text-sm w-5"></i>
                                <span class="text-sm">{{ $course->schedule }}</span>
                            </div>
                        @endif
                        @php
                            $studentGrade = $course->students()->where('course_student.student_id', Auth::id())->first();
                            $finalGrade = $studentGrade ? $studentGrade->pivot->final_grade : null;
                        @endphp
                        @if($finalGrade)
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-ranking-star text-sm w-5 text-yellow-500"></i>
                                <span class="text-sm font-semibold">Final Grade: 
                                    <span class="{{ $finalGrade <= 3.0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $finalGrade }}
                                    </span>
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-5">
                        <a href="{{ route('student.course.show', $course->id) }}" class="block w-full bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-center py-2 rounded-xl transition font-medium text-sm">
                            View Course Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
            <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-book-open text-indigo-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Courses Enrolled</h3>
            <p class="text-gray-500">You haven't enrolled in any courses yet.</p>
            <p class="text-sm text-gray-400 mt-2">Please contact your instructor to enroll you in courses.</p>
        </div>
    @endif
</div>
@endsection