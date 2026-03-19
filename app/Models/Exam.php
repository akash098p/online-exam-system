<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',
        'subject',
        'description',
        'duration_minutes',
        'total_marks',
        'pass_percentage',
        'start_time',
        'end_time',
        'status',
        'negative_enabled',
        'negative_marking',
    ];

    // ✅ FIX: Proper datetime casting
    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
        'pass_percentage' => 'float',
    ];

    // Relationships
    public function attempts()
    {
        return $this->hasMany(\App\Models\ExamAttempt::class);
    }

    // Exam belongs to a creator (admin/teacher)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Exam has many questions
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    // Exam belongs to many students
    public function students()
    {
        return $this->belongsToMany(User::class, 'exam_user');
    }

    // Exam has many results
    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
