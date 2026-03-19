<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Result;
use App\Models\User;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Existing counts
        $totalExams = Exam::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalAttempts = Result::count();
        $recentExams = Exam::latest()->take(5)->get();

        // NEW Real-Time Stats (No logic changed)
        $activeExams = Exam::where('status', 'published')->count();
        $draftExams = Exam::where('status', 'draft')->count();
        $totalResults = Result::count();

        return view('admin.dashboard', compact(
            'totalExams',
            'totalStudents',
            'totalAttempts',
            'recentExams',
            'activeExams',
            'draftExams',
            'totalResults'
        ));
    }

    public function analytics()
    {
        // ----- 1. Exam Summary -----
        $examSummary = DB::table('results')
            ->select(
                'exam_id',
                DB::raw('COUNT(*) as attempts'),
                DB::raw('AVG(percentage) as avg_percentage'),
                DB::raw('SUM(CASE WHEN status="Pass" THEN 1 ELSE 0 END) as passed')
            )
            ->groupBy('exam_id')
            ->get();

        $examLabels = [];
        $examScores = [];

        foreach ($examSummary as $row) {
            $exam = Exam::find($row->exam_id);
            if ($exam) {
                $examLabels[] = $exam->title;
                $examScores[] = round($row->avg_percentage, 2);
            }
        }

        // ----- 2. Daily Activity -----
        $dailyData = DB::table('results')
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as count'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $dailyLabels = $dailyData->pluck('day');
        $dailyCounts = $dailyData->pluck('count');

        // ----- 2b. Daily attempt details for popup -----
        $dailyAttemptDetails = Result::with(['user:id,name,profile_photo,sex,registration_no', 'exam:id,title'])
            ->select('id', 'exam_id', 'user_id', 'percentage', 'status', 'created_at')
            ->latest('created_at')
            ->get()
            ->groupBy(function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->map(function ($items) {
                return $items->map(function ($r) {
                    return [
                        'student_name' => $r->user?->name ?? 'Unknown',
                        'student_reg' => $r->user?->registration_no ?? 'N/A',
                        'student_photo' => $r->user?->profilePhotoUrl() ?? null,
                        'exam_title' => $r->exam?->title ?? 'Exam',
                        'percentage' => round((float) $r->percentage, 2),
                        'status' => $r->status,
                        'time' => optional($r->created_at)->format('d M Y, h:i A'),
                    ];
                })->values();
            });

        // ----- 3. Top Students -----
        $top = DB::table('results')
            ->select('user_id', DB::raw('AVG(percentage) as avg_percentage'))
            ->groupBy('user_id')
            ->orderByDesc('avg_percentage')
            ->limit(10)
            ->get();

        $topStudents = [];

        foreach ($top as $row) {
            $user = User::withTrashed()->find($row->user_id);
            if ($user) {
                $topStudents[] = (object)[
                    'user' => $user,
                    'avg_percentage' => round($row->avg_percentage, 2)
                ];
            }
        }

        return view('admin.analytics', compact(
            'examLabels',
            'examScores',
            'dailyLabels',
            'dailyCounts',
            'topStudents',
            'dailyAttemptDetails'
        ));
    }
}
