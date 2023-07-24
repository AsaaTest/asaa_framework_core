<?php

namespace Asaa\Storage;

use Asaa\Storage\Storage;

/**
 * Clase File que representa un archivo y proporciona métodos para su manipulación y almacenamiento.
 */
class File
{
    /**
     * Constructor de la clase File.
     *
     * @param mixed $content Contenido del archivo.
     * @param string $type Tipo MIME del archivo.
     * @param string $originalName Nombre original del archivo.
     */
    public function __construct(
        private mixed $content,
        private string $type,
        private string $originalName,
    ) {
        // Inicializa las propiedades de la clase con los valores proporcionados en el constructor.
        $this->content = $content;
        $this->type = $type;
        $this->originalName = $originalName;
    }

    /**
     * Verifica si el archivo actual es una imagen.
     *
     * @return bool True si el archivo es una imagen, False en caso contrario.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->type, "image");
    }

    /**
     * Obtiene la extensión del archivo basada en el tipo MIME.
     *
     * @return string|null La extensión del archivo o null si no se reconoce el tipo MIME.
     */
    public function extension(): ?string
    {
        // Utiliza una estructura "match" para determinar la extensión del archivo según el tipo MIME.
        // Si el tipo MIME no coincide con las opciones especificadas, se retorna null.
        return match ($this->type) {
            "image/jpeg" => "jpeg",
            "image/png" => "png",
            "application/pdf" => "pdf",
            default => null,
        };
    }

    /**
     * Almacena el archivo en el directorio de almacenamiento.
     *
     * @param string|null $directory Directorio opcional dentro del directorio de almacenamiento donde se almacenará el archivo.
     * @return string URL del archivo almacenado.
     */
    public function store(?string $directory = null): string
    {
        // Genera un nombre único para el archivo mediante la función uniqid() y le agrega la extensión obtenida.
        $file = uniqid() . $this->extension();

        // Comprueba si se proporcionó un directorio dentro del directorio de almacenamiento.
        // Si no se proporcionó, el archivo se almacenará en el directorio raíz del almacenamiento.
        $path = is_null($directory) ? $file : "$directory/$file";

        // Utiliza la clase Storage para almacenar el archivo en el directorio de almacenamiento y obtiene la URL del archivo almacenado.
        return Storage::put($path, $this->content);
    }
}
