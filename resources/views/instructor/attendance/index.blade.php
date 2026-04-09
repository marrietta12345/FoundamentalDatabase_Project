@extends('layouts.instructor')

@section('title', 'Attendance Management')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Attendance Management
                </h1>
                <p class="text-gray-500 mt-1">Track and manage student attendance efficiently</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="markAll('present')" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-check-circle"></i> All Present
                </button>
                <button type="button" onclick="markAll('late')" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-clock"></i> All Late
                </button>
                <button type="button" onclick="markAll('absent')" class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-times-circle"></i> All Absent
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl p-4 animate-slide-in">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-check-circle text-emerald-500 text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="text-emerald-700">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl p-4 animate-slide-in">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0">
                <i class="fa-solid fa-exclamation-triangle text-rose-500 text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="text-rose-700">{{ session('error') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Main Form -->
    <form method="POST" action="{{ route('instructor.attendance.store') }}" class="space-y-6">
        @csrf
        
        <!-- Filter Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Course Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-book-open text-indigo-500 mr-2"></i>
                    Select Course
                </label>
                <select name="course_id" id="course_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <option value="">Choose a course...</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ $course->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Student Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-user-graduate text-indigo-500 mr-2"></i>
                    Filter Student
                </label>
                <select name="student_filter" id="student_filter" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student') == $student->id ? 'selected' : '' }}>
                                            {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

            <!-- Date Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fa-solid fa-calendar-alt text-indigo-500 mr-2"></i>
                    Select Date
                </label>
                <input type="date" 
                       name="date" 
                       id="date_input"
                       value="{{ $date }}" 
                       required
                       max="{{ now()->format('Y-m-d') }}"
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            </div>

            <!-- Stats Card -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg p-5 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm mb-1">Total Students</p>
                        <p class="text-3xl font-bold" id="totalStudentsCount">{{ count($students) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                </div>
                <p class="text-indigo-100 text-xs mt-2">Currently enrolled in this course</p>
            </div>
        </div>

        <!-- Attendance Table Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header with Legend -->
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fa-solid fa-clipboard-list text-indigo-500 mr-2"></i>
                    Attendance Record
                </h3>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                        <span class="text-gray-600">Present</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-rose-500 rounded-full"></span>
                        <span class="text-gray-600">Absent</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-amber-500 rounded-full"></span>
                        <span class="text-gray-600">Late</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                        <span class="text-gray-600">Excused</span>
                    </div>
                </div>
            </div>

            @if(count($students) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID Number</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Program</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Time In</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="attendanceTableBody">
                        @foreach($students as $index => $student)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 student-row" data-student-id="{{ $student->id }}">
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <span class="text-indigo-600 font-semibold text-sm">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $student->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-gray-600">{{ $student->student_id ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $student->program ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <select name="attendance[{{ $student->id }}]" 
                                        class="attendance-status px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 hover:bg-white transition"
                                        data-student-id="{{ $student->id }}">
                                    <option value="present" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status == 'present' ? 'selected' : '' }}>✓ Present</option>
                                    <option value="absent" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status == 'absent' ? 'selected' : '' }}>✗ Absent</option>
                                    <option value="late" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status == 'late' ? 'selected' : '' }}>⏰ Late</option>
                                    <option value="excused" {{ isset($attendances[$student->id]) && $attendances[$student->id]->status == 'excused' ? 'selected' : '' }}>📝 Excused</option>
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <input type="time" 
                                       name="time_in[{{ $student->id }}]" 
                                       value="{{ isset($attendances[$student->id]) ? $attendances[$student->id]->time_in : '' }}"
                                       class="time-in-input px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50"
                                       placeholder="--:--">
                            </td>
                            <td class="px-6 py-4">
                                <input type="text" 
                                       name="remarks[{{ $student->id }}]" 
                                       value="{{ isset($attendances[$student->id]) ? $attendances[$student->id]->remarks : '' }}"
                                       class="px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 w-40"
                                       placeholder="Add remarks...">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 p-6 bg-gray-50 border-t border-gray-100">
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Present</p>
                            <p class="text-2xl font-bold text-emerald-600" id="presentCount">0</p>
                        </div>
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-check-circle text-emerald-500"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Absent</p>
                            <p class="text-2xl font-bold text-rose-600" id="absentCount">0</p>
                        </div>
                        <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-times-circle text-rose-500"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Late</p>
                            <p class="text-2xl font-bold text-amber-600" id="lateCount">0</p>
                        </div>
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-clock text-amber-500"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Excused</p>
                            <p class="text-2xl font-bold text-blue-600" id="excusedCount">0</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-shield-alt text-blue-500"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-indigo-100 mb-1">Attendance Rate</p>
                            <p class="text-2xl font-bold text-white" id="attendanceRate">0%</p>
                        </div>
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-chart-line text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 p-6 bg-white border-t border-gray-100">
                <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3 rounded-xl font-semibold transition-all duration-200 transform hover:scale-[1.02] flex items-center justify-center gap-2 shadow-md">
                    <i class="fa-solid fa-save"></i>
                    Save Attendance
                </button>
                <button type="reset" class="px-8 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-undo-alt"></i>
                    Reset
                </button>
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-users-slash text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Students Enrolled</h3>
                <p class="text-gray-500 mb-6">This course doesn't have any students yet</p>
                <a href="{{ route('instructor.courses.index') }}" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-700 font-medium">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to Courses
                </a>
            </div>
            @endif
        </div>
    </form>
