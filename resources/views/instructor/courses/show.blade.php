@extends('layouts.instructor')

@section('title', $course->name . ' - Course Details')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('instructor.courses.index') }}" class="text-indigo-600 hover:text-indigo-700">
                        <i class="fa-solid fa-arrow-left"></i> Back to Courses
                    </a>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    {{ $course->name }}
                </h1>
                <p class="text-gray-500 mt-1">{{ $course->code }} | {{ $course->class_code ?? 'No Class Code' }}</p>
                @if($course->class_name)
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fa-solid fa-chalkboard-user mr-1"></i> {{ $course->class_name }}
                    </p>
                @endif
            </div>
            <div class="flex gap-3">
                <a href="{{ route('instructor.courses.edit', $course) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-pen"></i> Edit Course
                </a>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition flex items-center gap-2">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                    <p class="text-sm text-gray-500 mb-1">With Grades</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $studentsWithGrades ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Average Grade</p>
                    <p class="text-3xl font-bold {{ ($averageGrade ?? 0) <= 3.0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $averageGrade ?? 'N/A' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-ranking-star text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Passing Rate</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $passingRate ?? 0 }}%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-trophy text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Info & Attendance Section -->
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
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $course->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($course->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Created</p>
                        <p class="font-medium text-gray-900">{{ $course->created_at->format('F d, Y') }}</p>
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

        <!-- Attendance Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fa-solid fa-calendar-check text-indigo-600 mr-2"></i>
                    Attendance Summary
                </h3>
            </div>
            <div class="p-6">
                <div class="text-center mb-4">
                    <div class="relative inline-flex items-center justify-center">
                        <svg class="w-32 h-32">
                            <circle class="text-gray-200" stroke-width="8" stroke="currentColor" fill="transparent" r="56" cx="64" cy="64"/>
                            <circle class="text-indigo-600" stroke-width="8" stroke-dasharray="{{ ($attendanceRate ?? 0) * 3.52 }}" stroke-dashoffset="0" stroke-linecap="round" stroke="currentColor" fill="transparent" r="56" cx="64" cy="64" transform="rotate(-90 64 64)"/>
                        </svg>
                        <span class="absolute text-2xl font-bold text-gray-900">{{ $attendanceRate ?? 0 }}%</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Overall Attendance Rate</p>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Present</span>
                        <span class="font-semibold text-green-600">{{ $presentCount ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Absent</span>
                        <span class="font-semibold text-red-600">{{ $absentCount ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Late</span>
                        <span class="font-semibold text-yellow-600">{{ $lateCount ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fa-solid fa-user-graduate text-indigo-600 mr-2"></i>
                Enrolled Students
            </h3>
            <a href="{{ route('instructor.courses.add-students', $course) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium flex items-center gap-1">
                <i class="fa-solid fa-plus"></i> Add Students
            </a>
        </div>
        
        <div class="overflow-x-auto">
            @if(isset($enrolledStudents) && $enrolledStudents->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Student ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Program</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Final Grade</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($enrolledStudents as $index => $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                                        <span class="text-indigo-600 font-semibold text-sm">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-gray-600">{{ $student->student_id ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $student->email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $student->program ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $finalGrade = $student->pivot->final_grade ?? null;
                                @endphp
                                @if($finalGrade)
                                    <span class="font-semibold {{ $finalGrade <= 3.0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $finalGrade }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Not graded</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($finalGrade)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $finalGrade <= 3.0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $finalGrade <= 3.0 ? 'Passed' : 'Failed' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('instructor.grades.edit', $student->pivot->grade_id ?? '#') }}" class="text-indigo-600 hover:text-indigo-800">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <button onclick="removeStudent({{ $course->id }}, {{ $student->id }})" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-users-slash text-gray-400 text-2xl"></i>
                    </div>
                    <p class="text-gray-500">No students enrolled in this course yet.</p>
                    <a href="{{ route('instructor.courses.add-students', $course) }}" class="inline-block mt-3 text-indigo-600 hover:text-indigo-700">
                        Add Students →
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Delete Course</h3>
            <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <p class="text-gray-600 mb-6">Are you sure you want to delete "{{ $course->name }}"? This action cannot be undone.</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
            <form id="deleteForm" method="POST" action="{{ route('instructor.courses.destroy', $course) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Delete Course</button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    function removeStudent(courseId, studentId) {
        if(confirm('Are you sure you want to remove this student from the course?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/instructor/courses/${courseId}/students/${studentId}`;
            const csrf = document.createElement('input');
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            const method = document.createElement('input');
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(csrf);
            form.appendChild(method);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection