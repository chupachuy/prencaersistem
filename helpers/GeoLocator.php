<?php

class GeoLocator
{
    /**
     * Obtiene datos de geolocalización a partir de una dirección IP.
     * Usa el servicio gratuito ip-api.com (sin API key, 45 req/min).
     *
     * @param string $ip Dirección IP a consultar
     * @return array|null [pais, region, ciudad, latitud, longitud, proveedor] o null si falla
     */
    public static function locate($ip)
    {
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return [
                'pais' => 'Local',
                'region' => '',
                'ciudad' => '',
                'latitud' => null,
                'longitud' => null,
                'proveedor' => 'Development',
            ];
        }

        $url = "http://ip-api.com/json/{$ip}?fields=country,regionName,city,lat,lon,isp";

        $context = stream_context_create([
            'http' => [
                'timeout' => 3,
                'method' => 'GET',
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if (!$response) {
            error_log("GeoLocator: No response from ip-api.com for IP {$ip}");
            return null;
        }

        $data = json_decode($response, true);
        if (empty($data) || (!empty($data['status']) && $data['status'] === 'fail')) {
            error_log("GeoLocator: API returned fail for IP {$ip}: " . ($data['message'] ?? 'unknown'));
            return null;
        }

        return [
            'pais' => $data['country'] ?? null,
            'region' => $data['regionName'] ?? null,
            'ciudad' => $data['city'] ?? null,
            'latitud' => $data['lat'] ?? null,
            'longitud' => $data['lon'] ?? null,
            'proveedor' => $data['isp'] ?? null,
        ];
    }
}
