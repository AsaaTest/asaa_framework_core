<?php

namespace Asaa\Config;

/**
 * Clase Config
 *
 * Esta clase proporciona una forma de cargar y acceder a la configuración de la aplicación desde archivos PHP.
 * Los archivos de configuración deben estar en formato PHP y almacenados en una carpeta específica.
 * La clase carga estos archivos y permite acceder a sus valores mediante una clave o un conjunto de claves.
 */
class Config
{
    // Variable estática para almacenar la configuración cargada.
    private static array $config = [];

    /**
     * Carga los archivos de configuración desde una carpeta específica.
     *
     * Cada archivo de configuración debe tener un nombre que será utilizado como clave para acceder a sus valores.
     * Los archivos de configuración deben estar en formato PHP y contener un array asociativo de claves y valores.
     * La función require_once se utiliza para cargar el contenido de cada archivo y almacenar los valores en self::$config.
     *
     * @param string $path La ruta de la carpeta que contiene los archivos de configuración.
     */
    public static function load(string $path)
    {
        foreach (glob("$path/*.php") as $config) {
            // Obtiene el nombre del archivo sin la extensión para usarlo como clave en self::$config.
            $key = explode(".", basename($config))[0];

            // Carga los valores del archivo de configuración y los asigna a la clave correspondiente en self::$config.
            $values = require_once $config;
            self::$config[$key] = $values;
        }
    }

    /**
     * Obtiene el valor de configuración correspondiente a la clave proporcionada.
     *
     * La clave puede ser una cadena con una sola clave, o una cadena con múltiples claves separadas por puntos
     * para acceder a un valor de configuración anidado.
     *
     * @param string $configuration La clave de configuración o claves anidadas separadas por puntos.
     * @param mixed $default El valor predeterminado a devolver si la clave de configuración no se encuentra.
     * @return mixed El valor de configuración correspondiente a la clave, o el valor predeterminado si no se encuentra.
     */
    public static function get(string $configuration, $default = null)
    {
        // Divide la cadena de clave en un arreglo de claves.
        $keys = explode(".", $configuration);

        // Obtiene la última clave del arreglo, que corresponde a la clave final de configuración.
        $finalKey = array_pop($keys);

        // Comienza desde la raíz del arreglo de configuración.
        $array = self::$config;

        // Recorre cada clave en el arreglo hasta llegar a la clave final.
        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                // Si la clave no se encuentra en el arreglo, retorna el valor predeterminado.
                return $default;
            }
            // Avanza al siguiente nivel del arreglo utilizando la clave actual.
            $array = $array[$key];
        }

        // Retorna el valor correspondiente a la clave final, o el valor predeterminado si no se encuentra.
        return $array[$finalKey] ?? $default;
    }
}
