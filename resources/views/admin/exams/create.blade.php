@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Create New Exam</h1>

<form method="POST" action="{{ route('admin.exams.store') }}">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block font-semibold">Exam Title</label>
            <input type="text" name="title"
                   value="{{ old('title') }}"
                   class="w-full p-2 border rounded mt-1 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100"
                   placeholder="Enter exam title" required>
        </div>

        <div>
            <label class="block font-semibold">Subject</label>
            <input type="text" name="subject"
                   value="{{ old('subject') }}"
                   class="w-full p-2 border rounded mt-1 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100"
                   placeholder="Subject name" required>
        </div>

        <div class="md:col-span-2">
            <label class="block font-semibold">Description</label>
            <textarea name="description" id="description"
                      class="w-full p-2 border rounded mt-1 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100"
                      placeholder="Exam description (optional)">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block font-semibold">Duration (minutes)</label>
            <input type="number" name="duration_minutes"
                   value="{{ old('duration_minutes') }}"
                   class="w-full p-2 border rounded mt-1 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100"
                   placeholder="e.g., 30" required>
        </div>

        <div>
            <label class="block font-semibold">Pass Percentage</label>
            <input type="number" name="pass_percentage" step="0.01" min="0" max="100"
                   value="{{ old('pass_percentage', 40) }}"
                   class="w-full p-2 border rounded mt-1 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100"
                   placeholder="e.g., 40" required>
        </div>

        <div>
            <label class="block font-semibold">Start Time</label>
            <input type="datetime-local" name="start_time"
                   value="{{ old('start_time') }}"
                   class="w-full p-2 border rounded mt-1 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>
        </div>

        <div>
            <label class="block font-semibold">End Time</label>
            <input type="datetime-local" name="end_time"
                   value="{{ old('end_time') }}"
                   class="w-full p-2 border rounded mt-1 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100" required>
        </div>

        <div class="md:col-span-2">
            <label class="block font-semibold">Negative Marking</label>

            <div class="flex items-center gap-4 mt-1">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="negative_enabled" value="1"
                           class="control-check w-4 h-4 accent-green-500 dark:accent-green-400"
                           {{ old('negative_enabled') ? 'checked' : '' }}>
                    Enable Negative Marking
                </label>

                <input type="number"
                       step="0.01"
                       name="negative_marking"
                       value="{{ old('negative_marking') }}"
                       class="p-2 border rounded w-40 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100"
                       placeholder="Penalty (e.g., 0.25)">
            </div>
        </div>

    </div>

    <div class="mt-6 flex gap-3">
        <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded">
            Save Exam
        </button>

        <a href="{{ route('admin.exams.index') }}"
           class="px-4 py-2 bg-red-600 rounded ml-2">
            Cancel
        </a>

        <a href="{{ route('admin.exams.index') }}"
           class="px-4 py-2 bg-yellow-600 rounded">
            Back
        </a>

    </div>
</form>

@endsection
