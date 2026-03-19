<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('user_id');      // student
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('option_id')->nullable(); // for mcq/true_false
            $table->text('answer_text')->nullable();             // for short_answer
            $table->boolean('is_correct')->nullable();           // null for subjective until checked
            $table->integer('marks_obtained')->default(0);
            $table->timestamps();

            $table->foreign('exam_id')
                  ->references('id')->on('exams')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('question_id')
                  ->references('id')->on('questions')
                  ->onDelete('cascade');

            $table->foreign('option_id')
                  ->references('id')->on('options')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
