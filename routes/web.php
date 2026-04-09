<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\Instructor\CourseController;

// Home/Welcome route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register/student', [RegisterController::class, 'showRegistrationForm'])->name('register.student');
Route::post('/register/student', [RegisterController::class, 'register']);

// Password Reset Routes (optional)
Route::get('/password/reset', function() {
    return view('auth.passwords.email');
})->name('password.request');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    
    // Student Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Student Courses Route
    Route::get('/student/courses', [DashboardController::class, 'studentCourses'])->name('student.courses');
    Route::get('/student/course/{course}', [DashboardController::class, 'showStudentCourse'])->name('student.course.show');

    // Student Grades Route
    Route::get('/student/grades', [DashboardController::class, 'studentGrades'])->name('student.grades');

    // Student Notices Route
    Route::get('/student/notices', [DashboardController::class, 'studentNotices'])->name('student.notices');

    // Student AJAX Routes
    Route::get('/students/{id}/edit-data', [StudentController::class, 'getStudentForEdit'])->name('students.edit-data');

    // Student Messaging Routes
    Route::get('/student/messages/{instructorId}', [DashboardController::class, 'getMessages'])->name('student.get-messages');
    Route::post('/student/send-message', [DashboardController::class, 'sendMessage'])->name('student.send-message');

    // Student Registration Routes
    Route::get('/student/registration', [DashboardController::class, 'studentRegistration'])->name('student.registration');
    Route::post('/student/registration', [DashboardController::class, 'submitRegistration'])->name('student.registration.submit');
    
    // Profile Update Routes (shared for all roles)
    Route::put('/profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [DashboardController::class, 'updatePassword'])->name('profile.password');
    
    // Student Management Routes (for admin)
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index');
        Route::post('/', [StudentController::class, 'store'])->name('store');
        Route::get('/{id}', [StudentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StudentController::class, 'update'])->name('update');
        Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroy');
    });
    
    // Faculty Routes
    Route::prefix('faculty')->name('faculty.')->group(function () {
        Route::get('/', [FacultyController::class, 'index'])->name('index');
        Route::post('/store', [FacultyController::class, 'store'])->name('store');
        Route::get('/{id}', [FacultyController::class, 'show'])->name('show');
        Route::put('/{id}', [FacultyController::class, 'update'])->name('update');
        Route::delete('/{id}', [FacultyController::class, 'destroy'])->name('delete');
    });
    
    // Teacher routes with teacher middleware (for backward compatibility)
    Route::prefix('teacher')->name('teacher.')->middleware('teacher')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'index'])->name('dashboard');
        Route::get('/classes', [TeacherController::class, 'classes'])->name('classes');
        Route::get('/students', [TeacherController::class, 'students'])->name('students');
        Route::get('/grades', [TeacherController::class, 'grades'])->name('grades');
        Route::get('/attendance', [TeacherController::class, 'attendance'])->name('attendance');
    });
    
    // ==================== INSTRUCTOR ROUTES ====================
    // Instructor routes with instructor middleware
    Route::prefix('instructor')->name('instructor.')->middleware('instructor')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [InstructorController::class, 'index'])->name('dashboard');

        // Analytics Routes
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [InstructorController::class, 'analytics'])->name('index');
            Route::get('/course/{course}', [InstructorController::class, 'courseAnalytics'])->name('course');
            Route::get('/export', [InstructorController::class, 'exportAnalytics'])->name('export');
        });
        
        // ==================== INSTRUCTOR MESSAGING ROUTES ====================
        // Get messages for a specific student conversation
        Route::get('/messages/{studentId}', [InstructorController::class, 'getMessages'])->name('get-messages');
        
        // Send a message to a student
        Route::post('/send-message', [InstructorController::class, 'sendMessage'])->name('send-message');
        // ==================== END INSTRUCTOR MESSAGING ROUTES ====================
        
        // ==================== COURSE MANAGEMENT ====================
        // Use CourseController for course operations
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [CourseController::class, 'index'])->name('index');
            Route::get('/create', [CourseController::class, 'create'])->name('create');
            Route::post('/', [CourseController::class, 'store'])->name('store');
            Route::get('/{course}', [CourseController::class, 'show'])->name('show');
            Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
            Route::put('/{course}', [CourseController::class, 'update'])->name('update');
            Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy');
            
            // Course Enrollment Routes
            Route::get('/{course}/add-students', [CourseController::class, 'addStudents'])->name('add-students');
            Route::post('/{course}/enroll-students', [CourseController::class, 'enrollStudents'])->name('enroll-students');
            Route::delete('/{course}/students/{student}', [CourseController::class, 'removeStudent'])->name('remove-student');
            Route::post('/{course}/bulk-enroll', [CourseController::class, 'bulkEnroll'])->name('bulk-enroll');
            Route::put('/{course}/students/{student}/grade', [CourseController::class, 'updateGrade'])->name('update-grade');
        });
        
        // Keep old routes for backward compatibility (optional)
        Route::prefix('old-courses')->name('old-courses.')->group(function () {
            Route::get('/', [InstructorController::class, 'courses'])->name('index');
            Route::get('/create', [InstructorController::class, 'createCourse'])->name('create');
            Route::post('/', [InstructorController::class, 'storeCourse'])->name('store');
            Route::get('/{course}', [InstructorController::class, 'showCourse'])->name('show');
            Route::get('/{course}/edit', [InstructorController::class, 'editCourse'])->name('edit');
            Route::put('/{course}', [InstructorController::class, 'updateCourse'])->name('update');
            Route::delete('/{course}', [InstructorController::class, 'destroyCourse'])->name('destroy');
            Route::get('/{course}/add-students', [InstructorController::class, 'addStudentsToCourse'])->name('add-students');
            Route::post('/{course}/enroll-students', [InstructorController::class, 'enrollStudentsToCourse'])->name('enroll-students');
            Route::delete('/{course}/students/{student}', [InstructorController::class, 'removeStudentFromCourse'])->name('remove-student');
            Route::post('/{course}/bulk-enroll', [InstructorController::class, 'bulkEnroll'])->name('bulk-enroll');
            Route::put('/{course}/students/{student}/grade', [InstructorController::class, 'updateClassGrade'])->name('update-grade');
        });
        
        // ==================== CLASS SCHEDULE VIEW ====================
        Route::prefix('class')->name('class.')->group(function () {
            Route::get('/', [InstructorController::class, 'schedule'])->name('index');
            Route::get('/{course}', [InstructorController::class, 'showClass'])->name('show');
            Route::get('/{course}/edit', [InstructorController::class, 'editCourse'])->name('edit');
            Route::get('/{course}/add-students', [InstructorController::class, 'addStudentsToClass'])->name('add-students');
            Route::post('/{course}/enroll-students', [InstructorController::class, 'enrollStudentsToClass'])->name('enroll-students');
            Route::delete('/{courseId}/students/{studentId}', [InstructorController::class, 'removeStudentFromClass'])->name('remove-student');
            Route::put('/{courseId}/students/{studentId}/grade', [InstructorController::class, 'updateClassGrade'])->name('update-grade');
            Route::post('/{course}/bulk-enroll', [InstructorController::class, 'bulkEnroll'])->name('bulk-enroll');
        });
        
        // Schedule alias (for backward compatibility)
        Route::get('/schedule', [InstructorController::class, 'schedule'])->name('schedule');
        
        // ==================== STUDENT MANAGEMENT ====================
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [InstructorController::class, 'students'])->name('index');
            Route::get('/create', [InstructorController::class, 'createStudent'])->name('create');
            Route::post('/', [InstructorController::class, 'storeStudent'])->name('store');
            Route::get('/{student}', [InstructorController::class, 'showStudent'])->name('show');
            Route::get('/{student}/edit', [InstructorController::class, 'editStudent'])->name('edit');
            Route::put('/{student}', [InstructorController::class, 'updateStudent'])->name('update');
            Route::delete('/{student}', [InstructorController::class, 'destroyStudent'])->name('destroy');
            
            // Student Enrollment Routes
            Route::get('/enroll/create', [InstructorController::class, 'createStudentEnrollment'])->name('enroll');
            Route::post('/enroll', [InstructorController::class, 'enrollStudent'])->name('enroll.store');
            Route::post('/{student}/enroll-course', [InstructorController::class, 'enrollStudentInCourse'])->name('enroll-course');
            Route::delete('/{student}/courses/{course}', [InstructorController::class, 'removeStudentFromCourseEdit'])->name('remove-course');
            
            // AJAX Routes
            Route::get('/{student}/courses', [InstructorController::class, 'getStudentCourses'])->name('courses');
            Route::get('/{student}/edit-data', [InstructorController::class, 'getStudentForEdit'])->name('edit-data');
        });
        
        // ==================== GRADES MANAGEMENT ====================
        Route::prefix('grades')->name('grades.')->group(function () {
            Route::get('/', [InstructorController::class, 'grades'])->name('index');
            Route::get('/create', [InstructorController::class, 'createGrade'])->name('create');
            Route::post('/store', [InstructorController::class, 'storeGrade'])->name('store');
            Route::get('/{grade}', [InstructorController::class, 'showGrade'])->name('show');
            Route::get('/{grade}/edit', [InstructorController::class, 'editGrade'])->name('edit');
            Route::put('/{grade}', [InstructorController::class, 'updateGrade'])->name('update');
            Route::delete('/{grade}', [InstructorController::class, 'deleteGrade'])->name('delete');
        });
        
        // ==================== ATTENDANCE MANAGEMENT ====================
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [InstructorController::class, 'attendance'])->name('index');
            Route::post('/store', [InstructorController::class, 'storeAttendance'])->name('store');
        });
        
    }); // Close instructor group
    
}); // Close the auth middleware group

// Fallback route for 404 errors (optional)
Route::fallback(function () {
    return view('errors.404');
});