<?php

namespace Asaa\Database\Migrations;

/**
 * Interfaz Migration
 *
 * Esta interfaz define los métodos que deben ser implementados por las clases que representan una migración en la base de datos.
 */
interface Migration
{
    /**
     * Método que realiza la actualización de la base de datos, aplicando los cambios definidos en la migración.
     * Este método se utiliza para llevar a cabo las modificaciones en la estructura de la base de datos.
     */
    public function up();

    /**
     * Método que revierte los cambios realizados por la migración, deshaciendo las modificaciones en la estructura de la base de datos.
     * Este método se utiliza para deshacer los cambios en caso de que sea necesario revertir la migración.
     */
    public function down();
}
