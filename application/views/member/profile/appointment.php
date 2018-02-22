
<div class="" id="">
    <form action="<?php echo site_url('member/trainer_agenda/new_save_agenda') ?>" id="reserveAppointment"
          class="form-horizontal text-left" method="post" enctype="multipart/form-data"
          onsubmit="return validate_agendaform();">
        <?php
               if(!empty($json_data)){

            $index = 0;

            foreach ($json_data as $value) {

                if (isset($value['start_date'])) {
                    $start_date = $value['start_date'];
                    $start_time = $value['start_time'];

                } else {
                    $json_start_date = $value['start'];
                    $d_start = new DateTime($json_start_date);
                    $start_date = $d_start->format('Y-m-d');
                    $start_time = $d_start->format('H:i:s');
                }

                if (isset($value['end_date'])) {
                    $end_date = $value['end_date'];
                    $end_time = $value['end_time'];

                } else {
                    $json_end_date = $value['end'];
                    $d_end = new DateTime($json_end_date);
                    $end_date = $d_end->format('Y-m-d');
                    $end_time = $d_end->format('H:i:s');

                }


                if (isset($value['appointment_description'])) {
                    $value['description'] = $value['appointment_description'];
                }

                ?>


        <div class="apppointement_wrapper">
                <div class="form-group">
                    <label for="#appointmentName" class="control-label col-md-2 col-sm-6 col-xs-12">
                        <?php echo $words['DTL_0040']; ?>:
                    </label>
                    <div class="col-md-10 col-sm-6 col-xs-12">
                        <input type="text" id="" class="form-control" name="appointmentName[]"
                               value="<?php if (isset($value['title'])) {
                                   echo $value['title'];
                               }
                               else {
                                   echo "";
                               } ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="#location" class="control-label col-md-2 col-sm-6 col-xs-12">
                        <?php echo $words['DTL_0142']; ?>:
                    </label>
                    <div class="col-md-10 col-sm-6 col-xs-12">
                        <input type="text" id="agenda_location_<?php echo $index ?>" class="form-control"
                               name="location[]"
                               value="<?php if (isset($value['location'])) {
                                   echo $value['location'];
                               } else {
                                   echo "";
                               } ?>">
                    </div>
                    <input type="hidden" name="longitude[]]" id="agenda_longitude_<?php echo $index ?>"
                           value="<?php if (isset($value['longitude'])) {
                               echo $value['longitude'];
                           } else {
                               echo "";
                           } ?>">
                    <input type="hidden" name="latitude[]" id="agenda_latitude_<?php echo $index ?>"
                           value="<?php if (isset($value['latitude'])) {
                               echo $value['latitude'];
                           } else {
                               echo "";
                           } ?>">
                </div>
                <div class="form-group" id="selected_date_div">
                    <label for="#date" class="control-label col-md-2 col-sm-6 col-xs-12"><?php echo $words['DTL_0081']; ?>
                        /<?php echo $words['DTL_0263']; ?>:</label>

                    <div class="col-md-10 col-sm-6 col-xs-12">
                        <?php
                        if (isset($value['start_date'])) { ?>
                        <input type="text" id="selected_date" class="form-control" name="selected_date[]" readonly
                               value="<?php echo $start_date . ' ' . $start_time . ' To ' . $end_date . ' ' . $end_time; ?>">
                        <?php } else { ?>
                        <input type="text" id="selected_date" class="form-control" name="selected_date[]" readonly
                                   value="<?php echo $start_date . ' ' . $start_time . ' To ' . $end_date . ' ' . $end_time; ?>">
                        <?php  } ?>
                    </div>

                    <input type="hidden" name="selected_date_date[]" id=""
                           value="<?php echo $start_date ;?>">


                </div>
                <div class="form-group">
                    <label for="#description" class="control-label col-md-2 col-sm-6 col-xs-12"><?php echo $words['DTL_0087']; ?>
                        :</label>
                    <div class="col-md-10 col-sm-6 col-xs-12">
                        <textarea id="description" cols="30" rows="5" class="form-control" name="description[]" placeholder="<?php if (isset($value['description'])) {
                            echo $value['description'];
                        } else {
                            echo "";
                        } ?>"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="#file_upload" class="control-label col-md-2 col-sm-6 col-xs-12"><?php echo $words['DTL_0088']; ?></label>
                    <div class="control-label col-md-10 col-sm-6 col-xs-12">
                        <input type="file" class="upload" id="upload_<?php echo $index; ?>" name="file_upload[]">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-6 col-xs-12"><i class="fa fa-upload"></i> <?php echo $words['DTL_0278']; ?></label>
                    <div class="control-label col-md-10 col-sm-6 col-xs-12">
                        <input id="filename_<?php echo $index; ?>" type="text" class="form-control" value="<?php if (isset($value['document'])) {
                                   echo $value['document'];
                               } else {
                                   echo "";
                               } ?>" readonly>
                    </div>

                </div>

            <?php

            if (isset($trainer_data)) {
            ?>
            <input type="hidden" id="trainer_id" name="trainer_id" value="<?php echo $trainer_data['id']; ?>">
            <?php }

            else{ ?>
                <input type="hidden" id="trainer_id" name="trainer_id" value="<?php echo $this->uri->segment(4); ?>">

      <?php   } ?>
                <input type="hidden" id="same_user_flag" name="same_user_flag" value="<?php echo $same_user; ?>">





            <input type="hidden" id="agenda_id" name="agenda_id[]"
                       value="<?php if (isset($value['appointment_id'])) {
                           echo $value['appointment_id'];
                       } else {
                           echo "";
                       } ?>">
                <input type="hidden" id="appointed_by" name="appointed_by[]"
                       value="<?php if (isset($value['appointed_by'])) {
                           echo $value['appointed_by'];
                       } else {
                           echo "";
                       } ?>">
                <div class="form-group">
                    <input type='hidden' name='date[]' id='datetime' value="<?php echo $start_date; ?>">
                    <input type='hidden' name='selectedTime[]' id='selectedTime'
                           value="<?php echo $start_time . ',' . $end_time; ?>">
                </div>

                <script>
                    $("#upload_<?php echo $index; ?>").change(function () {
                        var fname = $("#upload_<?php echo $index; ?>").val().split('\\').pop().split('/').pop();
                        $('#filename_<?php echo $index; ?>').val(fname);
                    });
                </script>






        </div>
                <?php $index++;
            }
        } ?>
    </form>
