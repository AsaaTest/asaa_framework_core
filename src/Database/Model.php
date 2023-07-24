<?php

namespace Asaa\Database;

use Asaa\Database\Drivers\DatabaseDriver;

/**
 * Clase abstracta Model
 *
 * Esta clase abstracta proporciona una base para la implementación de modelos de base de datos en la aplicación.
 * Los modelos representan tablas de la base de datos y se utilizan para interactuar con los registros de esas tablas.
 * Proporciona métodos para crear, leer, actualizar y eliminar registros de la tabla asociada al modelo.
 * Además, permite realizar consultas de búsqueda y filtrado en la tabla.
 * Cada modelo debe extender esta clase para obtener las funcionalidades comunes de interacción con la base de datos.
 */
abstract class Model
{
    protected ?string $table = null;

    protected string $primaryKey = "id";

    protected array $hidden = [];

    protected array $fillable = [];

    protected array $attributes = [];

    protected bool $insertTimestamps = true;

    private static ?DatabaseDriver $driver = null;

    /**
     * Establece el controlador de base de datos para ser utilizado por los modelos.
     *
     * @param DatabaseDriver $driver El objeto de tipo DatabaseDriver para la conexión de base de datos.
     */
    public static function setDatabaseDriver(DatabaseDriver $driver)
    {
        self::$driver = $driver;
    }

    /**
     * Constructor de la clase Model.
     *
     * Inicializa el nombre de la tabla asociada al modelo si no se proporcionó en la clase hija.
     */
    public function __construct()
    {
        if (is_null($this->table)) {
            $subClass = new \ReflectionClass(static::class);
            $this->table = snake_case("{$subClass->getShortName()}s");
        }
    }

    /**
     * Método mágico para establecer atributos dinámicamente en el modelo.
     *
     * @param string $name El nombre del atributo.
     * @param mixed $value El valor del atributo.
     */
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Método mágico para obtener atributos del modelo.
     *
     * @param string $name El nombre del atributo a obtener.
     * @return mixed|null El valor del atributo si existe, o null si no existe.
     */
    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Método mágico para personalizar la serialización del modelo.
     *
     * Oculta los atributos marcados como "hidden" antes de la serialización.
     *
     * @return array Un arreglo con los nombres de los atributos que serán serializados.
     */
    public function __sleep()
    {
        foreach ($this->hidden as $hide) {
            unset($this->attributes[$hide]);
        }
        return array_keys(get_object_vars($this));
    }

    /**
     * Establece los atributos del modelo con un arreglo de valores.
     *
     * @param array $attributes Un arreglo con los nombres de los atributos y sus respectivos valores.
     * @return static El objeto del modelo con los atributos establecidos.
     */
    protected function setAttributes(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            $this->__set($key, $value);
        }

