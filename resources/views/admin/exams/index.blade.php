@extends('layouts.admin')



@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Manage Exams</h1>

    <a href="{{ route('admin.exams.create') }}"
       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
       + Create Exam
    </a>
</div>

@if(session('success'))
    <div class="bg-green-600/20 text-green-400 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form method="GET" action="{{ route('admin.exams.index') }}" class="mb-4 flex flex-wrap items-center gap-3">
    <input
        type="text"
        name="search"
        value="{{ $search ?? '' }}"
        placeholder="Search exam by title or subject"
        class="w-full md:w-80 px-3 py-2 rounded bg-gray-900/80 border border-gray-600 text-gray-100 placeholder:text-gray-400"
    >

    <select
        name="status"
        class="px-3 py-2 rounded bg-gray-900/80 border border-gray-600 text-gray-100"
    >
        <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>All Status</option>
        <option value="draft" {{ ($status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="published" {{ ($status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
    </select>

    <button type="submit" class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white">
        Apply
    </button>

    <a href="{{ route('admin.exams.index') }}" class="px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 text-white">
        Reset
    </a>
</form>

<div class="bg-gray-800 shadow rounded overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-700 text-gray-300">
            <tr>
                <th class="p-3 text-left">Title</th>
                <th class="p-3 text-center">Questions</th>
                <th class="p-3 text-center">Duration</th>
                <th class="p-3 text-center">Status</th>
                <th class="p-3 text-center">Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($exams as $exam)
                <tr class="border-t border-gray-700 hover:bg-gray-700/40">
                    <td class="p-3">
                        <div class="font-semibold">{{ $exam->title }}</div>
                        <div class="text-sm text-gray-400">{{ $exam->subject }}</div>
                    </td>

                    <td class="p-3 text-center">
                        {{ $exam->questions_count ?? $exam->questions->count() }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $exam->duration_minutes }} mins
                    </td>

                    <td class="p-3 text-center">
                        <span class="px-2 py-1 rounded text-white text-xs
                            {{ $exam->status === 'published'
                                ? 'bg-green-600'
                                : 'bg-yellow-600'
                            }}">
                            {{ ucfirst($exam->status) }}
                        </span>
                    </td>

                    <td class="p-3">
                        <div class="flex justify-center gap-2 flex-wrap">

                            {{-- View --}}
                            <a href="{{ route('admin.exams.show', $exam) }}"
                               class="admin-action-btn bg-gray-600 hover:bg-gray-500 text-white">
                               View
                            </a>

                            {{-- Edit --}}
                            <a href="{{ route('admin.exams.edit', $exam) }}"
                               class="admin-action-btn bg-gray-600 hover:bg-gray-500 text-white">
                               Edit
                            </a>

                            {{-- Publish / Unpublish --}}
                            <form action="{{ route('admin.exams.toggle_publish', $exam) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="event.preventDefault(); appConfirm('{{ $exam->status === 'published' ? 'Are you sure you want to unpublish this exam?' : 'Are you sure you want to publish this exam?' }}', { title: '{{ $exam->status === 'published' ? 'Unpublish Exam' : 'Publish Exam' }}', confirmText: '{{ $exam->status === 'published' ? 'Unpublish' : 'Publish' }}' }).then(confirmed => { if (confirmed) this.submit(); });">
                                @csrf
                                <button class="admin-action-btn text-white
                                    {{ $exam->status === 'published'
                                        ? 'bg-yellow-600 hover:bg-yellow-700'
                                        : 'bg-green-600 hover:bg-green-700'
                                    }}">
                                    {{ $exam->status === 'published'
                                        ? 'Unpublish'
                                        : 'Publish'
                                    }}
                                </button>
                            </form>

                            {{-- Delete --}}
                            <form action="{{ route('admin.exams.destroy', $exam) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="event.preventDefault(); appConfirm('Are you sure you want to delete this exam?', { title: 'Delete Exam', confirmText: 'Delete' }).then(confirmed => { if (confirmed) this.submit(); });">
                                @csrf
                                @method('DELETE')
                                <button class="admin-action-btn bg-red-600 hover:bg-red-700 text-white">
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-400">
                        No exams found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $exams->links() }}
</div>

@endsection
