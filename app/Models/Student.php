<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    // The table associated with the model.
    protected $table = 'students';

    protected $fillable = [
        'E_name', 'C_name', 'start_date', 'Cellgroup', 'student_id', 'password', 'class',
    ];

    // The primary key associated with the table

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
