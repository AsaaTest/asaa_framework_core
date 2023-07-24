<?php

namespace Asaa\Crypto;

/**
 * Interfaz Hasher
 *
 * Esta interfaz define los métodos que deben ser implementados por cualquier clase que actúe como un hasher (hasheador).
 * Un hasher es responsable de generar y verificar hashes para ciertos datos, como contraseñas o información sensible.
 * Al definir esta interfaz, podemos asegurarnos de que todas las clases que actúen como hashers tengan los métodos
 * necesarios para realizar estas operaciones de manera consistente.
 */
interface Hasher
{
    /**
     * Genera el hash de una cadena de entrada.
     *
     * @param string $input La cadena a ser hasheada.
     * @return string El hash generado para la cadena de entrada.
     */
    public function hash(string $input): string;

    /**
     * Verifica si una cadena de entrada coincide con su hash correspondiente.
     *
     * @param string $input La cadena a verificar.
     * @param string $hash El hash contra el cual se verificará la cadena.
     * @return bool true si la cadena coincide con el hash, false si no coincide.
     */
    public function verify(string $input, string $hash): bool;
}
