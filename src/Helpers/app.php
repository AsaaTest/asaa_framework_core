<?php

use Asaa\App;
use Asaa\Config\Config;
use Asaa\container\Container;

/**
 * Resuelve una instancia de la clase especificada utilizando el contenedor de dependencias.
 * Si no se proporciona ninguna clase, se utilizará la clase App por defecto.
 *
 * @param string|null $class El nombre de la clase a resolver como instancia (opcional).
 * @return object|null Una instancia de la clase si existe o null si no existe.
 */
function app($class = App::class)
{
    return Container::resolve($class);
}

/**
 * Registra una instancia de clase como un singleton en el contenedor de dependencias.
 * Si la instancia ya existe, la función la retorna; de lo contrario, la crea y la almacena para futuras referencias.
 * Puedes proporcionar una clase o una función que construya la instancia.
 * También puedes usar singleton para resolver instancias en lugar de app si quieres asegurarte de obtener siempre la misma instancia de una clase.
 *
 * @param string $class El nombre de la clase a registrar como singleton.
 * @param string|callable|null $build El nombre de la clase o una función que construye la instancia (opcional).
 * @return object|null Una instancia de la clase si existe o null si no existe.
 */
function singleton(string $class, string|callable|null $build = null)
{
    return Container::singleton($class, $build);
}

/**
 * Obtiene el valor de una variable de entorno especificada.
 * Si la variable no existe, se devolverá el valor predeterminado proporcionado.
 * La función utiliza la variable superglobal $_ENV para acceder a las variables de entorno.
 *
 * @param string $variable El nombre de la variable de entorno a obtener.
 * @param mixed $default El valor predeterminado a devolver si la variable de entorno no existe (opcional).
 * @return mixed El valor de la variable de entorno si existe o el valor predeterminado si no existe.
 */
function env(string $variable, $default = null)
{
    return $_ENV[$variable] ?? $default;
}

/**
 * Obtiene el valor de una configuración especificada utilizando la clase Config.
 * Puedes usar esta función para acceder a la configuración de tu aplicación de forma sencilla.
 *
 * @param string $configuration La clave de configuración que se desea obtener.
 * @param mixed $default El valor predeterminado a devolver si la configuración no existe (opcional).
 * @return mixed El valor de la configuración si existe o el valor predeterminado si no existe.
 */
function config(string $configuration, $default = null)
{
    return Config::get($configuration, $default);
}

/**
 * Devuelve la ruta completa del directorio de recursos de la aplicación.
 * Utiliza la propiedad estática App::$root para obtener la ruta base de la aplicación y la concatena con "/resources".
 *
 * @return string La ruta completa del directorio de recursos de la aplicación.
 */
function resourcesDirectory(): string
{
    return App::$root . "/resources";
}
