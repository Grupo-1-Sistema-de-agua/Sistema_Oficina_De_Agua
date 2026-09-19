<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController 
{
    public function index()
    {
        // 1. Lógica del Clima
        $apiKey = '59a1d726c080747797be2f13080aab72'; 
        $ciudad = 'Guatemala';
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$ciudad}&appid={$apiKey}&units=metric&lang=es";

        try {
            $cliente = \Config\Services::curlrequest();
            $respuesta = $cliente->request('GET', $url);
            $climaData = json_decode($respuesta->getBody());

            $datosClima = [
                'temperatura' => round($climaData->main->temp),
                'descripcion' => $climaData->weather[0]->description,
                'icono'       => $climaData->weather[0]->icon,
                'humedad'     => $climaData->main->humidity
            ];
        } catch (\Exception $e) {
            $datosClima = [
                'temperatura' => '--',
                'descripcion' => 'Servicio no disponible',
                'icono'       => '',
                'humedad'     => '--'
            ];
        }

        // 2. Pasamos los datos a la vista del dashboard
        $data = [
            'clima' => $datosClima
        ];

        // 3. Cargamos la vista de tu dashboard (Asegúrate de que este nombre sea el correcto)
        return view('clima/index', $data); 
    }
}