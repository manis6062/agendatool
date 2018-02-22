<!--   <h4 class="heading"><?php echo $words['DTL_0228'];?></h4> -->

<div class="content-header">
    <h2 class="content-header-title"><?php echo $words['DTL_0228']; ?></h2>

    <ol class="breadcrumb">
        <li><a href="<?php echo site_url('member/search') ?>"><?php echo $words['DTL_0233']; ?></a></li>
        <li class="active"><?php echo $words['DTL_0228']; ?></li>
    </ol>

</div>



<?php if ($this->session->userdata('search_name') != null || $this->session->userdata('search_speciality_names') != null || $this->session->userdata('availability') != null || $this->session->userdata('search_time') != null){ ?>
<div class="row form-group search-result">
<div class="col-sm-12">
<h4 class="heading"><?php echo $words['DTL_0229']; ?>:</h4>


<!-- <label class="col-sm-3"> Address:</label>

<div class="col-sm-9"><input class="form-control" type="text" id="address" name="address" value="Ason" tabindex="2" placeholder="Enter a location" autocomplete="off">

</div> -->




<?php if ($this->session->userdata('search_name') != null) { ?>
    <div class="row">
        <label class="col-sm-2"><?php echo $words['DTL_0155']; ?>: </label>

        <div class="col-sm-10"><?php echo $this->session->userdata('search_name'); ?> </div>
    </div>
<?php } ?>
<br/>

<?php if ($this->session->userdata('search_speciality_names') != null) { ?>
    <div class="row">
        <label class="col-sm-2"><?php echo $words['DTL_0248']; ?>:</label>

        <div class="col-sm-10">


            <?php
            $count = 1;
            foreach ($this->session->userdata('search_speciality_names') as $speciality_name) {
                ?>


                <div class="block">
                    <?php
                    echo $count . ") ";
                    print_r($speciality_name['category_name']);
                    ?>
                </div>
                <?php
                $count++;
                ?>



            <?php } ?>

        </div>
    </div>

    <br/>

<?php
}

?>


<?php if ($this->session->userdata('availability') != null) { ?>

    <div class="row">
        <label class="col-sm-2"><?php echo $words['DTL_0081']; ?>: </label>

        <div class="col-sm-10">
            <?php echo $this->session->userdata('availability'); ?>
        </div>
    </div>
<?php } ?>
<?php if ($this->session->userdata('search_time') != null) { ?>
    <br/>

    <div class="row">
        <label class="col-sm-2"><?php echo $words['DTL_0263']; ?>: </label>

        <div class="col-sm-10">
            <?php echo $this->session->userdata('search_time'); ?>
        </div>
    </div>
<?php } ?>
<?php } ?>

<!-- 
	   </div> -->
<div class="row">
    <div class="col-sm-12">
        <div class="pull-right clearfix">

            <img src="<?php echo base_url() ?>assets/img/loader.gif" style="display: none;" width="25" height="25"
                 id="search_loader">

        </div>
    </div>
</div>

<!--<ul id="myTab1" class="nav nav-tabs">
            <li class="active">
              <a href="#list" data-toggle="tab"><?php /*echo $words['DTL_0141'];*/?></a>
            </li>
            <li>
              <a href="#mapTab" data-toggle="tab" onclick="myMap();"><?php /*echo $words['DTL_0149'];*/?></a>
            </li>
          </ul>-->

