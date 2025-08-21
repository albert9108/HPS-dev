<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdColumnToAutoIncrementInStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the primary key constraint if it exists
            $table->dropPrimary(['id']);

            // Change the id column to integer and set it to auto-increment
            $table->bigIncrements('id')->change();
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the primary key constraint
            $table->dropPrimary(['id']);

            // Change the id column back to string
            $table->string('id')->change();

            // Re-add the primary key constraint
            $table->primary('id');
        });
    }
}
