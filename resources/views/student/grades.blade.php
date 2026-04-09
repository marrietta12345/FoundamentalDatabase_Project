<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Grades • Bright Sphere University</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f5f7fb; }
        .sidebar { background: linear-gradient(180deg, #1a1c2e 0%, #2d2f42 100%); }
        .nav-item { transition: all 0.3s ease; border-left: 3px solid transparent; }
        .nav-item:hover { background: rgba(255, 255, 255, 0.1); border-left-color: #6366f1; }
        .nav-item.active { background: rgba(99, 102, 241, 0.15); border-left-color: #6366f1; }
        .nav-item.active i, .nav-item.active span { color: #6366f1; }
        .gradient-text { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .grade-card { transition: all 0.3s ease; }
        .grade-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="sidebar w-72 flex-shrink-0 hidden md:flex flex-col text-white shadow-2xl">
            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-graduation-cap text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-300 to-purple-300 bg-clip-text text-transparent">Bright Sphere</h1>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-[0.2em] mt-1">FSUU Portal</p>
                    </div>
                </div>
            </div>

            <div class="px-6 mb-8">
                <div class="bg-white/10 rounded-2xl p-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-lg">{{ $user->first_name }} {{ $user->last_name }}</p>
                            <p class="text-xs text-indigo-300">Student</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 px-4 space-y-2">
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-wider px-4 mb-2">Menu</p>
                <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-chart-pie w-6"></i><span>Dashboard</span>
                </a>
                <a href="#" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-chalkboard-user w-6"></i><span>Classes</span>
                </a>
                <a href="{{ route('student.grades') }}" class="nav-item active flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-ranking-star w-6"></i><span>My Grades</span>
                </a>
                <div class="border-t border-white/10 my-4"></div>
                <a href="{{ route('students.index') }}" class="nav-item flex items-center gap-4 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-user-graduate w-6"></i><span>Students</span>
                </a>
            </div>

            <div class="p-6 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item flex items-center gap-4 px-4 py-3 w-full rounded-xl text-red-300">Logout</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <div class="bg-white border-b border-gray-200 sticky top-0 z-40">
                <div class="px-8 py-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold gradient-text">My Grades</h1>
                            <p class="text-gray-500 text-sm mt-1">View your academic performance and grades</p>
                        </div>
                        <div class="text-sm text-gray-500">
                            <i class="fa-regular fa-calendar mr-2"></i>
                            {{ now()->format('l, F j, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <!-- Academic Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white">
                        <p class="text-sm opacity-80">Overall Average</p>
                        <p class="text-3xl font-bold mt-2">{{ number_format($averageGrade, 1) }}%</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
                        <p class="text-sm opacity-80">Academic Standing</p>
                        <p class="text-xl font-bold mt-2">{{ $academicStanding }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
                        <p class="text-sm opacity-80">Subjects Taken</p>
                        <p class="text-3xl font-bold mt-2">{{ $gradesBySubject->count() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white">
                        <p class="text-sm opacity-80">Total Assessments</p>
                        <p class="text-3xl font-bold mt-2">{{ $grades->count() }}</p>
                    </div>
                </div>

                <!-- Grades Table -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-900">Grade Records</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                32
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Assignment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Percentage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Remarks</th>
                                
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($grades as $grade)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium">{{ $grade->subject }}</td>
                                    <td class="px-6 py-4">{{ $grade->assignment_name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                            {{ ucfirst($grade->grade_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $grade->score }} / {{ $grade->max_score }}</td>
                                    <td class="px-6 py-4 font-semibold">{{ number_format($grade->percentage, 1) }}%</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 text-sm font-bold rounded-full 
                                            @if($grade->percentage >= 90) bg-green-100 text-green-800
                                            @elseif($grade->percentage >= 80) bg-blue-100 text-blue-800
                                            @elseif($grade->percentage >= 75) bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $grade->letter_grade }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $grade->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $grade->remarks ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-chart-line text-4xl mb-3 text-gray-300"></i>
                                        <p>No grades have been recorded yet.</p>
                                        <p class="text-sm mt-1">Your grades will appear here once your instructors add them.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Subject Summary Cards -->
                @if($gradesBySubject->count() > 0)
                <div class="mt-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Subject Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($gradesBySubject as $subject => $subjectGrades)
                            @php
                                $subjectAvg = $subjectGrades->avg('percentage');
                                $subjectLetter = $subjectGrades->first()->calculateLetterGrade($subjectAvg);
                            @endphp
                            <div class="bg-white rounded-xl p-4 shadow-sm grade-card">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $subject }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ $subjectGrades->count() }} assessments</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold {{ $subjectAvg >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ number_format($subjectAvg, 1) }}%
                                        </p>
                                        <p class="text-sm font-semibold">{{ $subjectLetter }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $subjectAvg }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>