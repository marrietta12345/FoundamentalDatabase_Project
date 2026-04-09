<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'student_id',
        'course_id',
        'instructor_id',
        'subject',
        'class_name',
        'prelim',
        'midterm',
        'prefinal',
        'final_exam',
        'final_grade',
        'status',
        'academic_year',
        'semester',
        'graded_by',
    ];
    
    protected $casts = [
        'prelim' => 'decimal:2',
        'midterm' => 'decimal:2',
        'prefinal' => 'decimal:2',
        'final_exam' => 'decimal:2',
        'final_grade' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function instructor()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
    
    // Accessor to display grade with proper formatting
    public function getFormattedFinalGradeAttribute()
    {
        return $this->final_grade ? number_format($this->final_grade, 2) : 'N/A';
    }
    
    // Accessor for status badge color
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'passed' => 'success',
            'failed' => 'danger',
            'pending' => 'warning',
            default => 'secondary',
        };
    }
    
    // Scope for passed grades
    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }
    
    // Scope for failed grades
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
    
    // Scope for pending grades
    public function scopePending($query)
    {
        return $query->whereNull('status');
    }
}