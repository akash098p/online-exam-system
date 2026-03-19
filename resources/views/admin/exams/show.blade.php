@extends('layouts.admin')

@section('content')

<h1 class="text-2xl font-bold mb-4">Exam Details</h1>

<div class="bg-white dark:bg-gray-800 dark:text-gray-100 p-6 rounded shadow">

    {{-- HEADER --}}
    <div class="flex justify-between mb-4">
        <div>
            <h2 class="text-xl font-bold">{{ $exam->title }}</h2>
            <p class="text-gray-600 dark:text-gray-400">{{ $exam->subject }}</p>
        </div>

        <span class="px-3 py-1 text-white rounded
            {{ $exam->status == 'published' ? 'bg-green-600' : 'bg-yellow-600' }}">
            {{ ucfirst($exam->status) }}
        </span>
    </div>

    {{-- DETAILS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <p><strong>Duration:</strong> {{ $exam->duration_minutes }} minutes</p>
            <p><strong>Total Questions:</strong> {{ $exam->questions->count() }}</p>

            <p class="mt-2">
                <strong>Negative Marking:</strong>
                @if($exam->negative_enabled)
                    -{{ $exam->negative_marking }} per wrong answer
                @else
                    No
                @endif
            </p>
        </div>

        <div>
            <p><strong>Start Time:</strong> {{ $exam->start_time }}</p>
            <p><strong>End Time:</strong> {{ $exam->end_time }}</p>
        </div>

        <div class="md:col-span-2 mt-3">
            <strong>Description:</strong>
            <div class="prose dark:prose-invert mt-2">
                {!! $exam->description !!}
            </div>
        </div>

    </div>

    {{-- ACTION BUTTONS --}}
    <div class="mt-6 flex gap-3 flex-wrap">

        {{-- EDIT --}}
        <a href="{{ route('admin.exams.edit', $exam) }}"
           class="admin-action-btn bg-blue-600 text-white">
            Edit Exam
        </a>

        {{-- MANAGE QUESTIONS (FIXED ROUTE) --}}
        <a href="{{ route('admin.questions.index', $exam) }}"
           class="admin-action-btn bg-indigo-600 text-white">
            Manage Questions
        </a>

        {{-- PUBLISH / UNPUBLISH --}}
        <form method="POST" action="{{ route('admin.exams.toggle_publish', $exam) }}">
            @csrf
            <button class="admin-action-btn text-white rounded
                {{ $exam->status == 'published' ? 'bg-yellow-600' : 'bg-green-600' }}">
                {{ $exam->status == 'published' ? 'Unpublish' : 'Publish' }}
            </button>
        </form>

        {{-- BACK --}}
        <a href="{{ route('admin.exams.index') }}"
           class="admin-action-btn bg-yellow-600 text-white">
            Back
        </a>
    </div>

</div>

@endsection
