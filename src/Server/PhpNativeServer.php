<?php

// Define el espacio de nombres para la clase PhpNativeServer.

namespace Asaa\Server;

// Importa la clase de solicitud (Request) y respuesta (Response) del espacio de nombres Asaa\Http.
use Asaa\Http\Request;
use Asaa\Storage\File;
use Asaa\Http\Response;

/**
 * Clase PhpNativeServer que implementa la interfaz Server.
 * Esta clase se utiliza para obtener información de la solicitud HTTP y enviar respuestas al cliente utilizando las variables superglobales de PHP.
 */
class PhpNativeServer implements Server
{
    protected function uploadedFiles(): array
    {
        $files = [];
        foreach ($_FILES as $key => $file) {
            if (!empty($file["tmp_name"])) {
                $files[$key] = new File(
                    file_get_contents($file["tmp_name"]),
                    $file["type"],
                    $file["name"],
                );
            }
        }

        return $files;
    }

    protected function requestData(): array
    {
        $headers = getallheaders();

        $isJson = isset($headers["Content-Type"])
            && $headers["Content-Type"] === "application/json";


        if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isJson) {
            return $_POST;
        }

        if ($isJson) {
            $data = json_decode(file_get_contents("php://input"), associative: true);
        } else {
            parse_str(file_get_contents("php://input"), $data);
        }

        return $data;
    }

    /**
     * Obtiene la solicitud (Request) del cliente.
     *
     * @return Request La solicitud (Request) del cliente.
     */
    public function getRequest(): Request
    {
        // Crea una nueva instancia de la clase Request y establece sus propiedades utilizando las variables superglobales de PHP.
        return (new Request())
            ->setUri(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH))
            ->setMethod($_SERVER["REQUEST_METHOD"])
            ->setHeaders(getallheaders())
            ->setPostData($this->requestData())
            ->setQueryParameters($_GET)
            ->setFiles($this->uploadedFiles());
    }

    /**
     * Envía la respuesta (Response) al cliente.
     *
     * @param Response $response La respuesta que se enviará al cliente.
     * @return void
     */
    public function sendResponse(Response $response)
    {
        // PHP envía el encabezado Content-Type por defecto, pero debe eliminarse si la respuesta no tiene contenido.
        // El encabezado Content-Type no puede eliminarse a menos que se establezca algún valor previamente.
        header("Content-Type: None");
        header_remove("Content-Type");

        // Prepara la respuesta antes de enviarla al cliente.
        $response->prepare();

        // Establece el código de estado HTTP en la respuesta.
        http_response_code($response->status());

        // Agrega los encabezados de la respuesta al cliente.
        foreach ($response->headers() as $header => $value) {
            header("$header: $value");
        }

        // Imprime el contenido de la respuesta.
        print($response->content());
    }
}
