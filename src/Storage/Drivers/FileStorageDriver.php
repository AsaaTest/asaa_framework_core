<?php

namespace Asaa\Storage\Drivers;

/**
 * Interfaz FileStorageDriver que define el contrato para los controladores de almacenamiento de archivos.
 */
interface FileStorageDriver
{
    /**
     * Almacena un archivo.
     *
     * @param string $path Ruta del archivo.
     * @param mixed $content Contenido del archivo a almacenar.
     * @return string La URL del archivo almacenado.
     */
    public function put(string $path, mixed $content): string;
}
