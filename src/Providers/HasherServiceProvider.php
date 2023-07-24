<?php

namespace Asaa\Providers;

use Asaa\Crypto\Bcrypt;
use Asaa\Crypto\Hasher;

/**
 * Clase HasherServiceProvider.
 *
 * Este proveedor de servicios se encarga de registrar el algoritmo de hashing que se utilizará en la aplicación para encriptar contraseñas u otros datos sensibles.
 * El algoritmo de hashing se define según la configuración proporcionada en el archivo de configuración "hashing.php".
 * En caso de que no se especifique una configuración, el algoritmo de hashing predeterminado será el Bcrypt.
 */
class HasherServiceProvider implements ServiceProvider
{
    /**
     * Registra el servicio del algoritmo de hashing de acuerdo a la configuración.
     *
     * Este método verifica la configuración de hashing definida en el archivo de configuración "hashing.php".
     * Si se especifica "bcrypt" como el algoritmo de hashing, se registra el Bcrypt como el algoritmo predeterminado utilizando la función `singleton()`.
     * En caso de que no se especifique una configuración válida, el algoritmo de hashing predeterminado será el Bcrypt.
     *
     * @return void
     */
    public function registerServices()
    {
        match (config('hashing.hasher', 'bcrypt')) {
            "bcrypt" => singleton(Hasher::class, Bcrypt::class)
        };
    }
}
