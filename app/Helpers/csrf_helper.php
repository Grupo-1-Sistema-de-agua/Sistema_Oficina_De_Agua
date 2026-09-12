<?php

/**
 * Proteccion CSRF nativa: token generado e incluido como campo oculto en cada formulario.
 */

if (! function_exists('csrf_token_nativo')) {
    /**
     * Devuelve el token de la sesion actual, generandolo si todavia no existe.
     */
    function csrf_token_nativo(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

if (! function_exists('csrf_field_nativo')) {
    /**
     * Campo oculto listo para insertar dentro de un form.
     * Uso en las vistas: csrf_field_nativo()
     */
    function csrf_field_nativo(): string
    {
        return '<input type="hidden" name="csrf_token_nativo" value="' . esc(csrf_token_nativo()) . '">';
    }
}