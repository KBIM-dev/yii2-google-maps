<div style="width: <?= $this->context->width?>;
        height: <?= $this->context->height?>">
    <div id="<?= $this->context->mapCanvas?>" style="width:100%; height:100%"></div>
</div>
<script>
	var gMarker;
	var map;
	function initialize() {
		var geocoder = new google.maps.Geocoder();
		if (!window.map) {
            window.map = new google.maps.Map(document.getElementById("<?= $this->context->mapCanvas?>"),
                {
                    <?php if (!empty($this->context->mapOptions) && is_array($this->context->mapOptions)): ?>
                        <?php foreach ($this->context->mapOptions as $mapOptionKey => $mapOption): ?>
                            <?=$mapOptionKey?>: <?=$mapOption?>,
                        <?php endforeach; ?>
                    <?php endif; ?>
                    zoom: <?= $this->context->zoom ?>,
                    mapTypeId: google.maps.MapTypeId.<?= $this->context->mapType ?>,
                    center: new google.maps.LatLng(0, 0)
                }
            );
        }

		var latInput = document.getElementById('<?= $this->context->latInput?>').value;
		var lngInput = document.getElementById('<?= $this->context->lngInput?>').value;
		if (latInput != "" && lngInput !="" ){
			var center = new google.maps.LatLng(latInput, lngInput);
			gMarker = new google.maps.Marker({
        <?php if (!empty($this->context->markerOptions) && is_array($this->context->markerOptions)): ?>
        <?php foreach ($this->context->markerOptions as $markerOptionKey => $markerOption): ?>
        <?=$markerOptionKey?>: <?=$markerOption?>,
        <?php endforeach; ?>
        <?php endif; ?>
			map: map,
				draggable: true,
				position: center
		});
			window.map.setCenter(center);
			google.maps.event.addListener(gMarker,'dragend',function(event){
				geocodeLocation(event);
				if (window.circle) {
					window.circle.setCenter(event.latLng);
				}
			});
		}else{
        <?php if (is_array($this->context->center)): ?>
			var center = new google.maps.LatLng(<?= $this->context->center[0] ?>, <?= $this->context->center[1] ?>);
			gMarker = new google.maps.Marker({
        <?php if (!empty($this->context->markerOptions) && is_array($this->context->markerOptions)): ?>
        <?php foreach ($this->context->markerOptions as $markerOptionKey => $markerOption): ?>
        <?=$markerOptionKey?>: <?=$markerOption?>,
        <?php endforeach; ?>
        <?php endif; ?>
			map: map,
				draggable: true,
				position: center
		});

			window.map.setCenter(center);
			google.maps.event.addListener(gMarker,'dragend',function(event){
				geocodeLocation(event);
				if (window.circle) {
					window.circle.setCenter(event.latLng);
				}
			});

		});


  <?php else: ?>
	geocoder.geocode({
		"address": "<?= $this->context->center ?>"
	}, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			var center = results[0].geometry.location;
			window.map.setCenter(center);
		}
	});
  <?php endif; ?>
	}
  <?php if ($this->context->circle): ?>
	var radiusInput = document.getElementById('<?= $this->context->radiusInput ?>');
	if (radiusInput.value) {
		window.circle = new google.maps.Circle({
			strokeColor: '<?= $this->context->circleOptions['strokeColor'] ?>',
			strokeOpacity: <?= $this->context->circleOptions['strokeOpacity'] ?>,
			strokeWeight: <?= $this->context->circleOptions['strokeWeight'] ?>,
			fillColor: '<?= $this->context->circleOptions['fillColor'] ?>',
			fillOpacity: <?= $this->context->circleOptions['fillOpacity'] ?>,
			map: map,
			center: center,
			radius: parseInt(radiusInput.value) * <?= $this->context->circleOptions['kilometers'] ? 1000 : 1 ?>
		});
	}
	var _handler = function(event) {
		window.circle.setRadius(event.target.value * <?= $this->context->circleOptions['kilometers'] ? 1000 : 1 ?>);
	};
	radiusInput.addEventListener('change', _handler);
	radiusInput.addEventListener('wheel', _handler);
  <?php endif; ?>

	// insert marker if no markers on map
	google.maps.event.addListener(map,'click',function(event){
		if (typeof gMarker === 'undefined'){
			gMarker = new google.maps.Marker({
        <?php if (!empty($this->context->markerOptions) && is_array($this->context->markerOptions)): ?>
        <?php foreach ($this->context->markerOptions as $markerOptionKey => $markerOption): ?>
        <?=$markerOptionKey?>: <?=$markerOption?>,
        <?php endforeach; ?>
        <?php endif; ?>
			map: map,
				draggable: true,
				position: event.latLng
		});

			google.maps.event.addListener(gMarker,'dragend',function(event){
				geocodeLocation(event);
			});

			geocodeLocation(event);

		}
	});
	// Create the search box and link it to the UI element.
	var input = document.getElementById('<?= $this->context->addressInput?>');
	var searchBox = new google.maps.places.SearchBox(input);
	//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

	// Bias the SearchBox results towards current map's viewport.
	map.addListener('bounds_changed', function() {
		searchBox.setBounds(map.getBounds());
	});

	var markers = [];
	// Listen for the event fired when the user selects a prediction and retrieve
	// more details for that place.
	searchBox.addListener('places_changed', function() {
		var places = searchBox.getPlaces();

		if (places.length == 0) {
			return;
		}

		// Clear out the old markers.
		markers.forEach(function(marker) {
			marker.setMap(null);
		});
		markers = [];

		// For each place, get the icon, name and location.
		var bounds = new google.maps.LatLngBounds();
		places.forEach(function(place) {
			// delete old marker
			if (typeof gMarker !== 'undefined'){
				gMarker.setMap(null);
			}

			gMarker = new google.maps.Marker({
        <?php if (!empty($this->context->markerOptions) && is_array($this->context->markerOptions)): ?>
        <?php foreach ($this->context->markerOptions as $markerOptionKey => $markerOption): ?>
        <?=$markerOptionKey?>: <?=$markerOption?>,
        <?php endforeach; ?>
        <?php endif; ?>
			map: map,
				draggable: true,
				title: place.name,
				position: place.geometry.location
		});
			window.map.setCenter(place.geometry.location);

			// get gountry from places object
			for(var i = 0; i < place.address_components.length; i += 1) {
				var addressObj = place.address_components[i];
				for(var j = 0; j < addressObj.types.length; j += 1) {
            <?php if (!empty($this->context->countryInput)):?>
					if (addressObj.types[j] === 'country') {
						document.getElementById('<?= $this->context->countryInput?>').value = addressObj.long_name;
					}
            <?php endif; ?>
				}
			}
			// put lat lng
			document.getElementById('<?= $this->context->latInput?>').value = gMarker.getPosition().lat();
			document.getElementById('<?= $this->context->lngInput?>').value = gMarker.getPosition().lng();

			google.maps.event.addListener(gMarker,'dragend',function(event){
				geocodeLocation(event);
			});

			//markers.push(marker);

			if (place.geometry.viewport) {
				// Only geocodes have viewport.
				bounds.union(place.geometry.viewport);
			} else {
				bounds.extend(place.geometry.location);
			}
		});
		map.fitBounds(bounds);
	});
	}


	function geocodeLocation(location){
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({
			"location": location.latLng
		}, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				// get gountry from places object
				<?php if ($this->context->addressElementsInput): ?>
                    let addressElements = {};
                    if (results[0]['address_components']) {
                        results[0]['address_components'].map(el => {
                            const types = [...el.types];
                            let element = {};
                            if (types.includes('street_number')) {
                                element = { streetNumber: el['long_name'] };
                            } else if (types.includes('street_address') || types.includes('route')) {
                                element = { street: el['long_name'] };
                            } else if (types.includes('locality') && types.includes('political')) {
                                element = { city: el['long_name'] };
                            } else if (types.includes('administrative_area_level_1') && types.includes('political')) {
                                element = { district: el['long_name'] };
                            } else if (types.includes('country') && types.includes('political')) {
                                element = { country: el['long_name'] };
                            } else if (types.includes('postal_code')) {
                                element = { postalCode: el['long_name'] };
                            }
                            addressElements = { ...addressElements, ...element };
                        });
                    }
                    document.getElementById('<?= $this->context->addressElementsInput?>').value = JSON.stringify(addressElements);
				<?php endif; ?>
          <?php if (!empty($this->context->addressTypeInput)):?>
				var index = results[0].types.indexOf('street_address');
				if (index > -1) {
					document.getElementById('<?= $this->context->addressTypeInput?>').value = results[0].types[index];
				} else {
					document.getElementById('<?= $this->context->addressTypeInput?>').value = results[0].types[0];
				}
          <?php endif; ?>
				for(var i = 0; i < results[0].address_components.length; i += 1) {
					var addressObj = results[0].address_components[i];
            <?php if (!empty($this->context->countryInput)):?>
					if (addressObj.types.indexOf('country') > -1) {
						document.getElementById('<?= $this->context->countryInput?>').value = addressObj.long_name;
					}
            <?php endif; ?>
				}
				document.getElementById('<?= $this->context->addressInput?>').value = results[0].formatted_address;
			}
		});

		document.getElementById('<?= $this->context->latInput?>').value = location.latLng.lat();
		document.getElementById('<?= $this->context->lngInput?>').value = location.latLng.lng();

	}

	function loadScript() {
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "https://maps.googleapis.com/maps/api/js?key=<?= $this->context->apiKey ?>&sensor=<?= $this->context->sensor ?>&libraries=places&callback=initialize&language=<?= $this->context->language ?>";
		document.body.appendChild(script);
	}
	window.onload = loadScript;
</script>
