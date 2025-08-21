<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add any additional columns if needed
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop any additional columns if needed
        });
    }
}
