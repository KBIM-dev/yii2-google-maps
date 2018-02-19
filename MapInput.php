<?php

<<<<<<< HEAD
	namespace kbim\GoogleMaps;

	use Yii;

	class MapInput extends \yii\base\Widget {

		const MAP_TYPE_ROADMAP = 'ROADMAP';
		const MAP_TYPE_HYBRID = 'HYBRID';
		const MAP_TYPE_SATELLITE = 'SATELLITE';
		const MAP_TYPE_TERRAIN = 'TERRAIN';

		public $sensor = false;
		public $mapCanvas = 'map_canvas';
		public $language = 'en';
		public $width = '100%';
		public $height = '100%';
		public $center = 'Riia 184, Tartu, Tartu linn, Estonia';
		public $circle = false;
		public $circleOptions = [
            'strokeColor' => '#0000FF',
            'strokeOpacity' => 0.8,
            'strokeWeight' => 2,
            'fillColor' => '#0000FF',
            'fillOpacity' => 0.35,
            'kilometers' => false,
        ];
		public $zoom = 12;
		public $mapType = 'ROADMAP';
		public $mapOptions = [];
		public $markerOptions = [];
		public $apiKey = null;

		public $countryInput = null;
		public $addressInput = 'address-input';
		public $addressTypeInput = 'address-type-input';
		public $latInput = 'lat-input';
		public $lngInput = 'lng-input';
		public $radiusInput = 'radius-input';


		public function init() {
			if ($this->apiKey === null) {
				$this->apiKey = Yii::$app->params['GOOGLE_API_KEY'];
			}
			$this->sensor = $this->sensor ? 'true' : 'false';
			parent::init();
		}

		public function run() {

			return $this->render('mapInput');
		}

	}
=======
namespace kbim\GoogleMaps;

use Yii;

class MapInput extends \yii\base\Widget {

    const MAP_TYPE_ROADMAP = 'ROADMAP';
    const MAP_TYPE_HYBRID = 'HYBRID';
    const MAP_TYPE_SATELLITE = 'SATELLITE';
    const MAP_TYPE_TERRAIN = 'TERRAIN';

    public $sensor = false;
    public $mapCanvas = 'map_canvas';
    public $language = 'en';
    public $width = '100%';
    public $height = '100%';
    public $center = 'Riia 184, Tartu, Tartu linn, Estonia';
    public $zoom = 12;
    public $mapType = 'ROADMAP';
    public $mapOptions = [];
    public $markerOptions = [];
    public $apiKey = null;

    public $countryInput = null;
    public $addressInput = 'address-input';
    public $addressTypeInput = 'address-type-input';
    public $latInput = 'lat-input';
    public $lngInput = 'lng-input';


    public function init() {
        if ($this->apiKey === null) {
            $this->apiKey = Yii::$app->params['GOOGLE_API_KEY'];
        }
        $this->sensor = $this->sensor ? 'true' : 'false';
        parent::init();
    }

    public function run() {

        return $this->render('mapInput');
    }

}
>>>>>>> 09e8fdcdc0892dde9d676799a8068a4c9c6c581e
