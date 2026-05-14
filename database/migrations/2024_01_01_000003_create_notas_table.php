<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->decimal('bimestre1', 4, 1);
            $table->decimal('bimestre2', 4, 1);
            $table->decimal('bimestre3', 4, 1);
            $table->decimal('bimestre4', 4, 1);
            $table->decimal('media', 4, 2)->nullable();
            $table->char('conceito', 1)->nullable();
            $table->decimal('nota_recuperacao', 4, 1)->nullable();
            $table->boolean('aprovado_recuperacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
