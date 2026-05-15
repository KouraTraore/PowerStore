<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->constrained('factures')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
           $table->string('reference', 191)->unique();
            $table->date('date_paiement');
            $table->enum('mode', ['especes', 'carte', 'mobile_money', 'virement']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};