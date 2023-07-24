<?php

namespace Asaa\Session;

/**
 * Clase PhpNativeSessionStorage que implementa la interfaz SessionStorage.
 * Esta clase se utiliza para interactuar con el almacenamiento de la sesión utilizando las variables superglobales de PHP.
 */
class PhpNativeSessionStorage implements SessionStorage
{
    /**
     * Inicia la sesión.
     *
     * @throws \RuntimeException Si no se puede iniciar la sesión.
     */
    public function start()
    {
        if (!session_start()) {
            throw new \RuntimeException("Failed starting session");
        }
    }

    /**
     * Guarda los cambios realizados en la sesión.
     *
     * Este método cierra la sesión y guarda los cambios realizados en el almacenamiento.
     */
    public function save()
    {
        session_write_close();
    }

    /**
     * Obtiene el ID de la sesión actual.
     *
     * @return string El ID de la sesión actual.
     */
    public function id(): string
    {
        return session_id();
    }

    /**
     * Obtiene el valor de un dato almacenado en la sesión.
     *
     * @param string $key La clave del dato que se desea obtener.
     * @param mixed $default Valor predeterminado a devolver si el dato no existe en la sesión.
     * @return mixed El valor del dato si existe, o el valor predeterminado si no existe.
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Almacena un dato en la sesión.
     *
     * @param string $key La clave del dato que se desea almacenar.
     * @param mixed $value El valor del dato que se desea almacenar.
     */
    public function set(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Verifica si un dato está almacenado en la sesión.
     *
     * @param string $key La clave del dato que se desea verificar.
     * @return bool True si el dato existe en la sesión, False en caso contrario.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Elimina un dato de la sesión.
     *
     * @param string $key La clave del dato que se desea eliminar de la sesión.
     */
    public function remove(string $key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destruye la sesión actual y elimina todos los datos almacenados en ella.
     *
     * Este método se encarga de destruir la sesión actual, lo que resulta en la eliminación de todos los datos almacenados en la misma.
     * Después de llamar a este método, la sesión ya no estará disponible y será necesario iniciar una nueva sesión.
     */
    public function destroy()
    {
        session_destroy();
    }
}
