<?php

namespace Asaa\Database\Migrations;

use Asaa\Database\Drivers\DatabaseDriver;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Clase Migrator
 *
 * Esta clase se encarga de gestionar las migraciones de la base de datos.
 * Las migraciones son un mecanismo para mantener y actualizar la estructura de la base de datos de forma controlada,
 * permitiendo realizar cambios en el esquema de la base de datos de manera reversible.
 */
class Migrator
{
    private ConsoleOutput $output;

    /**
     * Constructor de la clase Migrator.
     *
     * @param string $migrationsDirectory La ruta del directorio donde se encuentran los archivos de migración.
     * @param string $templatesDirectory La ruta del directorio donde se encuentran las plantillas para las migraciones.
     * @param DatabaseDriver $driver El objeto de tipo DatabaseDriver para la conexión de base de datos.
     */
    public function __construct(private string $migrationsDirectory, private string $templatesDirectory, private DatabaseDriver $driver, private bool $logProgress = true)
    {
        $this->migrationsDirectory = $migrationsDirectory;
        $this->templatesDirectory = $templatesDirectory;
        $this->driver = $driver;
        $this->output = new ConsoleOutput();
    }

    /**
     * Imprime un mensaje de log en la consola con formato de información.
     *
     * @param string $message El mensaje a imprimir en la consola.
     */
    private function log(string $message)
    {
        if ($this->logProgress) {
            $this->output->writeln("<info>$message</info>");
        }
    }

    /**
     * Crea la tabla de migraciones si no existe en la base de datos.
     */
    private function createMigrationsTableIfNotExists()
    {
        $this->driver->statement("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(256))");
    }

    /**
     * Ejecuta las migraciones pendientes para actualizar la estructura de la base de datos.
     * Las migraciones se ejecutan en orden cronológico según la fecha y hora de creación de los archivos de migración.
     * Se registra cada migración ejecutada en la tabla "migrations" para llevar un seguimiento del estado de las migraciones.
     */
    public function migrate()
    {
        $this->createMigrationsTableIfNotExists();
        $migrated = $this->driver->statement("SELECT * FROM migrations");
        $migrations = glob("$this->migrationsDirectory/*.php");

        if (count($migrated) >= count($migrations)) {
            $this->log("<comment>Nothing to migrate</comment>");
            return;
        }

        foreach (array_slice($migrations, count($migrated)) as $file) {
            $migration = require $file;
            $migration->up();
            $name = basename($file);
            $this->driver->statement("INSERT INTO migrations (name) VALUES (?)", [$name]);
            $this->log("<info>Migrated => $name</info>");
        }
    }

    /**
     * Revierte las últimas migraciones ejecutadas en la base de datos.
     * Se puede especificar la cantidad de migraciones a revertir mediante el parámetro $steps.
     * Si $steps es null o mayor que la cantidad de migraciones pendientes, se revierten todas las migraciones pendientes.
     *
     * @param int|null $steps El número de migraciones a revertir, o null para revertir todas las migraciones pendientes.
     */
    public function rollback(?int $steps = null)
    {
        $this->createMigrationsTableIfNotExists();
        $migrated = $this->driver->statement("SELECT * FROM migrations");
        $pending = count($migrated);

        if ($pending == 0) {
            $this->log("Nothing to rollback");
            return;
        }

        if (is_null($steps) || $steps > $pending) {
            $steps = $pending;
        }

        $migrations = array_slice(array_reverse(glob("$this->migrationsDirectory/*.php")), -$pending);

        foreach ($migrations as $file) {
            $migration = require $file;
            $migration->down();
            $name = basename($file);
            $this->driver->statement("DELETE FROM migrations WHERE name = ?", [$name]);
            $this->log("Rollback => $name");
            if (--$steps == 0) {
                break;
            }
        }
    }

    /**
     * Crea un nuevo archivo de migración con el nombre especificado.
     * El contenido del archivo de migración se genera utilizando una plantilla y el nombre de la migración.
     * Se pueden utilizar diferentes plantillas según el formato del nombre de la migración.
     * Retorna el nombre del archivo de migración creado.
     *
     * @param string $migrationName El nombre de la migración a crear.
     * @return string El nombre del archivo de migración creado.
     */
    public function make(string $migrationName)
    {
        $migrationName = snake_case($migrationName);
        $template = file_get_contents("$this->templatesDirectory/migration.php");

        if (preg_match("/create_.*_table/", $migrationName)) {
            $table = preg_replace_callback("/create_(.*)_table/", fn ($match) => $match[1], $migrationName);
            $template = str_replace('$UP', "CREATE TABLE $table (id INT AUTO_INCREMENT PRIMARY KEY)", $template);
            $template = str_replace('$DOWN', "DROP TABLE $table", $template);
        } elseif (preg_match("/.*(from|to)_(.*)_table/", $migrationName)) {
            $table = preg_replace_callback("/.*(from|to)_(.*)_table/", fn ($match) => $match[2], $migrationName);
            $template = preg_replace('/\$UP|\$DOWN/', "ALTER TABLE $table", $template);
        } else {
            $template = preg_replace_callback("/DB::statement.*/", fn ($match) => "// {$match[0]}", $template);
        }

        $date = date("Y_m_d");
        $id = 0;

        foreach (glob("$this->migrationsDirectory/*.php") as $file) {
            if (str_starts_with(basename($file), $date)) {
                $id++;
            }
        }

        $fileName = sprintf("%s_%06d_%s.php", $date, $id, $migrationName);
        file_put_contents("$this->migrationsDirectory/$fileName", $template);

        $this->log("Created migrations => $fileName");

        return $fileName;
    }
}
