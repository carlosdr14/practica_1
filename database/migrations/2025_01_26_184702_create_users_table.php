<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Crear la tabla 'users'
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('name'); // Nombre del usuario
            $table->string('email')->unique(); // Email del usuario, debe ser único
            $table->string('password'); // Contraseña del usuario
            $table->string('two_factor_code')->nullable(); // Código de autenticación de dos factores, puede ser nulo
            $table->boolean('active')->default(false); // Estado de activación del usuario, por defecto es falso
            $table->timestamp('two_factor_expires_at')->nullable(); // Fecha de expiración del código de autenticación de dos factores, puede ser nula
            $table->rememberToken(); // Token para recordar al usuario
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar la tabla 'users' si existe
        Schema::dropIfExists('users');
    }
}