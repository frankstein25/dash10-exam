<?php

use Illuminate\Support;  // https://laravel.com/docs/5.8/collections - provides the collect methods & collections class
use LSS\Array2Xml;

class Controller {
    public function __construct($args) {
        $this->args = $args;
    }

    public function export($type, $format) {
        $exporter = new Exporter();
        switch ($type) {
            case 'playerstats':
                $searchArgs = ['player', 'playerId', 'team', 'position', 'country'];
                $search = $this->args->filter(function($value, $key) use ($searchArgs) {
                    return in_array($key, $searchArgs);
                });
                $data = $exporter->getPlayerStats($search);
                break;
            case 'players':
                $searchArgs = ['player', 'playerId', 'team', 'position', 'country'];
                $search = $this->args->filter(function($value, $key) use ($searchArgs) {
                    return in_array($key, $searchArgs);
                });
                $data = $exporter->getPlayers($search);
                break;
        }

        if (!$data) {
            throw new Exception("No Data Found");
        }

        return $exporter->format($data, $format);
    }
}
