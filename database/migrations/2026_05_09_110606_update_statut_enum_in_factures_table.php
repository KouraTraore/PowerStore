<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modifier l'ENUM pour accepter 'Partiel'
        DB::statement("ALTER TABLE factures MODIFY COLUMN statut ENUM('Payé', 'Non payé', 'Partiel') DEFAULT 'Non payé'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE factures MODIFY COLUMN statut ENUM('Payé', 'Non payé') DEFAULT 'Non payé'");
    }
};