<?php

namespace Asaa\Http;

use Closure;

/**
 * Interfaz Middleware.
 * Representa un middleware en una aplicación PHP.
 * Un middleware es una capa intermedia que puede interceptar y modificar la solicitud entrante antes de que llegue al controlador
 * o a la siguiente capa de middleware. También puede manejar la respuesta saliente antes de que se devuelva al cliente.
 */
interface Middleware
{
    /**
     * Procesa la solicitud y la pasa a la siguiente capa de middleware o al controlador.
     * También puede modificar la solicitud o la respuesta antes de pasarla a la siguiente capa.
     *
     * @param Request $request La solicitud entrante.
     * @param Closure $next El siguiente middleware o el controlador.
     * @return Response La respuesta generada por el middleware o el controlador.
     */
    public function handle(Request $request, Closure $next): Response;
}
