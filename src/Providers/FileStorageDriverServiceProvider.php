<?php

namespace Asaa\Providers;

use Asaa\App;
use Asaa\Storage\Drivers\DiskFileStorage;

/**
 * Clase FileStorageDriverServiceProvider.
 *
 * Este proveedor de servicios se encarga de registrar el controlador de almacenamiento de archivos que se utilizará en la aplicación.
 * El controlador de almacenamiento de archivos se define según la configuración proporcionada en el archivo de configuración "storage.php".
 * En caso de que no se especifique una configuración, el controlador de almacenamiento de archivos predeterminado será el DiskFileStorage.
 */
class FileStorageDriverServiceProvider
{
    /**
     * Registra el servicio del controlador de almacenamiento de archivos de acuerdo a la configuración.
     *
     * Este método verifica la configuración de almacenamiento definida en el archivo de configuración "storage.php".
     * Si se especifica "disk" como el driver de almacenamiento, se registra el DiskFileStorage como el controlador de almacenamiento de archivos predeterminado.
     * El DiskFileStorage requiere la ruta de almacenamiento, el directorio raíz de la aplicación y la URL de la aplicación para funcionar correctamente.
     * El DiskFileStorage se inicializa con una función anónima que devuelve una nueva instancia del controlador con los parámetros necesarios.
     * Si se agrega soporte para otros tipos de controladores de almacenamiento, se pueden agregar más casos en el bloque "match".
     * En caso de que no se especifique una configuración válida, el controlador de almacenamiento de archivos predeterminado será el DiskFileStorage.
     *
     * @return void
     */
    public function registerServices()
    {
        match (config("storage.driver", "disk")) {
            "disk" => singleton(
                FileStorageDriver::class,
                fn () => new DiskFileStorage(
                    App::$root . "/storage",
                    "storage",
                    config("app.url")
                )
            ),
        };
    }
}
