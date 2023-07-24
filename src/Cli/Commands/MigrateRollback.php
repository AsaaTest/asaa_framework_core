<?php

namespace Asaa\Cli\Commands;

use Asaa\Database\Migrations\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Comando MigrateRollback
 *
 * Este comando permite revertir migraciones previamente aplicadas.
 * Utiliza el Migrator para manejar el proceso de reversión de migraciones.
 */
class MigrateRollback extends Command
{
    protected static $defaultName = "migrate:rollback";
    protected static $defaultDescription = "Rollback migrations";

    /**
     * Configura los argumentos del comando.
     *
     * Define el argumento "steps" para permitir al usuario especificar la cantidad de migraciones que desea revertir.
     * Si no se proporciona ningún valor para "steps", se revertirán todas las migraciones aplicadas.
     */
    protected function configure()
    {
        $this->addArgument("steps", InputArgument::OPTIONAL, "Amount of migrations to reverse, all by default");
    }

    /**
     * Ejecuta el comando MigrateRollback.
     *
     * Utiliza el Migrator para realizar el proceso de reversión de migraciones.
     * El número de migraciones que se revertirán se determina por el valor del argumento "steps".
     * Si no se proporciona ningún valor para "steps", se revertirán todas las migraciones aplicadas.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // Obtiene el Migrator y llama al método rollback para revertir las migraciones.
            app(Migrator::class)->rollback($input->getArgument('steps') ?? null);

            // Retorna el código de éxito del comando.
            return Command::SUCCESS;
        } catch (\PDOException $e) {
            // Si se produce una excepción durante el proceso de reversión, muestra un mensaje de error.
            $output->writeln("<error>Could not rollback migrations: {$e->getMessage()}</error>");
            $output->writeln($e->getTraceAsString());

            // Retorna el código de fallo del comando.
            return Command::FAILURE;
        }
    }
}
