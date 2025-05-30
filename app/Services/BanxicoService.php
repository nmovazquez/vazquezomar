<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class BanxicoService
{
    public static function obtenerTipoCambio()
    {
        $token = config('services.banxico.token');
        $serie = 'SF43718'; // FIX USD/MXN
        $url = "https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos/oportuno";
      
        $response = Http::withHeaders([
            'Bmx-Token' => $token,
        ])->get($url);
     
        if ($response->successful()) {
            $data = $response->json();

            return $data['bmx']['series'][0]['datos'][0]['dato'] ?? null;
        }

        return null;
    }
}
