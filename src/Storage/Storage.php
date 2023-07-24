<?php

namespace Asaa\Storage;

use Asaa\Storage\Drivers\FileStorageDriver;

/**
 * Clase Storage que proporciona utilidades para almacenamiento de archivos.
 */
class Storage
{
    /**
     * Almacena un archivo en el directorio de almacenamiento.
     *
     * @param string $path Ruta del archivo en el directorio de almacenamiento.
     * @param mixed $content Contenido del archivo que se desea almacenar.
     * @return string URL del archivo almacenado.
     */
    public static function put(string $path, mixed $content): string
    {
        // Utiliza el controlador de almacenamiento de archivos (FileStorageDriver) para almacenar el archivo.
        // El método "put" del controlador se encarga de realizar la operación de almacenamiento real.
        return app(FileStorageDriver::class)->put($path, $content);
    }
}
