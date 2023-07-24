<?php

namespace Asaa\Crypto;

/**
 * Clase Bcrypt
 *
 * Esta clase proporciona métodos para realizar el hash y la verificación de contraseñas utilizando el algoritmo Bcrypt.
 * Implementa la interfaz Hasher, lo que garantiza que cumpla con los métodos definidos en dicha interfaz.
 */
class Bcrypt implements Hasher
{
    /**
     * Genera el hash de una cadena utilizando el algoritmo Bcrypt.
     *
     * @param string $input La cadena a ser hasheada.
     * @return string El hash generado para la cadena.
     */
    public function hash(string $input): string
    {
        // Utiliza la función password_hash de PHP con el algoritmo PASSWORD_BCRYPT para generar el hash.
        return password_hash($input, PASSWORD_BCRYPT);
    }

    /**
     * Verifica si una cadena coincide con su hash Bcrypt correspondiente.
     *
     * @param string $input La cadena a verificar.
     * @param string $hash El hash contra el cual se verificará la cadena.
     * @return bool true si la cadena coincide con el hash, false si no coincide.
     */
    public function verify(string $input, string $hash): bool
    {
        // Utiliza la función password_verify de PHP para verificar si la cadena coincide con el hash.
        return password_verify($input, $hash);
    }
}
