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
        Schema::table('users', function (Blueprint $table) {
            $table->string('college_name')->nullable()->after('email');
            $table->string('registration_no')->nullable()->after('college_name');
            $table->string('semester')->nullable()->after('registration_no');
            $table->string('phone')->nullable()->after('semester');
            $table->string('sex')->nullable()->after('phone');
            $table->string('profile_photo')->nullable()->after('sex');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'college_name',
                'registration_no',
                'semester',
                'phone',
                'profile_photo'
            ]);
        });
    }

};
