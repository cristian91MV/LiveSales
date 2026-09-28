<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('live_sessions', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);

            $table->dateTime('scheduled_at')
                ->nullable();

            $table->dateTime('started_at')
                ->nullable();

            $table->dateTime('ended_at')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->string('status', 20)
                ->default('PROGRAMADO');

            $table->timestamps();

            $table->index('status');
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_sessions');
    }
};
