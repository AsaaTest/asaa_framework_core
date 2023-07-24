<?php

namespace Asaa\Storage\Drivers;

/**
 * Clase DiskFileStorage que implementa la interfaz FileStorageDriver.
 * Esta clase se utiliza para almacenar archivos en el sistema de archivos local (disco).
 */
class DiskFileStorage implements FileStorageDriver
{
    /**
     * Directorio donde se deben almacenar los archivos.
     *
     * @var string
     */
    protected string $storageDirectory;

    /**
     * URL de la aplicación.
     *
     * @var string
     */
    protected string $appUrl;

    /**
     * URI del directorio de almacenamiento público.
     *
     * @var string
     */
    protected string $storageUri;

    /**
     * Instancia DiskFileStorage.
     *
     * @param string $storageDirectory Directorio de almacenamiento de archivos.
     * @param string $storageUri URI del directorio de almacenamiento público.
     * @param string $appUrl URL de la aplicación.
     */
    public function __construct(string $storageDirectory, string $storageUri, string $appUrl)
    {
        $this->storageDirectory = $storageDirectory;
        $this->storageUri = $storageUri;
        $this->appUrl = $appUrl;
    }

    /**
     * Almacena un archivo en el sistema de archivos local (disco).
     *
     * @param string $path Ruta del archivo.
     * @param mixed $content Contenido del archivo a almacenar.
     * @return string La URL del archivo almacenado.
     */
    public function put(string $path, mixed $content): string
    {
        // Verificar si el directorio de almacenamiento existe, si no, crearlo.
        if (!is_dir($this->storageDirectory)) {
            mkdir($this->storageDirectory);
        }

        // Separar la ruta en segmentos (directorios y nombre de archivo).
        $directories = explode("/", $path);
        $file = array_pop($directories);
        $dir = "$this->storageDirectory/";

        // Si la ruta tiene directorios, asegurarse de que también existan en el sistema de archivos.
        if (count($directories) > 0) {
            $dir = $this->storageDirectory . "/" . implode("/", $directories);
            @mkdir($dir, recursive: true);
        }

        // Escribir el contenido del archivo en el sistema de archivos.
        file_put_contents("$dir/$file", $content);

        // Devolver la URL completa del archivo almacenado.
        return "$this->appUrl/$this->storageUri/$path";
    }
}
