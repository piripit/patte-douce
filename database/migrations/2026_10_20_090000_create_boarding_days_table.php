<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Un jour ouvert aux gardes (un chien ou plusieurs, selon la capacité)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boarding_days', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->unsignedTinyInteger('capacity')->default(1);
            $table->boolean('is_open')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_days');
    }
};
