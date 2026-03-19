<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'college_name',
        'registration_no',
        'semester',
        'phone',
        'sex',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'deleted_at' => 'datetime',
        ];
    }

    /* ====================================================
       RELATIONSHIPS FOR ONLINE EXAMINATION SYSTEM
       ==================================================== */

    // Exams created by this user (Admin/Teacher)
    public function createdExams()
    {
        return $this->hasMany(Exam::class, 'created_by');
    }

    // Exams assigned to this user (Student)
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_user');
    }

    // All responses submitted by this user
    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    // All results of this user
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function profilePhotoUrl()
    {
        if ($this->profile_photo) {
            return asset('storage/'.$this->profile_photo);
        }

        if ($this->sex === 'female') {
            return asset('images/default-female.png');
        }

        return asset('images/default-male.png');
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPassword($token));
    }

}
