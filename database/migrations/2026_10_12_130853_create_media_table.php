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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('path');                                    // путь файла ВНУТРИ диска s3-fake
            $table->foreignId('post_id')->constrained()->cascadeOnDelete(); // пост удалён → строки media удалены
            $table->string('type');                                    // MIME-тип: image/jpeg, application/pdf...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
