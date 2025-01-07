<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id_role'); // Primary key
            $table->string('nom');  // Role name
            $table->timestamps();
        });

        // Inserting default roles
        DB::table('roles')->insert([
            ['id_role' => 1, 'nom' => 'admin'],
            ['id_role' => 2, 'nom' => 'employé']
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }

};
