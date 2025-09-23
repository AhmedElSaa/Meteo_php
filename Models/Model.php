<?php

class Model
{

    /**
     * Récupère les données météo complètes depuis le JSON
     */
    public function getAllMeteo()
    {
        $str = file_get_contents("Data/meteo.json");
        $json = json_decode($str, true);
        return $json['meteo'];
    }

    /**
     * Récupère les infos du jour pour une ville donnée
     */
    public function getMeteoByVille($villeId)
    {
        $meteo = $this->getAllMeteo();
        foreach ($meteo['bulletin']['ville'] as $ville) {
            if ($ville['-id'] === $villeId) {
                return $ville;
            }
        }

        return null;
    }
}
