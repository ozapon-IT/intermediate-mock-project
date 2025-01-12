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
        Schema::create('break_correct_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_correct_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attendance_break_id')->constrained()->cascadeOnDelete();
            $table->datetime('old_break_in');
            $table->datetime('old_break_out')->nullable();
            $table->datetime('new_break_in');
            $table->datetime('new_break_out');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('break_correct_requests');
    }
};