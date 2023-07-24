<?php

namespace Asaa\Session;

class Session
{
    protected SessionStorage $storage;

    // Constante que representa la clave para el arreglo de datos flash en la sesión.
    public const FLASH_KEY = '_flash';

    /**
     * Constructor de la clase Session.
     *
     * @param SessionStorage $storage El objeto de almacenamiento de sesión que se utilizará.
     */
    public function __construct(SessionStorage $storage)
    {
        $this->storage = $storage;
        $this->storage->start(); // Inicia la sesión.

        // Si no existe la clave de datos flash en la sesión, se inicializa con un arreglo vacío para los datos nuevos y viejos.
        if (!$this->storage->has(self::FLASH_KEY)) {
            $this->storage->set(self::FLASH_KEY, ['old' => [], 'new' => []]);
        }
    }

    /**
     * Destructor de la clase Session.
     *
     * Este método se ejecuta al finalizar la ejecución del script y se encarga de eliminar los datos flash antiguos de la sesión,
     * así como de guardar los cambios realizados en la sesión.
     */
    public function __destruct()
    {
        // Elimina los datos flash antiguos de la sesión.
        foreach ($this->storage->get(self::FLASH_KEY)['old'] as $key) {
            $this->storage->remove($key);
        }

        // Actualiza los datos flash: los datos nuevos pasan a ser los datos viejos y se limpia la lista de datos nuevos.
        $this->ageFlashData();

        // Guarda los cambios realizados en la sesión.
        $this->storage->save();
    }

    /**
     * Actualiza los datos flash en la sesión.
     *
     * Los datos nuevos pasan a ser los datos viejos y se limpia la lista de datos nuevos.
     */
    public function ageFlashData()
    {
        $flash = $this->storage->get(self::FLASH_KEY);
        $flash['old'] = $flash['new'];
        $flash['new'] = [];
        $this->storage->set(self::FLASH_KEY, $flash);
    }

    /**
     * Agrega un dato flash a la sesión.
     *
     * Los datos flash son datos que están disponibles solo para la siguiente solicitud y luego se eliminan.
     *
     * @param string $key La clave del dato flash.
     * @param mixed $value El valor del dato flash.
     */
    public function flash(string $key, mixed $value)
    {
        // Almacena el dato flash en la sesión.
        $this->storage->set($key, $value);

        // Agrega la clave del dato flash a la lista de datos nuevos en los datos flash.
        $flash = $this->storage->get(self::FLASH_KEY);
        $flash['new'][] = $key;
        $this->storage->set(self::FLASH_KEY, $flash);
    }

    /**
     * Obtiene el ID de la sesión actual.
     *
     * @return string El ID de la sesión.
     */
    public function id(): string
    {
        return $this->storage->id();
    }

    /**
     * Obtiene el valor de un dato almacenado en la sesión.
     *
     * @param string $key La clave del dato.
     * @param mixed $default Valor predeterminado a devolver si el dato no existe.
     * @return mixed El valor del dato si existe, o el valor predeterminado si no existe.
     */
    public function get(string $key, $default = null)
    {
        return $this->storage->get($key, $default);
    }

    /**
     * Almacena un dato en la sesión.
     *
     * @param string $key La clave del dato.
     * @param mixed $value El valor del dato a almacenar.
     * @return mixed El valor almacenado.
     */
    public function set(string $key, mixed $value)
    {
        return $this->storage->set($key, $value);
    }

    /**
     * Verifica si un dato está almacenado en la sesión.
     *
     * @param string $key La clave del dato.
     * @return bool True si el dato existe en la sesión, False en caso contrario.
     */
    public function has(string $key): bool
    {
        return $this->storage->has($key);
    }

    /**
     * Elimina un dato de la sesión.
     *
     * @param string $key La clave del dato a eliminar.
     */
    public function remove(string $key)
    {
        return $this->storage->remove($key);
    }

    /**
     * Destruye la sesión actual y elimina todos los datos almacenados en ella.
     */
    public function destroy()
    {
        return $this->storage->destroy();
    }
}
