<?php

// Define el espacio de nombres para la interfaz Server.

namespace Asaa\Server;

// Importa la clase de solicitud (Request) y respuesta (Response) del espacio de nombres Asaa\Http.
use Asaa\Http\Request;
use Asaa\Http\Response;

/**
 * Interfaz Server que define los métodos para obtener información de una solicitud HTTP y enviar respuestas al cliente.
 */
interface Server
{
    /**
     * Obtiene la solicitud (Request) del cliente.
     *
     * @return Request La solicitud (Request) del cliente.
     */
    public function getRequest(): Request;

    /**
     * Envía la respuesta (Response) al cliente.
     *
     * @param Response $response La respuesta que se enviará al cliente.
     * @return void
     */
    public function sendResponse(Response $response);
}
