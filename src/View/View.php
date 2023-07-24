<?php

namespace Asaa\View;

/**
 * Interfaz View
 *
 * Esta interfaz define el contrato para una clase que representa una vista en una aplicación.
 * Las clases que implementan esta interfaz deben proporcionar la funcionalidad para renderizar
 * una vista y devolver el resultado como una cadena de texto.
 */
interface View
{
    /**
     * Método render
     *
     * Renderiza una vista con los parámetros especificados y devuelve el resultado como una cadena de texto.
     *
     * @param string $view El nombre o ruta de la vista que se desea renderizar.
     * @param array $params Un array de parámetros opcionales que se pueden pasar a la vista.
     * @param string|null $layout El nombre o ruta del layout que se utilizará para envolver la vista.
     *                           Si es nulo, la vista se renderizará sin layout.
     * @return string El resultado de renderizar la vista como una cadena de texto.
     */
    public function render(string $view, array $params = [], string $layout = null): string;
}
