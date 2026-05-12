<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            $table->unsignedBigInteger('client_id')->nullable()->after('id');
            $table->unsignedBigInteger('commande_id')->nullable()->after('client_id');
            $table->decimal('montant_total', 10, 2)->default(0)->after('commande_id');
            $table->enum('statut', ['Payé', 'Non payé'])->default('Non payé')->after('montant_total');
            
            // Ajouter les clés étrangères (optionnel)
            // $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            // $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn(['client_id', 'commande_id', 'montant_total', 'statut']);
        });
    }
};