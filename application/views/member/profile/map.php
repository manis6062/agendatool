<span>


<?php if(isset($check_flag)){
		?>
		
		<div id="map"></div>
	<script>
	loadMap();
	function loadMap(){
		var range = <?php echo $range;?>;
		var subjectPoint = {
					point: new google.maps.LatLng('<?php echo $latitude; ?>','<?php echo $longitude;?>'),
					radius: 1,
					color: '#00AA00',
				}
				
			<?php 
			$index = 0;
			foreach($search_results as $search){?>
				points_arr[<?php echo $index;?>] = {point : new  google.maps.LatLng('<?php echo $search['latitude']?>','<?php echo $search['longitude']?>')};
			<?php 
			$index++;
			}?>
			var zoom = 15;
			var map;
			var elevator;

			var mapOptions = {
				zoom: zoom,
				center: subjectPoint.point,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
			};

			map = new google.maps.Map($('#map')[0], mapOptions);
			//render the range
			var subjectMarker = new google.maps.Marker({
				map: map,
				position: subjectPoint.point,
				title: 'Subject',
			});
			var subjectRange = new google.maps.Circle({
				strokeColor: '#FF0000',
				strokeOpacity: 0,
				strokeWeight: 2,
				fillColor: '#FF0000',
				fillOpacity: 0,
				map: map,
				radius: subjectPoint.radius * range * 1000, // metres                
			});

			subjectRange.bindTo('center', subjectMarker, 'position');
			var bounds = subjectRange.getBounds();           

			//render the points
			for (var i = 0; i < points_arr.length; i++) {

				if (bounds.contains(points_arr[i].point)) {
					var point = points_arr[i];
					var marker = new google.maps.Marker({
						map: map,
						position: point.point,
						title: i.toString()
					});
				}
			}
	}

	</script>
	<?php }?>
	</span>