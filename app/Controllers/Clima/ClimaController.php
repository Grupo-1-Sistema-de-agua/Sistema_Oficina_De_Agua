<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ClimaController extends BaseController
{
    public function index()
    {
        // 1. Configurar la API (¡Reemplaza TU_API_KEY por la tuya!)
        $apiKey = 'TU_API_KEY_AQUI'; 
        $ciudad = 'Guatemala';
        // URL configurada para sistema métrico (Celsius) y en español
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$ciudad}&appid={$apiKey}&units=metric&lang=es";

        // 2. Consumir la API desde PHP de forma segura
        try {
            $cliente = \Config\Services::curlrequest();
            $respuesta = $cliente->request('GET', $url);
            $climaData = json_decode($respuesta->getBody());

            // Extraemos solo lo que necesitamos
            $datosClima = [
                'temperatura' => round($climaData->main->temp),
                'descripcion' => $climaData->weather[0]->description,
                'icono'       => $climaData->weather[0]->icon,
                'humedad'     => $climaData->main->humidity
            ];
        } catch (\Exception $e) {
            // Si la API falla, no rompemos el sistema, mostramos datos vacíos
            $datosClima = [
                'temperatura' => '--',
                'descripcion' => 'Servicio no disponible',
                'icono'       => '',
                'humedad'     => '--'
            ];
        }

        // 3. Pasamos la variable a la vista
        $data = [
            'clima' => $datosClima
        ];

        // Asegúrate de poner la ruta correcta hacia tu archivo de vista
        return view('app/views/layouts/main', $data); 
    }
}