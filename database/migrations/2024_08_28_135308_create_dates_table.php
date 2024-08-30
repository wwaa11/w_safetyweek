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
        Schema::create('dates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('date_string');
            $table->string('time');
            $table->integer('round');
            $table->integer('slot')->default(90);
            $table->integer('level1')->default(15);
            $table->integer('level2')->default(15);
            $table->integer('level3')->default(15);
            $table->integer('level4')->default(15);
            $table->integer('level5')->default(15);
            $table->integer('level6')->default(15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dates');
    }
};
