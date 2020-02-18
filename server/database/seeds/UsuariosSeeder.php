<?php

use App\ClienteUsuario;
use App\Cliente;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuarios = [
            [
                'name' => 'Isabella González',
                'email' => 'isabella@gmail.com',
                'password' => '12345678'
            ],
             [
                'name' => 'Benjamin Rodríguez',
                'email' => 'benja@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Martina Gómez',
                'email' => 'martina@gmail.com',
                'password' => '12345678'
            ],
             [
                'name' => 'Lorenzo Fernández',
                'email' => 'lorenzo@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Francesca López',
                'email' => 'francesca@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Mateo Díaz',
                'email' => 'mateo@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Delfina Martínez',
                'email' => 'delfi@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Joaquín Pérez',
                'email' => 'joaquin@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Valentina Romero',
                'email' => 'valentina@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Santino Sánchez',
                'email' => 'santino@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Juan Ignacio García',
                'email' => 'juanignacio@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Emilia Sosa',
                'email' => 'emilia@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Bautista Torres',
                'email' => 'bautista@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Emma Ramírez',
                'email' => 'emma@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Benicio Álvarez',
                'email' => 'benicio@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Catalina Benítez',
                'email' => 'catalina@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Valentino Acosta',
                'email' => 'valentino@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Sofía Flores',
                'email' => 'sofia@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Felipe Medina',
                'email' => 'felipe@gmail.com',
                'password' => '12345678'
            ],
            [
                'name' => 'Olivia Ruiz',
                'email' => 'olivia@gmail.com',
                'password' => '12345678'
            ]
        ];

        foreach ($usuarios as $usuario) {
            $nuevoCliente = new Cliente();
            $nuevoCliente->nombre = $usuario['name'];

            $nuevoUsuario = new ClienteUsuario();
            $nuevoUsuario->fill($usuario);
            $nuevoUsuario->cliente()->associate($nuevoCliente);
            $nuevoUsuario->save();
        }
    }
}
