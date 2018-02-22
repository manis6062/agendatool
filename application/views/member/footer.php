</div>
</div>
</div>
<footer class="footer">
</footer>





<script src="<?php echo base_url() ?>/assets/js/libs/bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>/assets/js/bootstrap-select.min.js"></script>
<script src="<?php echo base_url() ?>/assets/js/boostrap-select-custom.js"></script>

<!--[if lt IE 9]>
<script src="<?php echo base_url()?>/assets/js/libs/excanvas.compiled.js"></script>
<![endif]-->

<!-- Plugin JS -->
<script src="<?php echo base_url() ?>/assets/js/plugins/magnific/jquery.magnific-popup.min.js"></script>

<!-- App JS -->
<script src="<?php echo base_url() ?>/assets/js/target-admin.js"></script>

<!-- Plugin JS -->

<script src="<?php echo base_url() ?>/assets/js/plugins/select2/select2.js"></script>
<script src="<?php echo base_url() ?>/assets/js/demos/form-validation.js"></script>
<!--<script src="<?php /*echo base_url() */?>/assets/js/plugins/datepicker/bootstrap-datepicker.js"></script>-->
<script src="<?php echo base_url() ?>/assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url() ?>/assets/js/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="<?php echo base_url() ?>/assets/js/plugins/autosize/jquery.autosize.min.js"></script>
<script src="<?php echo base_url() ?>/assets/js/plugins/textarea-counter/jquery.textarea-counter.js"></script>
<script src="<?php echo base_url() ?>/assets/js/plugins/fileupload/bootstrap-fileupload.js"></script>
<script src="<?php echo base_url() ?>/assets/js/footer-btm.js"></script>

<!-- App JS -->
<script src="<?php echo base_url() ?>/assets/js/target-admin.js"></script>

<!-- Plugin JS -->

<?php if ($header == "Search" || $header == 'Admin Setting' || $header == 'Register Trainer' || $header == 'Register Company' || $header == 'Edit Profile' || $header == 'Agenda Notifications' || $header == 'Notification' || $header == 'Questions' || $header == 'Payment' || $header == 'View Agendas') { ?>
    <script src="<?php echo base_url() ?>/assets/js/demos/form-extended.js"></script>
    <script src="<?php echo base_url() ?>/assets/js/plugins/icheck/jquery.icheck.js"></script>
<?php } ?>

<script src="<?php echo base_url() ?>/assets/js/plugins/fullcalendar/fullcalendar.min.js"></script>
<script src="<?php echo base_url() ?>/assets/js/plugins/timepicker/bootstrap-timepicker.js"></script>

<?php if ($header != "Trainer Agenda") { ?>
    <!-- Non Trainer javascript -->
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/profile_page.js"></script>

<?php
} else {
    ?>
    <!-- Trainer javascript -->
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/trainer_agenda_page.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/footer.js"></script>


<!-- Flash Data -->
<?php
if (is_array($this->session->flashdata('flash_message'))) {
    $flash_data = $this->session->flashdata('flash_message');
    if ($flash_data["type"] == "success") {
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                $.bootstrapPurr('<?=$flash_data["message"]?>', {
                    type: 'success'
                });
            }, 1000);
        </script>
    <?php
    } elseif ($flash_data["type"] == "error") {
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                $.bootstrapPurr('<?=$flash_data["message"]?>', {
                    type: 'danger'
                });
            }, 1000);
        </script>
    <?php
    }
}
?>


<!-- End Flash Data -->

