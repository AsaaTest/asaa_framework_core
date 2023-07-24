<?php

namespace Asaa\Auth;

use Asaa\Auth\Authenticators\Authenticator;
use Asaa\Database\Model;

/**
 * Clase base para modelos autenticables.
 *
 * Esta clase extiende la clase Model y proporciona funcionalidades para manejar la autenticación de usuarios.
 * Los modelos que representan a los usuarios pueden extender esta clase para obtener métodos útiles relacionados con la autenticación.
 */
class Authenticatable extends Model
{
    /**
     * Obtiene el ID del usuario autenticable.
     *
     * @return int|string El ID del usuario autenticable.
     */
    public function id(): int|string
    {
        // Retorna el valor del atributo correspondiente a la clave primaria del modelo (ID del usuario).
        return $this->{$this->primaryKey};
    }

    /**
     * Inicia sesión con el usuario actual.
     *
     * Utiliza el servicio de autenticación para iniciar sesión con el usuario actual.
     */
    public function login()
    {
        // Utiliza el servicio de autenticación para iniciar sesión con el usuario actual.
        app(Authenticator::class)->login($this);
    }

    /**
     * Cierra la sesión del usuario actual.
     *
     * Utiliza el servicio de autenticación para cerrar la sesión del usuario actual.
     */
    public function logout()
    {
        // Utiliza el servicio de autenticación para cerrar la sesión del usuario actual.
        app(Authenticator::class)->logout($this);
    }

    /**
     * Comprueba si el usuario actual está autenticado.
     *
     * Utiliza el servicio de autenticación para verificar si el usuario actual está autenticado.
     *
     * @return bool TRUE si el usuario está autenticado, FALSE si no lo está.
     */
    public function isAuthenticated()
    {
        // Utiliza el servicio de autenticación para verificar si el usuario actual está autenticado.
        return app(Authenticator::class)->isAuthenticated($this);
    }
}
