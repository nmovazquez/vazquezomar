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
        Schema::create('prepedido_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prepedido_id')->constrained('prepedidos')->onDelete('cascade');
            $table->unsignedBigInteger('articulo_id'); // Asegúrate que exista esta tabla o relación
            $table->integer('cantidad');
            $table->decimal('precioDolares', 10, 2);
            $table->decimal('precioPesos', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prepedido_detalle');
    }
};
