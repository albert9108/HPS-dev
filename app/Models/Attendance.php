<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'class', 'date', 'status', 'notes', 'recorded_by'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    // Status constants
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';
    const STATUS_EXCUSED = 'excused';

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id', 'student_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // Scope for filtering by date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Scope for filtering by class
    public function scopeByClass($query, $class)
    {
        return $query->where('class', $class);
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PRESENT => 'success',
            self::STATUS_ABSENT => 'danger',
            self::STATUS_LATE => 'warning',
            self::STATUS_EXCUSED => 'info',
            default => 'secondary'
        };
    }
}
