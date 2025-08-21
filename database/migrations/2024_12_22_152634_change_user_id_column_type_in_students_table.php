<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUserIdColumnTypeInStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Change the user_id column to varchar
            $table->string('user_id')->change();
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Revert the user_id column to unsignedBigInteger
            $table->unsignedBigInteger('user_id')->change();
        });
    }
}
