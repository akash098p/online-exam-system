<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Response;

class ExamAnalysisController extends Controller
{
    // ✅ Exam list with attempt count
    public function index()
    {
        $exams = Exam::withCount('attempts')->latest()->get();

        return view('admin.analysis.exams', compact('exams'));
    }

    // ✅ Exam wise students
    public function showExam($examId)
    {
        $exam = Exam::findOrFail($examId);

        $attempts = ExamAttempt::where('exam_id', $examId)
            ->with('user')
            ->latest()
            ->get();

        return view('admin.analysis.exam_students', compact('exam', 'attempts'));
    }

    // ✅ Full student result
    public function showStudent($attemptId)
    {
        $attempt = ExamAttempt::with(['user', 'exam.questions.options'])
            ->findOrFail($attemptId);

        $responses = Response::where('exam_attempt_id', $attempt->id)->get();

        return view('admin.analysis.student_result', compact('attempt', 'responses'));
    }
}
