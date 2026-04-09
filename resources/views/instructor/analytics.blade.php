@extends('layouts.instructor')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Analytics Dashboard
                </h1>
                <p class="text-gray-500 mt-1">Track your course performance and student progress</p>
            </div>
            <button onclick="exportAnalytics()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition flex items-center gap-2">
                <i class="fa-solid fa-download"></i> Export Data
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Courses</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_courses'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Enrolled</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_enrolled'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-users text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Average Grade</p>
                    <p class="text-3xl font-bold {{ ($stats['avg_grade'] ?? 0) <= 3.0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['avg_grade'] ?? 0 }}
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
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['passing_rate'] ?? 0 }}%</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-trophy text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Performance</h3>
            <canvas id="courseGradeChart" height="250"></canvas>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Enrollment by Course</h3>
            <canvas id="enrollmentChart" height="250"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Passing Rates by Course</h3>
        <canvas id="passingRateChart" height="200"></canvas>
    </div>

    <!-- Course List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Course Analytics</h3>
            <p class="text-sm text-gray-500 mt-1">Detailed performance metrics for each course</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Course</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Students</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Avg Grade</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Passing Rate</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($courses ?? [] as $course)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ $course->name }}</p>
                                <p class="text-xs text-gray-500">{{ $course->code }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-900">{{ $course->students()->count() }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $avgGrade = 0;
                                $gradeCount = 0;
                                foreach($course->students as $student) {
                                    if($student->pivot->final_grade) {
                                        $avgGrade += $student->pivot->final_grade;
                                        $gradeCount++;
                                    }
                                }
                                $avgGrade = $gradeCount > 0 ? round($avgGrade / $gradeCount, 2) : 'N/A';
                            @endphp
                            <span class="font-medium {{ is_numeric($avgGrade) && $avgGrade <= 3.0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $avgGrade }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $passed = 0;
                                $total = 0;
                                foreach($course->students as $student) {
                                    if($student->pivot->final_grade) {
                                        $total++;
                                        if($student->pivot->final_grade <= 3.0) $passed++;
                                    }
                                }
                                $passRate = $total > 0 ? round(($passed / $total) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-24">
                                    <div class="bg-green-500 rounded-full h-2" style="width: {{ $passRate }}%"></div>
                                </div>
                                <span class="text-sm text-gray-600">{{ $passRate }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('instructor.analytics.course', $course) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                View Details <i class="fa-solid fa-arrow-right ml-1"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-solid fa-chart-line text-4xl mb-3 block"></i>
                            <p>No courses found. Create your first course to see analytics.</p>
                            <a href="{{ route('instructor.courses.create') }}" class="inline-block mt-3 text-indigo-600 hover:text-indigo-700">
                                Create Course →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(isset($courseNames) && count($courseNames) > 0)
    // Course Grade Chart
    const gradeCtx = document.getElementById('courseGradeChart').getContext('2d');
    new Chart(gradeCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($courseNames) !!},
            datasets: [{
                label: 'Average Grade',
                data: {!! json_encode($courseGrades) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgb(99, 102, 241)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: { beginAtZero: true, max: 5, title: { display: true, text: 'Grade (1.0 - 5.0)' } },
                x: { title: { display: true, text: 'Courses' } }
            }
        }
    });
    
    // Enrollment Chart
    const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(enrollmentCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($courseNames) !!},
            datasets: [{
                label: 'Number of Students',
                data: {!! json_encode($courseEnrollment) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Number of Students' } },
                x: { title: { display: true, text: 'Courses' } }
            }
        }
    });
    
    // Passing Rate Chart
    const passingCtx = document.getElementById('passingRateChart').getContext('2d');
    new Chart(passingCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($courseNames) !!},
            datasets: [{
                label: 'Passing Rate (%)',
                data: {!! json_encode($coursePassingRates) !!},
                borderColor: 'rgb(168, 85, 247)',
                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(168, 85, 247)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: { beginAtZero: true, max: 100, title: { display: true, text: 'Passing Rate (%)' }, ticks: { callback: function(value) { return value + '%'; } } },
                x: { title: { display: true, text: 'Courses' } }
            }
        }
    });
    @endif
    
    function exportAnalytics() {
        window.location.href = '{{ route("instructor.analytics.export") }}';
    }
</script>
@endsection