<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdColumnTypeInStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the primary key constraint
            $table->dropPrimary('id');

            // Change the id column to string
            $table->string('id')->change();

            // Re-add the primary key constraint
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the primary key constraint
            $table->dropPrimary('id');

            // Change the id column back to integer
            $table->integer('id')->change();

            // Re-add the primary key constraint
            $table->primary('id');
        });
    }
}
