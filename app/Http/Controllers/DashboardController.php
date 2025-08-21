<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->studentDashboard();
        }
    }

    private function adminDashboard()
    {
        // Student Statistics
        $totalStudents = Student::count();
        $activeStudents = User::where('role', 'student')->where('is_active', true)->count();
        $studentsByClass = Student::selectRaw('class, COUNT(*) as count')
                                 ->groupBy('class')
                                 ->pluck('count', 'class');

        // Attendance Statistics (Last 30 days)
        $startDate = now()->subDays(30);
        $endDate = now();

        $attendanceStats = [
            'total_records' => Attendance::dateRange($startDate, $endDate)->count(),
            'present' => Attendance::dateRange($startDate, $endDate)->where('status', 'present')->count(),
            'absent' => Attendance::dateRange($startDate, $endDate)->where('status', 'absent')->count(),
            'late' => Attendance::dateRange($startDate, $endDate)->where('status', 'late')->count(),
            'excused' => Attendance::dateRange($startDate, $endDate)->where('status', 'excused')->count(),
        ];

        // Recent Attendance (Today)
        $todayAttendance = Attendance::with('student')
                                   ->whereHas('student') // Only include attendance records for existing students
                                   ->where('date', now()->format('Y-m-d'))
                                   ->orderBy('created_at', 'desc')
                                   ->limit(10)
                                   ->get();

        // Blog/Post Statistics
        $postStats = [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'drafts' => Post::where('status', 'draft')->count(),
        ];

        // Recent Posts
        $recentPosts = Post::with('author')
                          ->orderBy('created_at', 'desc')
                          ->limit(5)
                          ->get();

        // Weekly Attendance Chart Data
        $weeklyAttendance = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $weeklyAttendance[] = [
                'date' => $date->format('M d'),
                'present' => Attendance::where('date', $date->format('Y-m-d'))
                                     ->where('status', 'present')
                                     ->count(),
                'absent' => Attendance::where('date', $date->format('Y-m-d'))
                                    ->where('status', 'absent')
                                    ->count(),
            ];
        }

        return view('dashboard.admin', compact(
            'totalStudents', 'activeStudents', 'studentsByClass',
            'attendanceStats', 'todayAttendance', 'postStats',
            'recentPosts', 'weeklyAttendance'
        ));
    }

    private function studentDashboard()
    {
        $user = Auth::user();
        $student = $user->student;

        // Student's attendance history (Last 30 days)
        $myAttendance = Attendance::where('student_id', $user->student_id)
                                 ->dateRange(now()->subDays(30), now())
                                 ->orderBy('date', 'desc')
                                 ->get();

        // Attendance summary
        $attendanceSummary = [
            'total_days' => $myAttendance->count(),
            'present_days' => $myAttendance->where('status', 'present')->count(),
            'absent_days' => $myAttendance->where('status', 'absent')->count(),
            'late_days' => $myAttendance->where('status', 'late')->count(),
            'excused_days' => $myAttendance->where('status', 'excused')->count(),
        ];

        $attendancePercentage = $attendanceSummary['total_days'] > 0
            ? round(($attendanceSummary['present_days'] / $attendanceSummary['total_days']) * 100, 1)
            : 0;

        // Recent posts for student
        $recentPosts = Post::published()
                          ->orderBy('published_at', 'desc')
                          ->limit(5)
                          ->get();

        // Class information
        $classmates = Student::where('class', $student->class ?? '第一班')
                            ->where('student_id', '!=', $user->student_id)
                            ->count();

        return view('dashboard.student', compact(
            'student', 'myAttendance', 'attendanceSummary',
            'attendancePercentage', 'recentPosts', 'classmates'
        ));
    }
}
