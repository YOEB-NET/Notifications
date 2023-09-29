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
        Schema::create('yoeb_notification_category_blocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->json("database")->default(JSON_encode([]));
            $table->json("email")->default(JSON_encode([]));
            $table->json("notification")->default(JSON_encode([]));
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yoeb_notification_category_blocks');
    }
};
