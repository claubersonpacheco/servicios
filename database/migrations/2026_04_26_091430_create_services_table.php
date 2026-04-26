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
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('code');
            $table->string('address')->nullable();
            $table->string('postal')->nullable();
            $table->text('description')->nullable();


            // 📌 status do serviço
            $table->enum('status', ['abierto', 'en_andamiento', 'cerrado'])
                ->default('abierto');

            // 📅 datas e horas separadas
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->time('hour_start')->nullable();
            $table->time('hour_end')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
