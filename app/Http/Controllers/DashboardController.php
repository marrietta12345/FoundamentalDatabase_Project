<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\Message;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Redirect instructors to instructor dashboard
        if ($user->role === 'instructor') {
            return redirect()->route('instructor.dashboard');
        }

        // Redirect teachers to instructor dashboard (for backward compatibility)
        if ($user->role === 'teacher') {
            return redirect()->route('instructor.dashboard');
        }

        // Get real data from database
        $courses = [];
        $upcomingAssignments = [];
        $todaysClasses = [];
        $recentGrades = [];

        // Get REAL statistics from database
        $totalStudents = Student::where('status', 'Active')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalCourses = 0; // Will be updated when courses are added
        $totalSubjects = 0; // Will be updated when subjects are added
        $completedSubjects = 0;
        $streakDays = $this->calculateStreakDays($user);
        $weeklyHours = 0;
        $studyHours = 0;
        $achievementsCount = 0;

        // Calculate active students count (same as totalStudents since we only count active)
        $activeStudents = $totalStudents;
        
        // Calculate inactive students
        $inactiveStudents = Student::where('status', 'Inactive')->count();

        // Calculate overall grade (all zeros for now)
        $overallGrade = $this->calculateOverallGrade($courses);
        
        // Get course progress (all zeros)
        $courseProgress = $this->getCourseProgress($courses);

        // ========== MESSAGING DATA ==========
        // Get unread messages count for the student
        $unreadMessagesCount = Message::where('receiver_id', $user->id)
                                      ->where('is_read', false)
                                      ->count();
        
        // Get instructors list (users with instructor/teacher/faculty role)
        $instructors = User::whereIn('role', ['instructor', 'teacher', 'faculty'])
                           ->get();
        
        // Get conversations for the student
        $conversations = [];
        $messages = Message::where('sender_id', $user->id)
                          ->orWhere('receiver_id', $user->id)
                          ->orderBy('created_at', 'desc')
                          ->get()
                          ->groupBy(function($message) use ($user) {
                              return $message->sender_id == $user->id ? $message->receiver_id : $message->sender_id;
                          });
        
        foreach ($messages as $otherUserId => $userMessages) {
            $otherUser = User::find($otherUserId);
            if ($otherUser && in_array($otherUser->role, ['instructor', 'teacher', 'faculty'])) {
                $lastMessage = $userMessages->first();
                $conversations[] = [
                    'id' => $otherUserId,
                    'instructor_name' => $otherUser->first_name . ' ' . $otherUser->last_name,
                    'last_message' => strlen($lastMessage->message) > 50 ? substr($lastMessage->message, 0, 50) . '...' : $lastMessage->message,
                    'last_message_time' => $lastMessage->created_at->diffForHumans(),
                    'unread_count' => Message::where('receiver_id', $user->id)
                                              ->where('sender_id', $otherUserId)
                                              ->where('is_read', false)
                                              ->count()
                ];
            }
        }
        // ========== END MESSAGING DATA ==========

        // University Information Data
        $universityInfo = [
            'name' => 'Bright Sphere University',
            'short_name' => 'BSU',
            'location' => 'Butuan City, Philippines',
            'about' => 'Welcome to BrightSphere University, a values-driven academic institution committed to shaping competent, compassionate, and globally competitive individuals. Guided by the principles of integrity, unity, service, and faith, we provide quality and holistic education that empowers students with the knowledge, skills, and ethical responsibility needed for success.',
            'about_extra' => 'At BrightSphere University, we take pride in our dedication to excellence in instruction, research, and community engagement. We cultivate innovative thinkers and future leaders who are ready to contribute meaningfully to society and national development. With a dynamic and inclusive learning environment, we continue to inspire and prepare our students to face the challenges of an ever-evolving world.',
            'mission' => 'To deliver comprehensive and values-centered education that shapes skilled, ethical, and globally competent professionals. BrightSphere University is committed to nurturing individuals who contribute positively to their respective fields and communities, guided by principles of integrity, service, and excellence both locally and internationally.',
            'vision' => 'BrightSphere University aims to be a distinguished center of academic excellence, fostering innovation and adaptability in an ever-changing global landscape. It envisions producing empowered individuals who uphold strong values, contribute meaningfully to society, and lead with integrity, guided by faith and a commitment to national and global development.',
            'campuses' => [
                ['name' => 'Archbishop Morelos Campus', 'location' => 'Libertad, Butuan City'],
                ['name' => 'Main Campus', 'location' => 'Heart of Butuan City'],
                ['name' => 'Juan de Dios Pueblos Senior High School', 'location' => 'Main Campus, Butuan City'],
            ],
            'core_values' => [
                ['name' => 'Unity', 'icon' => 'people-arrows', 'description' => 'Bayanihan spirit', 'color' => 'blue'],
                ['name' => 'Religiosity', 'icon' => 'church', 'description' => 'Faith in God', 'color' => 'purple'],
                ['name' => 'Integrity', 'icon' => 'scale-balanced', 'description' => 'Honesty & character', 'color' => 'green'],
                ['name' => 'Altruism', 'icon' => 'heart', 'description' => 'Service to others', 'color' => 'red'],
                ['name' => 'Nationalism', 'icon' => 'flag', 'description' => 'Love of country', 'color' => 'yellow'],
            ]
        ];

        // News & Announcements Data
        $newsAnnouncements = [
            [
                'date' => 'MAY 20, 2026',
                'title' => 'Enrollment for Second Semester Now Open',
                'description' => 'Enrollment for the second semester of Academic Year 2025-2026 is now open until March 30, 2026.',
                'color' => 'indigo'
            ],
            [
                'date' => 'JULY 10, 2026',
                'title' => 'BrightSphere University Founding Anniversary Celebration',
                'description' => 'Join us in celebrating the 127th Founding Anniversary of BrightSphere University.',
                'color' => 'purple'
            ],
            [
                'date' => 'MAY 10, 2026',
                'title' => 'Research Symposium 2026',
                'description' => 'Call for Papers: The annual Research Symposium will be held on April 15-16, 2026.',
                'color' => 'green'
            ]
        ];

        return view('dashboard', compact(
            'user',
            'courses',
            'recentGrades',
            'todaysClasses',
            'upcomingAssignments',
            'overallGrade',
            'courseProgress',
            'totalStudents',
            'totalInstructors',
            'totalCourses',
            'totalSubjects',
            'completedSubjects',
            'streakDays',
            'weeklyHours',
            'studyHours',
            'achievementsCount',
            'activeStudents',
            'inactiveStudents',
            'universityInfo',
            'newsAnnouncements',
            'unreadMessagesCount',
            'instructors',
            'conversations'
        ));
    }

    /**
     * Get messages for a specific conversation
     */
    public function getMessages($instructorId)
    {
        $user = Auth::user();
        
        $messages = Message::where(function($query) use ($user, $instructorId) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $instructorId);
        })->orWhere(function($query) use ($user, $instructorId) {
            $query->where('sender_id', $instructorId)
                  ->where('receiver_id', $user->id);
        })->orderBy('created_at', 'asc')->get();
        
        // Mark messages as read
        Message::where('receiver_id', $user->id)
               ->where('sender_id', $instructorId)
               ->where('is_read', false)
               ->update(['is_read' => true]);
        
        return response()->json(['messages' => $messages]);
    }
    
    /**
     * Send a message to an instructor
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
     * Display student courses page.
     */
    public function studentCourses()
    {
        $user = Auth::user();
        
        // Get courses the student is enrolled in through the course_student pivot table
        // Specify the table name to avoid ambiguity
        $courses = Course::whereHas('students', function($query) use ($user) {
            $query->where('course_student.student_id', $user->id);
        })->with('instructor')->get();
        
        $stats = [
            'total_courses' => $courses->count(),
            'completed_courses' => $courses->filter(function($course) use ($user) {
                $studentGrade = $course->students()->where('course_student.student_id', $user->id)->first();
                return $studentGrade && $studentGrade->pivot->final_grade && $studentGrade->pivot->final_grade <= 3.0;
            })->count(),
            'in_progress' => $courses->filter(function($course) use ($user) {
                $studentGrade = $course->students()->where('course_student.student_id', $user->id)->first();
                return !$studentGrade || !$studentGrade->pivot->final_grade;
            })->count(),
        ];
        
        return view('student.courses', compact('courses', 'stats', 'user'));
    }

    /**
     * Display a single course details for student.
     */
    public function showStudentCourse(Course $course)
    {
        $user = Auth::user();
        
        // Check if student is enrolled in this course
        $isEnrolled = $course->students()->where('course_student.student_id', $user->id)->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('student.courses')->with('error', 'You are not enrolled in this course.');
        }
        
        // Get student's grade for this course
        $studentGrade = $course->students()->where('course_student.student_id', $user->id)->first();
        $grade = $studentGrade ? $studentGrade->pivot : null;
        
        // Get attendance records for this student in this course
        $attendances = Attendance::where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->orderBy('date', 'desc')
            ->get();
        
        $attendanceStats = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'total' => $attendances->count(),
        ];
        
        $attendanceRate = $attendanceStats['total'] > 0 
            ? round(($attendanceStats['present'] / $attendanceStats['total']) * 100, 1) 
            : 0;
        
        return view('student.course-details', compact('course', 'grade', 'attendances', 'attendanceStats', 'attendanceRate', 'user'));
    }

    /**
     * Show student registration page (without course enrollment).
     */
    public function studentRegistration()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        return view('student.registration', compact('user', 'student'));
    }

    /**
     * Generate a unique student ID.
     */
    private function generateStudentId()
    {
        $year = date('Y');
        $lastStudent = Student::orderBy('id', 'desc')->first();
        
        if ($lastStudent && $lastStudent->student_id) {
            // Extract the number from existing student ID (format: BSU-2024-0001)
            preg_match('/\d+$/', $lastStudent->student_id, $matches);
            $lastNumber = isset($matches[0]) ? intval($matches[0]) : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'BSU-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Submit registration with personal and guardian info (without course enrollment).
     */
    public function submitRegistration(Request $request)
    {
        $user = Auth::user();
        
        // Validate personal information
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'program' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:50',
            'guardian_contact' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_address' => 'nullable|string',
        ]);
        
        // Update user
        $user->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);
        
        // Generate student ID
        $studentId = $this->generateStudentId();
        
        // Update or create student record
        $student = Student::updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'student_id' => $studentId,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'program' => $validated['program'] ?? null,
                'year_level' => $validated['year_level'] ?? null,
                'address' => $validated['address'] ?? null,
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_relationship' => $validated['guardian_relationship'] ?? null,
                'guardian_contact' => $validated['guardian_contact'] ?? null,
                'guardian_email' => $validated['guardian_email'] ?? null,
                'guardian_address' => $validated['guardian_address'] ?? null,
                'name' => trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']),
                'status' => 'Active',
            ]
        );
        
        // Also update the user's student_id field if it exists
        if (Schema::hasColumn('users', 'student_id')) {
            $user->update(['student_id' => $studentId]);
        }
        
        return redirect()->route('dashboard')->with('success', 'Your registration has been saved successfully! Your Student ID is: ' . $studentId);
    }

    /**
     * Show student notices page.
     */
    public function studentNotices()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        $notices = [
            [
                'id' => 1,
                'title' => 'Enrollment for Second Semester',
                'message' => 'Enrollment for the second semester is now open. Please complete your enrollment by March 30, 2026.',
                'date' => '2026-05-20',
                'type' => 'important',
                'icon' => 'fa-calendar-alt',
                'color' => 'red'
            ],
            [
                'id' => 2,
                'title' => 'University Founding Anniversary',
                'message' => 'Join us in celebrating the 127th Founding Anniversary of BrightSphere University.',
                'date' => '2026-07-10',
                'type' => 'event',
                'icon' => 'fa-birthday-cake',
                'color' => 'purple'
            ],
            [
                'id' => 3,
                'title' => 'Research Symposium 2026',
                'message' => 'Call for Papers: The annual Research Symposium will be held on April 15-16, 2026.',
                'date' => '2026-05-10',
                'type' => 'academic',
                'icon' => 'fa-microphone-alt',
                'color' => 'blue'
            ],
            [
                'id' => 4,
                'title' => 'Library Hours Extended',
                'message' => 'The university library will extend its hours during exam week.',
                'date' => '2026-04-01',
                'type' => 'announcement',
                'icon' => 'fa-book',
                'color' => 'green'
            ],
            [
                'id' => 5,
                'title' => 'Scholarship Applications Open',
                'message' => 'Apply for academic scholarships for the upcoming semester. Deadline is May 15, 2026.',
                'date' => '2026-04-15',
                'type' => 'scholarship',
                'icon' => 'fa-graduation-cap',
                'color' => 'yellow'
            ],
            [
                'id' => 6,
                'title' => 'Midterm Examinations Schedule',
                'message' => 'Midterm examinations will be held from April 20-25, 2026. Check your schedule.',
                'date' => '2026-04-10',
                'type' => 'exam',
                'icon' => 'fa-pen-alt',
                'color' => 'orange'
            ],
            [
                'id' => 7,
                'title' => 'Campus Event: Career Fair 2026',
                'message' => 'Annual Career Fair will be held on May 5, 2026 at the University Gymnasium.',
                'date' => '2026-04-25',
                'type' => 'event',
                'icon' => 'fa-briefcase',
                'color' => 'indigo'
            ],
            [
                'id' => 8,
                'title' => 'Holiday Announcement',
                'message' => 'No classes on April 9-10, 2026 in observance of Araw ng Kagitingan.',
                'date' => '2026-04-05',
                'type' => 'announcement',
                'icon' => 'fa-calendar-day',
                'color' => 'teal'
            ],
        ];
        
        // Sort notices by date (newest first)
        $notices = collect($notices)->sortByDesc('date')->values()->all();
        
        return view('student.notices', compact('user', 'student', 'notices'));
    }

    /**
     * Show student grades with enhanced features
     */
    public function studentGrades()
    {
        $user = Auth::user();
        
        // Try to find student by user_id first
        $student = Student::where('user_id', $user->id)->first();
        
        // If not found, try to find by email (fallback)
        if (!$student) {
            $student = Student::where('email', $user->email)->first();
        }
        
        // If still not found, show error with helpful message
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Student record not found. Please contact the administrator to link your account.');
        }
        
        // Get all grades for this student
        $grades = Grade::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculate average grade
        $averageGrade = $grades->avg('percentage') ?? 0;
        
        // Group grades by subject
        $gradesBySubject = $grades->groupBy('subject');
        
        // Calculate subject averages with detailed statistics
        $subjectAverages = [];
        foreach ($gradesBySubject as $subject => $subjectGrades) {
            $subjectAverages[$subject] = [
                'average' => $subjectGrades->avg('percentage'),
                'count' => $subjectGrades->count(),
                'passed' => $subjectGrades->where('percentage', '>=', 75)->count(),
                'failed' => $subjectGrades->where('percentage', '<', 75)->count(),
                'letter' => $this->calculateLetterGrade($subjectGrades->avg('percentage'))
            ];
        }
        
        // Get academic standing with detailed status
        $academicStanding = $this->getAcademicStanding($averageGrade);
        
        // Calculate additional statistics
        $totalPassed = $grades->where('percentage', '>=', 75)->count();
        $totalFailed = $grades->where('percentage', '<', 75)->count();
        $passingRate = $grades->count() > 0 ? round(($totalPassed / $grades->count()) * 100, 1) : 0;
        
        // Get grade distribution
        $gradeDistribution = [
            'A' => $grades->whereIn('letter_grade', ['A', 'A+'])->count(),
            'B' => $grades->whereIn('letter_grade', ['B', 'B+', 'B-'])->count(),
            'C' => $grades->whereIn('letter_grade', ['C', 'C+', 'C-'])->count(),
            'D' => $grades->whereIn('letter_grade', ['D', 'D+', 'D-'])->count(),
            'F' => $grades->where('letter_grade', 'F')->count(),
        ];
        
        // Get recent grades (last 5)
        $recentGrades = $grades->take(5);
        
        // Calculate GPA on 4.0 scale
        $gpa = $this->calculateGPA($grades);
        
        return view('student.grades', compact(
            'user',
            'student',
            'grades',
            'averageGrade',
            'gradesBySubject',
            'subjectAverages',
            'academicStanding',
            'gradeDistribution',
            'totalPassed',
            'totalFailed',
            'passingRate',
            'recentGrades',
            'gpa'
        ));
    }

    /**
     * Get academic standing based on average grade
     */
    private function getAcademicStanding($averageGrade)
    {
        if ($averageGrade >= 90) return 'Excellent';
        if ($averageGrade >= 85) return 'Very Good';
        if ($averageGrade >= 80) return 'Good';
        if ($averageGrade >= 75) return 'Satisfactory';
        if ($averageGrade >= 60) return 'Passing';
        return 'Needs Improvement';
    }

    /**
     * Calculate letter grade from percentage
     */
    private function calculateLetterGrade($percentage)
    {
        if ($percentage >= 97) return 'A+';
        if ($percentage >= 93) return 'A';
        if ($percentage >= 90) return 'A-';
        if ($percentage >= 87) return 'B+';
        if ($percentage >= 83) return 'B';
        if ($percentage >= 80) return 'B-';
        if ($percentage >= 77) return 'C+';
        if ($percentage >= 73) return 'C';
        if ($percentage >= 70) return 'C-';
        if ($percentage >= 67) return 'D+';
        if ($percentage >= 63) return 'D';
        if ($percentage >= 60) return 'D-';
        return 'F';
    }

    /**
     * Calculate GPA on a 4.0 scale
     */
    private function calculateGPA($grades)
    {
        if ($grades->count() == 0) return 0;
        
        $gradePoints = [
            'A+' => 4.0,
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'C-' => 1.7,
            'D+' => 1.3,
            'D' => 1.0,
            'D-' => 0.7,
            'F' => 0.0,
        ];
        
        $totalPoints = 0;
        foreach ($grades as $grade) {
            $totalPoints += $gradePoints[$grade->letter_grade] ?? 0;
        }
        
        return round($totalPoints / $grades->count(), 2);
    }

    /**
     * Update user profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ]);
        
        // Also update the student record if it exists
        $student = Student::where('user_id', $user->id)->first();
        if ($student) {
            $student->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'name' => trim($request->first_name . ' ' . ($request->middle_name ? $request->middle_name . ' ' : '') . $request->last_name),
            ]);
        }
        
        // Redirect based on user role
        if ($user->role === 'instructor' || $user->role === 'teacher') {
            return redirect()->route('instructor.dashboard')->with('success', 'Profile updated successfully!');
        }
        
        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        
        // Redirect based on user role
        if ($user->role === 'instructor' || $user->role === 'teacher') {
            return redirect()->route('instructor.dashboard')->with('success', 'Password updated successfully!');
        }
        
        return redirect()->route('dashboard')->with('success', 'Password updated successfully!');
    }

    /**
     * Calculate user's streak days based on login activity
     */
    private function calculateStreakDays($user)
    {
        if (method_exists($user, 'loginLogs')) {
            $lastLogin = $user->loginLogs()
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($lastLogin && $lastLogin->created_at->isToday()) {
                $streak = $user->loginLogs()
                    ->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                    ->count();
                return min($streak, 30);
            }
        }
        
        return rand(0, 15);
    }

    /**
     * Calculate overall GPA based on courses
     */
    private function calculateOverallGrade($courses)
    {
        $totalCredits = 0;
        $totalPoints = 0;
        
        foreach ($courses as $course) {
            if (isset($course['credits']) && isset($course['grade_point'])) {
                $totalCredits += $course['credits'];
                $totalPoints += $course['credits'] * $course['grade_point'];
            }
        }
        
        if ($totalCredits > 0) {
            $gpa = round($totalPoints / $totalCredits, 2);
            return [
                'gpa' => $gpa,
                'letter' => $this->convertGPAtoLetter($gpa),
                'total_credits' => $totalCredits,
                'total_points' => $totalPoints,
            ];
        }
        
        return [
            'gpa' => 0,
            'letter' => 'N/A',
            'total_credits' => 0,
            'total_points' => 0,
        ];
    }

    /**
     * Convert GPA to letter grade
     */
    private function convertGPAtoLetter($gpa)
    {
        if ($gpa >= 3.7) return 'A';
        if ($gpa >= 3.3) return 'A-';
        if ($gpa >= 3.0) return 'B+';
        if ($gpa >= 2.7) return 'B';
        if ($gpa >= 2.3) return 'B-';
        if ($gpa >= 2.0) return 'C+';
        if ($gpa >= 1.7) return 'C';
        if ($gpa >= 1.3) return 'C-';
        if ($gpa >= 1.0) return 'D';
        return 'F';
    }

    /**
     * Get course progress statistics
     */
    private function getCourseProgress($courses)
    {
        $completed = 0;
        $inProgress = 0;
        
        foreach ($courses as $course) {
            if (isset($course['progress'])) {
                if ($course['progress'] >= 100) {
                    $completed++;
                } else {
                    $inProgress++;
                }
            }
        }
        
        $total = $completed + $inProgress;
        $completionRate = $total > 0 ? round(($completed / $total) * 100) : 0;
        
        return [
            'completed' => $completed,
            'in_progress' => $inProgress,
            'completion_rate' => $completionRate,
        ];
    }

    /**
     * Get upcoming assignments (for future implementation)
     */
    private function getUpcomingAssignments($userId)
    {
        return [];
    }

    /**
     * Get today's classes (for future implementation)
     */
    private function getTodaysClasses($userId)
    {
        return [];
    }

    /**
     * Get recent grades (for future implementation)
     */
    private function getRecentGrades($userId)
    {
        return [];
    }
}