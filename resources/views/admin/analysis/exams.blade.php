@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold mb-6">📊 Completed Exams</h1>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

@foreach($exams as $exam)
<div class="p-5 rounded-xl bg-gray-800 border border-gray-700">
    <h2 class="text-xl font-semibold">{{ $exam->title }}</h2>
    <p class="text-gray-400 mt-2">Attempts: {{ $exam->attempts_count }}</p>

    <a href="{{ route('admin.analysis.exam.students', $exam->id) }}"
       class="inline-block mt-4 px-4 py-2 rounded bg-indigo-600 hover:bg-indigo-700">
        View Students
    </a>
</div>
@endforeach

</div>

@endsection