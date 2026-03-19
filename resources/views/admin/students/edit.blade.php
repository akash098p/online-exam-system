
@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">✏ Edit Student Profile</h1>

<div class="max-w-4xl bg-gray-800 rounded-xl p-8 shadow">

@if ($errors->any())
<div class="mb-4 p-4 bg-red-600 text-white rounded">
<ul class="list-disc list-inside text-sm">
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form method="POST" action="{{ route('admin.students.update', $student->id) }}" class="space-y-6" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="grid md:grid-cols-2 gap-6">

<div>
<label class="block text-gray-300 mb-1">Full Name</label>
<input type="text" name="name"
value="{{ old('name', $student->name) }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">Email</label>
<input type="email" name="email"
value="{{ old('email', $student->email) }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">College Name</label>
<input type="text" name="college_name"
value="{{ old('college_name', $student->college_name) }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">Registration Number</label>
<input type="text" name="registration_no"
value="{{ old('registration_no', $student->registration_no) }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">Semester</label>
<select name="semester"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
<option value="1st" {{ old('semester', $student->semester)=='1st'?'selected':'' }}>1st</option>
<option value="2nd" {{ old('semester', $student->semester)=='2nd'?'selected':'' }}>2nd</option>
<option value="3rd" {{ old('semester', $student->semester)=='3rd'?'selected':'' }}>3rd</option>
<option value="4th" {{ old('semester', $student->semester)=='4th'?'selected':'' }}>4th</option>
<option value="5th" {{ old('semester', $student->semester)=='5th'?'selected':'' }}>5th</option>
<option value="6th" {{ old('semester', $student->semester)=='6th'?'selected':'' }}>6th</option>
</select>
</div>

<div>
<label class="block text-gray-300 mb-1">Contact Number</label>
<input type="text" name="phone"
value="{{ old('phone', $student->phone) }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">Gender</label>
<select name="sex"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
<option value="male" {{ strtolower((string) old('sex', $student->sex))=='male'?'selected':'' }}>Male</option>
<option value="female" {{ strtolower((string) old('sex', $student->sex))=='female'?'selected':'' }}>Female</option>
</select>
</div>

<div class="md:col-span-2">
<label class="block text-gray-300 mb-1">Profile Photo</label>
<div class="flex items-center gap-4">
    <img src="{{ $student->profilePhotoUrl() }}" alt="{{ $student->name }}"
         class="w-16 h-16 rounded-full object-cover border border-white/20">
    <div class="flex-1">
        <input type="file" name="profile_photo"
               class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200">
        @if($student->profile_photo)
            <label class="mt-2 inline-flex items-center gap-2 text-sm text-gray-300">
                <input type="checkbox" name="remove_profile_photo" value="1" class="control-check">
                Remove current photo
            </label>
        @endif
    </div>
</div>
</div>

<!-- 🔐 PASSWORD RESET SECTION -->

<div class="md:col-span-2 border-t border-gray-700 pt-6 mt-4">
<h2 class="text-lg font-semibold text-gray-200 mb-4">
Reset Password (Optional)
</h2>

<div class="grid md:grid-cols-2 gap-6">

<div>
<label class="block text-gray-300 mb-1">
New Password
</label>
<input type="password" name="password"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200"
placeholder="Leave blank if no change">
</div>

<div>
<label class="block text-gray-300 mb-1">
Confirm New Password
</label>
<input type="password" name="password_confirmation"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200"
placeholder="Re-enter new password">
</div>

</div>
</div>

</div>

<div class="flex gap-4 pt-4">
<button type="submit"
class="px-6 py-2 bg-indigo-600 rounded text-white hover:bg-indigo-700 transition">
Update Student
</button>

<a href="{{ route('admin.students.index') }}"
class="px-6 py-2 bg-gray-600 rounded text-white hover:bg-gray-700 transition">
Cancel
</a>
</div>

</form>

</div>

@endsection
