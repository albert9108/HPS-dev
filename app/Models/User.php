<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // The table associated with the model.
    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'student_id', 'password', 'class', 'role', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_STUDENT = 'student';

    public function student()
    {
        return $this->hasOne(Student::class, 'student_id', 'student_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id', 'student_id');
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Check if user is student
    public function isStudent()
    {
        return $this->role === self::ROLE_STUDENT;
    }

    // Get display name
    public function getDisplayNameAttribute()
    {
        return $this->name ?: $this->student_id;
    }

    // Get class folder path for file management
    public function getClassFolderAttribute()
    {
        return $this->class ?: 'default';
    }
}
