<div style="width: <?= $this->context->width?>;
    height: <?= $this->context->height?>">
    <div id="<?= $this->context->mapCanvas?>" style="width:100%; height:100%"></div>
</div>
<script>
    var map;
    var bounds;
    function initialize() {
        var geocoder = new google.maps.Geocoder();
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
        <?php if ($this->context->markerFitBounds): ?>
        window.bounds = new google.maps.LatLngBounds();
        <?php elseif (is_array($this->context->center)): ?>
        window.map.setCenter(new google.maps.LatLng(<?= $this->context->center[0] ?>, <?= $this->context->center[1] ?>));
        <?php else: ?>
        geocoder.geocode({
            "address": "<?= $this->context->center ?>"
        }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                window.map.setCenter(results[0].geometry.location);
            }
        });
        <?php endif; ?>

<<<<<<< HEAD
        <?php if (!empty($this->context->circles) && is_array($this->context->circles)): ?>
            <?php foreach ($this->context->circles as $key => $circle): ?>
                var circle_<?= $key ?> = new google.maps.Circle({
                    strokeColor: '<?= array_key_exists('style', $circle) && array_key_exists('strokeColor', $circle['style']) ? $circle['style']['strokeColor'] : '#0000FF' ?>',
                    strokeOpacity: <?= array_key_exists('style', $circle) && array_key_exists('strokeOpacity', $circle['style']) ? $circle['style']['strokeOpacity'] : 0.8 ?>,
                    strokeWeight: <?= array_key_exists('style', $circle) && array_key_exists('strokeWeight', $circle['style']) ? $circle['style']['strokeWeight'] : 2 ?>,
                    fillColor: '<?= array_key_exists('style', $circle) && array_key_exists('fillColor', $circle['style']) ? $circle['style']['fillColor'] : '#0000FF' ?>',
                    fillOpacity: <?= array_key_exists('style', $circle) && array_key_exists('fillOpacity', $circle['style']) ? $circle['style']['fillOpacity'] : 0.35 ?>,
                    map: map,
                    center: {lat: <?= $circle['center'][0] ?>, lng: <?= $circle['center'][1] ?>},
                    radius: <?= $circle['radius'] * (array_key_exists('kilometers', $circle) && $circle['kilometers'] ? 1000 : 1) ?>
                });
            <?php endforeach; ?>
        <?php endif; ?>

=======
>>>>>>> 09e8fdcdc0892dde9d676799a8068a4c9c6c581e
        <?php if (!empty($this->context->markers) && is_array($this->context->markers)): ?>
        <?php foreach ($this->context->markers as $key => $marker): ?>
        var marker_<?= $key ?> = new google.maps.Marker({
            <?php if (!empty($marker['options']) && is_array($marker['options'])): ?>
            <?php foreach ($marker['options'] as $optionKey => $option): ?>
            <?=$optionKey?>: <?=$option?>,
            <?php endforeach; ?>
            <?php endif; ?>
            map: window.map
        });
        <?php if (isset($marker['title'])): ?>
        marker_<?= $key ?>.setTitle("<?= $marker['title'] ?>");
        <?php endif; ?>
        <?php if (isset($marker['content'])): ?>
        var infowindow_<?= $key ?> = new google.maps.InfoWindow({
            content: '<?= $marker['content'] ?>'
          });
        marker_<?= $key ?>.addListener('click', function() {
          infowindow_<?= $key ?>.open(window.map, marker_<?= $key ?>);
        });
        <?php endif; ?>
        <?php if (is_array($marker['position'])): ?>
        marker_<?= $key ?>.setPosition(new google.maps.LatLng(<?= $marker['position'][0] ?>, <?= $marker['position'][1] ?>));
        <?php if ($this->context->markerFitBounds): ?>
        window.bounds.extend(marker_<?= $key ?>.position);
        window.map.fitBounds(bounds);
        <?php endif; ?>
        <?php else: ?>
        geocoder.geocode({
            "address": "<?= $marker['position'] ?>"
        }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                marker_<?= $key ?>.setPosition(results[0].geometry.location);
                <?php if ($this->context->markerFitBounds): ?>
                window.bounds.extend(results[0].geometry.location);
                window.map.fitBounds(bounds);
                <?php endif; ?>
            }
        });
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>


    }
    function loadScript() {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "https://maps.googleapis.com/maps/api/js?key=<?= $this->context->apiKey ?>&sensor=<?= $this->context->sensor ?>&callback=initialize&language=<?= $this->context->language ?><?= $this->context->region !== false ? ('&region='.$this->context->region) : '' ?>";
        document.body.appendChild(script);
    }
    window.onload = loadScript;
</script>
