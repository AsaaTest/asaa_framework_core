<?php

namespace Asaa\Container;

use ReflectionClass;
use ReflectionMethod;
use ReflectionFunction;
use Asaa\Database\Model;
use Asaa\Http\HttpNotFoundException;

/**
 * Clase DependencyInjection
 *
 * Esta clase proporciona métodos para resolver los parámetros de una función o método utilizando inyección de dependencias.
 * Permite resolver y obtener los valores de los parámetros basándose en sus tipos de clase o primitivos.
 * También puede manejar la resolución de modelos basados en los parámetros pasados en la URL de una ruta.
 */
class DependencyInjection
{
    /**
     * Resuelve los parámetros de una función o método utilizando inyección de dependencias.
     *
     * El método recibe un callback, que puede ser un Closure o un arreglo representando un método de clase.
     * Itera sobre los parámetros del callback y resuelve sus valores basándose en sus tipos de clase o primitivos.
     * Si un parámetro de clase es una subclase de Model, intenta resolver un modelo basado en los parámetros de la URL.
     *
     * @param \Closure|array $callback El callback que contiene los parámetros a resolver.
     * @param array $routeParameters Un arreglo con los parámetros pasados en la URL de la ruta.
     * @return array Un arreglo con los valores resueltos para cada parámetro del callback.
     * @throws HttpNotFoundException Si un parámetro de clase es una subclase de Model y el modelo no se encuentra.
     */
    public static function resolveParameters(\Closure|array $callback, $routeParameters = [])
    {
        // Crea una instancia de ReflectionMethod o ReflectionFunction según el tipo de callback recibido.
        $methodOrFunction = is_array($callback) ? new ReflectionMethod($callback[0], $callback[1]) : new ReflectionFunction($callback);

        $params = [];

        // Itera sobre los parámetros del callback para resolver sus valores.
        foreach ($methodOrFunction->getParameters() as $param) {
            if (is_subclass_of($param->getType()->getName(), Model::class)) {
                // Si el parámetro es una subclase de Model, intenta resolver un modelo basado en los parámetros de la URL.
                $modelClass = new ReflectionClass($param->getType()->getName());
                $routeParamName = snake_case($modelClass->getShortName());

                // Obtiene el ID del modelo desde los parámetros de la URL.
                $modelId = $routeParameters[$routeParamName] ?? 0;

                // Intenta encontrar el modelo correspondiente con el ID proporcionado.
                $resolved = $param->getType()->getName()::find($modelId);

                // Si el modelo no se encuentra, lanza una excepción HttpNotFoundException.
                if (is_null($resolved)) {
                    throw new HttpNotFoundException();
                }
            } elseif ($param->getType()->isBuiltin()) {
                // Si el parámetro es de tipo primitivo (built-in), obtiene el valor directamente desde los parámetros de la URL.
                $resolved = $routeParameters[$param->getName()] ?? null;
            } else {
                // Si el parámetro es una clase no primitiva, resuelve la instancia utilizando el contenedor de dependencias.
                $resolved = app($param->getType()->getName());
            }

            // Almacena el valor resuelto en el arreglo de parámetros.
            $params[] = $resolved;
        }

        // Retorna el arreglo con los valores resueltos para cada parámetro del callback.
        return $params;
    }
}