<script>
    function validate_agendaform() {
        var result;
        if ($('#appointmentName').val() == "" || $('#agenda_location').val() == "" || $('#agenda_longitude').val() == "") {
            if ($('#appointmentName').val() == "")
                alert('Appointment name ' + "<?php echo $words['DTL_0301'];?>");
            if ($('#agenda_location').val() == "")
                alert('Location ' + "<?php echo $words['DTL_0301'];?>");
            if ($('#agenda_longitude').val() == '')
                alert("<?php echo $words['DTL_0307']?>");
            result = false;
        }

        else
            result = true;
        return result;

    }

    function validate_contact_trainerform() {
        var result;
        if ($('#contact_trainer_message').val() == "") {
            alert('Message field' + "<?php echo $words['DTL_0301'];?>");
            result = false;
        }

        else
            result = true;
        return result;
    }

    function validate_contact_companyform() {
        var result;
        if ($('#contact_company_message').val() == "") {
            alert('Message field ' + "<?php echo $words['DTL_0301'];?>");
            result = false;
        }

        else
            result = true;
        return result;
    }

    function validate_add_subscription_form() {
        var result;
        if ($("#select_user").val() == "" || $('#add_subscription_amount').val() == "") {
            if ($('#select_user').val() == "") {
                alert('User ' + "<?php echo $words['DTL_0301'];?>");
                result = false;
            }
            if ($('#add_subscription_amount').val() == "") {
                alert('Amount ' + "<?php echo $words['DTL_0301'];?>");
                if (isNaN($('#add_subscription_amount').val()))
                    alert('Amount ' + "<?php echo $words['DTL_0303'];?>");
                result = false;
            }
        }
        else
            result = true;
        return result;
    }

    function validate_edit_trial_time_form() {
        var result;
        if ($('#edit_trial_period_time').val() == "") {
            alert('Time ' + "<?php echo $words['DTL_0301'];?>");
            if (isNaN($('#edit_trial_period_time').val()))
                alert('Time ' + "<?php echo $words['DTL_0303'];?>");
            result = false;
        }

        else
            result = true;
        return result;
    }

    function validate_edit_subscription_form() {
        var result;
        if ($('#edit_subscription_time').val() == "") {
            alert('Time ' + "<?php echo $words['DTL_0301'];?>");
            if (isNaN($('#edit_subscription_time').val()))
                alert('Time ' + "<?php echo $words['DTL_0303'];?>");
            result = false;
        }

        else
            result = true;
        return result;
    }

    function validate_outlook_mail_form() {
        var result;
        if ($('#outlook_mail_address').val() == "") {
            alert('Email field ' + "<?php echo $words['DTL_0301'];?>");
            result = false;
        }

        else
            result = true;
        return result;
    }

    function validate_update_email_form() {
        var result;
        if ($('#email_save').val() == "" || $('#subject_save').val() == "") {
            if ($('#subject_save').val() == "")
                alert('Subject field ' + "<?php echo $words['DTL_0301'];?>");
            if ($('#email_save').val() == "")
                alert('Email field ' + "<?php echo $words['DTL_0301'];?>");
            result = false;
        }

        else
            result = true;
        return result;
    }

