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
       Schema::create('patients', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->nullable(); // or without nullable if required

    $table->string('phone')->nullable();
    $table->text('address')->nullable();
    $table->text('medical_history')->nullable();
    $table->unsignedBigInteger('branch_id');
    $table->timestamps();

    $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
