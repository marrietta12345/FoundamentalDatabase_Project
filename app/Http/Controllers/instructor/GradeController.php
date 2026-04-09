<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    /**
     * Display a listing of grades.
     */
    public function index(Request $request)
    {
        $courses = Course::where('instructor_id', Auth::id())->get();
        $students = User::role('student')->get();
        
        $grades = Grade::with(['student', 'course'])
            ->whereHas('course', function($query) {
                $query->where('instructor_id', Auth::id());
            });
        
        // Apply filters
        if ($request->course_id) {
            $grades->where('course_id', $request->course_id);
        }
        
        if ($request->student_id) {
            $grades->where('student_id', $request->student_id);
        }
        
        if ($request->status) {
            $grades->where('status', $request->status);
        }
        
        $grades = $grades->latest()->get();
        
        // Calculate statistics
        $totalGrades = $grades->count();
        $passed = $grades->where('status', 'passed')->count();
        $failed = $grades->where('status', 'failed')->count();
        $pending = $grades->where('status', 'pending')->count();
        
        return view('instructor.grades.index', compact(
            'grades', 'courses', 'students', 
            'totalGrades', 'passed', 'failed', 'pending'
        ));
    }
    
    /**
     * Show the form for creating a new grade.
     */
    public function create()
    {
        $courses = Course::where('instructor_id', Auth::id())->get();
        $students = User::role('student')->get();
        
        return view('instructor.grades.create', compact('courses', 'students'));
    }
    
    /**
     * Store a newly created grade.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'class_name' => 'required|string',
            'prelim' => 'nullable|numeric|min:1.0|max:5.0',
            'midterm' => 'nullable|numeric|min:1.0|max:5.0',
            'prefinal' => 'nullable|numeric|min:1.0|max:5.0',
            'final' => 'nullable|numeric|min:1.0|max:5.0',
        ]);
        
        // Calculate final grade (average of all components)
        $grades = [
            $request->prelim,
            $request->midterm,
            $request->prefinal,
            $request->final
        ];
        
        $validGrades = array_filter($grades, function($grade) {
            return !is_null($grade);
        });
        
        $finalGrade = count($validGrades) > 0 
            ? array_sum($validGrades) / count($validGrades) 
            : null;
        
        // Determine status based on Philippine grading system
        // 1.0 to 3.0 = PASSED, anything above 3.0 = FAILED
        $status = null;
        if ($finalGrade !== null) {
            $status = $finalGrade <= 3.0 ? 'passed' : 'failed';
        }
        
        // Check if grade record already exists
        $existingGrade = Grade::where('student_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->first();
        
        if ($existingGrade) {
            // Update existing grade
            $existingGrade->update([
                'class_name' => $request->class_name,
                'prelim' => $request->prelim,
                'midterm' => $request->midterm,
                'prefinal' => $request->prefinal,
                'final_exam' => $request->final,
                'final_grade' => $finalGrade,
                'status' => $status,
            ]);
            
            // Also update pivot table if you're using it
            $this->updatePivotTable($request->student_id, $request->course_id, [
                'prelim' => $request->prelim,
                'midterm' => $request->midterm,
                'prefinal' => $request->prefinal,
                'final_exam' => $request->final,
                'final_grade' => $finalGrade,
                'status' => $status,
            ]);
            
            $message = 'Grade updated successfully!';
        } else {
            // Create new grade
            Grade::create([
                'student_id' => $request->student_id,
                'course_id' => $request->course_id,
                'class_name' => $request->class_name,
                'prelim' => $request->prelim,
                'midterm' => $request->midterm,
                'prefinal' => $request->prefinal,
                'final_exam' => $request->final,
                'final_grade' => $finalGrade,
                'status' => $status,
                'instructor_id' => Auth::id(),
            ]);
            
            // Also update pivot table if you're using it
            $this->updatePivotTable($request->student_id, $request->course_id, [
                'prelim' => $request->prelim,
                'midterm' => $request->midterm,
                'prefinal' => $request->prefinal,
                'final_exam' => $request->final,
                'final_grade' => $finalGrade,
                'status' => $status,
            ]);
            
            $message = 'Grade created successfully!';
        }
        
        return redirect()->route('instructor.grades.index')
            ->with('success', $message);
    }
    
    /**
     * Update the pivot table if you're using course_student
     */
    private function updatePivotTable($studentId, $courseId, $data)
    {
        $course = Course::find($courseId);
        if ($course) {
            $course->students()->updateExistingPivot($studentId, $data);
        }
    }
    
    /**
     * Show the form for editing a grade.
     */
    public function edit($id)
    {
        $grade = Grade::findOrFail($id);
        
        // Check authorization
        if ($grade->instructor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $courses = Course::where('instructor_id', Auth::id())->get();
        $students = User::role('student')->get();
        
        return view('instructor.grades.edit', compact('grade', 'courses', 'students'));
    }
    
    /**
     * Update the specified grade.
     */
    public function update(Request $request, $id)
    {
        $grade = Grade::findOrFail($id);
        
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'class_name' => 'required|string',
            'prelim' => 'nullable|numeric|min:1.0|max:5.0',
            'midterm' => 'nullable|numeric|min:1.0|max:5.0',
            'prefinal' => 'nullable|numeric|min:1.0|max:5.0',
            'final' => 'nullable|numeric|min:1.0|max:5.0',
        ]);
        
        // Calculate final grade
        $grades = [
            $request->prelim,
            $request->midterm,
            $request->prefinal,
            $request->final
        ];
        
        $validGrades = array_filter($grades, function($grade) {
            return !is_null($grade);
        });
        
        $finalGrade = count($validGrades) > 0 
            ? array_sum($validGrades) / count($validGrades) 
            : null;
        
        $status = $finalGrade !== null ? ($finalGrade <= 3.0 ? 'passed' : 'failed') : null;
        
        $grade->update([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'class_name' => $request->class_name,
            'prelim' => $request->prelim,
            'midterm' => $request->midterm,
            'prefinal' => $request->prefinal,
            'final_exam' => $request->final,
            'final_grade' => $finalGrade,
            'status' => $status,
        ]);
        
        // Update pivot table
        $this->updatePivotTable($request->student_id, $request->course_id, [
            'prelim' => $request->prelim,
            'midterm' => $request->midterm,
            'prefinal' => $request->prefinal,
            'final_exam' => $request->final,
            'final_grade' => $finalGrade,
        ]);
        
        return redirect()->route('instructor.grades.index')
            ->with('success', 'Grade updated successfully!');
    }
    
    /**
     * Remove the specified grade.
     */
    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);
        
        // Clear pivot table data
        $this->updatePivotTable($grade->student_id, $grade->course_id, [
            'prelim' => null,
            'midterm' => null,
            'prefinal' => null,
            'final_exam' => null,
            'final_grade' => null,
        ]);
        
        $grade->delete();
        
        return redirect()->route('instructor.grades.index')
            ->with('success', 'Grade deleted successfully!');
    }
}