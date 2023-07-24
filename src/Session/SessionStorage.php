<?php

namespace Asaa\Session;

/**
 * Interfaz SessionStorage que define los métodos para interactuar con el almacenamiento de la sesión.
 * Esta interfaz proporciona una abstracción para gestionar la persistencia de la sesión de una forma independiente de la implementación.
 */
interface SessionStorage
{
    /**
     * Inicia la sesión.
     *
     * Este método se encarga de iniciar la sesión para permitir el acceso y almacenamiento de datos en la misma.
     */
    public function start();

    /**
     * Guarda los cambios realizados en la sesión.
     *
     * Este método se encarga de guardar los cambios realizados en la sesión, asegurándose de que los datos se almacenen de forma persistente.
     */
    public function save();

    /**
     * Obtiene el ID de la sesión actual.
     *
     * @return string El ID de la sesión actual.
     */
    public function id(): string;

    /**
     * Obtiene el valor de un dato almacenado en la sesión.
     *
     * @param string $key La clave del dato que se desea obtener.
     * @param mixed $default Valor predeterminado a devolver si el dato no existe en la sesión.
     * @return mixed El valor del dato si existe, o el valor predeterminado si no existe.
     */
    public function get(string $key, $default = null);

    /**
     * Almacena un dato en la sesión.
     *
     * @param string $key La clave del dato que se desea almacenar.
     * @param mixed $value El valor del dato que se desea almacenar.
     * @return mixed El valor almacenado en la sesión.
     */
    public function set(string $key, mixed $value);

    /**
     * Verifica si un dato está almacenado en la sesión.
     *
     * @param string $key La clave del dato que se desea verificar.
     * @return bool True si el dato existe en la sesión, False en caso contrario.
     */
    public function has(string $key): bool;

    /**
     * Elimina un dato de la sesión.
     *
     * @param string $key La clave del dato que se desea eliminar de la sesión.
     */
    public function remove(string $key);

    /**
     * Destruye la sesión actual y elimina todos los datos almacenados en ella.
     *
     * Este método se encarga de destruir la sesión actual, lo que resulta en la eliminación de todos los datos almacenados en la misma.
     * Después de llamar a este método, la sesión ya no estará disponible y será necesario iniciar una nueva sesión.
     */
    public function destroy();
}
