<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Exam;
use App\Models\Response as Resp;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('status', 'published')
            ->withCount('questions')
            ->with(['questions:id,exam_id,marks'])
            ->get();
        return view('student.exams.index', compact('exams'));
    }

    public function start($examId)
    {
        $exam = Exam::with('questions.options')->findOrFail($examId);

        $now       = Carbon::now(config('app.timezone'));
        $startTime = Carbon::parse($exam->start_time, config('app.timezone'));
        $endTime   = Carbon::parse($exam->end_time, config('app.timezone'));

        if ($now->lt($startTime)) {
            return redirect()->route('student.exams.index')
                ->with('error', 'Exam has not started yet.');
        }

        if ($now->gt($endTime)) {
            return redirect()->route('student.exams.index')
                ->with('error', 'Exam time is over.');
        }

        if (Result::where('exam_id', $examId)->where('user_id', auth()->id())->exists()) {
            return redirect()->route('student.exams.index')
                ->with('error', 'You have already attempted this exam.');
        }

        $questions = $exam->questions()->with('options')->get();

        session()->put("exam_attempt_{$examId}", [
            'attempt_id' => uniqid('attempt_' . auth()->id() . '_'),
            'started_at' => Carbon::now(),
        ]);

        return view('student.exams.start', compact('exam', 'questions'));
    }

    public function submit(Request $request, $examId)
    {
        $userId = auth()->id();

        if (Result::where('exam_id', $examId)->where('user_id', $userId)->exists()) {
            return redirect()->route('student.exams.index')
                ->with('error', 'You have already attempted this exam.');
        }

        $exam = Exam::with('questions.options')->findOrFail($examId);

        $totalQuestions = $exam->questions->count();
        $totalMarks     = 0;
        $obtainedMarks  = 0;

        $correct = 0;
        $wrong = 0;
        $notAttempted = 0;

        foreach ($exam->questions as $question) {

            $qid = $question->id;
            $selectedOptionId = $request->input("answers.$qid");

            $correctOptionId = $question->options
                ->where('is_correct', 1)
                ->pluck('id')
                ->first();

            $marks = (int) ($question->marks ?? 1);
            $totalMarks += $marks;

            // ❌ NOT ATTEMPTED
            if (is_null($selectedOptionId)) {

                $notAttempted++;

                Resp::create([
                    'exam_id' => $examId,
                    'user_id' => $userId,
                    'question_id' => $qid,
                    'option_id' => null,
                    'is_correct' => 0,
                    'marks_obtained' => 0,
                ]);

                continue;
            }

            // ✅ CORRECT
            if ((int)$selectedOptionId === (int)$correctOptionId) {

                $correct++;
                $obtainedMarks += $marks;

                Resp::create([
                    'exam_id' => $examId,
                    'user_id' => $userId,
                    'question_id' => $qid,
                    'option_id' => $selectedOptionId,
                    'is_correct' => 1,
                    'marks_obtained' => $marks,
                ]);
            }
            // ❌ WRONG
            else {

                $wrong++;

                // ✅ NEGATIVE MARKING LOGIC (ADDED)
                if ($exam->negative_enabled) {
                    $obtainedMarks -= (float) $exam->negative_marking;
                }

                Resp::create([
                    'exam_id' => $examId,
                    'user_id' => $userId,
                    'question_id' => $qid,
                    'option_id' => $selectedOptionId,
                    'is_correct' => 0,
                    'marks_obtained' => 0,
                ]);
            }
        }

        // Prevent negative total score
        if ($obtainedMarks < 0) {
            $obtainedMarks = 0;
        }

        $percentage = $totalMarks > 0
            ? ($obtainedMarks / $totalMarks) * 100
            : 0;
        $passPercentage = (float) ($exam->pass_percentage ?? 40);

        Result::create([
            'exam_id'         => $examId,
            'user_id'         => $userId,
            'total_questions' => $totalQuestions,
            'correct'         => $correct,
            'wrong'           => $wrong,
            'not_attempted'   => $notAttempted,
            'total_marks'     => $totalMarks,
            'obtained_marks'  => $obtainedMarks,
            'percentage'      => $percentage,
            'status'          => $percentage >= $passPercentage ? 'Pass' : 'Fail',
        ]);

        session()->forget("exam_attempt_{$examId}");

        return redirect()->route('student.exams.index')
            ->with('success', 'Exam submitted successfully.');
    }

    public function result($examId)
    {
        $userId = auth()->id();

        $result = Result::where('exam_id', $examId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $exam = Exam::with('questions')->findOrFail($examId);

        return view('student.exams.result', [
            'exam'           => $exam,
            'totalMarks'     => $result->total_marks,
            'obtainedMarks'  => $result->obtained_marks,
            'percentage'     => $result->percentage,
        ]);
    }
}
