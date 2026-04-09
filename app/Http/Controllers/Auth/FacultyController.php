<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FacultyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get all faculty members (users with role 'teacher')
        $faculty = User::where('role', 'teacher')->get();
        
        // Transform data for the view
        $facultyData = $faculty->map(function($member) {
            return [
                'id' => $member->id,
                'application_id' => 'FAC' . str_pad($member->id, 5, '0', STR_PAD_LEFT),
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
                'fullname' => $member->first_name . ' ' . $member->last_name,
                'initial' => strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name ?? '', 0, 1)),
                'avatar_color' => $this->getAvatarColor($member->id),
                'department' => $member->department ?? 'Not Assigned',
                'educational_background' => $member->educational_background ?? 'Not Specified',
                'status' => $member->status ?? 'Active',
                'status_color' => $this->getStatusColor($member->status ?? 'Active'),
                'date_hired' => $member->created_at ? $member->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                'email' => $member->email,
                'phone' => $member->phone ?? 'Not Provided',
            ];
        })->toArray();

        // Calculate statistics
        $totalFaculty = count($facultyData);
        $activeFaculty = count(array_filter($facultyData, fn($f) => $f['status'] === 'Active'));
        $inactiveFaculty = count(array_filter($facultyData, fn($f) => $f['status'] === 'Inactive'));
        
        // Get unique departments
        $departments = array_unique(array_column($facultyData, 'department'));
        $totalDepartments = count($departments);

        return view('auth.teacher', [
            'user' => $user,
            'faculty' => $facultyData,
            'totalFaculty' => $totalFaculty,
            'activeFaculty' => $activeFaculty,
            'inactiveFaculty' => $inactiveFaculty,
            'totalDepartments' => $totalDepartments,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string',
            'educational_background' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'date_hired' => 'nullable|date',
            'status' => 'required|in:Active,Inactive,Pending',
        ]);

        // Create user account
        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'educational_background' => $request->educational_background,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'date_hired' => $request->date_hired,
            'status' => $request->status,
            'role' => 'teacher',
            'password' => Hash::make('password123'), // Default password, should be changed on first login
        ]);

        // Also create teacher record if you have a separate teachers table
        if (class_exists('App\Models\Teacher')) {
            Teacher::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'department' => $request->department,
            ]);
        }

        return redirect()->route('teacher.dashboard')->with('success', 'Faculty member added successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string',
            'status' => 'required|in:Active,Inactive,Pending',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'status' => $request->status,
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Faculty member updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('teacher.dashboard')->with('success', 'Faculty member deleted successfully!');
    }

    private function getAvatarColor($id)
    {
        $colors = ['indigo', 'purple', 'blue', 'green', 'yellow', 'red', 'pink', 'cyan'];
        return $colors[$id % count($colors)];
    }

    private function getStatusColor($status)
    {
        return match($status) {
            'Active' => 'green',
            'Inactive' => 'red',
            'Pending' => 'yellow',
            default => 'gray',
        };
    }
}