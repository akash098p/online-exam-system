@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-4">Edit Exam</h1>

@if(session('success'))
    <div class="mb-4 rounded bg-green-600/20 p-3 text-green-400">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.exams.update', $exam) }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block font-semibold">Exam Title</label>
            <input type="text" name="title"
                   value="{{ old('title', $exam->title) }}"
                   class="w-full p-2 border rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>
        </div>

        <div>
            <label class="block font-semibold">Subject</label>
            <input type="text" name="subject"
                   value="{{ old('subject', $exam->subject) }}"
                   class="w-full p-2 border rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
        </div>

        <div class="md:col-span-2">
            <label class="block font-semibold">Description</label>
            <textarea name="description"
                      class="w-full p-2 border rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">{{ old('description', $exam->description) }}</textarea>
        </div>

        <div>
            <label class="block font-semibold">Duration (minutes)</label>
            <input type="number" name="duration_minutes"
                   value="{{ old('duration_minutes', $exam->duration_minutes) }}"
                   class="w-full p-2 border rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>
        </div>

        <div>
            <label class="block font-semibold">Pass Percentage</label>
            <input type="number" name="pass_percentage" step="0.01" min="0" max="100"
                   value="{{ old('pass_percentage', $exam->pass_percentage ?? 40) }}"
                   class="w-full p-2 border rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>
        </div>

        <div>
            <label class="block font-semibold">Start Time</label>
            <input type="datetime-local" name="start_time"
                   value="{{ old('start_time', $exam->start_time?->format('Y-m-d\TH:i')) }}"
                   class="w-full p-2 border rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
        </div>

        <div>
            <label class="block font-semibold">End Time</label>
            <input type="datetime-local" name="end_time"
                   value="{{ old('end_time', $exam->end_time?->format('Y-m-d\TH:i')) }}"
                   class="w-full p-2 border rounded bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
        </div>

        {{-- Negative Marking --}}
        <div class="md:col-span-2">
            <label class="block font-semibold">Negative Marking</label>

            <input type="hidden" name="negative_enabled" value="0">

            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="negative_enabled" value="1"
                        class="control-check accent-green-500 dark:accent-green-400"
                        {{ old('negative_enabled', $exam->negative_enabled) ? 'checked' : '' }}>
                    Enable Negative Marking
                </label>

                <input type="number" step="0.01"
                       name="negative_marking"
                       value="{{ old('negative_marking', $exam->negative_marking) }}"
                       class="p-2 border rounded w-40 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100">
            </div>
        </div>
    </div>

    <div class="mt-6 flex gap-2">
        <button class="px-4 py-2 bg-green-600 text-white rounded">Update Exam</button>

        <a href="{{ route('admin.exams.index') }}"
           class="px-4 py-2 bg-red-600 rounded">Cancel</a>

        <a href="{{ route('admin.exams.index') }}"
           class="px-4 py-2 bg-yellow-600 rounded">Back</a>

        <a href="{{ route('admin.questions.index', $exam) }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded">
            Manage Questions
        </a>
    </div>
</form>

@endsection
