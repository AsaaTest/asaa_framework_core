<?php

namespace Asaa\Validation\Exceptions;

use Asaa\Exceptions\AsaaException;

/**
 * Clase UnknowRuleException
 *
 * Esta clase representa una excepción específica para casos en los que se intenta utilizar
 * una regla de validación desconocida o no registrada en el sistema de validación.
 *
 * Extiende la clase AsaaException, lo que permite manejarla como una excepción genérica.
 */
class UnknowRuleException extends AsaaException
{
    // No se necesitan propiedades o métodos adicionales, ya que esta excepción no lleva información adicional.
}
