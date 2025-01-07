<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailCommandesTable extends Migration
{
    public function up()
    {
        Schema::create('detail_commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->onDelete('cascade');
            $table->foreignId('id_plat')->constrained('plats');
            $table->string('nom_plat');
            $table->integer('quantite');
            $table->decimal('prix', 8, 2); // Prix unitaire du plat
            $table->timestamps(); // Date de création et mise à jour
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_commandes');
    }
}
