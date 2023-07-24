<?php

namespace Asaa\Http;

/**
 * Clase base para controladores de la aplicación.
 * Los controladores son responsables de manejar las solicitudes HTTP y gestionar la lógica de la aplicación.
 * Esta clase puede ser extendida por controladores específicos que se encarguen de tareas y acciones específicas de la aplicación.
 *
 * Ejemplo de extensión:
 * class MyController extends Controller
 * {
 *     // Definir aquí métodos y funcionalidades específicas del controlador.
 * }
 */
class Controller
{
    // Aquí podrían añadirse propiedades y métodos comunes a todos los controladores, si es necesario.
    // Por ejemplo, métodos para renderizar vistas, redireccionar, manejar errores, etc.
    protected array $middlewares = [];

    /**
     * Obtiene los middlewares asociados a la ruta.
     *
     * @return array Los middlewares asociados a la ruta.
     */
    public function middlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * Establece los middlewares para la ruta.
     *
     * @param array $middlewares Los middlewares a establecer.
     * @return self
     */
    public function setMiddlewares(array $middlewares): self
    {
        $this->middlewares = array_map(fn ($middleware) => new $middleware(), $middlewares);
        return $this;
    }
}
