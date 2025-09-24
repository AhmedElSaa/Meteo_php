<?php

class Controller_home extends Controller
{
    private Model $model;

    /**
     * Instancie le modèle
     */
    public function __construct()
    {
        $this->model = new Model();
        parent::__construct();
    }

    /**
     * Rend la vue principale
     */
    public function action_home()
    {
        $data = [];
        $this->render('home', $data);
    }

    /**
     * Encode JSON pour récupérer toutes les coordonnées des villes.
     */
    public function action_coords()
    {
        header('Content-Type: application/json; charset=utf-8');
        try {
            $coords = $this->model->getCoords();
            echo json_encode(['coords' => $coords], JSON_UNESCAPED_UNICODE);
            return;
        } catch (Exception  $e) {
            throw $e;
        }
    }

    /**
     * Encode JSON qui renvoie la météo du jour et prévisions pour une VILLE donnée en paramètre.
     */
    public function action_ville()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $name = $_GET['name'] ?? null;
            if (!$name) {
                echo json_encode(['error' => 'Paramètre "name" manquant'], JSON_UNESCAPED_UNICODE);
                return;
            }
            $today = $this->model->todayWeatherByCity($name);
            if (!$today) {
                echo json_encode(['error' => 'Ville introuvable dans le bulletin'], JSON_UNESCAPED_UNICODE);
                return;
            }
            echo json_encode([
                'today'      => $today,
                'previsions' => $this->model->forecastsByCity($name),
            ], JSON_UNESCAPED_UNICODE);
            die();
        } catch (Exception  $e) {
            throw $e;
        }
    }


    /**
     * Page par défaut.
     */
    public function action_default()
    {
        $this->action_home();
    }
}
