<?php

use Illuminate\Database\Seeder;
use App\User;

class UsuariosDevelopeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //usuario por defecto para pruebas

        $usuarios = array(
            ['name' => 'AppLab', 'email' => 'applab@dev.com', 'password' => Hash::make('12345678')],
            ['name' => 'Cirel Romeo', 'email' => 'romeo@dev.com', 'password' => Hash::make('Cr123456')]
        );

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}

