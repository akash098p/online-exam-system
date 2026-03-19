<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;

class ExamReportController extends Controller
{
    /**
     * 📘 All completed exams
     */
    public function index()
    {
        $exams = Exam::withCount('attempts')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('admin.exam-performance.index', compact('exams'));
    }

    /**
     * 📊 One exam full student list
     */
    public function show($examId)
    {
        $exam = Exam::with('attempts.user')
            ->findOrFail($examId);

        return view('admin.exam-performance.show', compact('exam'));
    }

    /**
     * 👤 One student full attempt details
     */
    public function student($examId, $userId)
    {
        $attempt = ExamAttempt::with([
                'exam.questions.options',
                'answers.option',
                'user'
            ])
            ->where('exam_id', $examId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return view('admin.exam-performance.student', compact('attempt'));
    }
}
