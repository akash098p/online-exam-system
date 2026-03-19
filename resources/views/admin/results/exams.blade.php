@extends('layouts.admin')

@section('content')

<h2 class="text-2xl font-bold mb-6">Completed Exams</h2>

<form method="GET" action="{{ route('admin.results.index') }}" class="mb-4 flex flex-wrap items-center gap-3">
    <input
        type="text"
        name="search"
        value="{{ $search ?? '' }}"
        placeholder="Search exam by title or subject"
        class="w-full md:w-80 px-3 py-2 rounded bg-gray-900/80 border border-gray-600 text-gray-100 placeholder:text-gray-400"
    >
    <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">
        Search
    </button>
    <a href="{{ route('admin.results.index') }}" class="px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 text-white">
        Reset
    </a>
</form>

<table class="table-auto w-full border border-gray-700 rounded-lg overflow-hidden">
    <thead class="bg-gray-800">
        <tr>
            <th class="p-3 text-left">Exam</th>
            <th class="p-3">Attempts</th>
            <th class="p-3">Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($exams as $exam)
        <tr class="border-t border-gray-700 hover:bg-gray-800">
            <td class="p-3">{{ $exam->title }}</td>
            <td class="p-3 text-center">{{ $exam->results_count }}</td>
            <td class="p-3 text-center">
                <a href="{{ route('admin.results.show', $exam->id) }}"
                   class="px-4 py-1 bg-indigo-600 rounded hover:bg-indigo-700">
                    View Students
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="p-4 text-center text-gray-400">
                No completed exams yet.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
