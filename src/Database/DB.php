<?php

namespace Asaa\Database;

use Asaa\Database\Drivers\DatabaseDriver;

/**
 * Clase DB
 *
 * Esta clase proporciona una interfaz estática para ejecutar consultas SQL en la base de datos.
 * Utiliza un objeto de tipo DatabaseDriver para ejecutar las consultas en la base de datos subyacente.
 */
class DB
{
    /**
     * Ejecuta una consulta SQL en la base de datos utilizando el objeto DatabaseDriver.
     *
     * @param string $query La consulta SQL a ejecutar.
     * @param array $bind Un arreglo de valores para vincular a la consulta SQL (opcional).
     * @return mixed El resultado de la consulta, que puede ser un conjunto de resultados o un valor afectado.
     */
    public static function statement(string $query, array $bind = [])
    {
        // Utiliza el objeto de tipo DatabaseDriver para ejecutar la consulta SQL y retorna el resultado.
        return app(DatabaseDriver::class)->statement($query, $bind);
    }
}
