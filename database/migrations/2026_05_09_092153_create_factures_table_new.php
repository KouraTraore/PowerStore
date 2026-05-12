<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer l'ancienne table si elle existe
        Schema::dropIfExists('factures');
        
        // Créer la nouvelle table
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('commande_id')->nullable();
            $table->decimal('montant_total', 10, 2);
            $table->enum('statut', ['Payé', 'Non payé'])->default('Non payé');
            $table->timestamps();
            
            // Clés étrangères
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};