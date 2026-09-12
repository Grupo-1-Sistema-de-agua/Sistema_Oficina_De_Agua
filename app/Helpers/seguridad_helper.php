<?php

/**
 * Escape de salida nativo con htmlspecialchars(), en vez de esc() de CodeIgniter.
 */

if (! function_exists('esc_nativo')) {
    function esc_nativo($valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}