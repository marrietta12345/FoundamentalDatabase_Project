<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'department',
        'specialization',
        'qualification',
        'experience_years',
        'joining_date',
        'status',
        'profile_photo',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'bio',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'joining_date' => 'date',
        'experience_years' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the teacher profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the courses taught by the teacher.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    /**
     * Get the classes taught by the teacher.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Clas::class, 'teacher_id');
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    /**
     * Get the initials attribute.
     */
    public function getInitialsAttribute(): string
    {
        $first = substr($this->first_name, 0, 1);
        $last = substr($this->last_name, 0, 1);
        return strtoupper($first . $last);
    }

    /**
     * Scope a query to only include active teachers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include teachers by department.
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Get all available departments.
     */
    public static function getDepartments(): array
    {
        return [
            'Computer Science' => 'Computer Science',
            'Information Technology' => 'Information Technology',
            'Data Science' => 'Data Science',
            'Software Engineering' => 'Software Engineering',
            'Mathematics' => 'Mathematics',
            'Physics' => 'Physics',
            'Chemistry' => 'Chemistry',
            'Biology' => 'Biology',
            'Engineering' => 'Engineering',
            'Business' => 'Business',
            'Economics' => 'Economics',
            'Languages' => 'Languages',
        ];
    }

    /**
     * Get all available statuses.
     */
    public static function getStatuses(): array
    {
        return [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'on_leave' => 'On Leave',
            'pending' => 'Pending',
        ];
    }
}