<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFournisseurTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fournisseur', function (Blueprint $table) {
            $table->int('id_fournisseur'); // Crée une colonne id_fournisseur de type BIGINT avec auto_increment
            $table->string('nom'); // Crée une colonne nom
            $table->string('contact'); // Crée une colonne contact
            $table->string('adresse'); // Crée une colonne adresse
            $table->timestamps(); // Crée les colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fournisseur');
    }
}
