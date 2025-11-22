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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('room_id')->index();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('emergency')->default(false);
            $table->unsignedBigInteger('doctor_id')->index();
            $table->softDeletes();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
