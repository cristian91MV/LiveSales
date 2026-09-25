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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('code', 50)->unique();

            $table->string('name', 150);

            $table->text('description')->nullable();

            $table->string('size', 50)->nullable();

            $table->decimal('base_price', 10, 2);

            $table->string('condition', 30);

            $table->text('detail_description')->nullable();

            $table->string('status', 20)
                ->default('DISPONIBLE');

            $table->timestamps();

            $table->index('status');
            $table->index('condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
