<?php

namespace Asaa\Tests\Database;

use PDOException;
use Asaa\Database\Model;
use Asaa\Database\Drivers\PdoDriver;
use Asaa\Database\Drivers\DatabaseDriver;

trait RefreshDatabase
{
    protected function setUp(): void
    {
        if (is_null($this->driver)) {
            $this->driver = singleton(DatabaseDriver::class, PdoDriver::class);
            Model::setDatabaseDriver($this->driver);
            try {
                $this->driver->connect('mysql', 'localhost', 3306, 'curso_framework_tests', 'root', '');
            } catch (PDOException $e) {
                $this->markTestSkipped("Can't connect to test database: {$e->getMessage()}");
            }
        }
    }

    protected function tearDown(): void
    {
        $this->driver->statement("DROP DATABASE IF EXISTS curso_framework_tests");
        $this->driver->statement("CREATE DATABASE curso_framework_tests");
    }
}
