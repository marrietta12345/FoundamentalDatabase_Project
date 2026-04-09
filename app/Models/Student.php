<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_id',
        'first_name',
        'middle_name',
        'last_name',
        'name',
        'email',
        'phone',
        'program',
        'year_level',
        'section',
        'status',
        'address',
        'gender',
        'bio',
        'last_login_at',
        'last_login_ip',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user associated with the student
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all grades for this student
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get all classes this student is enrolled in
     */
    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_enrollments', 'student_id', 'class_id')
                    ->withPivot('status', 'final_grade', 'enrolled_date', 'remarks')
                    ->withTimestamps();
    }

    /**
     * Get all enrollments for this student
     */
    public function enrollments()
    {
        return $this->hasMany(ClassEnrollment::class);
    }

    /**
     * Get active enrolled classes
     */
    public function getActiveClassesAttribute()
    {
        return $this->classes()->wherePivot('status', 'enrolled')->get();
    }

    /**
     * Get completed classes
     */
    public function getCompletedClassesAttribute()
    {
        return $this->classes()->wherePivot('status', 'completed')->get();
    }

    /**
     * Get all grades for a specific subject
     */
    public function gradesBySubject($subject)
    {
        return $this->grades()->where('subject', $subject)->get();
    }

    /**
     * Get the student's average grade across all subjects
     */
    public function getAverageGradeAttribute()
    {
        return $this->grades()->avg('percentage') ?? 0;
    }

    /**
     * Get the student's GPA (4.0 scale)
     */
    public function getGpaAttribute()
    {
        $grades = $this->grades()->whereNotNull('percentage')->get();
        if ($grades->isEmpty()) return 0;
        
        $totalPoints = 0;
        foreach ($grades as $grade) {
            $totalPoints += $this->convertPercentageToGPA($grade->percentage);
        }
        
        return round($totalPoints / $grades->count(), 2);
    }

    /**
     * Convert percentage to GPA (4.0 scale)
     */
    private function convertPercentageToGPA($percentage)
    {
        if ($percentage >= 97) return 4.0;
        if ($percentage >= 93) return 4.0;
        if ($percentage >= 90) return 3.7;
        if ($percentage >= 87) return 3.3;
        if ($percentage >= 83) return 3.0;
        if ($percentage >= 80) return 2.7;
        if ($percentage >= 77) return 2.3;
        if ($percentage >= 73) return 2.0;
        if ($percentage >= 70) return 1.7;
        if ($percentage >= 67) return 1.3;
        if ($percentage >= 63) return 1.0;
        if ($percentage >= 60) return 0.7;
        return 0.0;
    }

    /**
     * Get student's total credits (for future implementation)
     */
    public function getTotalCreditsAttribute()
    {
        return $this->grades()->sum('credits') ?? 0;
    }

    /**
     * Get number of courses taken
     */
    public function getCoursesCountAttribute()
    {
        return $this->grades()->distinct('subject')->count('subject');
    }

    /**
     * Get passing rate
     */
    public function getPassingRateAttribute()
    {
        $total = $this->grades()->count();
        if ($total == 0) return 0;
        
        $passed = $this->grades()->where('percentage', '>=', 75)->count();
        return round(($passed / $total) * 100, 1);
    }

    /**
     * Get recent grades (last 5)
     */
    public function getRecentGradesAttribute()
    {
        return $this->grades()->orderBy('created_at', 'desc')->limit(5)->get();
    }

    /**
     * Get grades grouped by subject
     */
    public function getGradesBySubjectAttribute()
    {
        return $this->grades()->get()->groupBy('subject');
    }

    /**
     * Get the student's full name
     */
    public function getFullNameAttribute()
    {
        $nameParts = [];
        
        if ($this->first_name) {
            $nameParts[] = $this->first_name;
        }
        if ($this->middle_name) {
            $nameParts[] = $this->middle_name;
        }
        if ($this->last_name) {
            $nameParts[] = $this->last_name;
        }
        
        return trim(implode(' ', $nameParts));
    }

    /**
     * Get the student's initials
     */
    public function getInitialsAttribute()
    {
        $first = substr($this->first_name, 0, 1);
        $last = substr($this->last_name, 0, 1);
        return strtoupper($first . $last);
    }

    /**
     * Get formatted student ID
     */
    public function getFormattedStudentIdAttribute()
    {
        return $this->student_id ?? 'STU' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get status color for badges
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Active' => 'green',
            'Inactive' => 'red',
            'Pending' => 'yellow',
            'Graduated' => 'blue',
            'Suspended' => 'orange',
            default => 'gray',
        };
    }

    /**
     * Get avatar color
     */
    public function getAvatarColorAttribute()
    {
        $colors = ['indigo', 'purple', 'blue', 'green', 'yellow', 'red', 'pink', 'cyan'];
        $index = $this->id % count($colors);
        return $colors[$index];
    }

    /**
     * Get academic standing
     */
    public function getAcademicStandingAttribute()
    {
        $gpa = $this->gpa;
        if ($gpa >= 3.5) return 'Dean\'s Lister';
        if ($gpa >= 3.0) return 'Good Standing';
        if ($gpa >= 2.0) return 'Satisfactory';
        if ($gpa >= 1.0) return 'Probation';
        return 'Academic Warning';
    }

    /**
     * Check if student is on honor roll
     */
    public function getIsHonorRollAttribute()
    {
        return $this->gpa >= 3.5;
    }

    /**
     * Get student's class schedule summary
     */
    public function getScheduleSummaryAttribute()
    {
        $classes = $this->active_classes;
        $summary = [];
        foreach ($classes as $class) {
            $summary[] = [
                'name' => $class->name,
                'subject' => $class->subject,
                'schedule' => $class->schedule,
                'room' => $class->room,
                'instructor' => $class->instructor ? $class->instructor->first_name . ' ' . $class->instructor->last_name : 'N/A',
            ];
        }
        return $summary;
    }

    /**
     * Get list of available programs
     */
    public static function getPrograms()
    {
        return [
            'Computer Science Program' => 'Computer Science Program',
            'Teacher Education Program' => 'Teacher Education Program',
            'Nursing Program' => 'Nursing Program',
            'Accountancy Program' => 'Accountancy Program',
            'Business Administration Program' => 'Business Administration Program',
            'Criminal Justice Education Program' => 'Criminal Justice Education Program',
            'Art and Science Program' => 'Art and Science Program',
            'Engineering and Technology Program' => 'Engineering and Technology Program',
        ];
    }

    /**
     * Get list of available year levels
     */
    public static function getYearLevels()
    {
        return [
            '1' => '1st Year',
            '2' => '2nd Year',
            '3' => '3rd Year',
            '4' => '4th Year',
        ];
    }

    /**
     * Get list of available statuses
     */
    public static function getStatuses()
    {
        return [
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Pending' => 'Pending',
            'Graduated' => 'Graduated',
            'Suspended' => 'Suspended',
        ];
    }

    /**
     * Scope for active students
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for inactive students
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    /**
     * Scope for students by program
     */
    public function scopeByProgram($query, $program)
    {
        return $query->where('program', $program);
    }

    /**
     * Scope for students by year level
     */
    public function scopeByYearLevel($query, $yearLevel)
    {
        return $query->where('year_level', $yearLevel);
    }

    /**
     * Scope for students enrolled in a specific class
     */
    public function scopeEnrolledInClass($query, $classId)
    {
        return $query->whereHas('classes', function($q) use ($classId) {
            $q->where('class_id', $classId)->wherePivot('status', 'enrolled');
        });
    }

    /**
     * Scope for honor students (GPA >= 3.5)
     */
    public function scopeHonorStudents($query)
    {
        return $query->whereHas('grades', function($q) {
            $q->select('student_id')
                ->selectRaw('AVG(percentage) as avg_grade')
                ->groupBy('student_id')
                ->havingRaw('AVG(percentage) >= 87.5');
        });
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->student_id)) {
                $lastStudent = static::orderBy('id', 'desc')->first();
                $nextId = $lastStudent ? $lastStudent->id + 1 : 1;
                $student->student_id = 'STU' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }

            if (empty($student->name)) {
                $student->name = $student->getFullNameAttribute();
            }
        });

        static::updating(function ($student) {
            $student->name = $student->getFullNameAttribute();
        });
    }
}