<?php if (isset($check_flag)) {
    //print_r('if check_flag');
    ?>
    <div id="checkMap" style="display:none"></div>
    <script>
        var general_id_arrays = checkMap();
        function checkMap() {
            <?php if($range!=null){?>
            var range = <?php echo $range;?>;
            <?php }?>
            var subjectPoint = {
                point: new google.maps.LatLng('<?php echo $latitude; ?>', '<?php echo $longitude;?>'),
                radius: 1,
                color: '#00AA00',
            }

            <?php
            $index = 0;
            if(!empty($search_results)){
            foreach($search_results as $search){?>
            points_arr[<?php echo $index;?>] = {point: new google.maps.LatLng('<?php echo $search['latitude']?>', '<?php echo $search['longitude']?>'),
                id: '<?php echo $search['general_info_id']?>'};
            <?php
            $index++;
            }}
            else{?>
            points_arr = null;
            <?php }?>
            var zoom = 15;
            var map;
            var elevator;
            var mapOptions = {
                zoom: zoom,
                center: subjectPoint.point,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            };


            map = new google.maps.Map($('#checkMap')[0], mapOptions);
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
                fillColor: '#AED4FF',
                fillOpacity: 0.4,
                map: map,
                radius: subjectPoint.radius <?php if($range!=null){?> * range<?php } ?> * 1000, // metres
            });
            subjectRange.bindTo('center', subjectMarker, 'position');
            var bounds = subjectRange.getBounds();

            var general_id_array = new Array();
            //render the points
            if (points_arr != null) {
                for (var i = 0; i < points_arr.length; i++) {

                    if (bounds.contains(points_arr[i].point)) {
                        general_id_array[i] = points_arr[i].id;
                    }
                }
            }
            ajax_search_results(general_id_array.toString(),1,0);
			return general_id_array;
			
		}
		function sort_search_func() {
			alert(general_id_arrays);
			sort_search = $(".sort_search").val();
			ajax_search_results(general_id_arrays.toString(),sort_search, 0) 
		}
		function ajax_search_results(general_id_array,sort_by, offset) {
			$.ajax({
				url: site_url + 'member/search/ajax_search_results',
				type: "POST",
				dataType: "HTML",
				data: {"id_array": general_id_array, 'offset': offset,'sort_by':sort_by},
				success: function (result) {
					$("#grid_table").html(result);
					bind_event_to_contact();
					bind_event_to_favorite();

				}
			});
		}

    </script>
<?php
}
else
{
?>
    <script>
        var general_id_arrays = checkMap();
        function checkMap() {
            var subjectPoint = {
                point: new google.maps.LatLng('<?php echo $latitude; ?>', '<?php echo $longitude;?>'),
                radius: 1,
                color: '#00AA00',
            }

            <?php
            $index = 0;
            if(!empty($search_results)){
            foreach($search_results as $search){?>
            points_arr[<?php echo $index;?>] = {point: new google.maps.LatLng('<?php echo $search['latitude']?>', '<?php echo $search['longitude']?>'),
                id: '<?php echo $search['general_info_id']?>'};
            <?php
            $index++;
            }
            }else{?>
            points_arr = null;
            <?php }?>
            var general_id_array = new Array();

            //render the points
            if (points_arr != null) {
                for (var i = 0; i < points_arr.length; i++) {
                    general_id_array[i] = points_arr[i].id;
                }
            }
            ajax_search_results(general_id_array.toString(),1, 0);
			return general_id_array;
        }
		function sort_search_func() {
			sort_search = $(".sort_search").val();
			ajax_search_results(general_id_arrays.toString(),sort_search, 0) 
		}
        function ajax_search_results(general_id_array,sort_search, offset) {
			$(".main_loader").show();
            $.ajax({
                url: site_url + 'member/search/ajax_search_results',
                type: "POST",
                dataType: "HTML",
                data: {"id_array": general_id_array, "offset": offset,"sort_search":sort_search},
                success: function (result) {
					$(".main_loader").hide();
                    $("#grid_table").html(result);
                    bind_event_to_contact();
                    bind_event_to_favorite();
                }
            });
        }
    </script>
<?php } ?>
<div id="myTab1Content" class="tab-content">
<div class="row">
<div class="col-md-6 col-xs-12">
    <div class="user_search_list" id="list">
        <!-- tab for grid view -->
		<div class="row">
			<div class="col-xs-12">
				<div class="search_filter text-right clearfix">
					<span class="search_filter_title">SORT IN: </span>
					<select class="sort_search" onchange="sort_search_func()">
						<option value="1">Location</option>
						<option value="2">Price</option>
						<option value="3">Name</option>
					</select>
				</div>
			</div>
			
		</div>
        <div id="grid_table" class="mini_search_result">
            <!-- place to append the table -->
			
        </div>
        <!-- /.table-responsive -->

        <!-- tab for grid view end -->
    </div>
</div>
<div class="col-md-6 col-xs-12">
<div class="" id="mapTab">
<!--tab for map view -->
	  <span>
		
		<div id="map"></div>
	<script>

    <?php if(isset($check_flag)){?>
    function loadMap() {
        <?php if($range!=null){?>
        var range = <?php echo $range;?>;
        <?php }?>
        var subjectPoint = {
            point: new google.maps.LatLng('<?php echo $latitude; ?>', '<?php echo $longitude;?>'),
            radius: 1,
            color: '#00AA00',
        }

        <?php
        $index = 0;
        if(!empty($search_results)){
        foreach($search_results as $search){?>
        points_arr[<?php echo $index;?>] = {point: new google.maps.LatLng('<?php echo $search['latitude']?>', '<?php echo $search['longitude']?>'), id: '<?php echo $search['general_info_id']?>'};
        <?php
        $index++;
        }
        }
        else{?>
        points_arr = null;
        <?php }?>
        var zoom = 15;
        var map;
        var elevator;

        var mapOptions = {
            zoom: zoom,
            center: subjectPoint.point,
            color: "#00AA00",
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        };

        map = new google.maps.Map($('#map')[0], mapOptions);
        //render the range
        var subjectMarker = new google.maps.Marker({
            map: map,
            icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
            position: subjectPoint.point,
            title: 'Subject',
        });
        var subjectRange = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0,
            strokeWeight: 2,
            fillColor: '#AED4FF',
            fillOpacity: 0.4,
            map: map,
            radius: subjectPoint.radius <?php if($range!=null){?> * range <?php }?> * 1000, // metres
        });

        subjectRange.bindTo('center', subjectMarker, 'position');
        var bounds = subjectRange.getBounds();

        var infowindow = new google.maps.InfoWindow();

        //render the points
        if (points_arr != null) {
            for (var i = 0; i < points_arr.length; i++) {

                var point = points_arr[i];
                var marker = new google.maps.Marker({
                    map: map,
                    position: point.point,
                    title: i.toString()
                });

                activateMarkerAsBtn(marker, points_arr[i].id);
                //activateMarkerAsBtn(marker, i);
            }
        }

        function activateMarkerAsBtn(mrk, id) {
            mrk.addListener('click', function () {
                $.ajax({
                    url: site_url + 'member/search/ajax_search_results_map',
                    type: "POST",
                    dataType: "JSON",
                    data: {"id": id},
                    success: function (result) {
                        geocodePosition(mrk, mrk.getPosition(), result);
                    }
                });

            });
        }

        var geocoder = new google.maps.Geocoder();

        function geocodePosition(markers, pos, result) {
            geocoder.geocode({ latLng: pos }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    //console.log(result);
                    if (result.specialities.length > 0) {
                        var specialities = "<br/>";
                        $.each(result.specialities, function (index, element) {
                            specialities += element.category_name + "<br/>";
                        });
                        var strs = '<img src ="<?php echo base_url();?>uploads/userimage/' + result.photo_logo + '" style="height:50px;width:50px"><br/><strong>Name: ' + result.name + '</strong><br/><strong>Specialities:</strong>' + specialities;
                    }
                    else
                        var strs = '<img src ="<?php echo base_url();?>uploads/userimage/' + result.photo_logo + '" style="height:50px;width:50px"><br/><strong>Name: ' + result.name + '</strong>';

                    infowindow.setContent(strs);
                    infowindow.open(markers.get('map'), markers);
                }
            });
        }

    }
    <?php }
    else{?>
    function loadMap() {
        var range = 100000;
        var subjectPoint = {
            point: new google.maps.LatLng('<?php echo $latitude; ?>', '<?php echo $longitude;?>'),
            radius: 1,
            color: '#00AA00',
        }

        <?php
        $index = 0;
        if(!empty($search_results)){
        foreach($search_results as $search){?>
        points_arr[<?php echo $index;?>] = {point: new google.maps.LatLng('<?php echo $search['latitude']?>', '<?php echo $search['longitude']?>'),
            id: '<?php echo $search['general_info_id']?>'};
        <?php
        $index++;
        }}
        else{?>
        points_arr = null;
        <?php }?>
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
            icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
            position: subjectPoint.point,
            title: 'Subject',
        });
        var subjectRange = new google.maps.Circle({
            strokeColor: '#FF0000',
            strokeOpacity: 0,
            strokeWeight: 2,
            fillColor: '#AED4FF',
            fillOpacity: 0,
            map: map,
            radius: subjectPoint.radius * range * 1000, // metres
        });

        subjectRange.bindTo('center', subjectMarker, 'position');
        var bounds = subjectRange.getBounds();

        var infowindow = new google.maps.InfoWindow();

        //render the points
        if (points_arr != null) {
            for (var i = 0; i < points_arr.length; i++) {

                var point = points_arr[i];
                var marker = new google.maps.Marker({
                    map: map,
                    position: point.point,
                    title: i.toString()
                });

                activateMarkerAsBtn(marker, points_arr[i].id);
                //activateMarkerAsBtn(marker, i);
            }
        }

        function activateMarkerAsBtn(mrk, id) {
            mrk.addListener('click', function () {
                $.ajax({
                    url: site_url + 'member/search/ajax_search_results_map',
                    type: "POST",
                    dataType: "JSON",
                    data: {"id": id},
                    success: function (result) {
                        geocodePosition(mrk, mrk.getPosition(), result);
                    }
                });

            });
        }

        var geocoder = new google.maps.Geocoder();

        function geocodePosition(markers, pos, result) {
            geocoder.geocode({ latLng: pos }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    //console.log(result);
                    if (result.specialities.length > 0) {
                        var specialities = "<br/>";
                        $.each(result.specialities, function (index, element) {
                            specialities += element.category_name + "<br/>";
                        });
                        var strs = '<img src ="<?php echo base_url();?>uploads/userimage/' + result.photo_logo + '" style="height:50px;width:50px"><br/><strong>Name: ' + result.name + '</strong><br/><strong>Specialities:</strong>' + specialities;
                    }
                    else
                        var strs = '<img src ="<?php echo base_url();?>uploads/userimage/' + result.photo_logo + '" style="height:50px;width:50px"><br/><strong>Name: ' + result.name + '</strong>';

                    infowindow.setContent(strs);
                    infowindow.open(markers.get('map'), markers);
                }
            });
        }
    }
    <?php }?>

    function bind_event_to_contact() {
        $('.contact_trainer_search_button').on('click', function (e) {
			e.preventDefault();
            var clicked_obj = $(this);
            var obj = $(this);
            var general_id = $(this).attr('result');
            var email_address = $(this).attr('result_mail');
            $('#contact_to').val(email_address);
            $('#user_id_for_contact_to').val(general_id);
            $('#message_contact').val('');
            $('#contact_trainer_search_modal').modal('show');
        });
    }
	
    function bind_event_to_favorite() {
        $('.add_to_favorite').on('click', function (e) {
			e.preventDefault();
            var clicked_obj = $(this);
            var obj = $(this);
            var general_id = $(this).attr('result');
            $.ajax({
                url: site_url + 'member/profile/add_to_favorite',
                type: "POST",
                dataType: "JSON",
                data: {"general_id": general_id},
                beforeSend: function () {
                    clicked_obj.parent().parent().find('.notification_loader_favorite').show('slow');
                },
                complete: function () {
                    clicked_obj.parent().parent().find('.notification_loader_favorite').hide('slow');
                },
                success: function (result) {
                    if (result.status == "1") {
                        obj.html('<i class="fa fa-star"></i>Favorite');
                    }
                    else {
                        obj.html('<i class="fa fa-star-o"></i>Add to favorite');
                    }
                }
            });
        });
    }


    function myMap() {

        setTimeout(function () {
            loadMap();
        }, 1000);

    }

    $(document).ready(function () {
        loadMap();
    });
    </script>
	</span>
<!--tab for map view end -->
</div>
</div>
</div>
<!-- /.row -->
</div>
</div>
<!-- /.content-container -->

</div> <!-- /.content -->

</div> <!-- /.container -->