
@extends('layouts.admin')

@section('content')

<h1 class="text-3xl font-bold mb-4">📄 Student Exam Report</h1>

<div class="grid md:grid-cols-4 gap-4 mb-6">
<div class="bg-gray-800 p-4 rounded">👤 {{ $attempt->user->name }}</div>
<div class="bg-gray-800 p-4 rounded">🎓 {{ $attempt->exam->title }}</div>
<div class="bg-gray-800 p-4 rounded">📊 Score: {{ $attempt->score }}</div>
<div class="bg-gray-800 p-4 rounded">📅 {{ $attempt->created_at->format('d M Y, h:i A') }}</div>
</div>

@foreach($attempt->exam->questions as $q)

@php
$response = $responses->firstWhere('question_id', $q->id);
@endphp

<div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-4">

<h3 class="font-semibold mb-2">{{ $q->question_text }}</h3>

@foreach($q->options as $op)

<p class="ml-4
@if($op->is_correct) text-green-400 @endif
@if($response && $response->option_id == $op->id && !$op->is_correct) text-red-400 @endif
">
• {{ $op->option_text }}
</p>

@endforeach

<p class="mt-2 text-sm text-gray-400">
Status:
@if(!$response) Not Answered
@elseif($response->is_correct) Correct
@else Wrong
@endif
</p>

</div>
@endforeach

@endsection
