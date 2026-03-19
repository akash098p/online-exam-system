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
</style>

<div class="space-y-5">
    <h1 class="text-3xl font-bold text-white">Student Profile</h1>

    <div class="grid md:grid-cols-4 gap-4">
        <div class="glass-card p-4">Name: {{ $student->name }}</div>
        <div class="glass-card p-4">Reg No: {{ $student->registration_no }}</div>
        <div class="glass-card p-4">Semester: {{ $student->semester }}</div>
        <div class="glass-card p-4">Joined: {{ $student->created_at->format('d M Y') }}</div>
    </div>

    <h2 class="text-xl font-semibold text-white">Exam History</h2>

    @forelse($results as $r)
        <div class="glass-card p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold text-white">{{ $r->exam->title ?? 'Exam' }}</p>
                <p class="text-sm text-gray-300">
                    {{ optional($r->submitted_at ?? $r->created_at)->format('d M Y, h:i A') }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-green-400 font-semibold">Score: {{ $r->obtained_marks }} / {{ $r->total_marks }}</p>
                <a href="{{ route('admin.results.sheet', $r->id) }}"
                   class="inline-block mt-2 px-3 py-1 text-sm bg-indigo-600/80 rounded hover:bg-indigo-700 transition">
                    View Sheet
                </a>
            </div>
        </div>
    @empty
        <p class="text-gray-300">No exams attempted.</p>
    @endforelse
</div>

@endsection
