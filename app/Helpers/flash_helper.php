<?php

/**
 * Mensajes flash nativos sobre $_SESSION, en vez de session()->with()/getFlashdata() de CodeIgniter.
 */

if (! function_exists('flash_set')) {
    /**
     * @param string|array<int|string,string> $mensaje Texto simple, o un
     *        arreglo de errores (ej. el que devuelve $validator->getErrors()).
     */
    function flash_set(string $tipo, string|array $mensaje): void
    {
        $_SESSION['flash'][$tipo] = $mensaje;
    }
}

if (! function_exists('flash_get')) {
    /**
     * Devuelve el mensaje guardado para ese tipo (string, arreglo, o null
     * si no hay), y lo borra de la sesion para que no vuelva a aparecer en
     * la siguiente peticion.
     *
     * @return string|array<int|string,string>|null
     */
    function flash_get(string $tipo): string|array|null
    {
        $mensaje = $_SESSION['flash'][$tipo] ?? null;
        unset($_SESSION['flash'][$tipo]);

        return $mensaje;
    }
}

if (! function_exists('flash_has')) {
    function flash_has(string $tipo): bool
    {
        return isset($_SESSION['flash'][$tipo]);
    }
}