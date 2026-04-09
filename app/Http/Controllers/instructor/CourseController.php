<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index()
    {
        $courses = Course::where('instructor_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Calculate statistics
        $allCourses = Course::where('instructor_id', Auth::id())->get();
        
        $stats = [
            'total_courses' => $allCourses->count(),
            'active_courses' => $allCourses->where('status', 'active')->count(),
            'inactive_courses' => $allCourses->where('status', 'inactive')->count(),
            'total_students' => $allCourses->sum(function($course) {
                return $course->students()->count();
            }),
        ];
        
        return view('instructor.courses.index', compact('courses', 'stats'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('instructor.courses.create');
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'class_code' => 'required|string|max:255',
                'class_name' => 'required|string|max:255',
                'code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'schedule' => 'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
                'description' => 'nullable|string',
                'student_ids' => 'nullable|array',
                'student_ids.*' => 'exists:users,id'
            ]);

            $validated['instructor_id'] = Auth::id();
            
            $course = Course::create($validated);
            
            if ($request->has('student_ids') && !empty($request->student_ids)) {
                $course->students()->attach($request->student_ids);
                $studentCount = count($request->student_ids);
                $message = "Course '{$course->class_code}' created successfully with {$studentCount} student(s) enrolled!";
            } else {
                $message = "Course '{$course->class_code}' created successfully! You can add students later.";
            }
            
            return redirect()->route('instructor.courses.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        return view('instructor.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }
        
        return view('instructor.courses.edit', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        try {
            if ($course->instructor_id !== Auth::id()) {
                return back()->with('error', 'You are not authorized to update this course.');
            }

            $validated = $request->validate([
                'class_code' => 'required|string|max:255',
                'class_name' => 'required|string|max:255',
                'code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'schedule' => 'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
                'description' => 'nullable|string',
            ]);

            $course->update($validated);

            return redirect()->route('instructor.courses.index')
                ->with('success', 'Course updated successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating course: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        try {
            if ($course->instructor_id !== Auth::id()) {
                return back()->with('error', 'You are not authorized to delete this course.');
            }

            $course->students()->detach();
            $course->delete();

            return redirect()->route('instructor.courses.index')
                ->with('success', 'Course deleted successfully!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting course: ' . $e->getMessage());
        }
    }
}