</script>
<!--selectHours Modal -->
<!--    <div id="selectHours" class="modal fade" role="dialog">-->
<!--        <div class="modal-dialog">-->
<!--            <!-- Modal content-->
<!--            <div class="modal-content">-->
<!--                <div class="modal-header">-->
<!--                    <button type="button" class="close" data-dismiss="modal">&times;</button>-->
<!--                    <h4 class="modal-title"></h4>-->
<!--                </div>-->
<!--                <div class="modal-body" style="overflow: hidden;">-->
<!--                    <div class="hours toggled">-->
<!--                        <p>--><?php //echo $words['DTL_0236'];?><!--</p>-->
<!--                        <ul id="selectableHours">-->
<!--                        </ul>-->
<!--                        <button class="proceedBtn animated bounceIn" title="proceed" onclick="toggling()"><i class="fa fa-arrow-right"></i></button>-->
<!--                    </div>-->
<!--                    <div class="reserveAppointment toggled" id="reserve_form">-->
<!--                        <form action="--><?php //echo site_url('member/trainer_agenda/save_agenda')?><!--" id="reserveAppointment-form" class="form-horizontal text-left" method="post" enctype="multipart/form-data" onsubmit="return validate_agendaform();">-->
<!--                            <div class="form-group">-->
<!--                                <label for="#appointmentName" class="control-label col-md-6 col-sm-6">--><?php //echo $words['DTL_0040'];?><!--:</label>-->
<!--                                <input type="text" id="appointmentName" class="col-md-6 col-sm-6" name="appointmentName" value="--><?php //if(isset($edit_agenda_data)){echo $edit_agenda_data['appointment_name'];}else{echo "";}?><!--">-->
<!--                            </div>-->
<!--                            <div class="form-group">-->
<!--                                <label for="#location" class="control-label col-md-6 col-sm-6">--><?php //echo $words['DTL_0142'];?><!--:</label>-->
<!--                                <input type="text" id="agenda_location" class="col-md-6 col-sm-6" name="location" value="--><?php //if(isset($edit_agenda_data)){echo $edit_agenda_data['location'];}else{echo "";}?><!--">-->
<!--                                <input type="hidden" name="longitude" id="agenda_longitude" value="--><?php //if(isset($edit_agenda_data)){echo $edit_agenda_data['longitude'];}else{echo "";}?><!--">-->
<!--                                <input type="hidden" name="latitude" id="agenda_latitude" value="--><?php //if(isset($edit_agenda_data)){echo $edit_agenda_data['latitude'];}else{echo "";}?><!--">-->
<!--                            </div>-->
<!--                            <div class="form-group" id="selected_date_div">-->
<!--                                <label for="#date" class="control-label col-md-6 col-sm-6">--><?php //echo $words['DTL_0081'];?><!--/--><?php //echo $words['DTL_0263'];?><!--:</label>-->
<!--                                <input type="text" id="selected_date" class="col-md-6 col-sm-6" name="selected_date" readonly>-->
<!--                            </div>-->
<!--                            <div class="form-group">-->
<!--                                <label for="#description" class="control-label col-md-12 col-sm-12">--><?php //echo $words['DTL_0087'];?><!--:</label>-->
<!--                                <br>-->
<!--                                <textarea id="description" cols="30" rows="5" class="col-md-12 col-sm-12" name="description">--><?php //if(isset($edit_agenda_data)){echo $edit_agenda_data['appointment_description'];}else{echo "";}?><!--</textarea>-->
<!--                            </div>-->
<!--                            <div class="form-group">-->
<!--                                <label for="#file_upload" class="control-label col-md-12 col-sm-12">--><?php //echo $words['DTL_0088'];?><!--</label>-->
<!--                                <label class="control-label upload-label">-->
<!--                                    <input type="file" class="upload" id="upload" name="file_upload"><i class="fa fa-upload"></i> --><?php //echo $words['DTL_0278'];?>
<!--                                </label>-->
<!--                            </div>-->
<!--							<div class="form-group">-->
<!--								<label class="control-label"></label>-->
<!--									<input id="filename" type="text" class="col-md-6 col-sm-6" value="--><?php //if(isset($edit_agenda_data)){echo $edit_agenda_data['document']['document_name'];}else{echo "";}?><!--" readonly>-->
<!--								-->
<!--							</div>-->
<!--							--><?php //if(isset($trainerdata)){ ?>
<!--                            <input type="hidden" id="trainer_id" name="trainer_id" value="--><?php //echo $trainerdata['id']?><!--">-->
<!--							--><?php //} ?>
<!--							<input type="hidden" id="same_user_flag" name="same_user_flag" value="--><?php //echo $same_user;?><!--" >-->
<!--							<input type="hidden" id="agenda_id" name="agenda_id" value="--><?php //if(isset($edit_agenda_data)){echo $edit_agenda_data['id'];}else{echo "";}?><!--" >-->
<!--							<input type="hidden" id="appointed_by" name="appointed_by" value="--><?php //if(isset($edit_agenda_data)){echo $edit_agenda_data['appointed_by'];}else{echo "";}?><!--" >-->
<!--                            <div class="form-group">-->
<!--                                <span class="reserveBtn pull-left" onclick="toggling()"><i class="fa fa-arrow-left"></i> --><?php //echo $words['DTL_0100'];?><!--</span>-->
<!--                                <input type='hidden' name='date' id='datetime' value="">-->
<!--                                <input type='hidden' name='selectedTime' id='selectedTime' value=''>-->
<!--                                <button type="submit" class="reserveBtn pull-right" id="submit_btn">--><?php //echo $words['DTL_0219'];?><!--</button>-->
<!--                            </div>-->
<!--                        </form>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

<!-- notification modal one -->
<div id="notificationModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $words['DTL_0039']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label class="col-sm-4"><?php echo $words['DTL_0040']; ?>:</label>

                    <div class="col-sm-8"><p class="appointment_name"></p></div>
                </div>
                <div class="row">
                    <label class="col-sm-4"><?php echo $words['DTL_0063']; ?>:</label>

                    <div class="col-sm-8"><p class="company_name"></p></div>
                </div>
                <div class="row">
                    <label class="col-sm-4"><?php echo $words['DTL_0142']; ?>:</label>

                    <div class="col-sm-8"><p class="location"></p></div>
                </div>
                <div class="row">
                    <label class="col-sm-4"><?php echo $words['DTL_0082']; ?>:</label>

                    <div class="col-sm-8"><p class="date_time"></p></div>
                </div>
                <div class="row">
                    <label class="col-sm-4"><?php echo $words['DTL_0087']; ?>:</label>

                    <div class="col-sm-8"><p class="description"></p>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-4"><?php echo $words['DTL_0088']; ?>:</label>

                    <div class="col-sm-8"><a href="#" class="document"></a></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<form method="post" action="<?php echo site_url('member/profile/send_contact_mail_page') ?>"
      onsubmit="return validate_contact_trainerform();">
    <div id="contact_trainer_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $words['DTL_0071']; ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12"><textarea rows="4" class="form-control" id="contact_trainer_message"
                                                         name="message"></textarea>

                        </div>
                    </div>
                    <input type="hidden" name="send_to" id="send_to_trainer"
                           value="<?php echo $userdata['email_address']; ?>">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $userdata['id']; ?>">
                    <!-- <div class="row">
			    	<div class="col-sm-8">
			    		<button id="contact_trainer_btn" class="contact_trainer btn btn-primary"><?php echo $words['DTL_0244'];?>!</button>
			   		 </div>
			    </div> -->
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="contact_trainer_btn"
                            class="contact_trainer btn btn-primary"><?php echo $words['DTL_0244']; ?></button>
                </div>
            </div>
        </div>
    </div>
