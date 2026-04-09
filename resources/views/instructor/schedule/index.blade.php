@extends('layouts.instructor')

@section('title', 'Class Schedule')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Class Schedule
                </h1>
                <p class="text-gray-500 mt-1">View your teaching schedule and class timings</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Print Schedule
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Classes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_classes'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-chalkboard-user text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Active Courses</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['active_courses'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_students'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">This Week</p>
                    <p class="text-3xl font-bold text-gray-900">{{ now()->format('W') }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-calendar-week text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fa-solid fa-calendar-alt text-indigo-600 mr-2"></i>
                Weekly Schedule
            </h3>
            <p class="text-sm text-gray-500 mt-1">Your class schedule for the current semester</p>
        </div>
        
        <div class="overflow-x-auto">
            @if($courses->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Course Code</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Course Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Class Code</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Schedule</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Students</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($courses as $course)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="font-mono text-indigo-600 font-medium">{{ $course->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $course->name }}</p>
                                    @if($course->class_name)
                                        <p class="text-xs text-gray-500">{{ $course->class_name }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $course->class_code ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($course->schedule)
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-clock text-gray-400"></i>
                                        <span class="text-sm text-gray-700">{{ $course->schedule }}</span>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">Not scheduled</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-users text-gray-400"></i>
                                    <span class="text-sm text-gray-700">{{ $course->students_count ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($course->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fa-solid fa-circle text-green-500 text-xs mr-1"></i> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fa-solid fa-circle text-red-500 text-xs mr-1"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('instructor.courses.show', $course) }}" class="text-indigo-600 hover:text-indigo-800 transition" title="View Course">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('instructor.courses.edit', $course) }}" class="text-gray-600 hover:text-gray-800 transition" title="Edit Course">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="{{ route('instructor.attendance.index', ['course' => $course->id]) }}" class="text-green-600 hover:text-green-800 transition" title="Take Attendance">
                                        <i class="fa-solid fa-calendar-check"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-calendar-alt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Classes Scheduled</h3>
                    <p class="text-gray-500 mb-6">You haven't created any courses yet.</p>
                    <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl transition">
                        <i class="fa-solid fa-plus"></i>
                        Create Your First Course
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Week Days Quick View -->
    @if($courses->count() > 0)
    <div class="mt-8 grid grid-cols-1 md:grid-cols-5 gap-4">
        @php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $dayClasses = [];
            foreach ($courses as $course) {
                if ($course->schedule) {
                    foreach ($days as $day) {
                        if (stripos($course->schedule, substr($day, 0, 3)) !== false) {
                            $dayClasses[$day][] = $course;
                        }
                    }
                }
            }
        @endphp
        
        @foreach($days as $day)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-100">
                    <h4 class="font-semibold text-gray-900">{{ $day }}</h4>
                </div>
                <div class="p-3">
                    @if(isset($dayClasses[$day]) && count($dayClasses[$day]) > 0)
                        @foreach($dayClasses[$day] as $class)
                            <div class="mb-2 p-2 bg-gray-50 rounded-lg">
                                <p class="text-xs font-medium text-gray-900">{{ $class->code }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $class->name }}</p>
                                @if($class->schedule)
                                    <p class="text-xs text-indigo-600 mt-1">
                                        <i class="fa-regular fa-clock"></i> {{ $class->schedule }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-xs text-gray-400 text-center py-2">No classes</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection