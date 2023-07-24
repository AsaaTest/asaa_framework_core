<?php

namespace Asaa\Auth\Authenticators;

use Asaa\Auth\Authenticatable;

/**
 * Interfaz Authenticator
 *
 * Esta interfaz define los métodos que deben ser implementados por las clases que actúan como autenticadores.
 * Un autenticador se encarga de manejar la lógica de autenticación de usuarios y proporciona métodos para
 * iniciar sesión, cerrar sesión, verificar la autenticación y obtener el usuario autenticado actual.
 */
interface Authenticator
{
    /**
     * Inicia sesión con el usuario autenticable dado.
     *
     * @param Authenticatable $authenticatable El usuario al que se le iniciará sesión.
     */
    public function login(Authenticatable $authenticatable);

    /**
     * Cierra la sesión del usuario autenticable dado.
     *
     * @param Authenticatable $authenticatable El usuario al que se le cerrará sesión.
     */
    public function logout(Authenticatable $authenticatable);

    /**
     * Verifica si el usuario autenticable dado está autenticado.
     *
     * @param Authenticatable $authenticatable El usuario que se verificará si está autenticado.
     * @return bool TRUE si el usuario está autenticado, FALSE si no lo está.
     */
    public function isAuthenticated(Authenticatable $authenticatable): bool;

    /**
     * Resuelve y devuelve el usuario autenticable actualmente autenticado.
     *
     * @return Authenticatable|null El usuario autenticable actual o NULL si no hay usuario autenticado.
     */
    public function resolve(): ?Authenticatable;
}