        return $this;
    }

    /**
     * Asigna en masa los atributos del modelo utilizando un arreglo de valores.
     *
     * @param array $attributes Un arreglo con los nombres de los atributos y sus respectivos valores.
     * @return static El objeto del modelo con los atributos asignados en masa.
     * @throws \Error Si el modelo no tiene atributos "fillable" definidos.
     */
    protected function massAsign(array $attributes): static
    {
        if (count($this->fillable) == 0) {
            throw new \Error("Model " . static::class . " does not have fillable attributes");
        }

        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->__set($key, $value);
            }
        }

        return $this;
    }

    /**
     * Convierte el modelo en un arreglo, excluyendo los atributos ocultos.
     *
     * @return array Un arreglo con los atributos del modelo que no están ocultos.
     */
    public function toArray(): array
    {
        return array_filter($this->attributes, fn ($attr) => !in_array($attr, $this->hidden));
    }

    /**
     * Guarda el modelo en la base de datos como un nuevo registro.
     *
     * @return static El objeto del modelo después de ser guardado en la base de datos.
     */
    public function save()
    {
        // Verifica si se deben insertar las marcas de tiempo (timestamps).
        if ($this->insertTimestamps) {
            $this->attributes["created_at"] = date("Y-m-d H:m:s");
        }

        // Construye las consultas para insertar el modelo en la base de datos.
        $databaseColums = implode(",", array_keys($this->attributes));
        $bind = implode(",", array_fill(0, count($this->attributes), "?"));

        // Ejecuta la consulta para insertar el modelo en la base de datos y obtiene el ID del nuevo registro.
        self::$driver->statement("INSERT INTO $this->table ($databaseColums) VALUES ($bind)", array_values($this->attributes));
        $this->{$this->primaryKey} = self::$driver->lastInsertId();

        return $this;
    }

    /**
     * Actualiza el modelo en la base de datos.
     *
     * @return static El objeto del modelo después de ser actualizado en la base de datos.
     */
    public function update(): static
    {
        // Verifica si se deben insertar las marcas de tiempo (timestamps).
        if ($this->insertTimestamps) {
            $this->attributes["updated_at"] = date("Y-m-d H:m:s");
        }

        // Construye las consultas para actualizar el modelo en la base de datos.
        $databaseColumns = array_keys($this->attributes);
        $bind = implode(",", array_map(fn ($column) => "$column = ?", $databaseColumns));
        $id = $this->attributes[$this->primaryKey];

        // Ejecuta la consulta para actualizar el modelo en la base de datos.
        self::$driver->statement("UPDATE $this->table SET $bind WHERE $this->primaryKey = $id", array_values($this->attributes));

        return $this;
    }

    /**
     * Elimina el modelo de la base de datos.
     *
     * @return static El objeto del modelo después de ser eliminado de la base de datos.
     */
    public function delete(): static
    {
        // Ejecuta la consulta para eliminar el modelo de la base de datos.
        self::$driver->statement("DELETE FROM $this->table WHERE $this->primaryKey = {$this->attributes[$this->primaryKey]}");

        return $this;
    }

    /**
     * Crea un nuevo registro del modelo en la base de datos utilizando atributos en masa.
     *
     * @param array $attributes Un arreglo con los nombres de los atributos y sus respectivos valores.
     * @return static El objeto del modelo después de ser creado y guardado en la base de datos.
     */
    public static function create(array $attributes): static
    {
        return (new static())->massAsign($attributes)->save();
    }

    /**
     * Obtiene el primer registro del modelo de la base de datos.
     *
     * @return static|null El primer objeto del modelo si existe, o null si no hay registros.
     */
    public static function first(): ?static
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table LIMIT 1");

        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }

    /**
     * Busca un registro del modelo en la base de datos por su clave primaria.
     *
     * @param int|string $id El valor de la clave primaria para buscar el registro.
     * @return static|null El objeto del modelo encontrado si existe, o null si no se encontró.
     */
    public static function find(int|string $id): ?static
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table WHERE $model->primaryKey = ?", [$id]);

        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }

    /**
     * Obtiene todos los registros del modelo de la base de datos.
     *
     * @return array Un arreglo con todos los objetos del modelo encontrados en la base de datos.
     */
    public static function all(): array
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table");

        if (count($rows) == 0) {
            return [];
        }

        $models = [$model->setAttributes($rows[0])];

        for ($i = 1; $i < count($rows); $i++) {
            $models[] = (new static())->setAttributes($rows[$i]);
        }

        return $models;
    }

    /**
     * Realiza una consulta en la base de datos para obtener registros filtrados por un valor de columna.
     *
     * @param string $column El nombre de la columna a filtrar.
     * @param mixed $value El valor por el cual filtrar los registros.
     * @return array Un arreglo con los objetos del modelo encontrados que cumplen el criterio de búsqueda.
     */
    public static function where(string $column, mixed $value): array
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table WHERE $column = ?", [$value]);

        if (count($rows) == 0) {
            return [];
        }

        $models = [$model->setAttributes($rows[0])];

        for ($i = 1; $i < count($rows); $i++) {
            $models[] = (new static())->setAttributes($rows[$i]);
        }

        return $models;
    }

    /**
     * Obtiene el primer registro del modelo que cumple un criterio de búsqueda en la base de datos.
     *
     * @param string $column El nombre de la columna a filtrar.
     * @param mixed $value El valor por el cual filtrar los registros.
     * @return static|null El primer objeto del modelo que cumple el criterio de búsqueda, o null si no se encontró.
     */
    public static function firstWhere(string $column, mixed $value): ?static
    {
        $model = new static();
        $rows = self::$driver->statement("SELECT * FROM $model->table WHERE $column = ? LIMIT 1", [$value]);

        if (count($rows) == 0) {
            return null;
        }

        return $model->setAttributes($rows[0]);
    }
}
