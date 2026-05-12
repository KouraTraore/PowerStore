<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            // Champs de paiement
            $table->enum('mode_paiement', ['Espèces', 'Carte', 'Mobile Money', 'Virement'])->nullable()->after('statut');
            $table->date('date_paiement')->nullable()->after('mode_paiement');
            $table->string('reference_paiement')->nullable()->after('date_paiement');
            $table->decimal('montant_paye', 10, 2)->default(0)->after('reference_paiement');
            $table->decimal('reste_a_payer', 10, 2)->default(0)->after('montant_paye');
            
            // Champs pour l'envoi email
            $table->string('email_envoye')->default('Non')->after('reste_a_payer');
            $table->timestamp('date_envoi_email')->nullable()->after('email_envoye');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn([
                'mode_paiement', 
                'date_paiement', 
                'reference_paiement', 
                'montant_paye', 
                'reste_a_payer',
                'email_envoye',
                'date_envoi_email'
            ]);
        });
    }
};