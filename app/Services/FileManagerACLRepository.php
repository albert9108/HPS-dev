<?php

namespace App\Services;

use Alexusmai\LaravelFileManager\Services\ACLService\ACLRepository;
use Illuminate\Support\Facades\Auth;

class FileManagerACLRepository implements ACLRepository
{
    /**
     * Get user ID
     *
     * @return mixed
     */
    public function getUserID()
    {
        return Auth::id();
    }

    /**
     * Get ACL rules for user
     *
     * @return array
     */
    public function getRules(): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        // Admin users have full access
        if ($user->role === 'admin') {
            return [
                ['disk' => 'public', 'path' => '/', 'access' => 2], // Full read/write access
            ];
        }

        // Student users have limited access
        if ($user->role === 'student') {
            $currentYear = $this->getCurrentAcademicYear();

            // Get student class from the students table
            $student = \App\Models\Student::where('student_id', $user->student_id)->first();
            $studentClass = $student ? $student->class : 'default';

            return [
                // Access to current year folder
                ['disk' => 'public', 'path' => $currentYear, 'access' => 1], // Read access to year folder
                ['disk' => 'public', 'path' => $currentYear . '/*', 'access' => 1], // Read access to subfolders

                // Access to their specific class folder
                ['disk' => 'public', 'path' => $currentYear . '/' . $studentClass, 'access' => 1],
                ['disk' => 'public', 'path' => $currentYear . '/' . $studentClass . '/*', 'access' => 1],

                // Access to shared folder if it exists
                ['disk' => 'public', 'path' => 'shared', 'access' => 1],
                ['disk' => 'public', 'path' => 'shared/*', 'access' => 1],
            ];
        }

        // Default: no access
        return [];
    }

    /**
     * Get current academic year
     *
     * @return string
     */
    private function getCurrentAcademicYear(): string
    {
        $currentMonth = date('n');
        $currentYear = date('Y');

        // Academic year starts in September (month 9)
        if ($currentMonth >= 9) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }
}