</div>
<span class="reserveBtn pull-left" onclick="goBack()" style="cursor: pointer">
    <i class="fa fa-arrow-left"></i> <?php echo $words['DTL_0100']; ?></span>
<button type="submit" class="btn btn-primary reserveBtn pull-right" id="submit_btn"><?php echo $words['DTL_0219']; ?></button>

<script>
    function initialize_appointment() {
        <?php
        $index = 0;
        foreach($json_data as $value){?>
        var input_<?php echo $index?> = document.getElementById('agenda_location_<?php echo $index?>');
        var autocomplete_<?php echo $index?> = new google.maps.places.Autocomplete(input_<?php echo $index?>);
        google.maps.event.addListener(autocomplete_<?php echo $index?>, 'place_changed', function () {
            var place = autocomplete_<?php echo $index?>.getPlace();
            document.getElementById('agenda_latitude_<?php echo $index?>').value = place.geometry.location.lat();
            document.getElementById('agenda_longitude_<?php echo $index?>').value = place.geometry.location.lng();
        });
        <?php
            $index++;
                                     }?>
    }
    google.maps.event.addDomListener(window, 'load', initialize_appointment);
    $('#submit_btn').click(function () {
        $('#reserveAppointment').submit();
    });


    function goBack(){
        window.location.href = "<?php echo base_url('member/trainer_agenda/get_trainer_agenda' . '/' .  $this->session->userdata('general_id' )); ?>";

    }


    $(function(){
        $("#block").click(
            function(){
                $('#reserveAppointment').submit();
            }
        )
    });
</script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       