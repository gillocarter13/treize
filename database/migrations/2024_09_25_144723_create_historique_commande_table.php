<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriqueCommandeTable extends Migration
{
    public function up()
    {
        Schema::create('historique_commande', function (Blueprint $table) {
            $table->id('id_commande');
            $table->unsignedBigInteger('id_produit');
            $table->unsignedBigInteger('id_fournisseur');
            $table->integer('quantité')->default(1); // Valeur par défaut de 1
            $table->decimal('prix_unitaire', 8, 2);
            $table->integer('seuil_alerte')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('historique_commande');
    }
}
