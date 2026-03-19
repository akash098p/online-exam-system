<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Support\Str;
use Auth;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = strtolower((string) $request->query('status', 'all'));

        $examsQuery = Exam::withCount('questions');

        if ($search !== '') {
            $examsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['draft', 'published'], true)) {
            $examsQuery->where('status', $status);
        } else {
            $status = 'all';
        }

        $exams = $examsQuery
            ->latest()
            ->paginate(20)
            ->appends([
                'search' => $search,
                'status' => $status,
            ]);

        return view('admin.exams.index', compact('exams', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.exams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'negative_enabled' => 'nullable|boolean',
            'negative_marking' => 'nullable|numeric|min:0',
        ]);

        $exam = Exam::create([
            'created_by' => Auth::id(),
            'title' => $request->title,
            'subject' => $request->subject,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'pass_percentage' => $request->pass_percentage,

            // ✅ FIX: Save directly (no UTC conversion)
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,

            'negative_enabled' => $request->has('negative_enabled'),
            'negative_marking' => $request->negative_marking ?? 0,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('admin.exams.edit', $exam)
            ->with('success', 'Exam created. Add questions now.');
    }

    public function edit(Exam $exam)
    {
        $exam->load(['questions.options' => function ($q) {
            $q->orderBy('id');
        }]);

        return view('admin.exams.edit', compact('exam'));
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'pass_percentage' => 'required|numeric|min:0|max:100',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'negative_enabled' => 'nullable|boolean',
            'negative_marking' => 'nullable|numeric|min:0',
        ]);

        $exam->update([
            'title' => $request->title,
            'subject' => $request->subject,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'pass_percentage' => $request->pass_percentage,

            // ✅ FIX: Save directly (no UTC conversion)
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,

            'negative_enabled' => $request->has('negative_enabled'),
            'negative_marking' => $request->negative_marking ?? 0,
        ]);

        return back()->with('success', 'Exam updated.');
    }

    public function show(Exam $exam)
    {
        $exam->load('questions.options');
        return view('admin.exams.show', compact('exam'));
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()
            ->route('admin.exams.index')
            ->with('success', 'Exam deleted.');
    }

    public function togglePublish(Exam $exam)
    {
        $exam->status = $exam->status === 'published'
            ? 'draft'
            : 'published';

        $exam->save();

        return back()->with(
            'success',
            'Exam status changed to ' . ucfirst($exam->status)
        );
    }
}
