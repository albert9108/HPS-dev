<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Add this line
            $table->string('email')->unique()->nullable(); // Add this line
            $table->timestamp('email_verified_at')->nullable(); // Add this line
            $table->string('password');
            $table->string('student_id')->unique();
            $table->rememberToken(); // Add this line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
