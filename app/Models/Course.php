<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'class_code',
        'class_name',
        'description',
        'schedule',
        'status',
        'instructor_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the instructor that owns the course.
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the students enrolled in this course.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id')
            ->withPivot('enrolled_at', 'status', 'prelim', 'midterm', 'prefinal', 'final_exam', 'final_grade')
            ->withTimestamps();
    }

    /**
     * Get the grades for this course.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get the attendance records for this course.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Scope a query to only include active courses.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive courses.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Check if the course is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get the total number of students enrolled.
     */
    public function getTotalStudentsAttribute()
    {
        return $this->students()->count();
    }

    /**
     * Get the number of students with grades.
     */
    public function getStudentsWithGradesAttribute()
    {
        return $this->students()->wherePivotNotNull('final_grade')->count();
    }

    /**
     * Get the average grade for the course.
     */
    public function getAverageGradeAttribute()
    {
        return $this->students()
            ->wherePivotNotNull('final_grade')
            ->avg('course_student.final_grade');
    }

    /**
     * Get the passing rate for the course (grade <= 3.0 is passing).
     */
    public function getPassingRateAttribute()
    {
        $totalWithGrades = $this->students()->wherePivotNotNull('final_grade')->count();
        if ($totalWithGrades == 0) {
            return 0;
        }
        
        $passed = $this->students()
            ->wherePivot('final_grade', '<=', 3.0)
            ->count();
            
        return ($passed / $totalWithGrades) * 100;
    }

    /**
     * Get the formatted schedule.
     */
    public function getFormattedScheduleAttribute()
    {
        return $this->schedule ?? 'No schedule set';
    }

    /**
     * Get the full course name with class name.
     */
    public function getFullNameAttribute()
    {
        if ($this->class_name) {
            return "{$this->name} - {$this->class_name}";
        }
        return $this->name;
    }

    /**
     * Get the course display info.
     */
    public function getDisplayInfoAttribute()
    {
        $info = $this->code . ' - ' . $this->name;
        if ($this->class_name) {
            $info .= ' (' . $this->class_name . ')';
        }
        return $info;
    }
}