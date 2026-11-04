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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('partner_or_brand')->nullable();
            $table->string('email');
            $table->string('whatsapp')->nullable();
            $table->string('instagram')->nullable();
            $table->string('session_types')->nullable();
            $table->date('shoot_date')->nullable();
            $table->string('location')->nullable();
            $table->text('vision');
            $table->string('status')->default('new')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
