<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_type_id')->constrained('property_types')->onDelete('restrict');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price_per_night', 10, 2);
            $table->integer('capacity');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', ['draft', 'published', 'inactive'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};