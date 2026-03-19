@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-4">📘 {{ $exam->title }}</h1>

<div class="overflow-x-auto bg-gray-800 rounded-xl">
<table class="w-full text-sm text-gray-300">
<thead class="bg-gray-900 text-gray-400">
<tr>
<th class="p-3">Name</th>
<th class="p-3">Reg No</th>
<th class="p-3">Email</th>
<th class="p-3">Score</th>
<th class="p-3">Action</th>
</tr>
</thead>

<tbody>
@foreach($attempts as $a)
<tr class="border-b border-gray-700 hover:bg-gray-700/40">
<td class="p-3">{{ $a->user->name }}</td>
<td class="p-3">{{ $a->user->registration_no }}</td>
<td class="p-3">{{ $a->user->email }}</td>
<td class="p-3">{{ $a->score ?? '—' }}</td>
<td class="p-3">
<a href="{{ route('admin.analysis.student.result', $a->id) }}"
   class="px-3 py-1 rounded bg-indigo-600 hover:bg-indigo-700 text-white text-xs">
   View
</a>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>

@endsection
