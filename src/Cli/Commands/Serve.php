<?php

namespace Asaa\Cli\Commands;

use Asaa\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Comando Serve
 *
 * Este comando inicia el servidor de desarrollo para la aplicación Lune.
 * Utiliza el servidor web interno de PHP para servir la aplicación desde la carpeta pública.
 */
class Serve extends Command
{
    protected static $defaultName = "serve";
    protected static $defaultDescription = "Run Lune development application";

    /**
     * Configura las opciones del comando.
     *
     * Define las opciones "--host" y "--port" para permitir al usuario especificar la dirección IP y el puerto del servidor.
     * Los valores predeterminados para estas opciones son "127.0.0.1" y "8080", respectivamente.
     */
    protected function configure()
    {
        $this
            ->addOption("host", null, InputOption::VALUE_OPTIONAL, "Host address to run on", "127.0.0.1")
            ->addOption("port", null, InputOption::VALUE_OPTIONAL, "Port to run on", "8080");
    }

    /**
     * Ejecuta el comando Serve.
     *
     * Obtiene los valores de las opciones "--host" y "--port" especificados por el usuario, y luego inicia el servidor
     * de desarrollo utilizando el servidor web interno de PHP. La aplicación se sirve desde la carpeta pública.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $input->getOption("host");
        $port = $input->getOption("port");
        $dir = App::$root . "/public";

        $output->writeln("<info>Starting development server on $host:$port</info>");

        // Ejecuta el servidor de desarrollo usando el servidor web interno de PHP.
        shell_exec("php -S $host:$port $dir/index.php");

        // Retorna el código de éxito del comando.
        return Command::SUCCESS;
    }
}
