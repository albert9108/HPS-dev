<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSIdToStudentsTable extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Add the new s_id column
            $table->unsignedBigInteger('s_id')->nullable()->after('id');

            // Make the id column auto-incremental
            $table->bigIncrements('id')->change();

            // Set s_id as the primary key
            $table->primary('s_id');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the s_id column
            $table->dropColumn('s_id');

            // Revert the id column to its original state
            $table->unsignedBigInteger('id')->change();
        });
    }
}
