<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('amount_of_money');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

        });

    }

    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
