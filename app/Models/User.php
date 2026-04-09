<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'role',
        'password',
        'department',
        'educational_background',
        'specialization',
        'experience_years',
        'status',
        'student_id',
        'program',
        'year_level',
        'address',
        'city',
        'province',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_number',
        'enrolled_at',
        'graduated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'enrolled_at' => 'date',
        'graduated_at' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            // Auto-generate name from first, middle, last names
            $user->name = trim($user->first_name . ' ' . ($user->middle_name ?? '') . ' ' . $user->last_name);
            $user->name = preg_replace('/\s+/', ' ', $user->name);
        });
        
        static::updating(function ($user) {
            // Auto-generate name from first, middle, last names
            $user->name = trim($user->first_name . ' ' . ($user->middle_name ?? '') . ' ' . $user->last_name);
            $user->name = preg_replace('/\s+/', ' ', $user->name);
        });
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . ($this->middle_name ?? '') . ' ' . $this->last_name);
    }

    /**
     * Get the user's initials.
     */
    public function getInitialsAttribute()
    {
        $first = substr($this->first_name ?? $this->name ?? 'U', 0, 1);
        $last = substr($this->last_name ?? '', 0, 1);
        return strtoupper($first . $last);
    }

    /**
     * Check if user is a student.
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is a teacher.
     */
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an instructor.
     */
    public function isInstructor()
    {
        return $this->role === 'instructor';
    }

    /**
     * Get the student details associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    /**
     * Get the courses this user is enrolled in (for students).
     * Fixed: Uses correct column names for the pivot table
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student', 'student_id', 'course_id')
                    ->withPivot('id', 'enrolled_at', 'status', 'prelim', 'midterm', 'prefinal', 'final_exam', 'final_grade')
                    ->withTimestamps();
    }

    /**
     * Get the courses this instructor is teaching.
     */
    public function teachingCourses()
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Get the grades for this user.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    /**
     * Get the attendance records for this user.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    /**
     * Scope a query to only include students.
     */
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    /**
     * Scope a query to only include instructors.
     */
    public function scopeInstructors($query)
    {
        return $query->where('role', 'instructor');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if the user is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get the user's role badge class.
     */
    public function getRoleBadgeClassAttribute()
    {
        return match($this->role) {
            'admin' => 'bg-red-100 text-red-800',
            'instructor' => 'bg-blue-100 text-blue-800',
            'teacher' => 'bg-purple-100 text-purple-800',
            'student' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the user's status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'bg-green-100 text-green-800',
            'inactive' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'graduated' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get the total number of courses enrolled (for students).
     */
    public function getTotalCoursesEnrolledAttribute()
    {
        return $this->courses()->count();
    }

    /**
     * Get the average grade for the student.
     */
    public function getAverageGradeAttribute()
    {
        return $this->grades()
            ->whereNotNull('final_grade')
            ->avg('final_grade');
    }

    /**
     * Get the number of passed courses (grade <= 3.0).
     */
    public function getPassedCoursesAttribute()
    {
        return $this->grades()
            ->where('final_grade', '<=', 3.0)
            ->count();
    }

    /**
     * Get the number of failed courses (grade > 3.0).
     */
    public function getFailedCoursesAttribute()
    {
        return $this->grades()
            ->where('final_grade', '>', 3.0)
            ->count();
    }

    /**
     * Get the number of pending grades.
     */
    public function getPendingGradesAttribute()
    {
        return $this->grades()
            ->whereNull('final_grade')
            ->count();
    }
}