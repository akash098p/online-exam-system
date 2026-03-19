<x-app-layout>
    <div class="student-page min-h-screen py-8 sm:py-10">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 sm:px-6 lg:px-8">
            <section class="student-hero overflow-hidden rounded-[28px] border border-white/10">
                <div class="px-6 py-8 sm:px-8 lg:px-10 lg:py-9">
                    <div class="space-y-5 student-reveal student-reveal-delay-1">
                        <div class="inline-flex items-center gap-2 rounded-full border border-amber-300/30 bg-amber-300/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-amber-100 shadow-[inset_0_1px_0_rgba(255,255,255,0.24)] student-shimmer">
                            Result Detail
                        </div>

                        <div class="space-y-3">
                            <h1 class="student-title max-w-5xl text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                                Review your performance, compare responses and understand every question clearly.
                            </h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="student-panel p-5 sm:p-7 student-reveal student-reveal-delay-2">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="result-summary-card">
                        <p class="result-summary-label">Exam</p>
                        <p class="result-summary-value">{{ $exam->title }}</p>
                    </div>
                    <div class="result-summary-card">
                        <p class="result-summary-label">Total Questions</p>
                        <p class="result-summary-value">{{ $totalQuestions }}</p>
                    </div>
                    <div class="result-summary-card">
                        <p class="result-summary-label">Marks</p>
                        <p class="result-summary-value">{{ $result->obtained_marks }} / {{ $result->total_marks }}</p>
                    </div>
                    <div class="result-summary-card">
                        <p class="result-summary-label">Percentage</p>
                        <p class="result-summary-value counter" data-value="{{ number_format($result->percentage, 2, '.', '') }}" data-type="percentage">0%</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="result-performance-card result-performance-attempted">
                        <p class="result-performance-label">Attempted</p>
                        <p class="result-performance-value">{{ $attempted }}</p>
                    </div>
                    <div class="result-performance-card result-performance-correct">
                        <p class="result-performance-label">Correct</p>
                        <p class="result-performance-value">{{ $correct }}</p>
                    </div>
                    <div class="result-performance-card result-performance-wrong">
                        <p class="result-performance-label">Wrong</p>
                        <p class="result-performance-value">{{ $wrong }}</p>
                    </div>
                    <div class="result-performance-card result-performance-empty">
                        <p class="result-performance-label">Not Answered</p>
                        <p class="result-performance-value">{{ $notAnswered }}</p>
                    </div>
                </div>
            </section>

            <section class="student-panel p-5 sm:p-7 student-reveal student-reveal-delay-2">
                <div class="border-b border-white/10 pb-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-100/80">Question Review</p>
                    <h2 class="mt-2 text-2xl font-semibold text-white">Answer Breakdown</h2>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach($exam->questions as $index => $question)
                        @php
                            $response = $responses[$question->id] ?? null;
                            $selectedOptionId = $response?->option_id;
                            $correctOptionId = $question->options->where('is_correct', 1)->first()?->id;
                        @endphp

                        @php
                            $questionState = !$selectedOptionId
                                ? 'not_answered'
                                : ($selectedOptionId === $correctOptionId ? 'correct' : 'wrong');
                        @endphp

                        <article class="result-question-card {{ $questionState === 'correct' ? 'result-question-card-correct' : ($questionState === 'wrong' ? 'result-question-card-wrong' : 'result-question-card-empty') }}">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <h3 class="text-lg font-semibold leading-7 text-white">Q{{ $index + 1 }}. {{ $question->question_text }}</h3>
                                <span class="result-review-pill {{ $questionState === 'correct' ? 'result-review-pill-correct' : ($questionState === 'wrong' ? 'result-review-pill-wrong' : 'result-review-pill-empty') }}">
                                    {{ $questionState === 'correct' ? 'Correct' : ($questionState === 'wrong' ? 'Wrong' : 'Not Answered') }}
                                </span>
                            </div>

                            <ul class="mt-4 space-y-2">
                                @foreach($question->options as $option)
                                    @php
                                        $isSelected = $selectedOptionId === $option->id;
                                        $isCorrect = $option->id === $correctOptionId;
                                    @endphp

                                    <li class="result-option {{ $isCorrect ? 'result-option-correct' : '' }} {{ $isSelected && !$isCorrect ? 'result-option-wrong' : '' }} {{ $isSelected ? 'result-option-selected' : '' }}">
                                        <span class="result-option-text">{{ $option->option_text }}</span>
                                        <span class="flex shrink-0 flex-wrap items-center gap-2 text-xs font-semibold uppercase tracking-[0.14em]">
                                            @if($isCorrect)
                                                <span class="result-chip result-chip-correct">Correct Answer</span>
                                            @endif
                                            @if($isSelected)
                                                <span class="result-chip result-chip-selected">Your Answer</span>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </article>
                    @endforeach
                </div>

                <a href="{{ route('student.results.index') }}" class="student-action-btn student-action-muted mt-6 inline-flex">
                    Back to Exam History
                </a>
            </section>
        </div>
    </div>

    <style>
        .student-page { position: relative; }
        .student-hero, .student-panel { position: relative; overflow: hidden; border: 1px solid rgba(200,200,194,0.32); background: rgba(8,10,34,0.42); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); box-shadow: inset 0 1px 0 rgba(255,255,255,0.14), 0 20px 50px rgba(2,6,23,0.28); border-radius: 24px; }
        .student-hero::before, .student-panel::before { content: ""; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,0.14), transparent 34%); pointer-events: none; }
        .student-hero::after { content: ""; position: absolute; inset: 0; background: linear-gradient(120deg, transparent 20%, rgba(255,255,255,0.08) 40%, transparent 58%); transform: translateX(-120%); animation: studentHeroSweep 8s ease-in-out infinite; pointer-events: none; }
        .student-title { text-shadow: 0 6px 24px rgba(15,23,42,0.34); }
        .result-summary-card, .result-performance-card, .result-question-card, .result-option { border: 1px solid rgba(255,255,255,0.12); border-radius: 20px; background: linear-gradient(180deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03)), rgba(15,23,42,0.24); box-shadow: inset 0 1px 0 rgba(255,255,255,0.14); }
        .result-summary-card, .result-performance-card, .result-question-card { padding: 1rem; }
        .result-summary-label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: rgb(162 173 194); }
        .result-summary-value { margin-top: 0.7rem; color: white; font-size: 1.25rem; font-weight: 600; line-height: 1.4; }
        .result-performance-card { padding: 1.1rem 1rem; }
        .result-performance-label { font-size: 0.72rem; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: rgb(203 213 225); }
        .result-performance-value { margin-top: 0.65rem; font-size: 2rem; font-weight: 700; line-height: 1; }
        .result-performance-attempted { color: rgb(191 219 254); background: linear-gradient(180deg, rgba(59,130,246,0.18), rgba(15,23,42,0.3)); }
        .result-performance-correct { color: rgb(167 243 208); background: linear-gradient(180deg, rgba(16,185,129,0.18), rgba(15,23,42,0.3)); }
        .result-performance-wrong { color: rgb(254 205 211); background: linear-gradient(180deg, rgba(244,63,94,0.18), rgba(15,23,42,0.3)); }
        .result-performance-empty { color: rgb(253 230 138); background: linear-gradient(180deg, rgba(245,158,11,0.18), rgba(15,23,42,0.3)); }
        .result-question-card { padding: 1.2rem; background: linear-gradient(180deg, rgba(255,255,255,0.09), rgba(255,255,255,0.03)), rgba(15,23,42,0.32); }
        .result-question-card-correct { border-color: rgba(52,211,153,0.28); box-shadow: inset 0 1px 0 rgba(255,255,255,0.14), 0 14px 30px rgba(5,150,105,0.08); }
        .result-question-card-wrong { border-color: rgba(251,113,133,0.24); box-shadow: inset 0 1px 0 rgba(255,255,255,0.14), 0 14px 30px rgba(190,24,93,0.08); }
        .result-question-card-empty { border-color: rgba(251,191,36,0.24); box-shadow: inset 0 1px 0 rgba(255,255,255,0.14), 0 14px 30px rgba(202,138,4,0.07); }
        .result-review-pill, .result-chip { display: inline-flex; align-items: center; justify-content: center; border-radius: 9999px; border: 1px solid transparent; padding: 0.4rem 0.78rem; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.16em; text-transform: uppercase; white-space: nowrap; }
        .result-review-pill-correct, .result-chip-correct { background: rgba(16,185,129,0.16); border-color: rgba(110,231,183,0.32); color: rgb(167 243 208); }
        .result-review-pill-wrong { background: rgba(244,63,94,0.16); border-color: rgba(253,164,175,0.28); color: rgb(254 205 211); }
        .result-review-pill-empty { background: rgba(245,158,11,0.14); border-color: rgba(252,211,77,0.28); color: rgb(253 230 138); }
        .result-chip-selected { background: rgba(59,130,246,0.16); border-color: rgba(147,197,253,0.28); color: rgb(191 219 254); }
        .result-option { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1rem 1rem; color: rgb(226 232 240); transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease; }
        .result-option:hover { transform: translateY(-1px); }
        .result-option-text { flex: 1 1 auto; line-height: 1.6; }
        .result-option-selected { border-color: rgba(96,165,250,0.28); }
        .result-option-correct { background: rgba(5,150,105,0.26); border-color: rgba(52,211,153,0.32); }
        .result-option-wrong { background: rgba(220,38,38,0.24); border-color: rgba(251,113,133,0.28); }
        @media (max-width: 640px) { .result-option { flex-direction: column; } }
        .student-action-btn { display: inline-flex; align-items: center; justify-content: center; border-radius: 0.85rem; padding: 0.6rem 0.95rem; font-size: 0.8rem; font-weight: 600; transition: all 0.25s ease; }
        .student-action-muted { background: rgba(51,65,85,0.42); color: rgb(226 232 240); }
        .student-action-muted:hover { background: rgba(71,85,105,0.5); }
        .student-reveal { opacity: 0; transform: translateY(22px); animation: studentReveal 0.75s cubic-bezier(0.22,1,0.36,1) forwards; will-change: transform, opacity; }
        .student-reveal-delay-1 { animation-delay: 0.06s; }
        .student-reveal-delay-2 { animation-delay: 0.14s; }
        .student-shimmer { position: relative; overflow: hidden; }
        .student-shimmer::after { content: ""; position: absolute; inset: 0; background: linear-gradient(110deg, transparent 20%, rgba(255,255,255,0.22) 45%, transparent 70%); transform: translateX(-135%); animation: studentBadgeShimmer 6.5s ease-in-out infinite; }
        @keyframes studentReveal { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes studentHeroSweep { 0%,100% { transform: translateX(-120%); } 45%,55% { transform: translateX(120%); } }
        @keyframes studentBadgeShimmer { 0%,100% { transform: translateX(-135%); } 48%,60% { transform: translateX(135%); } }
        @media (prefers-reduced-motion: reduce) { .student-hero::after, .student-shimmer::after, .student-reveal { animation: none !important; opacity: 1; transform: none; } }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll(".counter");

            counters.forEach((counter) => {
                const target = parseFloat(counter.getAttribute("data-value"));
                const isPercentage = counter.getAttribute("data-type") === "percentage";

                let count = 0;
                const speed = Math.max(20, 1000 / (target || 1));

                const update = () => {
                    if (count < target) {
                        count += target / 60;

                        counter.innerText = isPercentage
                            ? count.toFixed(2) + "%"
                            : Math.ceil(count);

                        setTimeout(update, speed);
                    } else {
                        counter.innerText = isPercentage
                            ? target.toFixed(2) + "%"
                            : target;
                    }
                };

                update();
            });
        });
    </script>
</x-app-layout>
