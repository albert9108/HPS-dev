<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserLogin extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';
    protected $fillable = [
        'student_id', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }
}
