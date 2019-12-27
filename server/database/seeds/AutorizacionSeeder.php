<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class AutorizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Resetear roles y permisos de la cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * Clientes
         * Modelos: Cliente, ClienteDomicilio, ClienteTelefono, ClienteEmail, ClienteRazonSocial
         */
        Permission::create(['name' => 'ver clientes', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'crear clientes', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'actualizar clientes', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'eliminar clientes', 'guard_name' => 'empleado']);
    }
}
