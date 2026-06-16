<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // ex : Mensuel, Trimestriel, Annuel
            $table->unsignedInteger('duration_days'); // durée en jours
            $table->decimal('price', 8, 2);          // prix
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_types');
    }
};
