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
        Schema::create('yoeb_notification_details', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("brief")->nullable();
            $table->longText("description")->nullable();
            $table->string("image")->nullable();
            $table->json("extra")->nullable();
            $table->timestamps();     
            $table->softDeletes();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yoeb_notification_details');
    }
};
