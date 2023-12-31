<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->decimal('item_price');
            $table->string('image')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
