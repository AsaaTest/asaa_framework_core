<?php

namespace Asaa;

use Throwable;
use Asaa\Http\Request;
use Asaa\Config\Config;
use Asaa\Http\Response;
use Asaa\Server\Server;
use Asaa\Database\Model;
use Asaa\Routing\Router;
use Asaa\Session\Session;
use Asaa\Http\HttpNotFoundException;
use Asaa\Database\Drivers\DatabaseDriver;
use Asaa\Session\SessionStorage;
use Asaa\Validation\Exceptions\ValidationException;
use Dotenv\Dotenv;

/**
 * Clase App que representa la aplicación web.
 */
class App
{
    /**
     * @var string La ruta raíz de la aplicación.
     */
    public static string $root;

    /**
     * @var Router El enrutador de la aplicación.
     */
    public Router $router;

    /**
     * @var Request La solicitud actual de la aplicación.
     */
    public Request $request;

    /**
     * @var Server El servidor web utilizado por la aplicación.
     */
    public Server $server;

    /**
     * @var Session El sistema de sesión utilizado por la aplicación.
     */
    public Session $session;

    /**
     * @var DatabaseDriver El controlador de base de datos utilizado por la aplicación.
     */
    public DatabaseDriver $database;

    /**
     * Método bootstrap
     *
     * Inicializa y configura la aplicación.
     *
     * @param string $root La ruta raíz de la aplicación.
     * @return App La instancia de la aplicación configurada.
     */
    public static function bootstrap(string $root): App
    {
        self::$root = $root;

        $app = singleton(self::class);

        return $app
                ->loadConfig()
                ->runServiceProviders('boot')
                ->setHttpHandlers()
                ->setUpDatabaseConnection()
                ->runServiceProviders('runtime');

        return $app;
    }

    /**
     * Método loadConfig
     *
     * Carga la configuración de la aplicación desde los archivos .env y config.
     *
     * @return self La instancia de la aplicación actualizada.
     */
    protected function loadConfig(): self
    {
        Dotenv::createImmutable(self::$root)->load();
        Config::load(self::$root. "/config");
        return $this;
    }

    /**
     * Método runServiceProviders
     *
     * Ejecuta los service providers registrados en la configuración de la aplicación.
     *
     * @param string $type El tipo de service providers a ejecutar (boot o runtime).
     * @return self La instancia de la aplicación actualizada.
     */
    protected function runServiceProviders(string $type): self
    {
        foreach(config("providers.$type", []) as $provider) {
            $provider = new $provider();
            $provider->registerServices();
        }

        return $this;
    }

    /**
     * Método setHttpHandlers
     *
     * Configura los manejadores HTTP necesarios para la aplicación.
     *
     * @return self La instancia de la aplicación actualizada.
     */
    protected function setHttpHandlers(): self
    {
        $this->router = singleton(Router::class);
        $this->server = app(Server::class);
        $this->request = singleton(Request::class, fn () => $this->server->getRequest());
        $this->session = singleton(Session::class, fn () => new Session(app(SessionStorage::class)));

        return $this;
    }

    /**
     * Método setUpDatabaseConnection
     *
     * Configura y establece la conexión a la base de datos utilizando el controlador de base de datos.
     *
     * @return self La instancia de la aplicación actualizada.
     */
    protected function setUpDatabaseConnection(): self
    {
        $this->database = app(DatabaseDriver::class);
        $this->database->connect(
            config("database.connection"),
            config("database.host"),
            config("database.port"),
            config("database.database"),
            config("database.username"),
            config("database.password")
        );
        Model::setDatabaseDriver($this->database);

        return $this;
    }

    /**
     * Método prepareNextRequest
     *
     * Prepara la próxima solicitud para almacenar la URL actual en la sesión si es una solicitud GET.
     */
    protected function prepareNextRequest()
    {
        if($this->request->method() == 'GET') {
            $this->session->set('_previous', $this->request->uri());
        }
    }

    /**
     * Método terminate
     *
     * Finaliza la aplicación enviando la respuesta al cliente y cerrando la conexión a la base de datos.
     *
     * @param Response $response La respuesta que se enviará al cliente.
     */
    protected function terminate(Response $response)
    {
        $this->prepareNextRequest();
        $this->server->sendResponse($response);
        $this->database->close();
        exit();
    }

    /**
     * Método run
     *
     * Ejecuta la aplicación, resuelve la ruta de la solicitud actual y termina la aplicación enviando la respuesta al cliente.
     * Maneja excepciones y errores comunes, y envía una respuesta adecuada al cliente en caso de fallo.
     */
    public function run()
    {
        try {
            $this->terminate($this->router->resolve($this->request));
        } catch (HttpNotFoundException $e) {
            $this->abort(Response::text("No encontrado")->setStatus(404));
        } catch (ValidationException $e) {
            $this->abort(back()->withErrors($e->errors(), 422));
        } catch(Throwable $e) {
            $response = json([
                "error" => $e::class,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ]);
            $this->abort($response->setStatus(500));
        }
    }

    /**
     * Método abort
     *
     * Termina la aplicación enviando una respuesta de error al cliente.
     *
     * @param Response $response La respuesta de error que se enviará al cliente.
     */
    public function abort(Response $response)
    {
        $this->terminate($response);
    }
}
