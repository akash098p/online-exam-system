<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Result;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ✅ Total exams in system
        $totalExams = Exam::count();

        // ✅ Exams already attempted by this student
        $attemptedExamIds = Result::where('user_id', $user->id)->pluck('exam_id');

        // ✅ Upcoming exams (not attempted + future)
        $nextExams = Exam::withCount('questions')
            ->where('start_time', '>', now())
            ->whereNotIn('id', $attemptedExamIds)
            ->orderBy('start_time', 'asc')
            ->get();

        // ✅ Upcoming exams count
        $upcomingExams = $nextExams->count();

        // ✅ Completed exams count
        $completedExams = Result::where('user_id', $user->id)->count();

        // ✅ Average score
        $averageScore = Result::where('user_id', $user->id)->avg('percentage');

        return view('student.dashboard', compact(
            'totalExams',
            'upcomingExams',
            'completedExams',
            'averageScore',
            'nextExams'
        ));
    }
}
