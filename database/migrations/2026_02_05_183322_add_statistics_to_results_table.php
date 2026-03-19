<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {

            $table->integer('total_questions')->default(0);
            $table->integer('correct')->default(0);
            $table->integer('wrong')->default(0);
            $table->integer('not_attempted')->default(0);

        });
    }

    public function down()
    {
        Schema::table('results', function (Blueprint $table) {

            $table->dropColumn([
                'total_questions',
                'correct',
                'wrong',
                'not_attempted'
            ]);

        });
    }

};
