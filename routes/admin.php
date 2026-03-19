<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\ExamReportController;
use App\Http\Controllers\Admin\ExamAnalysisController;
use App\Http\Controllers\Admin\StudentController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /* ===========================
           DASHBOARD
        ============================ */

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/analytics', [DashboardController::class, 'analytics'])
            ->name('analytics');

        /* ===========================
           EXAMS
        ============================ */

        Route::post('exams/{exam}/toggle-publish', [ExamController::class, 'togglePublish'])
            ->name('exams.toggle_publish');

        Route::resource('exams', ExamController::class);

        /* ===========================
           QUESTIONS
        ============================ */

        Route::get('exams/{exam}/questions', [QuestionController::class, 'index'])
            ->name('questions.index');

        Route::get('exams/{exam}/questions/create', [QuestionController::class, 'create'])
            ->name('questions.create');

        Route::post('exams/{exam}/questions', [QuestionController::class, 'store'])
            ->name('questions.store');

        Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])
            ->name('questions.edit');

        Route::put('questions/{question}', [QuestionController::class, 'update'])
            ->name('questions.update');

        Route::delete('questions/{question}', [QuestionController::class, 'destroy'])
            ->name('questions.destroy');

        /* ===========================
           OPTIONS
        ============================ */

        Route::post('questions/{question}/options', [OptionController::class, 'store'])
            ->name('options.store');

        Route::put('options/{option}', [OptionController::class, 'update'])
            ->name('options.update');

        Route::delete('options/{option}', [OptionController::class, 'destroy'])
            ->name('options.destroy');

        Route::post('questions/{question}/options/reorder', [OptionController::class, 'reorder'])
            ->name('options.reorder');

        /* ===========================
           RESULTS
        ============================ */

        Route::get('/results', [ResultController::class, 'index'])
            ->name('results.index');

        Route::get('/results/{exam}', [ResultController::class, 'show'])
            ->name('results.show');

        Route::get('/results/sheet/{result}', [ResultController::class, 'sheet'])
            ->name('results.sheet');

        /* ===========================
           EXAM PERFORMANCE SYSTEM
        ============================ */

        Route::get('/exam-performance', [ExamReportController::class, 'index'])
            ->name('performance.index');

        Route::get('/exam-performance/{examId}', [ExamReportController::class, 'show'])
            ->name('performance.show');

        Route::get('/exam-performance/{examId}/student/{userId}', [ExamReportController::class, 'student'])
            ->name('performance.student');

        /* ===========================
           EXAM ANALYSIS
        ============================ */

        Route::get('/analysis/exams', [ExamAnalysisController::class, 'index'])
            ->name('analysis.exams');

        Route::get('/analysis/exams/{exam}', [ExamAnalysisController::class, 'showExam'])
            ->name('analysis.exam.students');

        Route::get('/analysis/attempt/{attempt}', [ExamAnalysisController::class, 'showStudent'])
            ->name('analysis.student.result');

        /* ===========================
           STUDENT MANAGEMENT
        ============================ */

        // List students
        Route::get('/students', [StudentController::class, 'index'])
            ->name('students.index');

        // Create student
        Route::get('/students/create', [StudentController::class, 'create'])
            ->name('students.create');

        Route::post('/students', [StudentController::class, 'store'])
            ->name('students.store');

        // Edit student
        Route::get('/students/{id}/edit', [StudentController::class, 'edit'])
            ->name('students.edit');

        Route::put('/students/{id}', [StudentController::class, 'update'])
            ->name('students.update');

        // View student profile
        Route::get('/students/{id}', [StudentController::class, 'show'])
            ->name('students.show');

        // Block / Unblock
        Route::post('/students/{id}/toggle', [StudentController::class, 'toggleStatus'])
            ->name('students.toggle');

        // Delete student
        Route::delete('/students/{id}', [StudentController::class, 'destroy'])
            ->name('students.destroy');

});
