<?php

namespace Asaa\Providers;

use Asaa\View\View;
use Asaa\View\AsaaEngine;

/**
 * Clase ViewServiceProvider.
 *
 * Este proveedor de servicios se encarga de registrar el motor de plantillas que se utilizará en la aplicación para la generación de vistas.
 * La implementación del motor de plantillas se define mediante la función `singleton()` en el método `registerServices()`.
 * En este caso, se registra el motor de plantillas `AsaaEngine` como el motor predeterminado utilizando la función `singleton()`.
 */
class ViewServiceProvider implements ServiceProvider
{
    /**
     * Registra el servicio del motor de plantillas para las vistas de la aplicación.
     *
     * Este método registra la implementación del motor de plantillas utilizando la función `singleton()`.
     * La implementación del motor de plantillas registrada es `AsaaEngine`, que se utilizará como el motor predeterminado para generar las vistas de la aplicación.
     *
     * @return void
     */
    public function registerServices()
    {
        match(config("view.engine", "asaa")) {
            "asaa" => singleton(View::class, fn () => new AsaaEngine(config("view.path")))
        };
    }
}
