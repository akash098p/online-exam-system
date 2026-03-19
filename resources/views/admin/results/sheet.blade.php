<link rel="icon" type="image/png" href="{{ asset('App-logo.png') }}">
@extends('layouts.admin')

@section('content')

<style>
.glass-card {
    background: rgba(8, 10, 34, 0.38);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(220, 220, 220, 0.22);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    border-radius: 0.9rem;
}
.question-card {
    background: rgba(8, 10, 34, 0.46);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(220, 220, 220, 0.18);
    border-radius: 0.9rem;
}
</style>

<div class="space-y-5">
    <h1 class="text-3xl font-bold text-white">Student Answer Sheet</h1>

    <div class="grid md:grid-cols-4 gap-4">
        <div class="glass-card p-4">Student: {{ $result->user->name }}</div>
        <div class="glass-card p-4">Reg No: {{ $result->user->registration_no ?? 'N/A' }}</div>
        <div class="glass-card p-4">Exam: {{ $exam->title }}</div>
        <div class="glass-card p-4">
            Submitted: {{ optional($result->submitted_at ?? $result->created_at)->format('d M Y, h:i A') }}
        </div>
    </div>

    <div class="grid md:grid-cols-4 gap-4">
        <div class="glass-card p-4">Total Questions: {{ $totalQuestions }}</div>
        <div class="glass-card p-4">Answered: {{ $attempted }}</div>
        <div class="glass-card p-4">Not Answered: {{ $notAnswered }}</div>
        <div class="glass-card p-4">
            Correct: {{ $result->correct ?? 0 }} | Wrong: {{ $result->wrong ?? 0 }}
        </div>
    </div>

    <div class="grid md:grid-cols-4 gap-4">
        <div class="glass-card p-4">Obtained: {{ $result->obtained_marks }} / {{ $result->total_marks }}</div>
        <div class="glass-card p-4">Percentage: {{ number_format((float) $result->percentage, 2) }}%</div>
        <div class="glass-card p-4">
            Status:
            <span class="{{ ($result->status ?? '') === 'Pass' ? 'text-green-400' : 'text-red-400' }}">
                {{ $result->status }}
            </span>
        </div>
        <div class="glass-card p-4">
            Pass Mark:
            @php $passPercentage = (float) ($exam->pass_percentage ?? 40); @endphp
            {{ number_format($passPercentage, 2) }}%
        </div>
    </div>

    @foreach($questions as $index => $q)
        @php
            $response = $responses->get($q->id);
            $selectedOption = $response?->option;
            $correctOption = $q->options->firstWhere('is_correct', 1);
            $isAnswered = !is_null($response?->option_id);
            $isCorrect = (bool) ($response?->is_correct);
            $status = !$isAnswered ? 'Not Answered' : ($isCorrect ? 'Correct' : 'Wrong');
        @endphp

        <div class="question-card p-4 mb-4">
            <h3 class="font-semibold mb-2 text-white">Q{{ $index + 1 }}. {{ $q->question_text }}</h3>

            <div class="grid md:grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-300">Selected Option:</p>
                    <p class="{{ $isAnswered ? ($isCorrect ? 'text-green-400' : 'text-red-400') : 'text-yellow-400' }}">
                        {{ $selectedOption?->option_text ?? 'Not Answered' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-300">Correct Option:</p>
                    <p class="text-green-400">{{ $correctOption?->option_text ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-300">Status:</p>
                    <p class="{{ $status === 'Correct' ? 'text-green-400' : ($status === 'Wrong' ? 'text-red-400' : 'text-yellow-400') }}">
                        {{ $status }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-300">Marks Obtained:</p>
                    <p class="text-white">{{ (int) ($response->marks_obtained ?? 0) }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection
