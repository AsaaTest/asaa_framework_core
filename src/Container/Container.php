<?php

namespace Asaa\container;

/**
 * Clase Container
 *
 * Esta clase proporciona un contenedor para gestionar y resolver las instancias de las clases como singletons.
 * Permite crear instancias de clases y almacenarlas para futuras referencias, evitando así la creación repetida
 * de instancias para clases que deben comportarse como singletons.
 */
class Container
{
    /**
     * Almacena las instancias de las clases singleton creadas.
     *
     * @var array
     */
    private static array $instances = [];

    /**
     * Resuelve una instancia de una clase como singleton.
     *
     * Si la instancia de la clase ya ha sido creada y almacenada en el arreglo $instances, la retorna.
     * Si la instancia no existe, la crea y la almacena en el arreglo $instances para futuras referencias.
     * Se puede proporcionar un nombre de clase o una función de construcción personalizada.
     *
     * @param string $class El nombre de la clase a resolver como singleton.
     * @param string|callable|null $build Una cadena con el nombre de una clase personalizada a construir
     *                                     o una función de construcción personalizada que retorna la instancia.
     * @return object|null Una instancia de la clase si existe o null si no existe.
     */
    public static function singleton(string $class, string|callable|null $build = null)
    {
        // Verifica si la instancia de la clase ya existe en el arreglo $instances.
        if (!array_key_exists($class, self::$instances)) {
            // Si no existe, crea una nueva instancia de la clase utilizando la reflexión de PHP.
            // La instancia de la clase se almacena en el arreglo $instances para futuras referencias.
            match (true) {
                is_null($build) => self::$instances[$class] = new $class(),
                is_string($build) => self::$instances[$class] = new $build(),
                is_callable($build) => self::$instances[$class] = $build(),
            };
        }

        // Retorna la instancia de la clase.
        return self::$instances[$class];
    }

    /**
     * Resuelve una instancia de una clase.
     *
     * Retorna la instancia de la clase si ya ha sido creada y almacenada en el arreglo $instances.
     * Si la instancia no existe en el arreglo, retorna null.
     *
     * @param string $class El nombre de la clase a resolver.
     * @return object|null Una instancia de la clase si existe o null si no existe.
     */
    public static function resolve(string $class)
    {
        // Retorna la instancia de la clase si existe en el arreglo $instances, de lo contrario, retorna null.
        return self::$instances[$class] ?? null;
    }
}
