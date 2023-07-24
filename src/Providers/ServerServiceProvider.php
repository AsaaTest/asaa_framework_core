<?php

namespace Asaa\Providers;

use Asaa\Server\PhpNativeServer;
use Asaa\Server\Server;

/**
 * Clase ServerServiceProvider.
 *
 * Este proveedor de servicios se encarga de registrar la implementación del servidor que se utilizará para la aplicación.
 * La implementación del servidor se define mediante la función `singleton()` en el método `registerServices()`.
 * En este caso, se registra la implementación `PhpNativeServer` como el servidor predeterminado utilizando la función `singleton()`.
 */
class ServerServiceProvider implements ServiceProvider
{
    /**
     * Registra el servicio del servidor de la aplicación.
     *
     * Este método registra la implementación del servidor utilizando la función `singleton()`.
     * La implementación del servidor registrada es `PhpNativeServer`, que se utilizará como el servidor predeterminado para la aplicación.
     *
     * @return void
     */
    public function registerServices()
    {
        singleton(Server::class, PhpNativeServer::class);
    }
}
