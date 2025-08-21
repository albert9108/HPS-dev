<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeyFromStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['student_id']);
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Re-add the foreign key constraint
            $table->foreign('student_id')->references('student_id')->on('users')->onDelete('cascade');
        });
    }
}
