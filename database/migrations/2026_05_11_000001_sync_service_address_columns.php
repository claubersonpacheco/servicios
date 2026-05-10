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
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'address_type')) {
                $table->tinyInteger('address_type')->default(1);
            }

            if (! Schema::hasColumn('services', 'number')) {
                $table->string('number')->nullable();
            }

            if (! Schema::hasColumn('services', 'complement')) {
                $table->string('complement')->nullable();
            }

            if (! Schema::hasColumn('services', 'city')) {
                $table->string('city')->nullable();
            }

            if (! Schema::hasColumn('services', 'state')) {
                $table->string('state')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            foreach (['address_type', 'number', 'complement', 'city', 'state'] as $column) {
                if (Schema::hasColumn('services', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
