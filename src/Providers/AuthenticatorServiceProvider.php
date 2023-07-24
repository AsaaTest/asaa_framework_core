<?php

namespace Asaa\Providers;

use Asaa\Auth\Authenticators\Authenticator;
use Asaa\Auth\Authenticators\SessionAuthenticator;

/**
 * Clase AuthenticatorServiceProvider que implementa la interfaz ServiceProvider.
 *
 * Este proveedor de servicios se encarga de registrar el autenticador que se utilizará en la aplicación.
 * El autenticador se define según la configuración de autenticación proporcionada en el archivo de configuración "auth.php".
 * En caso de que no se especifique una configuración, el autenticador predeterminado será el SessionAuthenticator.
 */
class AuthenticatorServiceProvider implements ServiceProvider
{
    /**
     * Registra el servicio del autenticador de acuerdo a la configuración.
     *
     * Este método verifica la configuración de autenticación definida en el archivo de configuración "auth.php".
     * Si se especifica el método de autenticación "session", se registra el SessionAuthenticator como el autenticador predeterminado.
     * Si se agrega soporte para otros métodos de autenticación, se pueden agregar más casos en el bloque "match" para registrar los correspondientes autenticadores.
     * En caso de que no se especifique una configuración válida, el autenticador predeterminado será el SessionAuthenticator.
     *
     * @return void
     */
    public function registerServices()
    {
        match (config("auth.method", "session")) {
            "session" => singleton(Authenticator::class, SessionAuthenticator::class)
        };
    }
}
