<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
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

        /**
         * Cliente usuarios
         */
        Permission::create(['name' => 'ver usuarios de clientes', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'crear usuarios de clientes', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'actualizar usuarios de clientes', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'eliminar usuarios de clientes', 'guard_name' => 'empleado']);

        /**
         * Provincias
         */
        Permission::create(['name' => 'ver provincias', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'crear provincias', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'actualizar provincias', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'eliminar provincias', 'guard_name' => 'empleado']);

        /**
         * Localidades
         */
        Permission::create(['name' => 'ver localidades', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'crear localidades', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'actualizar localidades', 'guard_name' => 'empleado']);
        Permission::create(['name' => 'eliminar localidades', 'guard_name' => 'empleado']);

        /**
         * Roles
         */
        Role::create(['name' => 'super administrador', 'guard_name' => 'empleado']);
    }
}
