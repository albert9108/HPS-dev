<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Exception;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function create()
    {
        return view('add_student');
    }

    public function store(Request $request)
    {
        // Log the received form content
        Log::info('Received form content:', $request->all());

        // Validate the request
        $request->validate([
            'user_id' => 'required|string|unique:students,student_id', // Validate user_id for uniqueness in students table
            'E_name' => 'required|string|max:255',
            'C_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'Cellgroup' => 'required|string|max:255',
            'class' => 'required|string|max:255', // Add validation for class
            'password' => 'required|string|min:8',
        ]);

        // Concatenate the prefix with the user_id
        $user_id = 'HPS' . $request->user_id;

        DB::beginTransaction();

        try {
            // Create the user
            $user = User::create([
                'student_id' => $user_id,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_STUDENT,
                'is_active' => true,
                'class' => $request->class,
            ]);

            // Log user creation success
            Log::info('User created successfully:', ['user_id' => $user->id]);

            // Create the student
            $student = Student::create([
                'student_id' => $user_id, // Use the concatenated user_id
                'E_name' => $request->E_name,
                'C_name' => $request->C_name,
                'start_date' => $request->start_date,
                'Cellgroup' => $request->Cellgroup,
                'class' => $request->class, // Store the class
                'password' => $request->password, // Store unhashed password for visibility
            ]);

            // Log student creation success
            Log::info('Student added successfully:', ['student_id' => $student->student_id]);

            DB::commit();

            return redirect()->back()->with('status', 'Student added successfully!');
        } catch (QueryException $e) {
            DB::rollBack();

            // Log the error with detailed information
            Log::error('Database error adding student:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Check for unique constraint violation
            if ($e->getCode() == 23000) {
                return redirect()->back()->withErrors(['error' => 'The user ID already exists. Please use a different ID.']);
            }

            return redirect()->back()->withErrors(['error' => 'Database error. Please try again.']);
        } catch (Exception $e) {
            DB::rollBack();

            // Log the error with detailed information
            Log::error('Error adding student:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withErrors(['error' => 'Failed to add student. Please try again.']);
        }
    }

    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'student_id');
        $sortOrder = $request->get('sort_order', 'asc');
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $query = Student::query();

        if ($search) {
            $query->where('student_id', 'like', "%{$search}%")
                  ->orWhere('E_name', 'like', "%{$search}%")
                  ->orWhere('C_name', 'like', "%{$search}%")
                  ->orWhere('Cellgroup', 'like', "%{$search}%");
        }

        $students = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return view('view_student', compact('students', 'sortBy', 'sortOrder', 'perPage', 'search'));
    }

    public function export(Request $request)
    {
        $sortBy = $request->get('sort_by', 'student_id');
        $sortOrder = $request->get('sort_order', 'asc');
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');

        $query = Student::query();

        if ($search) {
            $query->where('student_id', 'like', "%{$search}%")
                  ->orWhere('E_name', 'like', "%{$search}%")
                  ->orWhere('C_name', 'like', "%{$search}%")
                  ->orWhere('Cellgroup', 'like', "%{$search}%");
        }

        return Excel::download(new StudentsExport($query), 'students.xlsx');
    }

    public function show($user_id)
    {
        $student = Student::where('student_id', $user_id)->firstOrFail();

        return view('view_profile', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit($user_id)
    {
        $student = Student::where('student_id', $user_id)->firstOrFail();
        return view('edit_student', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, $user_id)
    {
        $request->validate([
            'E_name' => 'required|string|max:255',
            'C_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'Cellgroup' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        DB::beginTransaction();

        try {
            $student = Student::where('student_id', $user_id)->firstOrFail();
            $user = User::where('student_id', $user_id)->firstOrFail();

            // Update student record
            $student->update([
                'E_name' => $request->E_name,
                'C_name' => $request->C_name,
                'start_date' => $request->start_date,
                'Cellgroup' => $request->Cellgroup,
                'class' => $request->class,
                'password' => $request->password ?: $student->password, // Keep old password if not provided
            ]);

            // Update user record
            $updateData = [
                'class' => $request->class,
            ];

            // Only update password if provided
            if ($request->password) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            Log::info('Student updated successfully:', ['student_id' => $student->student_id]);

            DB::commit();

            return redirect()->route('students.show', $user_id)
                           ->with('success', 'Student updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error updating student:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                           ->withErrors(['error' => 'Failed to update student. Please try again.'])
                           ->withInput();
        }
    }

    /**
     * Show the form for changing student password.
     */
    public function changePasswordForm($user_id)
    {
        $student = Student::where('student_id', $user_id)->firstOrFail();
        return view('students.change_password', compact('student'));
    }

    /**
     * Change student password.
     */
    public function changePassword(Request $request, $user_id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $student = Student::where('student_id', $user_id)->firstOrFail();
            $user = User::where('student_id', $user_id)->firstOrFail();

            // Update both student and user records
            $student->update(['password' => $request->password]); // Unhashed for visibility
            $user->update(['password' => Hash::make($request->password)]); // Hashed for authentication

            Log::info('Student password changed:', ['student_id' => $student->student_id]);

            DB::commit();

            return redirect()->route('students.show', $user_id)
                           ->with('success', 'Student password changed successfully!');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error changing student password:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                           ->withErrors(['error' => 'Failed to change password. Please try again.']);
        }
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $sortBy = $request->get('sort_by', 'student_id');
        $sortOrder = $request->get('sort_order', 'asc');
        $perPage = $request->get('per_page', 10);

        $query = Student::query();

        if ($search) {
            $query->where('student_id', 'like', "%{$search}%")
                  ->orWhere('E_name', 'like', "%{$search}%")
                  ->orWhere('C_name', 'like', "%{$search}%")
                  ->orWhere('Cellgroup', 'like', "%{$search}%");
        }

        $students = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return view('partials.student_table', compact('students', 'sortBy', 'sortOrder', 'perPage', 'search'))->render();
    }

    /**
     * Show the confirmation form for deleting a student.
     */
    public function deleteConfirm($user_id)
    {
        $student = Student::where('student_id', $user_id)->firstOrFail();
        return view('students.delete_confirm', compact('student'));
    }

    /**
     * Delete the specified student from storage.
     */
    public function destroy($user_id)
    {
        DB::beginTransaction();

        try {
            $student = Student::where('student_id', $user_id)->firstOrFail();
            $user = User::where('student_id', $user_id)->first();

            // Store student info for logging
            $studentInfo = [
                'student_id' => $student->student_id,
                'name' => $student->E_name . ' (' . $student->C_name . ')',
                'class' => $student->class
            ];

            // Delete the student record
            $student->delete();

            // Delete the user record if it exists
            if ($user) {
                $user->delete();
            }

            Log::info('Student deleted successfully:', $studentInfo);

            DB::commit();

            return redirect()->route('students.index')
                           ->with('success', 'Student "' . $studentInfo['name'] . '" has been deleted successfully!');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error deleting student:', [
                'student_id' => $user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                           ->withErrors(['error' => 'Failed to delete student. Please try again.']);
        }
    }
}
