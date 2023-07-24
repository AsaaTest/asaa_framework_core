<?php

namespace Asaa\Cli\Commands;

use Asaa\Database\Migrations\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Comando Migrate
 *
 * Este comando permite ejecutar las migraciones pendientes para actualizar la base de datos.
 * Utiliza el Migrator para manejar el proceso de ejecución de las migraciones.
 */
class Migrate extends Command
{
    protected static $defaultName = "migrate";
    protected static $defaultDescription = "Run migrations";

    /**
     * Ejecuta el comando Migrate.
     *
     * Utiliza el Migrator para realizar el proceso de ejecución de las migraciones pendientes.
     * Las migraciones pendientes son aquellas que aún no se han aplicado en la base de datos.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // Obtiene el Migrator y llama al método migrate para ejecutar las migraciones pendientes.
            app(Migrator::class)->migrate();

            // Retorna el código de éxito del comando.
            return Command::SUCCESS;
        } catch (\PDOException $e) {
            // Si se produce una excepción durante el proceso de ejecución de migraciones, muestra un mensaje de error.
            $output->writeln("<error>Could not run migrations: {$e->getMessage()}</error>");
            $output->writeln($e->getTraceAsString());

            // Retorna el código de fallo del comando.
            return Command::FAILURE;
        }
    }
}
