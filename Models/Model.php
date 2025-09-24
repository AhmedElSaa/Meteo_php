<?php

class Model
{
    /** Stockage de données */
    private ?array $meteo = null;
    private ?array $coords = null;

    /**
     * Lit et décode Data/meteo.json
     */
    private function loadMeteo()
    {
        $str = file_get_contents("Data/meteo.json");
        $json = json_decode($str, true);
        $this->meteo = $json['meteo'];
    }

    /**
     * Lit et décode Data/coords.json
     */
    private function loadCoords()
    {
        $str = file_get_contents("Data/coords.json");
        $json = json_decode($str, true);
        $this->coords = $json;
    }

    /**
     * Retourne toutes les coordonnées par nom de ville.
     */
    public function getCoords()
    {
        $this->loadCoords();
        return $this->coords;
    }

    /**
     * Retourne une liste des villes avec toutes ses données et des noms simplifiés.
     */
    public function listCities()
    {
        $this->loadMeteo();
        $villes = $this->meteo['bulletin']['ville'];

        return array_map(function ($v) {
            return [
                'id'         => $v['-id'],
                'temp_min'   => (int)$v['-temperature_mini'],
                'temp_max'   => (int)$v['-temperature_maxi'],
                'temps_code' => $v['-temps'],
                'vent_dir'   => $v['-vent_direction'],
                'vent_force' => (int)$v['-vent_force'],
                'rafales'    => (int)$v['-vent_rafales'],
                'humidite'   => (int)$v['-humidite'],
            ];
        }, $villes);
    }

    /**
     * Récupere le nom de la ville er retourne les infos du jour cette ville.
     */
    public function todayWeatherByCity(string $villeId)
    {
        $this->loadCoords();
        foreach ($this->listCities() as $v) {
            if ($v['id'] === $villeId) {
                $v['coord'] = $this->coords[$villeId] ?? null;
                return $v;
            }
        }
        return null;
    }

    /**
     * Récupere le nom de la ville et retourne les prévisions de cette ville.
     */
    public function forecastsByCity(string $villeId)
    {
        $this->loadMeteo();
        $jours = $this->meteo['previsions']['prevision'];
        $res = [];

        foreach ($jours as $j) {
            $date = $j['-date'];
            foreach (($j['ville']) as $v) {
                if (($v['-id']) === $villeId) {
                    $res[] = [
                        'date'       => $date,
                        'temp_min'   => (int)$v['-temperature_mini'],
                        'temp_max'   => (int)$v['-temperature_maxi'],
                        'temps_code' => $v['-temps'],
                        'vent_dir'   => $v['-vent_direction'],
                        'vent_force' => (int)$v['-vent_force'],
                        'humidite'   => (int)$v['-humidite'],
                    ];
                }
            }
        }
        return $res;
    }
}
