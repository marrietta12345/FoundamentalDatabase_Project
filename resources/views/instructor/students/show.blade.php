@extends('layouts.instructor')

@section('title', 'Student Details')

@section('content')
<div class="p-8 max-w-6xl mx-auto">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Student Details</h1>
                <p class="text-gray-600 mt-1">View student information and enrolled courses</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('instructor.students.edit', $student) }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-pen"></i>
                    Edit Student
                </a>
                <a href="{{ route('instructor.students.index') }}" 
                   class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-xl transition">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Student Information Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white text-center">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-4xl font-bold">
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold">{{ $student->first_name }} {{ $student->last_name }}</h2>
                    <p class="text-indigo-200 text-sm mt-1">Student ID: {{ $student->student_id ?? 'N/A' }}</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</label>
                            <p class="text-gray-900 mt-1">{{ $student->email }}</p>
                        </div>
                        @if($student->phone)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</label>
                            <p class="text-gray-900 mt-1">{{ $student->phone }}</p>
                        </div>
                        @endif
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</label>
                            <p class="mt-1">
                                @if($student->status == 'active')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Inactive</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</label>
                            <p class="text-gray-900 mt-1">{{ $student->created_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrolled Courses Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fa-solid fa-book-open text-indigo-600"></i>
                        Enrolled Courses
                    </h3>
                </div>
                <div class="p-6">
                    @if($student->courses && $student->courses->count() > 0)
                        <div class="space-y-3">
                            @foreach($student->courses as $course)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-indigo-50 transition">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $course->name }}</p>
                                    <p class="text-sm text-gray-500">Code: {{ $course->code }}</p>
                                </div>
                                <div class="text-right">
                                    @if($course->pivot->final_grade)
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $course->pivot->final_grade >= 75 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            Grade: {{ number_format($course->pivot->final_grade, 2) }}
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                                            No Grade
                                        </span>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-1">
                                        Enrolled: {{ $course->pivot->enrolled_at ? $course->pivot->enrolled_at->format('M d, Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-book-open text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500">No courses enrolled yet</p>
                            <a href="{{ route('instructor.students.enroll') }}?student_id={{ $student->id }}" 
                               class="text-indigo-600 hover:underline mt-2 inline-block">
                                Enroll in courses →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection