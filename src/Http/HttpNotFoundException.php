<?php

namespace Asaa\Http;

use Asaa\Exceptions\AsaaException;

/**
 * Excepción HTTP 404 Not Found.
 * Esta excepción se lanza cuando el servidor no encuentra el recurso o página solicitada por el cliente.
 * Es utilizada para indicar que la ruta o URL solicitada no existe en la aplicación.
 *
 * Ejemplo de uso:
 * try {
 *     // Código que intenta encontrar un recurso en la aplicación
 * } catch (HttpNotFoundException $e) {
 *     // Manejo de la excepción cuando se produce un error 404
 *     header("HTTP/1.0 404 Not Found");
 *     echo "Error 404: Página no encontrada";
 *     // Otra lógica o redireccionamiento en caso de error 404
 * }
 */
class HttpNotFoundException extends AsaaException
{
    // Aquí se podrían agregar métodos o propiedades específicas de la excepción, si es necesario.
}
