<?php

namespace Asaa\Auth\Authenticators;

use Asaa\Auth\Authenticatable;

/**
 * Clase SessionAuthenticator
 *
 * Esta clase implementa la interfaz Authenticator y proporciona métodos para manejar la autenticación
 * de usuarios utilizando sesiones de PHP. Almacena la información del usuario autenticado en la sesión
 * para mantener su estado durante la navegación.
 */
class SessionAuthenticator implements Authenticator
{
    /**
     * Inicia sesión con el usuario autenticable dado.
     *
     * @param Authenticatable $authenticatable El usuario al que se le iniciará sesión.
     */
    public function login(Authenticatable $authenticatable)
    {
        // Almacena el usuario autenticable en la sesión utilizando la clave "_auth".
        session()->set('_auth', $authenticatable);
    }

    /**
     * Cierra la sesión del usuario autenticable dado.
     *
     * @param Authenticatable $authenticatable El usuario al que se le cerrará sesión.
     */
    public function logout(Authenticatable $authenticatable)
    {
        // Destruye toda la información de la sesión, lo que incluye el usuario autenticado.
        session()->destroy();

        // Otra opción sería solo eliminar la clave "_auth" de la sesión sin destruir toda la sesión.
        // session()->remove("_auth");
    }

    /**
     * Verifica si el usuario autenticable dado está autenticado.
     *
     * @param Authenticatable $authenticatable El usuario que se verificará si está autenticado.
     * @return bool TRUE si el usuario está autenticado, FALSE si no lo está.
     */
    public function isAuthenticated(Authenticatable $authenticatable): bool
    {
        // Obtiene el usuario autenticado almacenado en la sesión y compara su ID con el ID del usuario dado.
        // Si los IDs coinciden, el usuario está autenticado; de lo contrario, no lo está.
        return session()->get("_auth")?->id() === $authenticatable->id();
    }

    /**
     * Resuelve y devuelve el usuario autenticable actualmente autenticado.
     *
     * @return Authenticatable|null El usuario autenticable actual o NULL si no hay usuario autenticado.
     */
    public function resolve(): ?Authenticatable
    {
        // Obtiene el usuario autenticado almacenado en la sesión y lo devuelve.
        // Si no hay usuario autenticado, retorna NULL.
        return session()->get("_auth");
    }
}
