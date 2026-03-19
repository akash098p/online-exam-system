
@extends('layouts.admin')                       

@section('content')

<h1 class="text-3xl font-bold mb-6">🎓 Student Management</h1>

<!-- Top Bar -->
<div class="flex flex-wrap items-center gap-3 mb-4">

    <!-- Filter Form -->
    <form method="GET" class="flex flex-wrap items-center gap-3">
        <input type="text" name="search" placeholder="Search name, email, reg no"
        value="{{ request('search') }}"
        class="p-2 rounded bg-gray-800 border border-gray-700 text-gray-200">

        <select name="semester"
        class="admin-semester-select p-2 rounded bg-gray-800 border border-gray-700 text-gray-200">
            <option value="">All semesters</option>
            <option value="1st" {{ request('semester')=='1st'?'selected':'' }}>1st</option>
            <option value="2nd" {{ request('semester')=='2nd'?'selected':'' }}>2nd</option>
            <option value="3rd" {{ request('semester')=='3rd'?'selected':'' }}>3rd</option>
            <option value="4th" {{ request('semester')=='4th'?'selected':'' }}>4th</option>
            <option value="5th" {{ request('semester')=='5th'?'selected':'' }}>5th</option>
            <option value="6th" {{ request('semester')=='6th'?'selected':'' }}>6th</option>
        </select>

        <button class="px-4 py-2 bg-indigo-600 rounded text-white">
            Filter
        </button>
    </form>

    <!-- Add Student Button -->
    <a href="{{ route('admin.students.create') }}"
       class="px-4 py-2 bg-green-600 rounded text-white ml-auto">
        + Add Student
    </a>

</div>

<div class="overflow-x-auto bg-gray-800 rounded-xl shadow">
<table class="w-full text-sm text-gray-300">
<thead class="bg-gray-900 text-gray-200">
<tr>
<th class="p-3 text-left">Name</th>
<th class="p-3 text-left">Reg No</th>
<th class="p-3 text-left">Email</th>
<th class="p-3 text-left">Semester</th>
<th class="p-3 text-left">Status</th>
<th class="p-3 text-left">Action</th>
</tr>
</thead>

<tbody>
@foreach($students as $s)
<tr class="border-b border-gray-700 hover:bg-gray-700/40 transition">
<td class="p-3">
    <div class="flex items-center gap-2">
        <img src="{{ $s->profilePhotoUrl() }}" alt="{{ $s->name }}" class="w-8 h-8 rounded-full object-cover border border-white/20">
        <span>{{ $s->name }}</span>
    </div>
</td>
<td class="p-3">{{ $s->registration_no }}</td>
<td class="p-3">{{ $s->email }}</td>
<td class="p-3">{{ $s->semester }}</td>

<td class="p-3">
@if($s->trashed())
<span class="px-2 py-1 text-xs bg-gray-600 rounded">Deleted From App</span>
@elseif($s->is_blocked)
<span class="px-2 py-1 text-xs bg-red-600 rounded">Blocked</span>
@else
<span class="px-2 py-1 text-xs bg-green-600 rounded">Active</span>
@endif
</td>

<td class="p-3 flex flex-wrap gap-2">

<!-- View -->
<a href="{{ route('admin.students.show',$s->id) }}"
class="admin-action-btn bg-blue-600 text-white">
View
</a>

<!-- Edit -->
<a href="{{ route('admin.students.edit',$s->id) }}"
class="admin-action-btn bg-indigo-600 text-white">
Edit
</a>

<!-- Block / Unblock -->
<form method="POST" action="{{ route('admin.students.toggle',$s->id) }}"
    x-data
    @submit.prevent="appConfirm('Are you sure you want to change student status?', { title: 'Change Student Status', confirmText: 'Yes, Continue' }).then(confirmed => { if (confirmed) $el.submit(); });">
    @csrf
    <button type="submit"
        {{ $s->trashed() ? 'disabled' : '' }}
        class="admin-action-btn bg-yellow-600 text-white hover:bg-yellow-700 transition">
        Block/Unblock
    </button>
</form>

<!-- Delete -->
<form method="POST" action="{{ route('admin.students.destroy',$s->id) }}"
    x-data
    @submit.prevent="appConfirm('{{ $s->trashed() ? 'Restore this student account to app access?' : 'Remove this student from app access? Their database records will stay.' }}', { title: '{{ $s->trashed() ? 'Restore Student' : 'Remove Student' }}', confirmText: '{{ $s->trashed() ? 'Restore' : 'Remove' }}' }).then(confirmed => { if (confirmed) $el.submit(); });">
    @csrf 
    @method('DELETE')
    <button type="submit"
        class="admin-action-btn bg-red-600 text-white hover:bg-red-700 transition">
        {{ $s->trashed() ? 'Restore' : 'Delete' }}
    </button>
</form>

</td>
</tr>
@endforeach
</tbody>
</table>
</div>

<div class="mt-4">
{{ $students->links() }}
</div>

@endsection
