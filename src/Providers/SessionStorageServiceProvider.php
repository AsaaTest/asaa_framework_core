<?php

namespace Asaa\Providers;

use Asaa\Session\PhpNativeSessionStorage;
use Asaa\Session\SessionStorage;

/**
 * Clase SessionStorageServiceProvider.
 *
 * Este proveedor de servicios se encarga de registrar la implementación del almacenamiento de sesiones que se utilizará en la aplicación.
 * La implementación del almacenamiento de sesiones se define mediante la función `singleton()` en el método `registerServices()`.
 * En este caso, se registra la implementación `PhpNativeSessionStorage` como el almacenamiento de sesiones predeterminado utilizando la función `singleton()`.
 */
class SessionStorageServiceProvider implements ServiceProvider
{
    /**
     * Registra el servicio de almacenamiento de sesiones de la aplicación.
     *
     * Este método registra la implementación del almacenamiento de sesiones utilizando la función `singleton()`.
     * La implementación del almacenamiento de sesiones registrada es `PhpNativeSessionStorage`, que se utilizará como el almacenamiento de sesiones predeterminado para la aplicación.
     *
     * @return void
     */
    public function registerServices()
    {
        match (config("session.storage", "native")) {
            "native" => singleton(SessionStorage::class, PhpNativeSessionStorage::class)
        };
    }
}
