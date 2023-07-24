<?php

namespace Asaa\Cli\Commands;

use Asaa\App;
use Asaa\Database\Migrations\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Comando MakeModel
 *
 * Este comando permite crear un nuevo modelo en la aplicación.
 * Además, opcionalmente, se puede generar un archivo de migración asociado al modelo recién creado.
 */
class MakeModel extends Command
{
    protected static $defaultName = "make:model";
    protected static $defaultDescription = "Create a new model";

    /**
     * Configura los argumentos y opciones del comando.
     *
     * Define el argumento "name" como requerido para especificar el nombre del nuevo modelo a crear.
     * Define la opción "migration" (-m) como opcional para generar un archivo de migración asociado al modelo.
     */
    protected function configure()
    {
        $this
            ->addArgument("name", InputArgument::REQUIRED, "Model name")
            ->addOption("migration", "m", InputOption::VALUE_OPTIONAL, "Also create migration file", false);
    }

    /**
     * Ejecuta el comando MakeModel.
     *
     * Crea un nuevo modelo en la carpeta "app/Models" de la aplicación utilizando una plantilla.
     * El nombre del modelo se toma del argumento "name".
     * Si se especifica la opción "migration", también se genera un archivo de migración para el modelo.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");
        $migration = $input->getOption("migration");

        // Lee la plantilla del modelo y reemplaza el nombre del modelo.
        $template = file_get_contents(resourcesDirectory() . "/templates/model.php");
        $template = str_replace("ModelName", $name, $template);

        // Crea el archivo del modelo en la carpeta "app/Models" de la aplicación.
        file_put_contents(App::$root . "/app/Models/$name.php", $template);
        $output->writeln("<info>Model created => $name.php</info>");

        // Si se especificó la opción "migration", crea un archivo de migración para el modelo.
        if ($migration !== false) {
            app(Migrator::class)->make("create_{$name}s_table");
        }

        // Retorna el código de éxito del comando.
        return Command::SUCCESS;
    }
}
