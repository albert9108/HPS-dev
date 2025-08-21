<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPasswordToStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Add the password column with a default value
            $table->string('password')->default('default_password');
        });


    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the password column
            $table->dropColumn('password');
        });
    }
}
