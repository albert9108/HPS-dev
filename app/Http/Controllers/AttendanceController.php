<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $class = $request->get('class', '');

        $classes = Student::distinct()->pluck('class');

        $query = Attendance::with(['student', 'recorder'])
                          ->whereHas('student') // Only include attendance records for existing students
                          ->where('date', $date);

        if ($class) {
            $query->where('class', $class);
        }

        $attendances = $query->orderBy('student_id')->get();

        return view('attendance.index', compact('attendances', 'classes', 'date', 'class'));
    }

    public function create(Request $request)
    {
        $date = $request->get('date', now()->format('Y-m-d'));
        $class = $request->get('class', '第一班');

        $classes = Student::distinct()->pluck('class');
        $students = Student::where('class', $class)->orderBy('student_id')->get();

        // Get existing attendance for this date and class
        $existingAttendance = Attendance::where('date', $date)
                                      ->where('class', $class)
                                      ->pluck('status', 'student_id');

        return view('attendance.create', compact('students', 'classes', 'date', 'class', 'existingAttendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'class' => 'required|string',
            'attendance' => 'required|array',
        ]);

        // Ensure date is properly formatted (date only, no time)
        $date = Carbon::parse($request->date)->format('Y-m-d');

        try {
            DB::beginTransaction();

            foreach ($request->attendance as $studentId => $status) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'date' => $date,
                    ],
                    [
                        'class' => $request->class,
                        'status' => $status,
                        'notes' => $request->notes[$studentId] ?? null,
                        'recorded_by' => Auth::id(),
                    ]
                );
            }

            DB::commit();

            return redirect()->route('attendance.index', [
                'date' => $date,
                'class' => $request->class
            ])->with('success', 'Attendance recorded successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error recording attendance:', [
                'error' => $e->getMessage(),
                'date' => $date,
                'class' => $request->class,
                'student_count' => count($request->attendance)
            ]);

            return redirect()->back()
                           ->withErrors(['error' => 'Failed to record attendance. Please try again.'])
                           ->withInput();
        }
    }

    public function statistics(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $class = $request->get('class', '');

        $classes = Student::distinct()->pluck('class');

        // Overall statistics - only include records for existing students
        $query = Attendance::whereHas('student')
                          ->dateRange($startDate, $endDate);
        if ($class) {
            $query->byClass($class);
        }

        $totalRecords = $query->count();
        $presentCount = $query->where('status', 'present')->count();
        $absentCount = $query->where('status', 'absent')->count();
        $lateCount = $query->where('status', 'late')->count();
        $excusedCount = $query->where('status', 'excused')->count();

        // Student-wise statistics - only include records for existing students
        $studentStats = Attendance::with('student')
                                 ->whereHas('student') // Only include attendance records for existing students
                                 ->dateRange($startDate, $endDate)
                                 ->when($class, fn($q) => $q->byClass($class))
                                 ->selectRaw('student_id,
                                           COUNT(*) as total_days,
                                           SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_days,
                                           SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absent_days,
                                           SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as late_days,
                                           SUM(CASE WHEN status = "excused" THEN 1 ELSE 0 END) as excused_days')
                                 ->groupBy('student_id')
                                 ->get();

        return view('attendance.statistics', compact(
            'classes', 'class', 'startDate', 'endDate',
            'totalRecords', 'presentCount', 'absentCount', 'lateCount', 'excusedCount',
            'studentStats'
        ));
    }
}
