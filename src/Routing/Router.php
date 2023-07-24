<?php

namespace Asaa\Routing;

use Asaa\Container\DependencyInjection;
use Closure;

use Asaa\Http\Request;
use Asaa\Http\Response;
use Asaa\Routing\Route;
use Asaa\Http\HttpNotFoundException;

/**
 * Clase Router.
 *
 * Esta clase se encarga de administrar las rutas y resolver la ruta correspondiente a una solicitud HTTP.
 */
class Router
{
    // Array que almacenará las rutas registradas, agrupadas por métodos HTTP.
    protected array $routes = [];

    /**
     * Resuelve la ruta y el método HTTP para obtener la acción correspondiente.
     *
     * @param Request $request La solicitud HTTP entrante.
     * @return Route La ruta que coincide con la URI y el método.
     * @throws HttpNotFoundException Si no se encuentra ninguna acción para la ruta y el método especificados.
     */
    public function resolveRoute(Request $request): Route
    {
        // Iterar sobre las rutas registradas para el método HTTP específico.
        foreach ($this->routes[$request->method()] as $route) {
            // Comprobar si la ruta coincide con la URI solicitada utilizando el método "matches" de la clase "Route".
            if ($route->matches($request->uri())) {
                return $route; // Devuelve la ruta que coincide.
            }
        }

        // Si no se encuentra ninguna ruta coincidente, lanzar una excepción "HttpNotFoundException".
        throw new HttpNotFoundException();
    }

    /**
     * Resuelve la ruta asociada a la solicitud y ejecuta la acción correspondiente.
     * Si la ruta tiene middlewares configurados, los ejecuta antes de la acción principal.
     *
     * @param Request $request La solicitud HTTP entrante.
     * @return Response Respuesta generada por la acción principal o por los middlewares.
     */
    public function resolve(Request $request): Response
    {
        // Resuelve la ruta asociada a la solicitud utilizando el método "resolveRoute()" del enrutador.
        $route = $this->resolveRoute($request);

        // Asigna la ruta resuelta al objeto de solicitud para que pueda ser accedida por otros componentes.
        $request->setRoute($route);

        // Obtiene la acción asociada a la ruta resuelta.
        $action = $route->action();

        $middlewares = $route->middlewares();
        // Si la acción es un arreglo, significa que es una acción de controlador (controller) con el formato [Controlador, Método].
        // En ese caso, crea una instancia del controlador y reemplaza la acción por el objeto del controlador y el nombre del método.
        if (is_array($action)) {
            $controller = new $action[0]();
            $action[0] = $controller;
            $middlewares = array_merge($middlewares, $controller->middlewares());
        }

        // Resuelve los parámetros de la acción utilizando el contenedor de dependencias (DependencyInjection).
        $params = DependencyInjection::resolveParameters($action, $request->routeParameters());

        // Ejecuta los middlewares y la acción principal mediante el método "runMiddlewares()".
        return $this->runMiddlewares($request, $middlewares, fn () => call_user_func($action, ...$params));
    }

    /**
     * Ejecuta los middlewares y la acción principal.
     *
     * @param Request $request La solicitud HTTP entrante.
     * @param array $middlewares Los middlewares asociados a la ruta.
     * @param  $target La acción principal a ejecutar.
     * @return Response Respuesta generada por la acción principal o por los middlewares.
     */
    protected function runMiddlewares(Request $request, array $middlewares, $target): Response
    {
        if (count($middlewares) == 0) {
            return $target(); // Si no hay middlewares, ejecuta la acción principal.
        }

        // Ejecuta el primer middleware y pasa una función de callback que ejecutará los middlewares restantes y la acción principal.
        return $middlewares[0]->handle(
            $request,
            fn ($request) => $this->runMiddlewares($request, array_slice($middlewares, 1), $target)
        );
    }

    /**
     * Registra una nueva ruta para el método HTTP GET.
     *
     * @param string $uri La URI de la ruta.
     * @param \Closure|array $action La acción asociada a la ruta.
     * @return Route La instancia de la clase Route creada.
     */
    public function get(string $uri, \Closure|array $action): Route
    {
        return $this->registerRoute('GET', $uri, $action);
    }

    /**
     * Registra una nueva ruta para el método HTTP POST.
     *
     * @param string $uri La URI de la ruta.
     * @param \Closure|array $action La acción asociada a la ruta.
     * @return Route La instancia de la clase Route creada.
     */
    public function post(string $uri, \Closure|array $action): Route
    {
        return $this->registerRoute('POST', $uri, $action);
    }

    /**
     * Registra una nueva ruta para el método HTTP PUT.
     *
     * @param string $uri La URI de la ruta.
     * @param \Closure|array $action La acción asociada a la ruta.
     * @return Route La instancia de la clase Route creada.
     */
    public function put(string $uri, \Closure|array $action): Route
    {
        return $this->registerRoute('PUT', $uri, $action);
    }

    /**
     * Registra una nueva ruta para el método HTTP PATCH.
     *
     * @param string $uri La URI de la ruta.
     * @param \Closure|array $action La acción asociada a la ruta.
     * @return Route La instancia de la clase Route creada.
     */
    public function patch(string $uri, \Closure|array $action): Route
    {
        return $this->registerRoute('PATCH', $uri, $action);
    }

    /**
     * Registra una nueva ruta para el método HTTP DELETE.
     *
     * @param string $uri La URI de la ruta.
     * @param \Closure|array $action La acción asociada a la ruta.
     * @return Route La instancia de la clase Route creada.
     */
    public function delete(string $uri, \Closure|array $action): Route
    {
        return $this->registerRoute('DELETE', $uri, $action);
    }

    /**
     * Registra una nueva ruta en el enrutador.
     *
     * @param string $method El método HTTP de la ruta.
     * @param string $uri La URI de la ruta.
     * @param \Closure|array $action La acción asociada a la ruta.
     * @return Route La instancia de la clase Route creada.
     */
    protected function registerRoute(string $method, string $uri, \Closure|array $action): Route
    {
        $route = new Route($uri, $action);
        // Crea un nuevo objeto "Route" con la URI y la acción proporcionadas y lo agrega al array de rutas.
        $this->routes[$method][] = $route;
        return $route;
    }
}
