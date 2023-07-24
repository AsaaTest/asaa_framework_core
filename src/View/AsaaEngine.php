<?php

namespace Asaa\View;

/**
 * Clase AsaaEngine
 *
 * Esta clase implementa la interfaz View y proporciona la funcionalidad para renderizar vistas
 * utilizando un motor de plantillas PHP simple. Las vistas se pueden envolver en layouts para
 * establecer una estructura común en todas las páginas renderizadas.
 */
class AsaaEngine implements View
{
    /**
     * @var string La ruta del directorio donde se encuentran las vistas y layouts.
     */
    protected string $viewsDirectory;

    /**
     * @var string El layout predeterminado que se utilizará si no se especifica otro layout.
     */
    protected string $defaultLayout = "main";

    /**
     * @var string El marcador de posición utilizado en el layout para indicar dónde se insertará el contenido de la vista.
     */
    protected string $contentAnnotation = "@content";

    /**
     * Constructor de la clase AsaaEngine.
     *
     * @param string $viewsDirectory La ruta del directorio donde se encuentran las vistas y layouts.
     */
    public function __construct(string $viewsDirectory)
    {
        $this->viewsDirectory = $viewsDirectory;
    }

    /**
     * Método render
     *
     * Renderiza una vista con los parámetros especificados y devuelve el resultado como una cadena de texto.
     * La vista puede ser envuelta en un layout para establecer una estructura común.
     *
     * @param string $view El nombre o ruta de la vista que se desea renderizar.
     * @param array $params Un array de parámetros opcionales que se pueden pasar a la vista.
     * @param string|null $layout El nombre o ruta del layout que se utilizará para envolver la vista.
     *                           Si es nulo, la vista se renderizará sin layout, usando el layout predeterminado.
     * @return string El resultado de renderizar la vista como una cadena de texto.
     */
    public function render(string $view, array $params = [], $layout = null): string
    {
        $layoutContent = $this->renderLayout($layout ?? $this->defaultLayout);
        $viewContent = $this->renderView($view, $params);

        // Reemplaza el marcador de contenido del layout con el contenido de la vista.
        return str_replace($this->contentAnnotation, $viewContent, $layoutContent);
    }

    /**
     * Método renderView
     *
     * Renderiza una vista con los parámetros especificados y devuelve el resultado como una cadena de texto.
     *
     * @param string $view El nombre o ruta de la vista que se desea renderizar.
     * @param array $params Un array de parámetros opcionales que se pueden pasar a la vista.
     * @return string El resultado de renderizar la vista como una cadena de texto.
     */
    protected function renderView(string $view, array $params = []): string
    {
        // Utiliza la función phpFileOutput para obtener el contenido de la vista a partir del archivo PHP correspondiente.
        return $this->phpFileOutput("{$this->viewsDirectory}/{$view}.php", $params);
    }

    /**
     * Método renderLayout
     *
     * Renderiza un layout con los parámetros especificados y devuelve el resultado como una cadena de texto.
     *
     * @param string $layout El nombre o ruta del layout que se desea renderizar.
     * @return string El resultado de renderizar el layout como una cadena de texto.
     */
    protected function renderLayout(string $layout): string
    {
        // Utiliza la función phpFileOutput para obtener el contenido del layout a partir del archivo PHP correspondiente.
        return $this->phpFileOutput("{$this->viewsDirectory}/layouts/{$layout}.php");
    }

    /**
     * Método phpFileOutput
     *
     * Incluye un archivo PHP y captura su salida como una cadena de texto.
     * Los parámetros proporcionados en el array se convierten en variables dentro del archivo PHP.
     *
     * @param string $phpFile La ruta del archivo PHP que se desea incluir.
     * @param array $params Un array de parámetros opcionales para el archivo PHP.
     * @return string El contenido del archivo PHP como una cadena de texto.
     */
    protected function phpFileOutput(string $phpFile, array $params = []): string
    {
        // Verifica si el archivo PHP existe.
        if(!file_exists($phpFile)) {
            return "Vista $phpFile no encontrada";
        }

        // Extrae los parámetros y los convierte en variables locales dentro del archivo PHP.
        foreach($params as $param => $value) {
            $$param = $value;
        }

        // Captura la salida del archivo PHP utilizando un buffer de salida.
        ob_start();
        include_once $phpFile;
        return ob_get_clean();
    }

}
