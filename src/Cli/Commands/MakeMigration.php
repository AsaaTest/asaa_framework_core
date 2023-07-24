<?php

namespace Asaa\Cli\Commands;

use Asaa\Database\Migrations\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Comando MakeMigration
 *
 * Este comando permite crear un nuevo archivo de migración para la base de datos.
 * Utiliza el Migrator para manejar el proceso de creación del archivo de migración.
 */
class MakeMigration extends Command
{
    protected static $defaultName = "make:migration";
    protected static $defaultDescription = "Create new migration file";

    /**
     * Configura los argumentos del comando.
     *
     * Define el argumento "name" como requerido para especificar el nombre del nuevo archivo de migración.
     */
    protected function configure()
    {
        $this->addArgument("name", InputArgument::REQUIRED, "Migration name");
    }

    /**
     * Ejecuta el comando MakeMigration.
     *
     * Utiliza el Migrator para crear un nuevo archivo de migración con el nombre especificado en el argumento "name".
     * El Migrator se encargará de generar el nombre completo del archivo con una marca de tiempo para asegurar
     * la unicidad del nombre.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Obtiene el Migrator y llama al método make para crear el nuevo archivo de migración.
        app(Migrator::class)->make($input->getArgument('name'));

        // Retorna el código de éxito del comando.
        return Command::SUCCESS;
    }
}
