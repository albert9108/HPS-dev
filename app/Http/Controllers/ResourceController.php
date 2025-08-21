<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ResourceController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        $class = $student->class;
        $directory = public_path('resources/' . $class);

        // Create the directory if it doesn't exist
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $files = File::files($directory);
        $directories = File::directories($directory);

        return view('home', compact('files', 'directories', 'class'));
    }
}
