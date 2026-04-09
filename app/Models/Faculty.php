<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'faculty';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'faculty_id',
        'first_name',
        'middle_name',
        'last_name',
        'name',
        'email',
        'phone',
        'department',
        'educational_background',
        'specialization',
        'qualification',
        'experience_years',
        'joining_date',
        'date_hired',
        'status',
        'profile_photo',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'bio',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'employment_type',
        'employee_id',
        'office_location',
        'office_hours',
        'publications',
        'research_interests',
        'awards',
        'certifications',
        'languages',
        'social_links',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'joining_date' => 'date',
        'date_hired' => 'date',
        'experience_years' => 'integer',
        'last_login_at' => 'datetime',
        'social_links' => 'array',
        'publications' => 'array',
        'research_interests' => 'array',
        'awards' => 'array',
        'certifications' => 'array',
        'languages' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the user that owns the faculty profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the courses taught by the faculty.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'faculty_id');
    }

    /**
     * Get the classes taught by the faculty.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Clas::class, 'faculty_id');
    }

    /**
     * Get the assignments created by the faculty.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'faculty_id');
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
     * Get the formatted faculty ID attribute.
     */
    public function getFormattedFacultyIdAttribute(): string
    {
        return 'FAC' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Scope a query to only include active faculty.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope a query to only include inactive faculty.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    /**
     * Scope a query to only include pending faculty.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope a query to filter by department.
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope a query to search faculty by name or email.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('faculty_id', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%");
        });
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
            'Arts' => 'Arts',
            'Humanities' => 'Humanities',
            'Social Sciences' => 'Social Sciences',
            'Education' => 'Education',
        ];
    }

    /**
     * Get all available statuses.
     */
    public static function getStatuses(): array
    {
        return [
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Pending' => 'Pending',
            'On Leave' => 'On Leave',
            'Retired' => 'Retired',
            'Resigned' => 'Resigned',
        ];
    }

    /**
     * Get all available employment types.
     */
    public static function getEmploymentTypes(): array
    {
        return [
            'Full-time' => 'Full-time',
            'Part-time' => 'Part-time',
            'Adjunct' => 'Adjunct',
            'Visiting' => 'Visiting',
            'Emeritus' => 'Emeritus',
            'Contractual' => 'Contractual',
        ];
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Active' => 'green',
            'Inactive' => 'red',
            'Pending' => 'yellow',
            'On Leave' => 'orange',
            'Retired' => 'gray',
            'Resigned' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get the avatar background color based on ID.
     */
    public function getAvatarColorAttribute(): string
    {
        $colors = ['indigo', 'purple', 'blue', 'green', 'yellow', 'red', 'pink', 'cyan'];
        return $colors[$this->id % count($colors)];
    }

    /**
     * Check if faculty is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    /**
     * Check if faculty is on leave.
     */
    public function isOnLeave(): bool
    {
        return $this->status === 'On Leave';
    }

    /**
     * Check if faculty is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'Pending';
    }

    /**
     * Get the total number of students taught.
     */
    public function getTotalStudentsAttribute(): int
    {
        // This would need to be implemented based on your database structure
        // For example, counting students enrolled in this faculty's courses
        return 0; // Placeholder
    }

    /**
     * Get the average rating (if you have a rating system).
     */
    public function getAverageRatingAttribute(): float
    {
        // Implement based on your rating system
        return 0.0; // Placeholder
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate faculty_id when creating
        static::creating(function ($faculty) {
            if (empty($faculty->faculty_id)) {
                $lastFaculty = static::orderBy('id', 'desc')->first();
                $nextId = $lastFaculty ? $lastFaculty->id + 1 : 1;
                $faculty->faculty_id = 'FAC' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }

            // Set name from first and last name if not provided
            if (empty($faculty->name)) {
                $faculty->name = trim($faculty->first_name . ' ' . $faculty->middle_name . ' ' . $faculty->last_name);
            }
        });

        // Update name when first/last name changes
        static::updating(function ($faculty) {
            if ($faculty->isDirty('first_name') || $faculty->isDirty('middle_name') || $faculty->isDirty('last_name')) {
                $faculty->name = trim($faculty->first_name . ' ' . $faculty->middle_name . ' ' . $faculty->last_name);
            }
        });
    }
}