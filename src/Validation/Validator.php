<?php

namespace Asaa\Validation;

use Asaa\Validation\Exceptions\ValidationException;

/**
 * Clase Validator
 *
 * se encarga de validar un conjunto de datos proporcionados contra un conjunto de reglas de validación definidas previamente.
 * Permite especificar reglas de validación para cada campo de los datos y, en caso de que algún campo no cumpla con las reglas establecidas, se generarán mensajes de error que indican el motivo del fallo.
 */
class Validator
{
    protected array $data;

    /**
     * Constructor de la clase Validator.
     *
     * @param array $data Los datos que se van a validar.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Valida un conjunto de reglas de validación para los campos proporcionados y retorna un array con los datos validados.
     *
     * @param array $validationRules Un array que contiene las reglas de validación para cada campo.
     * @param array $messages Un array opcional que contiene mensajes personalizados para las reglas de validación.
     *
     * @return array Un array con los datos validados.
     * @throws ValidationException Si se encuentran errores de validación, se lanza una excepción con los errores encontrados.
     */
    public function validate(array $validationRules, array $messages = []): array
    {
        $validated = []; // Almacenará los datos validados
        $errors = []; // Almacenará los errores de validación encontrados

        // Iterar sobre cada campo y sus reglas de validación
        foreach ($validationRules as $field => $rules) {
            if (!is_array($rules)) {
                $rules = [$rules]; // Asegurarse de que las reglas sean siempre un array
            }
            $fieldUnderValidationErrors = []; // Almacenará los errores de validación para el campo actual

            // Iterar sobre cada regla del campo
            foreach ($rules as $rule) {
                if (is_string($rule)) {
                    $new_rules = explode('|', $rule); // Si la regla es un string, separarla en varias reglas
                    foreach ($new_rules as $rule_string) {
                        $rule = Rule::from($rule_string); // Convertir el string en una instancia de la regla
                        if (!$rule->isValid($field, $this->data)) {
                            // Si la regla no es válida para el campo, obtener el mensaje correspondiente o el mensaje predeterminado
                            $message = $messages[$field][Rule::nameOf($rule)] ?? $rule->message();
                            $fieldUnderValidationErrors[Rule::nameOf($rule)] = $message;
                        }
                    }
                } else {
                    // Si la regla es una instancia de Rule, validarla directamente
                    if (!$rule->isValid($field, $this->data)) {
                        // Si la regla no es válida para el campo, obtener el mensaje correspondiente o el mensaje predeterminado
                        $message = $messages[$field][Rule::nameOf($rule)] ?? $rule->message();
                        $fieldUnderValidationErrors[Rule::nameOf($rule)] = $message;
                    }
                }
            }

            // Si hay errores de validación para el campo, agregarlos al array de errores; de lo contrario, agregar el dato validado al array correspondiente
            if (count($fieldUnderValidationErrors) > 0) {
                $errors[$field] = $fieldUnderValidationErrors;
            } else {
                $validated[$field] = $this->data[$field] ?? null;
            }
        }

        // Si hay errores de validación, lanzar una excepción con los errores encontrados
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return $validated; // Devolver los datos validados
    }
}
