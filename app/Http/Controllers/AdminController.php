<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'student_id' => 'required|string|unique:users,student_id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();

        try {
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'student_id' => $request->student_id,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_ADMIN,
                'is_active' => true,
                'class' => null, // Admins don't belong to a class
            ]);

            Log::info('Admin created successfully:', ['admin_id' => $admin->id]);

            DB::commit();

            return redirect()->route('admin.create')
                           ->with('success', 'Admin created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating admin:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                           ->withErrors(['error' => 'Failed to create admin. Please try again.'])
                           ->withInput();
        }
    }

    /**
     * Display a listing of admins.
     */
    public function index()
    {
        $admins = User::where('role', User::ROLE_ADMIN)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);

        return view('admin.index', compact('admins'));
    }

    /**
     * Show the form for changing admin password.
     */
    public function changePasswordForm($id)
    {
        $admin = User::where('role', User::ROLE_ADMIN)->findOrFail($id);
        return view('admin.change_password', compact('admin'));
    }

    /**
     * Change admin password.
     */
    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::where('role', User::ROLE_ADMIN)->findOrFail($id);

        $admin->update([
            'password' => Hash::make($request->password)
        ]);

        Log::info('Admin password changed:', ['admin_id' => $admin->id]);

        return redirect()->route('admin.index')
                       ->with('success', 'Admin password changed successfully!');
    }
}
