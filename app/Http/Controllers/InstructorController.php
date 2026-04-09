<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class InstructorController extends Controller
{
    /**
     * Display the instructor dashboard.
     */
    public function index()
    {
        $instructorId = Auth::id();
        
        // Get courses taught by this instructor
        $myCourses = Course::where('instructor_id', $instructorId)
            ->withCount('students')
            ->get();
        
        // Get ALL students (not just enrolled in courses)
        $totalAllStudents = User::where('role', 'student')->count();
        
        // Get total students across all courses (enrolled)
        $totalEnrolledStudents = DB::table('course_student')
            ->whereIn('course_id', $myCourses->pluck('id'))
            ->count();
        
        // Get total instructors (for stats)
        $totalInstructors = User::where('role', 'instructor')->count();
        
        // Get today's classes
        $upcomingClasses = $myCourses->filter(function($course) {
            return $course->schedule && $course->status === 'active';
        })->take(3);
        
        // Get streak days
        $streakDays = 5;
        
        // ========== MESSAGING DATA ==========
        // Get unread messages count for the instructor
        $unreadMessagesCount = Message::where('receiver_id', $instructorId)
                                      ->where('is_read', false)
                                      ->count();
        
        // Get my students (students enrolled in instructor's courses)
        $myStudents = User::where('role', 'student')
            ->whereHas('courses', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->distinct()
            ->get();
        
        // Get conversations for the instructor
        $conversations = [];
        $messages = Message::where('sender_id', $instructorId)
                          ->orWhere('receiver_id', $instructorId)
                          ->orderBy('created_at', 'desc')
                          ->get()
                          ->groupBy(function($message) use ($instructorId) {
                              return $message->sender_id == $instructorId ? $message->receiver_id : $message->sender_id;
                          });
        
        foreach ($messages as $otherUserId => $userMessages) {
            $otherUser = User::find($otherUserId);
            if ($otherUser && $otherUser->role === 'student') {
                $lastMessage = $userMessages->first();
                $conversations[] = [
                    'id' => $otherUserId,
                    'student_name' => $otherUser->first_name . ' ' . $otherUser->last_name,
                    'last_message' => strlen($lastMessage->message) > 50 ? substr($lastMessage->message, 0, 50) . '...' : $lastMessage->message,
                    'last_message_time' => $lastMessage->created_at->diffForHumans(),
                    'unread_count' => Message::where('receiver_id', $instructorId)
                                              ->where('sender_id', $otherUserId)
                                              ->where('is_read', false)
                                              ->count()
                ];
            }
        }
        // ========== END MESSAGING DATA ==========
        
        // Add info message for new instructors with no courses
        if ($myCourses->count() == 0) {
            session()->flash('info', 'Welcome! Get started by creating your first course.');
        }
        
        return view('instructor.dashboard', compact(
            'myCourses', 
            'totalAllStudents',
            'totalEnrolledStudents',
            'totalInstructors', 
            'upcomingClasses',
            'streakDays',
            'unreadMessagesCount',
            'myStudents',
            'conversations'
        ));
    }
    
    /**
     * Get messages for a specific conversation with a student
     */
    public function getMessages($studentId)
    {
        $instructorId = Auth::id();
        
        $messages = Message::where(function($query) use ($instructorId, $studentId) {
            $query->where('sender_id', $instructorId)
                  ->where('receiver_id', $studentId);
        })->orWhere(function($query) use ($instructorId, $studentId) {
            $query->where('sender_id', $studentId)
                  ->where('receiver_id', $instructorId);
        })->orderBy('created_at', 'asc')->get();
        
        // Mark messages as read
        Message::where('receiver_id', $instructorId)
               ->where('sender_id', $studentId)
               ->where('is_read', false)
               ->update(['is_read' => true]);
        
        return response()->json(['messages' => $messages]);
    }
    
    /**
     * Send a message to a student
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);
        
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false
        ]);
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        
        return redirect()->back()->with('success', 'Message sent successfully!');
    }
    
    /**
     * Display analytics dashboard.
     */
    public function analytics()
    {
        $instructorId = Auth::id();
        $courses = Course::where('instructor_id', $instructorId)->get();
        
        // Calculate overall statistics
        $totalEnrolled = 0;
        $totalGrades = 0;
        $gradeCount = 0;
        $passedCount = 0;
        
        foreach ($courses as $course) {
            $enrolledCount = $course->students()->count();
            $totalEnrolled += $enrolledCount;
            
            foreach ($course->students as $student) {
                if ($student->pivot->final_grade) {
                    $totalGrades += $student->pivot->final_grade;
                    $gradeCount++;
                    if ($student->pivot->final_grade <= 3.0) {
                        $passedCount++;
                    }
                }
            }
        }
        
        $stats = [
            'total_courses' => $courses->count(),
            'total_enrolled' => $totalEnrolled,
            'avg_grade' => $gradeCount > 0 ? round($totalGrades / $gradeCount, 2) : 0,
            'passing_rate' => $gradeCount > 0 ? round(($passedCount / $gradeCount) * 100, 1) : 0,
        ];
        
        // Prepare chart data
        $courseNames = [];
        $courseGrades = [];
        $courseEnrollment = [];
        $coursePassingRates = [];
        
        foreach ($courses as $course) {
            $courseNames[] = $course->name;
            
            // Average grade per course
            $courseGradeTotal = 0;
            $courseGradeCount = 0;
            $coursePassed = 0;
            foreach ($course->students as $student) {
                if ($student->pivot->final_grade) {
                    $courseGradeTotal += $student->pivot->final_grade;
                    $courseGradeCount++;
                    if ($student->pivot->final_grade <= 3.0) {
                        $coursePassed++;
                    }
                }
            }
            $courseGrades[] = $courseGradeCount > 0 ? round($courseGradeTotal / $courseGradeCount, 2) : 0;
            $courseEnrollment[] = $course->students()->count();
            $coursePassingRates[] = $courseGradeCount > 0 ? round(($coursePassed / $courseGradeCount) * 100, 1) : 0;
        }
        
        return view('instructor.analytics', compact('courses', 'stats', 'courseNames', 'courseGrades', 'courseEnrollment', 'coursePassingRates'));
    }
    
    /**
     * Show course-specific analytics.
     */
    public function courseAnalytics(Course $course)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }
        
        $students = $course->students()->get();
        
        // Grade distribution
        $gradeDistribution = [
            'excellent' => 0,   // 1.0 - 1.25
            'very_good' => 0,   // 1.5 - 1.75
            'good' => 0,        // 2.0 - 2.25
            'satisfactory' => 0,// 2.5 - 2.75
            'passing' => 0,     // 3.0
            'failing' => 0,     // 3.25 - 5.0
        ];
        
        $grades = [];
        $studentGrades = [];
        
        foreach ($students as $student) {
            if ($student->pivot->final_grade) {
                $grade = $student->pivot->final_grade;
                $grades[] = $grade;
                $studentGrades[] = [
                    'student' => $student,
                    'grade' => $grade
                ];
                
                if ($grade <= 1.25) $gradeDistribution['excellent']++;
                elseif ($grade <= 1.75) $gradeDistribution['very_good']++;
                elseif ($grade <= 2.25) $gradeDistribution['good']++;
                elseif ($grade <= 2.75) $gradeDistribution['satisfactory']++;
                elseif ($grade <= 3.0) $gradeDistribution['passing']++;
                else $gradeDistribution['failing']++;
            }
        }
        
        // Sort students by grade
        usort($studentGrades, function($a, $b) {
            return $a['grade'] <=> $b['grade'];
        });
        
        // Calculate statistics
        $avgGrade = count($grades) > 0 ? round(array_sum($grades) / count($grades), 2) : 0;
        $passingRate = count($grades) > 0 ? round(($gradeDistribution['excellent'] + $gradeDistribution['very_good'] + $gradeDistribution['good'] + $gradeDistribution['satisfactory'] + $gradeDistribution['passing']) / count($grades) * 100, 1) : 0;
        $highestGrade = count($grades) > 0 ? min($grades) : 0;
        $lowestGrade = count($grades) > 0 ? max($grades) : 0;
        
        // Monthly attendance data
        $attendanceData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthName = $month->format('M Y');
            $attendanceCount = Attendance::where('course_id', $course->id)
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->count();
            $presentCount = Attendance::where('course_id', $course->id)
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->where('status', 'present')
                ->count();
            $attendanceData[$monthName] = $attendanceCount > 0 ? round(($presentCount / $attendanceCount) * 100, 1) : 0;
        }
        
        return view('instructor.course-analytics', compact('course', 'students', 'grades', 'gradeDistribution', 'avgGrade', 'passingRate', 'highestGrade', 'lowestGrade', 'studentGrades', 'attendanceData'));
    }
    
    /**
     * Export analytics data as PDF.
     */
    public function exportAnalytics()
    {
        $courses = Course::where('instructor_id', Auth::id())->get();
        
        // Calculate overall statistics
        $totalEnrolled = 0;
        $totalGrades = 0;
        $gradeCount = 0;
        $passedCount = 0;
        
        foreach ($courses as $course) {
            $totalEnrolled += $course->students()->count();
            
            foreach ($course->students as $student) {
                if ($student->pivot->final_grade) {
                    $totalGrades += $student->pivot->final_grade;
                    $gradeCount++;
                    if ($student->pivot->final_grade <= 3.0) {
                        $passedCount++;
                    }
                }
            }
        }
        
        $stats = [
            'total_courses' => $courses->count(),
            'total_enrolled' => $totalEnrolled,
            'avg_grade' => $gradeCount > 0 ? round($totalGrades / $gradeCount, 2) : 0,
            'passing_rate' => $gradeCount > 0 ? round(($passedCount / $gradeCount) * 100, 1) : 0,
            'export_date' => now()->format('F d, Y H:i:s'),
            'instructor' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'instructor_email' => Auth::user()->email,
        ];
        
        // Prepare course data
        $courseData = [];
        foreach ($courses as $course) {
            $courseGradeTotal = 0;
            $courseGradeCount = 0;
            $coursePassed = 0;
            $studentList = [];
            
            foreach ($course->students as $student) {
                if ($student->pivot->final_grade) {
                    $courseGradeTotal += $student->pivot->final_grade;
                    $courseGradeCount++;
                    if ($student->pivot->final_grade <= 3.0) {
                        $coursePassed++;
                    }
                }
                
                $studentList[] = [
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'student_id' => $student->student_id ?? 'N/A',
                    'email' => $student->email,
                    'prelim' => $student->pivot->prelim ?? 'N/A',
                    'midterm' => $student->pivot->midterm ?? 'N/A',
                    'prefinal' => $student->pivot->prefinal ?? 'N/A',
                    'final_exam' => $student->pivot->final_exam ?? 'N/A',
                    'final_grade' => $student->pivot->final_grade ?? 'N/A',
                ];
            }
            
            $courseData[] = [
                'course' => $course,
                'total_students' => $course->students()->count(),
                'average_grade' => $courseGradeCount > 0 ? round($courseGradeTotal / $courseGradeCount, 2) : 'N/A',
                'passing_rate' => $courseGradeCount > 0 ? round(($coursePassed / $courseGradeCount) * 100, 1) : 0,
                'students' => $studentList
            ];
        }
        
        $data = [
            'stats' => $stats,
            'courses' => $courseData,
            'generated_at' => now()->format('F d, Y H:i:s'),
        ];
        
        $filename = 'analytics_report_' . date('Y-m-d_His') . '.pdf';
        
        $pdf = Pdf::loadView('exports.analytics-pdf', $data);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download($filename);
    }
    
    /**
     * Display a specific grade.
     */
    public function showGrade($id)
    {
        $grade = Grade::whereHas('course', function($q) {
                $q->where('instructor_id', Auth::id());
            })
            ->with(['student', 'course'])
            ->findOrFail($id);
        
        return view('instructor.grades.show', compact('grade'));
    }
    
    /**
     * Display instructor's courses.
     */
    public function courses()
    {
        $instructorId = Auth::id();
        
        $courses = Course::where('instructor_id', $instructorId)
            ->withCount('students')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $stats = [
            'total_courses' => $courses->total(),
            'active_courses' => Course::where('instructor_id', $instructorId)->where('status', 'active')->count(),
            'total_students' => Course::where('instructor_id', $instructorId)->withCount('students')->get()->sum('students_count'),
        ];
        
        return view('instructor.courses.index', compact('courses', 'stats'));
    }
    
    /**
     * Show form to create a new course.
     */
    public function createCourse()
    {
        return view('instructor.courses.create');
    }
    
    /**
     * Store a new course with student enrollments.
     */
    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'class_code' => 'required|string|max:50',
            'class_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'schedule' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);
        
        $course = Course::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'class_code' => $validated['class_code'],
            'class_name' => $validated['class_name'],
            'description' => $validated['description'] ?? null,
            'schedule' => $validated['schedule'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'instructor_id' => Auth::id(),
        ]);
        
        $enrolledCount = 0;
        if (!empty($validated['student_ids'])) {
            $enrollmentData = [];
            foreach ($validated['student_ids'] as $studentId) {
                $enrollmentData[$studentId] = [
                    'enrolled_at' => now(),
                    'status' => 'active',
                ];
            }
            $course->students()->attach($enrollmentData);
            $enrolledCount = count($validated['student_ids']);
        }
        
        $message = "Course '{$course->name}' created successfully!";
        if ($enrolledCount > 0) {
            $message .= " {$enrolledCount} student(s) enrolled.";
        }
        
        return redirect()->route('instructor.courses.index')
            ->with('success', $message);
    }
    
    /**
     * Display a specific course.
     */
    public function showCourse($id)
    {
        $course = Course::where('instructor_id', Auth::id())
            ->with(['students' => function($query) {
                $query->orderBy('last_name')->orderBy('first_name');
            }])
            ->findOrFail($id);
        
        $enrolledStudents = $course->students;
        $totalStudents = $enrolledStudents->count();
        $studentsWithGrades = $enrolledStudents->filter(function($student) {
            return !is_null($student->pivot->final_grade);
        })->count();
        $averageGrade = $studentsWithGrades > 0 ? $enrolledStudents->avg(function($student) {
            return $student->pivot->final_grade;
        }) : 0;
        $passingRate = $studentsWithGrades > 0 ? ($enrolledStudents->filter(function($student) {
            return $student->pivot->final_grade <= 3.0;
        })->count() / $studentsWithGrades) * 100 : 0;
        
        return view('instructor.courses.show', compact('course', 'enrolledStudents', 'totalStudents', 'studentsWithGrades', 'averageGrade', 'passingRate'));
    }
    
    /**
     * Show form to edit a course.
     */
    public function editCourse($id)
    {
        $course = Course::where('instructor_id', Auth::id())->findOrFail($id);
        return view('instructor.courses.edit', compact('course'));
    }
    
    /**
     * Update a course.
     */
    public function updateCourse(Request $request, $id)
    {
        $course = Course::where('instructor_id', Auth::id())->findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'class_code' => 'required|string|max:50',
            'class_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'schedule' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive',
        ]);
        
        $course->update($validated);
        
        return redirect()->route('instructor.courses.index')
            ->with('success', 'Course updated successfully!');
    }
    
    /**
     * Delete a course.
     */
    public function destroyCourse($id)
    {
        $course = Course::where('instructor_id', Auth::id())->findOrFail($id);
        
        if ($course->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete course with enrolled students. Remove students first.');
        }
        
        $course->delete();
        
        return redirect()->route('instructor.courses.index')
            ->with('success', 'Course deleted successfully!');
    }
    
    /**
     * Show form to add students to course.
     */
    public function addStudentsToCourse($courseId)
    {
        $course = Course::where('instructor_id', Auth::id())->findOrFail($courseId);
        $enrolledStudentIds = $course->students()->pluck('users.id')->toArray();
        
        $students = User::where('role', 'student')
            ->whereNotIn('id', $enrolledStudentIds)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        
        return view('instructor.courses.add-students', compact('course', 'students'));
    }
    
    /**
     * Remove student from course.
     */
    public function removeStudentFromCourse($courseId, $studentId)
    {
        try {
            $course = Course::where('instructor_id', Auth::id())->findOrFail($courseId);
            $course->students()->detach($studentId);
            
            Grade::where('student_id', $studentId)
                ->where('course_id', $courseId)
                ->delete();
            
            return redirect()->route('instructor.courses.show', $courseId)
                ->with('success', 'Student removed from course successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to remove student: ' . $e->getMessage());
        }
    }
    
    /**
     * Enroll students to course (from add students page).
     */
    public function enrollStudentsToCourse(Request $request, $courseId)
    {
        $course = Course::where('instructor_id', Auth::id())->findOrFail($courseId);
        
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
        ]);
        
        $enrolledCount = 0;
        foreach ($validated['student_ids'] as $studentId) {
            if (!$course->students()->where('user_id', $studentId)->exists()) {
                $course->students()->attach($studentId, [
                    'enrolled_at' => now(),
                    'status' => 'active',
                ]);
                $enrolledCount++;
            }
        }
        
        return redirect()->route('instructor.courses.show', $courseId)
            ->with('success', "$enrolledCount student(s) enrolled successfully!");
    }
    
    /**
     * Display all students.
     */
    public function students(Request $request)
    {
        $instructorId = Auth::id();
        $courses = Course::where('instructor_id', $instructorId)->get();
        
        $studentsQuery = User::where('role', 'student')
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
                });
            });
        
        $students = $studentsQuery->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        
        $studentData = [];
        $avatarColors = ['indigo', 'purple', 'pink', 'blue', 'green', 'yellow', 'red', 'orange'];
        
        foreach ($students as $student) {
            $randomColor = $avatarColors[array_rand($avatarColors)];
            
            $studentData[] = [
                'id' => $student->id,
                'student_id' => $student->student_id ?? 'N/A',
                'fullname' => trim($student->first_name . ' ' . ($student->middle_name ?? '') . ' ' . $student->last_name),
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'phone' => $student->phone,
                'program' => $student->program ?? 'Not Assigned',
                'year_level' => $student->year_level ?? 'Not Assigned',
                'status' => ucfirst($student->status ?? 'active'),
                'status_color' => ($student->status ?? 'active') == 'active' ? 'green' : (($student->status ?? 'active') == 'inactive' ? 'red' : 'yellow'),
                'initial' => strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)),
                'avatar_color' => $randomColor,
            ];
        }
        
        $totalStudents = $students->count();
        $activeStudents = User::where('role', 'student')->where('status', 'active')->count();
        $inactiveStudents = User::where('role', 'student')->where('status', 'inactive')->count();
        $totalCourses = $courses->count();
        
        $programs = [
            'Business Management' => 'Business Management',
            'BLISS' => 'BLISS (Bachelor of Library and Information Science)',
            'Information Technology' => 'Information Technology',
            'Computer Science' => 'Computer Science',
            'Accountancy' => 'Accountancy',
            'Financial Management' => 'Financial Management',
            'Nursing' => 'Nursing',
            'Criminology' => 'Criminology',
            'Lawyer' => 'Lawyer',
            'Physical Education' => 'Physical Education',
        ];
        
        $yearLevels = [
            '1st Year' => '1st Year',
            '2nd Year' => '2nd Year',
            '3rd Year' => '3rd Year',
            '4th Year' => '4th Year',
        ];
        
        return view('instructor.students.index', compact(
            'studentData', 'students', 'courses', 'activeStudents', 'inactiveStudents', 
            'totalStudents', 'totalCourses', 'programs', 'yearLevels'
        ));
    }
    
    /**
     * Show form to create a new student.
     */
    public function createStudent()
    {
        $programs = [
            'Business Management' => 'Business Management',
            'BLISS' => 'BLISS (Bachelor of Library and Information Science)',
            'Information Technology' => 'Information Technology',
            'Computer Science' => 'Computer Science',
            'Accountancy' => 'Accountancy',
            'Financial Management' => 'Financial Management',
            'Nursing' => 'Nursing',
            'Criminology' => 'Criminology',
            'Lawyer' => 'Lawyer',
            'Physical Education' => 'Physical Education',
        ];
        
        $yearLevels = [
            '1st Year' => '1st Year',
            '2nd Year' => '2nd Year',
            '3rd Year' => '3rd Year',
            '4th Year' => '4th Year',
        ];
        
        return view('instructor.students.create', compact('programs', 'yearLevels'));
    }
    
    /**
     * Store a new student.
     */
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'student_id' => 'nullable|string|max:50|unique:users,student_id',
            'program' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'status' => 'nullable|string|in:active,inactive,pending',
        ]);
        
        if (!empty($validated['student_id'])) {
            $studentId = trim($validated['student_id']);
        } else {
            $lastStudent = User::where('role', 'student')
                ->whereNotNull('student_id')
                ->orderBy('student_id', 'desc')
                ->first();
            
            if ($lastStudent && $lastStudent->student_id) {
                preg_match('/\d+$/', $lastStudent->student_id, $matches);
                if (!empty($matches)) {
                    $lastNumber = intval($matches[0]);
                    $nextNumber = $lastNumber + 1;
                    $studentId = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                } else {
                    $nextNumber = intval($lastStudent->student_id) + 1;
                    $studentId = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                }
            } else {
                $studentId = '001';
            }
        }
        
        $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']);
        $fullName = preg_replace('/\s+/', ' ', $fullName);
        $defaultPassword = $studentId . '123';
        
        $studentData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'student_id' => $studentId,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'name' => $fullName,
            'role' => 'student',
            'password' => Hash::make($defaultPassword),
            'status' => $validated['status'] ?? 'active',
            'program' => $validated['program'],
            'year_level' => $validated['year_level'],
        ];
        
        try {
            $student = User::create($studentData);
            Log::info('Student created successfully', [
                'student_id' => $studentId,
                'program' => $validated['program'],
                'year_level' => $validated['year_level']
            ]);
            return redirect()->route('instructor.students.index')
                ->with('success', "Student created! ID: {$studentId}, Password: {$defaultPassword}");
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create student: ' . $e->getMessage());
        }
    }
    
    /**
     * Get student data for editing (AJAX)
     */
    public function getStudentForEdit($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        return response()->json($student);
    }
    
    /**
     * Show form to enroll existing student in courses.
     */
    public function createStudentEnrollment()
    {
        $students = User::where('role', 'student')->orderBy('last_name')->orderBy('first_name')->get();
        $courses = Course::where('instructor_id', Auth::id())->get();
        return view('instructor.students.enroll', compact('students', 'courses'));
    }
    
    /**
     * Enroll student in courses.
     */
    public function enrollStudent(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
        ]);
        
        $student = User::where('role', 'student')->findOrFail($validated['student_id']);
        $enrolledCount = 0;
        $alreadyEnrolled = [];
        
        foreach ($validated['course_ids'] as $courseId) {
            if (!$student->courses()->where('course_id', $courseId)->exists()) {
                $student->courses()->attach($courseId, ['enrolled_at' => now(), 'status' => 'active']);
                $enrolledCount++;
            } else {
                $alreadyEnrolled[] = $courseId;
            }
        }
        
        $message = "Enrolled in {$enrolledCount} course(s).";
        if (count($alreadyEnrolled) > 0) {
            $message .= " " . count($alreadyEnrolled) . " already enrolled.";
        }
        
        return redirect()->route('instructor.students.index')->with('success', $message);
    }
    
    /**
     * Display a specific student.
     */
    public function showStudent($id)
    {
        $student = User::where('role', 'student')->with('courses')->findOrFail($id);
        return view('instructor.students.show', compact('student'));
    }
    
    /**
     * Show form to edit a student.
     */
    public function editStudent($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        
        $programs = [
            'Business Management' => 'Business Management',
            'BLISS' => 'BLISS',
            'Information Technology' => 'Information Technology',
            'Computer Science' => 'Computer Science',
            'Accountancy' => 'Accountancy',
            'Financial Management' => 'Financial Management',
            'Nursing' => 'Nursing',
            'Criminology' => 'Criminology',
            'Lawyer' => 'Lawyer',
            'Physical Education' => 'Physical Education',
        ];
        
        $yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        $enrolledCourseIds = $student->courses()->pluck('courses.id')->toArray();
        $availableCourses = Course::where('instructor_id', Auth::id())
            ->whereNotIn('id', $enrolledCourseIds)->get();
        
        return view('instructor.students.edit', compact('student', 'availableCourses', 'programs', 'yearLevels'));
    }
    
    /**
     * Update a student.
     */
    public function updateStudent(Request $request, $id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_id' => 'required|string|max:50|unique:users,student_id,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'program' => 'required|string|max:255',
            'year_level' => 'required|string|max:50',
            'status' => 'nullable|string|in:active,inactive,graduated,pending',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']);
        $fullName = preg_replace('/\s+/', ' ', $fullName);
        
        $studentData = [
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'student_id' => $validated['student_id'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'name' => $fullName,
            'program' => $validated['program'],
            'year_level' => $validated['year_level'],
            'status' => $validated['status'] ?? 'active',
        ];
        
        if (!empty($validated['password'])) {
            $studentData['password'] = Hash::make($validated['password']);
        }
        
        try {
            $student->update($studentData);
            return redirect()->route('instructor.students.index')
                ->with('success', 'Student updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete a student.
     */
    public function destroyStudent($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        
        if ($student->courses()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete student enrolled in courses.');
        }
        
        $student->delete();
        return redirect()->route('instructor.students.index')->with('success', 'Student deleted!');
    }
    
    /**
     * Enroll student in a course (from edit page).
     */
    public function enrollStudentInCourse(Request $request, $studentId)
    {
        $student = User::where('role', 'student')->findOrFail($studentId);
        $validated = $request->validate(['course_id' => 'required|exists:courses,id']);
        
        if ($student->courses()->where('course_id', $validated['course_id'])->exists()) {
            return redirect()->back()->with('error', 'Already enrolled.');
        }
        
        $student->courses()->attach($validated['course_id'], ['enrolled_at' => now(), 'status' => 'active']);
        return redirect()->route('instructor.students.edit', $student)->with('success', 'Enrolled!');
    }
    
    /**
     * Remove student from a course (from edit page).
     */
    public function removeStudentFromCourseEdit($studentId, $courseId)
    {
        $student = User::where('role', 'student')->findOrFail($studentId);
        $course = Course::where('instructor_id', Auth::id())->findOrFail($courseId);
        $student->courses()->detach($courseId);
        Grade::where('student_id', $studentId)->where('course_id', $courseId)->delete();
        return redirect()->route('instructor.students.edit', $student)->with('success', 'Removed!');
    }
    
    /**
     * Display grades management page.
     */
    public function grades(Request $request)
    {
        $instructorId = Auth::id();
        $courses = Course::where('instructor_id', $instructorId)->get();
        $students = User::where('role', 'student')->orderBy('last_name')->get();
        
        $grades = Grade::whereHas('course', function($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->with(['student', 'course'])
            ->when($request->course_id, function($q, $c) {
                return $q->where('course_id', $c);
            })
            ->when($request->student_id, function($q, $s) {
                return $q->where('student_id', $s);
            })
            ->when($request->status, function($q, $s) {
                if ($s == 'passed') return $q->where('status', 'passed');
                if ($s == 'failed') return $q->where('status', 'failed');
                if ($s == 'pending') return $q->whereNull('status');
                return $q;
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        $totalGrades = $grades->count();
        $passed = $grades->where('status', 'passed')->count();
        $failed = $grades->where('status', 'failed')->count();
        $pending = $grades->whereNull('status')->count();
        
        return view('instructor.grades.index', compact('grades', 'courses', 'students', 'totalGrades', 'passed', 'failed', 'pending'));
    }
    
    /**
     * Create grade form.
     */
    public function createGrade()
    {
        $courses = Course::where('instructor_id', Auth::id())->get();
        $students = User::where('role', 'student')->orderBy('last_name')->get();
        return view('instructor.grades.create', compact('courses', 'students'));
    }
    
    /**
     * Store a new grade.
     */
    public function storeGrade(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'subject' => 'required|string|max:255',
            'class_name' => 'nullable|string|max:255',
            'prelim' => 'nullable|numeric|min:1.0|max:5.0',
            'midterm' => 'nullable|numeric|min:1.0|max:5.0',
            'prefinal' => 'nullable|numeric|min:1.0|max:5.0',
            'final' => 'nullable|numeric|min:1.0|max:5.0',
        ]);
        
        $existing = Grade::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->first();
        
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Grade already exists for this student and course.');
        }
        
        $grades = array_filter([$validated['prelim'], $validated['midterm'], $validated['prefinal'], $validated['final']]);
        $finalGrade = count($grades) > 0 ? round(array_sum($grades) / count($grades), 2) : null;
        $status = null;
        if ($finalGrade !== null) {
            $status = $finalGrade <= 3.0 ? 'passed' : 'failed';
        }
        
        try {
            $grade = Grade::create([
                'student_id' => $validated['student_id'],
                'course_id' => $validated['course_id'],
                'instructor_id' => Auth::id(),
                'subject' => $validated['subject'],
                'class_name' => $validated['class_name'] ?? null,
                'prelim' => $validated['prelim'],
                'midterm' => $validated['midterm'],
                'prefinal' => $validated['prefinal'],
                'final_exam' => $validated['final'],
                'final_grade' => $finalGrade,
                'status' => $status,
                'academic_year' => now()->year . '-' . (now()->year + 1),
                'semester' => '1st',
                'graded_by' => Auth::id(),
            ]);
            
            $course = Course::find($validated['course_id']);
            if ($course) {
                $course->students()->updateExistingPivot($validated['student_id'], [
                    'prelim' => $validated['prelim'],
                    'midterm' => $validated['midterm'],
                    'prefinal' => $validated['prefinal'],
                    'final_exam' => $validated['final'],
                    'final_grade' => $finalGrade,
                ]);
            }
            
            return redirect()->route('instructor.grades.index')
                ->with('success', 'Grade added successfully!');
                
        } catch (\Exception $e) {
            Log::error('Grade creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to add grade: ' . $e->getMessage());
        }
    }
    
    /**
     * Edit grade form.
     */
    public function editGrade($id)
    {
        $grade = Grade::whereHas('course', function($q) {
                $q->where('instructor_id', Auth::id());
            })
            ->with(['student', 'course'])
            ->findOrFail($id);
        
        $courses = Course::where('instructor_id', Auth::id())->get();
        $students = User::where('role', 'student')->orderBy('last_name')->get();
        
        return view('instructor.grades.edit', compact('grade', 'courses', 'students'));
    }
    
    /**
     * Update a grade.
     */
    public function updateGrade(Request $request, $id)
    {
        $grade = Grade::whereHas('course', function($q) {
                $q->where('instructor_id', Auth::id());
            })
            ->findOrFail($id);
        
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'subject' => 'required|string|max:255',
            'class_name' => 'nullable|string|max:255',
            'prelim' => 'nullable|numeric|min:1.0|max:5.0',
            'midterm' => 'nullable|numeric|min:1.0|max:5.0',
            'prefinal' => 'nullable|numeric|min:1.0|max:5.0',
            'final' => 'nullable|numeric|min:1.0|max:5.0',
        ]);
        
        $grades = array_filter([$validated['prelim'], $validated['midterm'], $validated['prefinal'], $validated['final']]);
        $finalGrade = count($grades) > 0 ? round(array_sum($grades) / count($grades), 2) : null;
        $status = null;
        if ($finalGrade !== null) {
            $status = $finalGrade <= 3.0 ? 'passed' : 'failed';
        }
        
        try {
            $grade->update([
                'student_id' => $validated['student_id'],
                'course_id' => $validated['course_id'],
                'subject' => $validated['subject'],
                'class_name' => $validated['class_name'] ?? null,
                'prelim' => $validated['prelim'],
                'midterm' => $validated['midterm'],
                'prefinal' => $validated['prefinal'],
                'final_exam' => $validated['final'],
                'final_grade' => $finalGrade,
                'status' => $status,
                'graded_by' => Auth::id(),
            ]);
            
            $course = Course::find($validated['course_id']);
            if ($course) {
                $course->students()->updateExistingPivot($validated['student_id'], [
                    'prelim' => $validated['prelim'],
                    'midterm' => $validated['midterm'],
                    'prefinal' => $validated['prefinal'],
                    'final_exam' => $validated['final'],
                    'final_grade' => $finalGrade,
                ]);
            }
            
            return redirect()->route('instructor.grades.index')
                ->with('success', 'Grade updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Grade update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update grade: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete a grade.
     */
    public function deleteGrade($id)
    {
        $grade = Grade::whereHas('course', function($q) {
                $q->where('instructor_id', Auth::id());
            })
            ->findOrFail($id);
        
        try {
            $course = Course::find($grade->course_id);
            if ($course) {
                $course->students()->updateExistingPivot($grade->student_id, [
                    'prelim' => null,
                    'midterm' => null,
                    'prefinal' => null,
                    'final_exam' => null,
                    'final_grade' => null,
                ]);
            }
            
            $grade->delete();
            
            return redirect()->route('instructor.grades.index')
                ->with('success', 'Grade deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete grade: ' . $e->getMessage());
        }
    }
    
    /**
     * Display attendance management page.
     */
    public function attendance(Request $request)
    {
        $instructorId = Auth::id();
        $courses = Course::where('instructor_id', $instructorId)->get();
        $courseIds = $courses->pluck('id');
        
        $selectedCourseId = $request->course;
        
        $students = User::where('role', 'student')
            ->whereHas('courses', function($q) use ($courseIds) {
                $q->whereIn('courses.id', $courseIds);
            })
            ->when($request->course, function($q, $c) {
                return $q->whereHas('courses', function($sq) use ($c) {
                    $sq->where('courses.id', $c);
                });
            })
            ->with(['courses' => function($q) use ($selectedCourseId) {
                if ($selectedCourseId) {
                    $q->where('courses.id', $selectedCourseId);
                }
            }])
            ->orderBy('last_name')
            ->get();
        
        $date = $request->date ?? now()->format('Y-m-d');
        $attendances = Attendance::whereDate('date', $date)
            ->whereIn('course_id', $courseIds)
            ->when($request->course, function($q, $c) {
                return $q->where('course_id', $c);
            })
            ->get()
            ->keyBy('student_id');
        
        return view('instructor.attendance.index', compact('courses', 'students', 'attendances', 'date', 'selectedCourseId'));
    }
    
    /**
     * Store attendance records.
     */
    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,absent,late,excused',
            'time_in' => 'nullable|array',
            'time_in.*' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string|max:500',
        ]);
        
        $course = Course::where('id', $validated['course_id'])
            ->where('instructor_id', Auth::id())
            ->firstOrFail();
        
        foreach ($validated['attendance'] as $studentId => $status) {
            $attendanceData = [
                'status' => $status,
                'recorded_by' => Auth::id(),
            ];
            
            if (isset($validated['time_in'][$studentId]) && 
                ($status == 'present' || $status == 'late') && 
                !empty($validated['time_in'][$studentId])) {
                $attendanceData['time_in'] = $validated['time_in'][$studentId];
            }
            
            if (isset($validated['remarks'][$studentId]) && !empty($validated['remarks'][$studentId])) {
                $attendanceData['remarks'] = $validated['remarks'][$studentId];
            }
            
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'course_id' => $validated['course_id'],
                    'date' => $validated['date']
                ],
                $attendanceData
            );
        }
        
        $courseName = $course->name;
        
        return redirect()->route('instructor.attendance.index', [
            'course' => $validated['course_id'], 
            'date' => $validated['date']
        ])->with('success', "Attendance recorded successfully for {$courseName} on " . date('F j, Y', strtotime($validated['date'])));
    }
    
    /**
     * Display course schedule page.
     */
    public function schedule()
    {
        $courses = Course::where('instructor_id', Auth::id())
            ->withCount('students')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $stats = [
            'total_courses' => $courses->total(),
            'active_courses' => $courses->where('status', 'active')->count(),
            'total_students' => $courses->sum('students_count'),
            'total_classes' => $courses->total(),
        ];
        
        return view('instructor.schedule.index', compact('courses', 'stats'));
    }
    
    /**
     * Get enrolled courses for a student (AJAX).
     */
    public function getStudentCourses($studentId)
    {
        $student = User::findOrFail($studentId);
        $courses = $student->courses()->where('instructor_id', Auth::id())->with('instructor')->get();
        return response()->json(['courses' => $courses]);
    }
    
    /**
     * Bulk enroll students via CSV/Excel.
     */
    public function bulkEnroll(Request $request, $courseId)
    {
        $course = Course::where('instructor_id', Auth::id())->findOrFail($courseId);
        $request->validate(['student_ids' => 'required|string']);
        
        $studentIds = explode(',', $request->student_ids);
        $success = 0;
        $failed = [];
        
        foreach ($studentIds as $id) {
            $id = trim($id);
            $student = User::where('role', 'student')->where(function($q) use ($id) {
                $q->where('id', $id)->orWhere('student_id', $id);
            })->first();
            
            if ($student && !$course->students()->where('user_id', $student->id)->exists()) {
                $course->students()->attach($student->id, ['enrolled_at' => now(), 'status' => 'active']);
                $success++;
            } else {
                $failed[] = $id;
            }
        }
        
        $msg = "$success student(s) enrolled!";
        if (!empty($failed)) $msg .= " Failed: " . implode(', ', $failed);
        
        return redirect()->route('instructor.courses.show', $courseId)->with('success', $msg);
    }
    
    /**
     * Display class schedule (alias for schedule).
     */
    public function showClass()
    {
        return $this->schedule();
    }
    
    /**
     * Add students to class (alias for addStudentsToCourse).
     */
    public function addStudentsToClass($courseId)
    {
        return $this->addStudentsToCourse($courseId);
    }
    
    /**
     * Enroll students to class (alias for enrollStudentsToCourse).
     */
    public function enrollStudentsToClass(Request $request, $courseId)
    {
        return $this->enrollStudentsToCourse($request, $courseId);
    }
    
    /**
     * Remove student from class (alias for removeStudentFromCourse).
     */
    public function removeStudentFromClass($courseId, $studentId)
    {
        return $this->removeStudentFromCourse($courseId, $studentId);
    }
    
    /**
     * Update class grade (alias for updateGrade in course context).
     */
    public function updateClassGrade(Request $request, $courseId, $studentId)
    {
        // This method can be implemented for updating grades from class view
        // Similar to the updateGrade method but with different parameters
        return redirect()->back()->with('info', 'Update grade functionality available in Grades section.');
    }
}