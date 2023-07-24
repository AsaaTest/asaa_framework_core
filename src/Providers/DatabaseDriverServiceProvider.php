<?php

namespace Asaa\Providers;

use Asaa\Database\Drivers\DatabaseDriver;
use Asaa\Database\Drivers\PdoDriver;

/**
 * Clase DatabaseDriverServiceProvider que implementa la interfaz ServiceProvider.
 *
 * Este proveedor de servicios se encarga de registrar el controlador de base de datos que se utilizará en la aplicación.
 * El controlador de base de datos se define según la configuración de conexión proporcionada en el archivo de configuración "database.php".
 * En caso de que no se especifique una configuración, el controlador de base de datos predeterminado será el PdoDriver.
 */
class DatabaseDriverServiceProvider implements ServiceProvider
{
    /**
     * Registra el servicio del controlador de base de datos de acuerdo a la configuración.
     *
     * Este método verifica la configuración de conexión definida en el archivo de configuración "database.php".
     * Si se especifica la conexión como "mysql" o "pgsql", se registra el PdoDriver como el controlador de base de datos predeterminado.
     * Si se agrega soporte para otros tipos de conexiones, se pueden agregar más casos en el bloque "match" para registrar los correspondientes controladores de base de datos.
     * En caso de que no se especifique una configuración válida, el controlador de base de datos predeterminado será el PdoDriver.
     *
     * @return void
     */
    public function registerServices()
    {
        match (config("database.connection", "mysql")) {
            "mysql", "pgsql" => singleton(DatabaseDriver::class, PdoDriver::class)
        };
    }
}
