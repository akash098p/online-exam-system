<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // 📘 All students list
    public function index(Request $request)
    {
        $query = User::withTrashed()->where('role', 'student');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('registration_no', 'like', "%{$request->search}%");
            });
        }

        if ($request->semester) {
            $query->where('semester', $request->semester);
        }

        $students = $query->latest()->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    // ➕ Show create student form
    public function create()
    {
        return view('admin.students.create');
    }

    // 💾 Store new student (FULL REGISTRATION STYLE)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'college_name' => 'required|string|max:255',
            'registration_no' => 'required|unique:users,registration_no',
            'semester' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'sex' => 'required|in:male,female',
            'password' => 'required|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'college_name' => $request->college_name,
            'registration_no' => $request->registration_no,
            'semester' => $request->semester,
            'phone' => $request->phone,
            'sex' => strtolower($request->sex),
            'role' => 'student',
            'password' => Hash::make($request->password),
        ];

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        User::create($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student added successfully.');
    }

    // 👤 Student full profile
    public function show($id)
    {
        $student = User::withTrashed()->where('role', 'student')->findOrFail($id);

        $results = Result::where('user_id', $id)
            ->with('exam')
            ->latest()
            ->get();

        return view('admin.students.show', compact('student', 'results'));
    }

    // ✏ Edit student form
    public function edit($id)
    {
        $student = User::withTrashed()->where('role', 'student')->findOrFail($id);
        return view('admin.students.edit', compact('student'));
    }

    // 🔄 Update student (INCLUDING PASSWORD RESET)
    public function update(Request $request, $id)
    {
        $student = User::withTrashed()->where('role', 'student')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'college_name' => 'required|string|max:255',
            'registration_no' => 'required|unique:users,registration_no,' . $id,
            'semester' => 'required|string|max:20',
            'phone' => 'required|string|max:20',
            'sex' => 'required|in:male,female',
            'password' => 'nullable|min:6|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'remove_profile_photo' => 'nullable|boolean',
        ]);

        $student->name = $request->name;
        $student->email = $request->email;
        $student->college_name = $request->college_name;
        $student->registration_no = $request->registration_no;
        $student->semester = $request->semester;
        $student->phone = $request->phone;
        $student->sex = strtolower($request->sex);

        if ($request->boolean('remove_profile_photo') && $student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);
            $student->profile_photo = null;
        }

        if ($request->hasFile('profile_photo')) {
            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }
            $student->profile_photo = $request->file('profile_photo')->store('profiles', 'public');
        }

        // ✅ Only update password if admin entered new one
        if ($request->filled('password')) {
            $student->password = Hash::make($request->password);
        }

        $student->save();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    // 🚫 Block / unblock student
    public function toggleStatus($id)
    {
        $student = User::withTrashed()->findOrFail($id);

        if ($student->trashed()) {
            return back()->with('success', 'Restore the student account before changing blocked status.');
        }

        $student->is_blocked = !$student->is_blocked;
        $student->save();

        return back()->with('success', 'Student status updated.');
    }

    // 🗑 Delete student
    public function destroy($id)
    {
        $student = User::withTrashed()->findOrFail($id);

        if ($student->trashed()) {
            $student->restore();

            return back()->with('success', 'Student account restored.');
        }

        $student->delete();

        return back()->with('success', 'Student removed from app access.');
    }
}
