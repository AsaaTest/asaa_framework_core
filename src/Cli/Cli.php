<?php

namespace Asaa\Cli;

use Asaa\App;
use Asaa\Cli\Commands\MakeController;
use Asaa\Cli\Commands\MakeMigration;
use Asaa\Cli\Commands\MakeModel;
use Asaa\Cli\Commands\Migrate;
use Asaa\Cli\Commands\MigrateRollback;
use Asaa\Cli\Commands\Serve;
use Dotenv\Dotenv;
use Asaa\Config\Config;
use Asaa\Database\Drivers\DatabaseDriver;
use Asaa\Database\Migrations\Migrator;
use Symfony\Component\Console\Application;

/**
 * Clase Cli
 *
 * Esta clase proporciona la funcionalidad para iniciar y gestionar la interfaz de línea de comandos (CLI) de la aplicación.
 * Se encarga de cargar la configuración, los proveedores de servicios, establecer la conexión a la base de datos y definir
 * los comandos disponibles en la CLI. Utiliza la biblioteca Symfony Console para manejar los comandos de la CLI.
 */
class Cli
{
    /**
     * Inicializa la CLI y carga la configuración y los proveedores de servicios.
     *
     * @param string $root La ruta raíz de la aplicación.
     * @return self La instancia actual de la clase Cli.
     */
    public static function bootstrap(string $root): self
    {
        // Establece la ruta raíz de la aplicación.
        App::$root = $root;

        // Carga las variables de entorno desde el archivo .env en la ruta raíz.
        Dotenv::createImmutable($root)->load();

        // Carga la configuración de la carpeta "config" en la ruta raíz.
        Config::load($root. "/config");

        // Registra los servicios proporcionados por los proveedores de servicios configurados.
        foreach(config("providers.cli") as $provider) {
            (new $provider())->registerServices();
        }

        // Establece la conexión a la base de datos utilizando la configuración proporcionada.
        app(DatabaseDriver::class)->connect(
            config("database.connection"),
            config("database.host"),
            config("database.port"),
            config("database.database"),
            config("database.username"),
            config("database.password")
        );

        // Crea e inyecta una instancia de Migrator para manejar las migraciones de la base de datos.
        singleton(
            Migrator::class,
            fn () => new Migrator(
                "$root/database/migrations",
                resourcesDirectory() . "/templates",
                app(DatabaseDriver::class)
            )
        );

        return new self();
    }

    /**
     * Ejecuta la CLI y agrega los comandos disponibles.
     */
    public function run()
    {
        // Crea una nueva instancia de la aplicación CLI con el nombre "Asaa".
        $cli = new Application("Asaa");

        // Agrega los comandos disponibles a la aplicación CLI.
        $cli->addCommands([
            new MakeMigration(),
            new Migrate(),
            new MigrateRollback(),
            new MakeController(),
            new MakeModel(),
            new Serve()
        ]);

        // Ejecuta la aplicación CLI.
        $cli->run();
    }
}
