<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    
    protected $table = 'attendance';
    
   protected $fillable = [
    'student_id',
    'course_id',
    'date',
    'status',
    'time_in',
    'remarks',
    'recorded_by',
];

protected $casts = [
    'date' => 'date',
    'time_in' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];
    
    // Relationship with student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    // Relationship with course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    // Relationship with recorder (instructor)
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
    
    // Scope for today's attendance
    public function scopeToday($query)
    {
        return $query->whereDate('date', now()->toDateString());
    }
    
    // Scope for specific date
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }
    
    // Scope for specific student
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
    
    // Scope for specific course
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }
    
    // Accessor for status badge
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'excused' => 'info',
            default => 'secondary',
        };
    }
    
    // Accessor for status text
    public function getStatusTextAttribute()
    {
        return ucfirst($this->status);
    }
}