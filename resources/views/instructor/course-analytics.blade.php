@extends('layouts.instructor')

@section('title', 'Course Analytics - ' . $course->name)

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('instructor.analytics.index') }}" class="text-indigo-600 hover:text-indigo-700">
                        <i class="fa-solid fa-arrow-left"></i> Back to Analytics
                    </a>
                </div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    {{ $course->name }}
                </h1>
                <p class="text-gray-500 mt-1">{{ $course->code }} | {{ $course->class_name ?? 'No Class' }}</p>
            </div>
            <a href="{{ route('instructor.courses.show', $course) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition flex items-center gap-2">
                <i class="fa-solid fa-eye"></i> View Course
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $students->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Average Grade</p>
                    <p class="text-3xl font-bold {{ $avgGrade <= 3.0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $avgGrade }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Passing Rate</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $passingRate }}%</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-trophy text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Highest Grade</p>
                    <p class="text-3xl font-bold text-green-600">{{ $highestGrade }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-arrow-up text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Lowest Grade</p>
                    <p class="text-3xl font-bold text-red-600">{{ $lowestGrade }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-arrow-down text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Grade Distribution</h3>
            <canvas id="gradeDistributionChart" height="250"></canvas>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance Trend (Last 6 Months)</h3>
            <canvas id="attendanceTrendChart" height="250"></canvas>
        </div>
    </div>

    <!-- Student Grades Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Student Grades</h3>
            <p class="text-sm text-gray-500 mt-1">Detailed grade breakdown for each student</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Student ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Prelim</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Midterm</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Prefinal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Final Exam</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Final Grade</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($studentGrades as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                                    <span class="text-indigo-600 font-semibold text-sm">
                                        {{ substr($item['student']->first_name, 0, 1) }}{{ substr($item['student']->last_name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item['student']->first_name }} {{ $item['student']->last_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['student']->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-mono text-gray-600">{{ $item['student']->student_id ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $item['student']->pivot->prelim ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $item['student']->pivot->midterm ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $item['student']->pivot->prefinal ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $item['student']->pivot->final_exam ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold {{ $item['grade'] <= 3.0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $item['grade'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item['grade'] <= 3.0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item['grade'] <= 3.0 ? 'Passed' : 'Failed' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('gradeDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Excellent', 'Very Good', 'Good', 'Satisfactory', 'Passing', 'Failing'],
            datasets: [{
                data: [{{ $gradeDistribution['excellent'] }}, {{ $gradeDistribution['very_good'] }}, {{ $gradeDistribution['good'] }}, {{ $gradeDistribution['satisfactory'] }}, {{ $gradeDistribution['passing'] }}, {{ $gradeDistribution['failing'] }}],
                backgroundColor: ['#22c55e', '#4ade80', '#facc15', '#fb923c', '#a855f7', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } }
    });
    
    new Chart(document.getElementById('attendanceTrendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($attendanceData)) !!},
            datasets: [{
                label: 'Attendance Rate (%)',
                data: {!! json_encode(array_values($attendanceData)) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: { y: { beginAtZero: true, max: 100, title: { display: true, text: 'Attendance Rate (%)' }, ticks: { callback: function(v) { return v + '%'; } } } }
        }
    });
</script>
@endsection