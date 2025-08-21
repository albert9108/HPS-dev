<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUsersTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('users');
    }

    public function down()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('student_id')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }
}
