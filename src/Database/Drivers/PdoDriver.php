<?php

namespace Asaa\Database\Drivers;

use PDO;

/**
 * Clase PdoDriver
 *
 * Esta clase es una implementación de la interfaz DatabaseDriver que utiliza PDO para conectarse a la base de datos y ejecutar consultas.
 */
class PdoDriver implements DatabaseDriver
{
    protected ?PDO $pdo;

    /**
     * Método para conectarse a la base de datos utilizando PDO.
     *
     * @param string $protocol El protocolo de conexión (ejemplo: "mysql", "pgsql", etc.).
     * @param string $host La dirección del host de la base de datos.
     * @param int $port El número de puerto para la conexión.
     * @param string $database El nombre de la base de datos a la que se desea conectar.
     * @param string $username El nombre de usuario para la conexión.
     * @param string $password La contraseña para la conexión.
     */
    public function connect(string $protocol, string $host, int $port, string $database, string $username, string $password)
    {
        $dsn = "$protocol:host=$host;port=$port;dbname=$database";
        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Método que retorna el último ID insertado en la base de datos.
     *
     * @return string El último ID insertado.
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Método para cerrar la conexión a la base de datos.
     */
    public function close()
    {
        $this->pdo = null;
    }

    /**
     * Método para ejecutar una consulta en la base de datos.
     *
     * @param string $query La consulta a ejecutar.
     * @param array $bind Los valores a enlazar en la consulta.
     * @return mixed El resultado de la consulta.
     */
    public function statement(string $query, array $bind = []): mixed
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($bind);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
