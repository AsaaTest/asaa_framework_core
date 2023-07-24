<?php

namespace Asaa\Validation\Exceptions;

use Asaa\Exceptions\AsaaException;

/**
 * Clase ValidationException
 *
 * Esta clase representa una excepción específica para errores de validación.
 * Se utiliza para encapsular y proporcionar información detallada sobre los errores
 * de validación encontrados durante la validación de datos.
 *
 * Extiende la clase AsaaException, lo que permite manejarla como una excepción genérica.
 */
class ValidationException extends AsaaException
{
    /**
     * @var array Almacena los errores de validación detallados.
     */
    protected array $errors;

    /**
     * Constructor de la clase ValidationException.
     *
     * @param array $errors Un array que contiene los errores de validación detallados.
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Método errors
     *
     * Obtiene los errores de validación detallados almacenados en esta excepción.
     *
     * @return array Un array con los errores de validación detallados.
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
