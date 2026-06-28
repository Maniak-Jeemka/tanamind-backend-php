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
        Schema::create('scan_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('disease_id')->nullable()->constrained('diseases')->onDelete('set null');
            $table->string('image_path');
            $table->string('disease_label');
            $table->float('disease_confidence');
            $table->string('severity_label')->nullable();
            $table->float('severity_confidence')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_shared')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_results');
    }
};
