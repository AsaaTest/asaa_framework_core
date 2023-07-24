<?php

namespace Asaa\Validation;

use Asaa\Validation\Exceptions\RuleParseException;
use Asaa\Validation\Exceptions\UnknowRuleException;
use Asaa\Validation\Rules\Email;
use Asaa\Validation\Rules\LessThan;
use Asaa\Validation\Rules\Number;
use Asaa\Validation\Rules\Required;
use Asaa\Validation\Rules\RequiredWhen;
use Asaa\Validation\Rules\RequiredWith;
use Asaa\Validation\Rules\ValidationRule;
use ReflectionClass;

/**
 * Clase Rule
 *
 * Esta clase representa una colección de reglas de validación que pueden aplicarse
 * a los datos de entrada. Proporciona métodos para cargar reglas predefinidas,
 * así como para analizar y crear reglas personalizadas.
 */
class Rule
{
    private static array $rules = [];

    private static array $defaultRules = [
        Required::class,
        RequiredWith::class,
        RequiredWhen::class,
        Number::class,
        LessThan::class,
        Email::class
    ];

    /**
     * Carga las reglas predefinidas por defecto en la colección de reglas disponibles.
     */
    public static function loadDefaultRules()
    {
        self::load(self::$defaultRules);
    }

    /**
     * Carga reglas personalizadas en la colección de reglas disponibles.
     *
     * @param array $rules Un array de nombres de clases de reglas personalizadas.
     */
    public static function load(array $rules)
    {
        foreach($rules as $class) {
            $className = array_slice(explode("\\", $class), -1)[0];
            $ruleName = snake_case($className);
            self::$rules[$ruleName] = $class;
        }
    }

    /**
     * Obtiene el nombre de una regla a partir de su instancia.
     *
     * @param ValidationRule $rule La regla de validación de la que se desea obtener el nombre.
     * @return string El nombre de la regla en formato snake_case.
     */
    public static function nameOf(ValidationRule $rule): string
    {
        $class = new ReflectionClass($rule);
        return snake_case($class->getShortName());
    }

    // Métodos para crear instancias de reglas predefinidas

    /**
     * Crea una instancia de la regla de validación "Email".
     *
     * @return ValidationRule Una instancia de la regla de validación "Email".
     */
    public static function email(): ValidationRule
    {
        return new Email();
    }

    /**
     * Crea una instancia de la regla de validación "Required".
     *
     * @return ValidationRule Una instancia de la regla de validación "Required".
     */
    public static function required(): ValidationRule
    {
        return new Required();
    }

    /**
     * Crea una instancia de la regla de validación "RequiredWith".
     *
     * @param string $withField El nombre del campo requerido cuando se cumple una condición.
     * @return ValidationRule Una instancia de la regla de validación "RequiredWith".
     */
    public static function requiredWith(string $withField): ValidationRule
    {
        return new RequiredWith($withField);
    }

    /**
     * Crea una instancia de la regla de validación "Number".
     *
     * @return ValidationRule Una instancia de la regla de validación "Number".
     */
    public static function number(): ValidationRule
    {
        return new Number();
    }

    /**
     * Crea una instancia de la regla de validación "LessThan".
     *
     * @param float $value El valor máximo permitido.
     * @return ValidationRule Una instancia de la regla de validación "LessThan".
     */
    public static function lessThan(float $value): ValidationRule
    {
        return new LessThan($value);
    }

    /**
     * Crea una instancia de la regla de validación "RequiredWhen".
     *
     * @param string $otherField El nombre del campo que determina si la regla es requerida.
     * @param string $operator El operador de comparación (ej. '=', '>', '<', '!=').
     * @param int|float $value El valor con el que se compara el campo.
     * @return ValidationRule Una instancia de la regla de validación "RequiredWhen".
     */
    public static function requiredWhen(string $otherField, string $operator, int|float $value): ValidationRule
    {
        return new RequiredWhen($otherField, $operator, $value);
    }

    // Métodos para analizar y crear instancias de reglas personalizadas

    /**
     * Analiza una regla de validación básica y crea una instancia de la misma.
     *
     * @param string $ruleName El nombre de la regla de validación básica a analizar.
     * @return ValidationRule Una instancia de la regla de validación básica especificada.
     * @throws RuleParseException Si la regla requiere parámetros, pero no se proporcionaron.
     */
    public static function parseBasicRule(string $ruleName): ValidationRule
    {
        $class = new ReflectionClass(self::$rules[$ruleName]);
        if(count($class->getConstructor()?->getParameters() ?? []) > 0) {
            throw new RuleParseException("Rule $ruleName requiere parámetros, pero no se proporcionaron.");
        }
        return $class->newInstance();
    }

    /**
     * Analiza una regla de validación con parámetros y crea una instancia de la misma.
     *
     * @param string $ruleName El nombre de la regla de validación a analizar.
     * @param string $params Los parámetros separados por comas requeridos por la regla.
     * @return ValidationRule Una instancia de la regla de validación con parámetros especificados.
     * @throws RuleParseException Si el número de parámetros dados no coincide con los requeridos por la regla.
     */
    public static function parseRuleWithParameters(string $ruleName, string $params): ValidationRule
    {
        $class = new ReflectionClass(self::$rules[$ruleName]);
        $constructorParameters = $class->getConstructor()?->getParameters() ?? [];
        $givenParameters = array_filter(explode(",", $params), fn ($p) => !empty($p));

        if(count($givenParameters) !== count($constructorParameters)) {
            throw new RuleParseException(sprintf(
                "Rule %s requiere %d parámetros, pero se proporcionaron %d: %s",
                $ruleName,
                count($constructorParameters),
                count($givenParameters),
                $params
            ));
        }

        return $class->newInstance(...$givenParameters);
    }

    /**
     * Crea una instancia de una regla de validación a partir de una cadena de texto.
     *
     * @param string $str La cadena de texto que contiene el nombre de la regla y, opcionalmente, sus parámetros.
     * @return ValidationRule Una instancia de la regla de validación especificada en la cadena de texto.
     * @throws RuleParseException Si la cadena está vacía o si el nombre de la regla no es reconocido.
     */
    public static function from(string $str): ValidationRule
    {
        if(strlen($str) == 0) {
            throw new RuleParseException("No se puede analizar una cadena de texto vacía como regla de validación.");
        }

        $ruleParts = explode(":", $str);

        if(!array_key_exists($ruleParts[0], self::$rules)) {
            throw new UnknowRuleException("Regla {$ruleParts[0]} no encontrada.");
        }

        if(count($ruleParts) == 1) {
            return self::parseBasicRule($ruleParts[0]);
        }

        [$ruleName, $params] = $ruleParts;

        return self::parseRuleWithParameters($ruleName, $params);

    }

}
