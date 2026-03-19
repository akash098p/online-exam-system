@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-4">
    Questions for: {{ $exam->title }}
</h1>

<div class="mb-4">
    <a href="{{ route('admin.exams.show', $exam) }}" class="admin-action-btn bg-yellow-600 text-white">
        Back
    </a>
</div>

<div class="mb-4">
    <a href="{{ route('admin.questions.create', $exam) }}"
       class="admin-action-btn bg-blue-600 text-white">
        + Add Question
    </a>
</div>

<table class="w-full bg-white dark:bg-gray-800 rounded shadow">
    <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
            <th class="p-3 text-left">Question</th>
            <th class="p-3">Options</th>
            <th class="p-3">Correct Answer</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($questions as $q)
        <tr class="border-t">
            <td class="p-3 text-gray-900 dark:text-gray-100">{{ $q->question_text }}</td>

            <td class="p-3 text-gray-900 dark:text-gray-100">
                {{ $q->options_count }}
            </td>

            <td class="p-3">
                @foreach($q->options as $op)
                    @if($op->is_correct)
                        <span class="text-green-600 font-bold">{{ $op->option_text }}</span>
                    @endif
                @endforeach
            </td>

            <td class="p-3 flex gap-2 text-gray-900 dark:text-gray-100">
                <a href="{{ route('admin.questions.edit', $q) }}"
                   class="admin-action-btn bg-blue-600 text-white">
                    Edit
                </a>

                <form method="POST"
                      action="{{ route('admin.questions.destroy', $q) }}"
                      onsubmit="event.preventDefault(); appConfirm('Delete question?', { title: 'Delete Question', confirmText: 'Delete' }).then(confirmed => { if (confirmed) this.submit(); });"
                      class="inline-block">
                    @csrf @method('DELETE')
                    <button class="admin-action-btn bg-red-600 text-white">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="p-3 text-gray-500 text-center">
                No questions added yet.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
