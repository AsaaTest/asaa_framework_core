<?php

/**
 * Convierte una cadena en formato "snake_case".
 *
 * @param string $str La cadena a convertir.
 * @return string La cadena en formato "snake_case".
 */
function snake_case(string $str): string
{
    $snake_cased = []; // Un array para almacenar los caracteres de la cadena en formato "snake_case".
    $skip = [' ', '-', '_', '/', '\\', '|', ',', '.', ';', ':']; // Caracteres que se omitirán al convertir.

    $i = 0; // Variable para recorrer los caracteres de la cadena.

    while ($i < strlen($str)) { // Bucle para recorrer cada caracter de la cadena.
        $last = count($snake_cased) > 0
            ? $snake_cased[count($snake_cased) - 1]
            : null; // Obtener el último caracter agregado al array.

        $character = $str[$i++]; // Obtener el siguiente caracter de la cadena.

        if (ctype_upper($character)) { // Si el caracter es una letra mayúscula.
            if ($last !== '_') { // Si el último caracter no es un guión bajo.
                $snake_cased[] = '_'; // Agregar un guión bajo para separar palabras.
            }
            $snake_cased[] = strtolower($character); // Convertir la letra mayúscula a minúscula y agregar al array.
        } elseif (ctype_lower($character)) { // Si el caracter es una letra minúscula.
            $snake_cased[] = $character; // Agregar la letra minúscula al array.
        } elseif (in_array($character, $skip)) { // Si el caracter está en el array de caracteres a omitir.
            if ($last !== '_') { // Si el último caracter no es un guión bajo.
                $snake_cased[] = '_'; // Agregar un guión bajo para separar palabras.
            }
            while ($i < strlen($str) && in_array($str[$i], $skip)) { // Omitir los caracteres repetidos del array de caracteres a omitir.
                $i++;
            }
        }
    }

    if ($snake_cased[0] == '_') { // Si el primer caracter es un guión bajo.
        $snake_cased[0] = ''; // Eliminar el guión bajo.
    }

    if ($snake_cased[count($snake_cased) - 1] == '_') { // Si el último caracter es un guión bajo.
        $snake_cased[count($snake_cased) - 1] = ''; // Eliminar el guión bajo.
    }

    return implode($snake_cased); // Convertir el array en una cadena y devolverla.
}
