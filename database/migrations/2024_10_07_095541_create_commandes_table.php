<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandesTable extends Migration
{
  public function up()
  {
    Schema::create('commandes', function (Blueprint $table) {
      $table->id();
      $table->string('status'); // Statut de la commande (ex: en attente, confirmé, etc.)
      $table->timestamps(); // Date de création et mise à jour de la commande
    });
  }

  public function down()
  {
    Schema::dropIfExists('commandes');
  }
}