</form>


<form method="post" action="<?php echo site_url('member/profile/send_contact_mail_page') ?>"
      onsubmit="return validate_contact_companyform();">
    <div id="contact_company_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $words['DTL_0068']; ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12"><textarea class="form-control" rows="4" id="contact_company_message"
                                                         name="message"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="send_to" id="send_to_company"
                           value="<?php echo $userdata['email_address']; ?>">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $userdata['id']; ?>">

                    <!--     <div class="modal-footer">
		       <button id="contact_company_btn" class="contact_company_btn btn btn-primary"><?php echo $words['DTL_0244'];?>!</button>
		      </div> -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="contact_company_btn"
                                class="contact_company_btn btn btn-primary"><?php echo $words['DTL_0244']; ?></button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>

<div id="contact_trainer_search_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $words['DTL_0071']; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12"><textarea rows="4" id="message_contact" name="message"
                                                     class="form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="send_to" id="contact_to" value="">
                <input type="hidden" name="user_id" id="user_id_for_contact_to" value="">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="contact_company"
                        class="contact_company btn btn-primary"><?php echo $words['DTL_0244']; ?></button>
            </div>
        </div>
    </div>
</div>


<!-- Add Subscription Modal -->
<form method="post" action="<?php echo site_url('admin/payment/add_subscription') ?>" id="add_subscription_form"
      onsubmit="return validate_add_subscription_form();">
    <div id="add_subscription_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">


            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $words['DTL_0022']; ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="#user"
                                       class="control-label col-md-6 col-sm-6"><?php echo $words['DTL_0279']; ?></label>

                                <p id="username_subs"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="#amount"
                                       class="control-label col-md-6 col-sm-6"><?php echo $words['DTL_0036']; ?></label>
                                <input type="text" id="add_subscription_amount" class="col-md-6 col-sm-6" name="amount">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="general_id" id="general_id_for_subs"/>
                    <input type="hidden" name="user_type_id" id="user_type_id_for_subs"/>

                    <div class="row">
                        <button id="add_subscription_submit"><?php echo $words['DTL_0022']; ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


<!-- Edit Trial Time Modal -->
<form method="post" action="<?php echo site_url('admin/payment/edit_trial_time') ?>" id="trial_time_form"
      onsubmit="return validate_edit_trial_time_form();">
    <div id="trial_period_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $words['DTL_0101']; ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="#trial_time"
                                       class="control-label col-md-6 col-sm-6"><?php echo $words['DTL_0171']; ?></label>
                                <input type="text" id="edit_trial_period_time" class="col-md-6 col-sm-6"
                                       name="trial_period_time">
                            </div>
                        </div>
                    </div>
                    <!-- <input type="hidden" id="edit_id" name="id">
			    <div class="row">
			    	<div class="col-sm-8">
			    		<button id="trial_time" class="trial_time btn btn-primary"><?php echo $words['DTL_0090'];?></button>
			    	</div>
			    </div> -->
                </div>

                <div class="modal-footer">
                    <input type="hidden" id="edit_id" name="id">

                    <button id="trial_time"
                            class="trial_time btn btn-primary"><?php echo $words['DTL_0223']; ?></button>

                </div>


            </div>
        </div>
    </div>
</form>

<form method="post" action="<?php echo site_url('admin/payment/edit_subscription_time') ?>" id="subscription_time_form"
      onsubmit="return validate_edit_subscription_form();">
    <div id="subscription_time_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal_title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="#subscription_time"
                                       class="control-label col-md-6 col-sm-6"><?php echo $words['DTL_0171']; ?></label>
                                <input type="text" id="edit_subscription_time" class="col-md-6 col-sm-6"
                                       name="trial_period_time">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="hidden" id="edit_sub_id" name="id">

                    <button id="trial_time"
                            class="trial_time btn btn-primary"><?php echo $words['DTL_0223']; ?></button>

                </div>


            </div>
        </div>
    </div>
</form>

<!-- Contact Modal End -->

