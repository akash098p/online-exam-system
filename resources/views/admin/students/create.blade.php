
@extends('layouts.admin')   

@section('content')

<h1 class="text-3xl font-bold mb-6">➕ Register New Student</h1>

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

<form method="POST" action="{{ route('admin.students.store') }}" class="space-y-6" enctype="multipart/form-data">
@csrf

<div class="grid md:grid-cols-2 gap-6">

<div>
<label class="block text-gray-300 mb-1">Full Name</label>
<input type="text" name="name" value="{{ old('name') }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">Email</label>
<input type="email" name="email" value="{{ old('email') }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">College Name</label>
<input type="text" name="college_name" value="{{ old('college_name') }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">Registration Number</label>
<input type="text" name="registration_no" value="{{ old('registration_no') }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">Semester</label>
<select name="semester"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
<option value="">Select Semester</option>
<option value="1st" {{ old('semester')=='1st'?'selected':'' }}>1st</option>
<option value="2nd" {{ old('semester')=='2nd'?'selected':'' }}>2nd</option>
<option value="3rd" {{ old('semester')=='3rd'?'selected':'' }}>3rd</option>
<option value="4th" {{ old('semester')=='4th'?'selected':'' }}>4th</option>
<option value="5th" {{ old('semester')=='5th'?'selected':'' }}>5th</option>
<option value="6th" {{ old('semester')=='6th'?'selected':'' }}>6th</option>
</select>
</div>

<div>
<label class="block text-gray-300 mb-1">Contact Number</label>
<input type="text" name="phone" value="{{ old('phone') }}"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">Gender</label>
<select name="sex"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
<option value="">Select Gender</option>
<option value="male" {{ old('sex')=='male'?'selected':'' }}>Male</option>
<option value="female" {{ old('sex')=='female'?'selected':'' }}>Female</option>
</select>
</div>

<div class="md:col-span-2">
<label class="block text-gray-300 mb-1">Profile Photo (Optional)</label>
<input type="file" name="profile_photo"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200">
</div>

<div>
<label class="block text-gray-300 mb-1">Password</label>
<input type="password" name="password"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

<div>
<label class="block text-gray-300 mb-1">Confirm Password</label>
<input type="password" name="password_confirmation"
class="w-full p-2 rounded bg-gray-900 border border-gray-700 text-gray-200" required>
</div>

</div>

<div class="flex gap-4 pt-4">
<button type="submit"
class="px-6 py-2 bg-green-600 rounded text-white hover:bg-green-700 transition">
Register Student
</button>

<a href="{{ route('admin.students.index') }}"
class="px-6 py-2 bg-gray-600 rounded text-white hover:bg-gray-700 transition">
Cancel
</a>
</div>

</form>

</div>

@endsection
