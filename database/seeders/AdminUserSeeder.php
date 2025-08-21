<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@harvestpastoralschool.org',
            'student_id' => 'HPSadmin1',
            'password' => Hash::make('!Q@W#E4r5t6y'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'class' => null, // Admins don't belong to a class
        ]);

        // Create a test student user
        $studentUser = User::create([
            'name' => 'Test Student',
            'email' => 'student@harvestpastoralschool.org',
            'student_id' => 'HPS001',
            'password' => Hash::make('student123'),
            'role' => User::ROLE_STUDENT,
            'is_active' => true,
            'class' => '第一班',
        ]);

        // Create corresponding student record
        Student::create([
            'student_id' => 'HPS001',
            'E_name' => 'Test Student',
            'C_name' => '測試學生',
            'start_date' => now()->subMonths(6)->format('Y-m-d'),
            'Cellgroup' => 'Group A',
            'class' => '第一班',
            'password' => 'student123', // Store unhashed password for visibility
        ]);

        $this->command->info('Admin user created: admin@harvestpastoralschool.org / !Q@W#E4r5t6y');
        $this->command->info('Student user created: student@harvestpastoralschool.org / student123');
    }
}
