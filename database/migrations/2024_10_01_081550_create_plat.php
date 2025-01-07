<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('plat', function (Blueprint $table) {
            $table->id('id_plat');
            $table->string('nom');
            $table->unsignedBigInteger('id_produit');
            $table->integer('quantité')->default(1); // Valeur par défaut de 1
   
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plat');
    }
};
