<?php

namespace Asaa\Tests\View;

use Asaa\View\AsaaEngine;
use PHPUnit\Framework\TestCase;

class AssaEngineTest extends TestCase
{
    public function test_renders_template_with_parameters()
    {
        $parameter1 = "test1";
        $parameter2 = 1997;

        $expected = "<html>
        <body>
        <h1>$parameter1</h1>
        <h1>$parameter2</h1>
        </body>
        </html>";

        $engine = new AsaaEngine(__DIR__. "/views");

        $content = $engine->render('test', compact('parameter1', 'parameter2'), 'layout');

        $this->assertEquals(preg_replace("/\s*/", "", $expected), preg_replace("/\s*/", "", $content));
    }
}
