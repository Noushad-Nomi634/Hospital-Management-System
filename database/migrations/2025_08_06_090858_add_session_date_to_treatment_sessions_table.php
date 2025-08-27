<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('treatment_sessions', function (Blueprint $table) {
            $table->date('session_date')->nullable()->after('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('treatment_sessions', function (Blueprint $table) {
            $table->dropColumn('session_date');
        });
    }
};