</div>

<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }
</style>

<script>
    // Filter students by selected student
    function filterStudents() {
        const studentFilter = document.getElementById('student_filter').value;
        const rows = document.querySelectorAll('.student-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const studentId = row.getAttribute('data-student-id');
            if (studentFilter === '' || studentFilter === studentId) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        document.getElementById('totalStudentsCount').textContent = visibleCount;
        updateSummary();
    }
    
    // Update summary counts
    function updateSummary() {
        let present = 0, absent = 0, late = 0, excused = 0;
        const rows = document.querySelectorAll('.student-row:not([style*="display: none"])');
        const total = rows.length;
        
        rows.forEach(row => {
            const select = row.querySelector('.attendance-status');
            if (select) {
                switch(select.value) {
                    case 'present': present++; break;
                    case 'absent': absent++; break;
                    case 'late': late++; break;
                    case 'excused': excused++; break;
                }
            }
        });
        
        document.getElementById('presentCount').textContent = present;
        document.getElementById('absentCount').textContent = absent;
        document.getElementById('lateCount').textContent = late;
        document.getElementById('excusedCount').textContent = excused;
        
        const attendanceRate = total > 0 ? ((present + late) / total * 100).toFixed(1) : 0;
        document.getElementById('attendanceRate').textContent = attendanceRate + '%';
    }
    
    // Mark all students
    function markAll(status) {
        const rows = document.querySelectorAll('.student-row:not([style*="display: none"])');
        rows.forEach(row => {
            const select = row.querySelector('.attendance-status');
            if (select) {
                select.value = status;
                if (status === 'present' || status === 'late') {
                    const timeInput = row.querySelector('.time-in-input');
                    if (timeInput && !timeInput.value) {
                        timeInput.value = setCurrentTime();
                    }
                }
            }
        });
        updateSummary();
    }
    
    // Get current time
    function setCurrentTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    }
    
    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('.attendance-status');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                const row = this.closest('tr');
                const timeInput = row.querySelector('.time-in-input');
                if ((this.value === 'present' || this.value === 'late') && timeInput && !timeInput.value) {
                    timeInput.value = setCurrentTime();
                }
                updateSummary();
            });
        });
        
        const studentFilter = document.getElementById('student_filter');
        if (studentFilter) {
            studentFilter.addEventListener('change', filterStudents);
        }
        
        updateSummary();
        
        const courseSelect = document.getElementById('course_id');
        const dateInput = document.getElementById('date_input');
        
        function autoLoadAttendance() {
            if (courseSelect.value && dateInput.value) {
                let url = '{{ route("instructor.attendance.index") }}?course=' + courseSelect.value + '&date=' + dateInput.value;
                const studentFilterValue = document.getElementById('student_filter').value;
                if (studentFilterValue) {
                    url += '&student=' + studentFilterValue;
                }
                window.location.href = url;
            }
        }
        
        if (courseSelect) courseSelect.addEventListener('change', autoLoadAttendance);
        if (dateInput) dateInput.addEventListener('change', autoLoadAttendance);
        
        filterStudents();
    });
</script>
@endsection