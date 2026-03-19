<x-app-layout>
    <div class="max-w-3xl mx-auto mt-20 text-center">

        <div class="bg-gray-800 rounded-xl p-8 shadow-lg">

            <h1 class="text-3xl font-bold text-red-400 mb-4">
                🔒 Result Locked
            </h1>

            <p class="text-gray-300 text-lg mb-4">
                Detailed answers for
                <span class="font-semibold text-white">
                    {{ $exam->title }}
                </span>
                are locked until the exam ends.
            </p>

            <p class="text-yellow-400 mb-6">
                ⏳ Exam ends at:
                <strong>
                    {{ \Carbon\Carbon::parse($exam->end_time)->format('d M Y, h:i A') }}
                </strong>
            </p>

            <a href="{{ route('student.results.index') }}"
               class="inline-block px-5 py-2 rounded bg-indigo-600 hover:bg-indigo-700 text-white">
                ← Back to Exam History
            </a>
        </div>

    </div>
</x-app-layout>