<!-- Email Modal -->
<form method="post" action="<?php echo site_url('admin/email/update_email') ?>"
      onsubmit="return validate_update_email_form();">
    <div id="email_save_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $words['DTL_0093']; ?></h4>
                </div>
                <div class="modal-body">
                    <?php echo $words['DTL_0258']; ?>:
                    <div class="row">
                        <div class="col-sm-8"><input type="text" id="subject_save" name="subject" value="">
                        </div>
                    </div>
                    <br/>
                    <?php echo $words['DTL_0151']; ?>:
                    <div class="row">
                        <div class="col-sm-12"><textarea class="form-control" rows="4" id="email_save" name="email"
                                                         value=""></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="email_id" id="email_id" value="">

                    <button id="email_save_btn"
                            class="email_save_btn btn btn-primary"><?php echo $words['DTL_0223']; ?></button>

                </div>
            </div>
        </div>
    </div>
</form>
<!-- Email Modal End -->


<!-- Outlook Mail -->
<form method="post" action="<?php echo site_url('member/profile/save_outlook_mail') ?>" id="outlook_mail_form"
      onsubmit="return validate_outlook_mail_form();">
    <div id="outlook_email_modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $words['DTL_0018']; ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="#outlook_email_address"
                                       class="control-label col-md-6 col-sm-6"><?php echo $words['DTL_0105']; ?></label>
                                <input type="text" id="outlook_mail_address" class="col-md-6 col-sm-6"
                                       name="outlook_email">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <button id="submit_btn"
                                    class="submit_btn btn btn-secondary"><?php echo $words['DTL_0259']; ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Outlook Mail End -->

<!-- Change Api Modal Start -->

<div id="api_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="api_modal_title"><?php echo $words['DTL_0047']; ?>
                    API <?php echo $words['DTL_0130']; ?></h4>
            </div>
            <div class="modal-body">


                <div class="row form-group">
                    <div class="col-xs-12 col-sm-3">
                        <label for="#api_name" class="control-label"
                               id="api_name_label">API <?php echo $words['DTL_0155']; ?>:</label>
                    </div>
                    <div class="col-xs-12 col-sm-8">
                        <input id="api_name_id" type="text" value="" class="form-control" disabled="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <label for="#api_key" class="control-label">Api <?php echo $words['DTL_0130']; ?></label>
                    </div>
                    <div class="col-xs-12 col-sm-8">
                        <input type="text" id="api_key" class="form-control" name="api_key">
                    </div>
                </div>


                <!-- <div class="row">
					<div class="col-sm-8">
					<div class="form-group">
                                <label for="#api_name" class="control-label col-md-6 col-sm-6" id="api_name_label">API <?php echo $words['DTL_0155'];?>:</label>
                                <input id="api_name_id" type="text" value="" class="form-control col-md-6 col-sm-6" disabled="">
                              
                            </div>
			    	</div>
			    	<div class="col-sm-8">
					<div class="form-group">
                                <label for="#api_key" class="control-label col-md-6 col-sm-6">Api <?php echo $words['DTL_0130'];?></label>
                                <input type="text" id="api_key" class="col-md-6 col-sm-6" name="api_key">
                            </div>
			    	</div>
			    </div> -->
                <input type="hidden" id="api_id" name="id" value="">
                <!--  <div class="row">
			    	<div class="col-sm-8">
			    		<button id="api_submit_btn" class="submit_btn btn btn-secondary"><?php echo $words['DTL_0047'];?></button>
			    	</div>
			    </div> -->
            </div>

            <div class="modal-footer">
                <button id="api_submit_btn"
                        class="submit_btn btn btn-primary"><?php echo $words['DTL_0047']; ?></button>
            </div>
        </div>
    </div>
</div>
<div class="main_loader" style="display:none;">
	<div class="main_loader_wrapper">
		<div class="sk-circle">
		  <div class="sk-circle1 sk-child"></div>
		  <div class="sk-circle2 sk-child"></div>
		  <div class="sk-circle3 sk-child"></div>
		  <div class="sk-circle4 sk-child"></div>
		  <div class="sk-circle5 sk-child"></div>
		  <div class="sk-circle6 sk-child"></div>
		  <div class="sk-circle7 sk-child"></div>
		  <div class="sk-circle8 sk-child"></div>
		  <div class="sk-circle9 sk-child"></div>
		  <div class="sk-circle10 sk-child"></div>
		  <div class="sk-circle11 sk-child"></div>
		  <div class="sk-circle12 sk-child"></div>
		</div>
	</div>
</div>
<!-- Change Api Modal End -->

</body>
</html>