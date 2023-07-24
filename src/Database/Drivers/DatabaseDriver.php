<?php

namespace Asaa\Database\Drivers;

/**
 * Interfaz DatabaseDriver
 *
 * Esta interfaz define los métodos que deben ser implementados por las clases que representan un controlador de base de datos.
 */
interface DatabaseDriver
{
    /**
     * Método para conectarse a la base de datos.
     *
     * @param string $protocol El protocolo de conexión (ejemplo: "mysql", "pgsql", etc.).
     * @param string $host La dirección del host de la base de datos.
     * @param int $port El número de puerto para la conexión.
     * @param string $database El nombre de la base de datos a la que se desea conectar.
     * @param string $username El nombre de usuario para la conexión.
     * @param string $password La contraseña para la conexión.
     */
    public function connect(string $protocol, string $host, int $port, string $database, string $username, string $password);

    /**
     * Método para obtener el último ID insertado en la base de datos.
     *
     * @return string El último ID insertado.
     */
    public function lastInsertId();

    /**
     * Método para cerrar la conexión a la base de datos.
     */
    public function close();

    /**
     * Método para ejecutar una consulta en la base de datos.
     *
     * @param string $query La consulta a ejecutar.
     * @param array $bind Los valores a enlazar en la consulta.
     * @return mixed El resultado de la consulta.
     */
    public function statement(string $query, array $bind = []): mixed;
}
