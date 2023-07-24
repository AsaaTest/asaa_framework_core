<?php

namespace Asaa\Auth;

use Asaa\Routing\Route;
use Asaa\Auth\Authenticators\Authenticator;
use App\Controllers\Auth\AuthenticateController;

class Auth
{
    /**
     * Obtiene el usuario autenticado actualmente.
     *
     * @return Authenticatable|null El usuario autenticado o NULL si no hay usuario.
     */
    public static function user(): ?Authenticatable
    {
        // Utiliza el servicio de autenticación para obtener el usuario autenticado.
        return app(Authenticator::class)->resolve();
    }

    /**
     * Comprueba si el usuario actual es un invitado (no autenticado).
     *
     * @return bool TRUE si el usuario es un invitado, FALSE si está autenticado.
     */
    public static function isGuest(): bool
    {
        // Verifica si no hay un usuario autenticado llamando al método "user".
        return is_null(self::user());
    }

    /**
     * Define las rutas de autenticación para el framework.
     *
     * Estas rutas son utilizadas para el registro, inicio de sesión y cierre de sesión de los usuarios.
     * Se definen las rutas correspondientes y se asocian con los controladores adecuados.
     */
    public static function routes()
    {
        // Ruta para mostrar el formulario de registro.
        Route::get('/register', [AuthenticateController::class, 'register']);

        // Ruta para procesar el formulario de registro (crear el usuario).
        Route::post('/register', [AuthenticateController::class, 'store']);

        // Ruta para mostrar el formulario de inicio de sesión.
        Route::get('/login', [AuthenticateController::class, 'index']);

        // Ruta para procesar el inicio de sesión (autenticar al usuario).
        Route::post('/login', [AuthenticateController::class, 'login']);

        // Ruta para cerrar la sesión del usuario (logout).
        Route::get('/logout', [AuthenticateController::class, 'logout']);
    }
}
