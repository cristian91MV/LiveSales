<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);

            $table->string('tiktok_username', 100)
                ->nullable()
                ->unique();

            $table->string('whatsapp', 30)
                ->nullable()
                ->unique();

            $table->timestamps();

            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
