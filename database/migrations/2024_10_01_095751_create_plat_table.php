<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plat', function (Blueprint $table) {
            $table->id('id_plat');
            $table->string('nom');
            $table->text('description');
            $table->decimal('prix', 8, 2)->nullable(); // Optionnel: Prix du plat
            $table->json('produits_quantites'); // Stockage des produits et quantités en JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plat');
    }
}
