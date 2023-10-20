<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->double('category_percentage');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

    }


    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
