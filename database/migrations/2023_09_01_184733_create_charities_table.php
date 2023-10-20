<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->json('phones');
            $table->text('address');
            $table->string('website');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charities');
    }
};
