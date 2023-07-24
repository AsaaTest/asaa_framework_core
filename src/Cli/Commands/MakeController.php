<?php

namespace Asaa\Cli\Commands;

use Asaa\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Comando MakeController
 *
 * Este comando permite crear un nuevo controlador en la aplicación.
 * Utiliza una plantilla para generar el código del controlador y lo guarda en la carpeta "app/Controllers".
 */
class MakeController extends Command
{
    protected static $defaultName = "make:controller";
    protected static $defaultDescription = "Create a new controller";

    /**
     * Configura los argumentos del comando.
     *
     * Define el argumento "name" como requerido para especificar el nombre del nuevo controlador a crear.
     */
    protected function configure()
    {
        $this->addArgument("name", InputArgument::REQUIRED, "Controller name");
    }

    /**
     * Ejecuta el comando MakeController.
     *
     * Crea un nuevo controlador en la carpeta "app/Controllers" de la aplicación utilizando una plantilla.
     * El nombre del controlador se toma del argumento "name".
     * La plantilla se reemplaza con el nombre del controlador y se guarda en un archivo con el mismo nombre en "app/Controllers".
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument("name");

        // Lee la plantilla del controlador y reemplaza el nombre del controlador.
        $template = file_get_contents(resourcesDirectory() . "/templates/controller.php");
        $template = str_replace("ControllerName", $name, $template);

        // Crea el archivo del controlador en la carpeta "app/Controllers" de la aplicación.
        file_put_contents(App::$root . "/app/Controllers/$name.php", $template);
        $output->writeln("<info>Controller created => $name.php</info>");

        // Retorna el código de éxito del comando.
        return Command::SUCCESS;
    }
}
