<?php

namespace kbim\GoogleMaps;

use Yii;

class Map extends \yii\base\Widget {

    const MAP_TYPE_ROADMAP = 'ROADMAP';
    const MAP_TYPE_HYBRID = 'HYBRID';
    const MAP_TYPE_SATELLITE = 'SATELLITE';
    const MAP_TYPE_TERRAIN = 'TERRAIN';

    public $sensor = false;
    public $mapCanvas = 'map_canvas';
    public $width = '100%';
    public $height = '100%';
    public $center = 'Riia 184, Tartu, Tartu linn, Estonia';
    public $zoom = 12;
    public $mapType = 'ROADMAP';
    public $markers = [];
    public $mapOptions = [];
<<<<<<< HEAD
    public $circles = [];
=======
>>>>>>> 09e8fdcdc0892dde9d676799a8068a4c9c6c581e
    public $apiKey = null;
    public $markerFitBounds = false;
    public $language = 'en';    
    public $region = false;

    public function init() {
        if ($this->apiKey === null) {
            $this->apiKey = Yii::$app->params['GOOGLE_API_KEY'];
        }
        $this->sensor = $this->sensor ? 'true' : 'false';
        parent::init();
    }

    public function run() {

        return $this->render('map');
    }

}
