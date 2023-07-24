<?php

namespace Asaa\Tests\Routing;

use Asaa\Routing\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /**
     * Proporciona una lista de rutas sin parámetros para probar las coincidencias con la expresión regular.
     *
     * @return array Un arreglo de rutas sin parámetros.
     */
    public function routesWithNoParameters()
    {
        return [
            ['/'],
            ['/test'],
            ['/test/nested'],
            ['/test/another/nested'],
            ['/test/another/nested/very/nested/route']
        ];
    }

    /**
     * Prueba la coincidencia de la expresión regular para rutas sin parámetros.
     *
     * @dataProvider routesWithNoParameters
     * @param string $uri La URI de la ruta a probar.
     */
    public function test_regex_with_no_parameters(string $uri)
    {
        $route = new Route($uri, fn () => "Test");
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches("$uri/extra/path"));
        $this->assertFalse($route->matches("/some/path/$uri"));
        $this->assertFalse($route->matches("/some/$uri/extra/path"));
        $this->assertFalse($route->matches("/random/route"));
    }

    /**
     * Prueba la coincidencia de la expresión regular para rutas que terminan con una barra diagonal (/).
     *
     * @dataProvider routesWithNoParameters
     * @param string $uri La URI de la ruta a probar.
     */
    public function test_regex_on_uri_that_ends_with_slash(string $uri)
    {
        $route = new Route($uri, fn () => "Test");
        $this->assertTrue($route->matches("$uri/"));
    }

    /**
     * Proporciona una lista de rutas con parámetros para probar las coincidencias con la expresión regular y el análisis de parámetros.
     *
     * @return array Un arreglo de rutas con parámetros, su URI correspondiente y los parámetros esperados.
     */
    public function routesWithParameters()
    {
        return [
            ['/test/{test}', '/test/1', ['test' => 1]],
            ['/users/{user}', '/users/205', ['user' => 205]],
            ['/test/{test}', '/test/string', ['test' => "string"]],
            ['/users/{user}', '/users/2string', ['user' => "2string"]],
            ['/test/nested/{route}', '/test/nested/abc334adasda', ['route' => "abc334adasda"]],
            ['/test/{param}/long/{test}/with/{multiple}', '/test/2/long/mole/with/23ab34dsadasdjoc93', ['param' => 2, 'test' => "mole", 'multiple' => "23ab34dsadasdjoc93" ]]
        ];
    }

    /**
     * Prueba la coincidencia de la expresión regular para rutas con parámetros.
     *
     * @dataProvider routesWithParameters
     * @param string $definition La definición de la ruta con parámetros.
     * @param string $uri La URI de la ruta a probar.
     */
    public function test_regex_with_parameters(string $definition, string $uri)
    {
        $route = new Route($definition, fn () => "Test");
        $this->assertTrue($route->matches($uri));
        $this->assertFalse($route->matches("$uri/extra/path"));
        $this->assertFalse($route->matches("/some/path/$uri"));
        $this->assertFalse($route->matches("/some/$uri/extra/path"));
        $this->assertFalse($route->matches("/random/route"));
    }

    /**
     * Prueba el análisis de parámetros de la URI para rutas con parámetros.
     *
     * @dataProvider routesWithParameters
     * @param string $definition La definición de la ruta con parámetros.
     * @param string $uri La URI de la ruta a probar.
     * @param array $expectedParams Los parámetros esperados y sus valores extraídos de la URI.
     */
    public function test_parse_paramaters(string $definition, string $uri, array $expectedParams)
    {
        $route = new Route($definition, fn () => "Test");
        $this->assertTrue($route->hasParameters());
        $this->assertEquals($expectedParams, $route->parseParameters($uri));
    }
}
