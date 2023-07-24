<?php

namespace Asaa\Providers;

/**
 * Interfaz ServiceProvider para la definición de proveedores de servicios.
 *
 * Los proveedores de servicios son utilizados para registrar y configurar diferentes servicios dentro de la aplicación.
 * Cada proveedor de servicios debe implementar esta interfaz y definir el método "registerServices()" para realizar el registro de servicios.
 */
interface ServiceProvider
{
    /**
     * Registra los servicios proporcionados por el proveedor.
     *
     * Este método se encarga de registrar y configurar los servicios proporcionados por el proveedor.
     * Los servicios pueden ser, por ejemplo, clases, instancias de objetos, o cualquier tipo de componente que la aplicación requiera.
     *
     * @return void
     */
    public function registerServices();
}
