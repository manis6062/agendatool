<script>

	$(document).ready(function() {
		var edit_bit = 0;
		var flag_bit = 1;
		$(".login_to_icloud").click(function(e) {
			edit_bit = 0;
			$(".main_loader").show();
			$(".append_edit_btn").html("");
			getUserIcloudDetails();
		});
		$("#json-three").change(function () {
			ids = new Array();
			text = new Array();
			var $speciality_list = $('#speciality_list');
			if ($(".select2-search-choice").length >= 3) {
				alert("<?php echo $words['DTL_0336']?>");
			}
			else {
				$("#json-three option").each(function () {
					if ($(this).is(":checked")) {
						ids.push($(this).val());
						text.push($(this).text());
						$(this).remove();
					}
				});
				for (var i = 0; i < ids.length; i++) {
					$speciality_list.append('<li class="select2-search-choice"><div>' + text[i] + '</div><span class="select2-search-choice-close" tabindex="-1"></span><input type="hidden" name="speciality_ids[]" value="' + ids[i] + '"/></li>');
				}
				$(".select2-search-choice-close").unbind("click");
				$(".select2-search-choice-close").click(function () {
					$("#json-three").append("<option value=" + $(this).parent().find("input[type='hidden']").val() + ">" + $(this).parent().text() + "</option>");
					$(this).parent().remove();
				});
			}
		});
		function bindEditLogin() {
			$(".edit_icloud_login").on("click",function(){
				edit_bit = 1;
				flag_bit = 1;
				$(".calendars_lists").html("");
				$("#iCloud_login_modal input[name='apple_id']").removeAttr("readonly");
				$("#iCloud_login_modal input[name='password']").removeAttr("readonly");
			});
		}
		function getCalenders(result) {
			var append_list = "";
			if(result.calendars.length == 0) {
				append_list = "<div class='row'><div class='col-xs-12'>No Calendars</div></div>";
			}
			else {
				var default_calendar_id = result.default_calendar_id;
				append_list += "<select name='calendar' class='form-control'>"
				for(var i=0;i<result.calendars.length;i++) {
					var selected = "";
					if(default_calendar_id == result.calendars[i].calendar_id)
						selected = "selected";
					append_list += "<option "+selected+" value='"+result.calendars[i].href+"'>"+result.calendars[i].name+"</option>";
				}
				append_list+= "</select>";
				append_list = "<div class='row'><div class='col-xs-12'>Select Your Default Calendars</div></div>"+append_list
			}
			$(".calendars_lists").html(append_list);
		}
		function getUserIcloudDetails(){
			$("#iCloud_login_modal input[name='apple_id']").val('');
			$("#iCloud_login_modal input[name='password']").val('');
			$(".calendars_lists").html("");
			$.ajax({
				url: site_url+'member/icloud_controller/getUserIcloudDetails',
				dataType: "JSON",
				success: function(result) {
					$("#iCloud_login_modal").modal("show");
					if(result && result !== "null" && result!== "undefined") {
						flag_bit = 2;
						$("#iCloud_login_modal input[name='apple_id']").attr("readonly","readonly");
						$("#iCloud_login_modal input[name='password']").attr("readonly","readonly");
						$(".append_edit_btn").html("<input type=button class='btn btn-primary pull-right edit_icloud_login' value='Edit iCloud'/>");
						$("#iCloud_login_modal input[name='apple_id']").val(result.apple_id);
						$("#iCloud_login_modal input[name='password']").val(result.password);
						bindEditLogin();
						getCalenders(result);
					}
					$(".main_loader").hide();
				},
				error: function(jqXHR,textStatus,errorThrown){
					alert(errorThrown);
				}
			});
		}
		$("#icloud_login_form").submit(function(e) {
			e.preventDefault();
			$(".main_loader").show();
			url_data = $("#icloud_login_form").serialize() + '&' + $.param({edit_bit: edit_bit,flag_bit:flag_bit});
			$.ajax({
				url: site_url+'member/icloud_controller/save_iCloud_Credentials',
				data:url_data,
				dataType: "JSON",
				type:"POST",
				success: function(result) {
					$(".main_loader").hide();
					if(result.status == "error") {
						alert(result.message);
					}
					else {
						if(result.flag_bit == "1") {
							flag_bit = 2;
							edit_bit = 2;
							getCalenders(result);
						}
						else {
							location.reload();
						}
					}
				},
				error: function(jqXHR,textStatus,errorThrown){
					alert(errorThrown);
				}
			});
			return false;
		});
	});
</script>
<?php $tabindex = 1; ?>
<div class="content-header">
    <h2 class="content-header-title"><?php echo $words['DTL_0095']; ?></h2>
    <ol class="breadcrumb">
        <li><a href="<?php echo site_url('member/profile') ?>"><?php echo $words['DTL_0205']; ?></a></li>
        <li class="active"><?php echo $words['DTL_0091']; ?></li>
    </ol>
</div>

<div class="row">

<div class="col-xs-12">

<div class="row">


<form action="<?php echo site_url('member/profile/edit') ?>" method="post" enctype="multipart/form-data">
<div class="col-md-3 col-sm-5 col-xs-12">
<h3><?php echo $words['DTL_0052']; ?></h3>


<?php if ($userdata['linkedin_image_status'] == 0){ ?>
<div class="fileupload fileupload-new" data-provides="fileupload">
    <div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"><img
            src="<?php echo base_url() ?>uploads/userimage/<?php echo $userdata['photo_logo']; ?>"
            alt="Profile Avatar"/></div>
    <div class="fileupload-preview fileupload-exists thumbnail"
         style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>

    <?php
    }
    elseif ($userdata['linkedin_image_status'] == 1)
    {
    ?>
    <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="fileupload-new thumbnail" style="width: 180px; height: 180px;"><img
                src="<?php echo $userdata['linkedin_image'] ?>?>uploads/userimage/<?php echo $userdata['photo_logo']; ?>"
                alt="Profile Avatar"/></div>
        <div class="fileupload-preview fileupload-exists thumbnail"
             style="max-width: 200px; max-height: 200px; line-height: 20px;"></div>

        <?php } ?>
        <div>
                    <span class="btn btn-default btn-file"><span class="fileupload-new">Select image</span>
                        <span class="fileupload-exists">Change</span><input type="file" id="photo_logo"
                                                                            name="photo_logo"
                                                                            tabindex="<?php echo $tabindex;
                                                                            $tabindex++; ?>"/></span>
            <a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
        </div>


    </div>

    <input type="hidden" name="id_for_photo" id="id_for_photo" value="<?php echo $userdata['id'] ?>"/>
    <input type="hidden" name="old_photo" id="old_photo" value="<?php echo $userdata['photo_logo'] ?>"/>

    <br/>

    <?php if ($userdata['is_company'] == 0 && $userdata['user_type_id'] != 1) { ?>
        IFRAME <?php echo $words['DTL_0058']; ?> <?php echo $words['DTL_0287']; ?>
        <?php
        if (!empty($videos)) {
            foreach ($videos as $video) {
                ?>
                <textarea name="video_link" id="video_link" style="resize:none;overflow:auto"
                          tabindex="<?php echo $tabindex;
                          $tabindex++; ?>"><?php echo $video['video_link']; ?></textarea>
                <input type="hidden" name="video_id" id="video_id" value="<?php echo $video['id']; ?>">
                <input type="hidden" name="trainer_info_id_for_video" id="trainer_info_id_for_video"
                       value="<?php echo $trainerdata['id']; ?>">
            <?php
            }
        } else {
            ?>
            <textarea name="video_link" id="video_link" tabindex="<?php echo $tabindex;
            $tabindex++; ?>"></textarea>
            <input type="hidden" name="trainer_info_id_for_video" id="trainer_info_id_for_video"
                   value="<?php echo $trainerdata['id']; ?>">
        <?php
        }
    }?>
    <br/> <br/>

    <?php if ($this->session->userdata('is_admin') != 1) { ?>
        <div class="list-group delete-text">
            <a class="btn btn-danger"
               href="<?php echo site_url('member/profile/delete_user') ?>/<?php echo $userdata['id'] ?>"
               onclick="return confirm('Are you sure you want to delete account');"><?php echo $words['DTL_0086']; ?></a>
        </div> <!-- /.list-group -->
    <?php } ?>

</div>
<!-- /.col -->


<div class="col-md-9 col-sm-7 col-xs-12">

<h3 class="heading"><?php echo $words['DTL_0121']; ?>
    <?php
    if (($userdata['user_type_id'] != 1)) {

        if (!empty($left_days)) {
            ?>
            ( Free Trial : <?php echo $left_days . ' days remaining'; ?> )
        <?php }
    } ?></h3>


<ul class="icons-list form-horizontal">
    <li class="vald-errors">
    </li>
    <input class="form-control" type="hidden" id="general_info_id" name="general_info_id"
           value="<?php echo $userdata['id']; ?>">
    <?php if ($userdata['is_company'] == 0) { ?>
        <li>
            <div class="row form-group">
                <label class="col-md-3 col-xs-12"><?php echo $words['DTL_0155']; ?>:</label>
                <!--  <?php if(form_error('name')){?><span class="validation_error_msg"><?php echo form_error('name'); ?></span><?php } ?> -->
                <div class="col-md-6 col-xs-12"><input class="form-control" type="text" id="name" name="name"
                                             value="<?php echo $userdata['name']; ?>" tabindex="<?php echo $tabindex;
                    $tabindex++; ?>"></div>
                <?php if (form_error('name')) { ?>
                    <ul class="parsley-error-list validation_error_msg" style="display: block;">
                        <li class="required" style="display: list-item;"><?php echo form_error('name'); ?></li>
                    </ul>
                <?php } ?>
            </div>
        </li>
    <?php
    } else {
        ?>
        <li>
            <div class="row form-group">
                <label class="col-md-3 col-xs-12"> <?php echo $words['DTL_0155']; ?>:</label>

                <div class="col-md-6 col-xs-12">
                    <input type="text" name="user-name" value=" <?php echo $userdata['name']; ?>" class="form-control"
                           disabled="">
                </div>
            </div>
        </li>
        <input type="hidden" id="name" name="name" value="<?php echo $userdata['name']; ?>">
    <?php } ?>
    <li>
        <div class="row form-group">
            <label class="col-md-3 col-xs-12"> <?php echo $words['DTL_0007']; ?>:</label>
            <!--    <?php if(form_error('address')){?><span class="validation_error_msg"><?php echo form_error('address'); ?></span><?php } ?> -->
            <div class="col-md-6 col-xs-12"><input class="form-control" type="text" id="address" name="address"
                                         value="<?php echo $userdata['address']; ?>" tabindex="<?php echo $tabindex;
                $tabindex++; ?>">

                <?php if (form_error('address')) { ?>
                    <ul class="parsley-error-list validation_error_msg" style="display: block;">
                        <li class="required" style="display: list-item;"><?php echo form_error('address'); ?></li>
                    </ul>
                <?php } ?>

            </div>


        </div>
    </li>
    <li>
        <div class="row form-group">


            <label class="col-md-3 col-xs-12"><?php echo $words['DTL_0299']; ?>:</label>
            <!--    <?php if(form_error('zip_code')){?><span class="validation_error_msg"><?php echo form_error('zip_code'); ?></span><?php } ?> -->
            <div class="col-md-6 col-xs-12"><input class="form-control" type="text" id="zip_code" name="zip_code"
                                         value="<?php echo $userdata['zip_code']; ?>" tabindex="<?php echo $tabindex;
                $tabindex++; ?>">
                <?php if (form_error('zip_code')) { ?>
                    <ul class="parsley-error-list validation_error_msg" style="display: block;">
                        <li class="required" style="display: list-item;"><?php echo form_error('zip_code'); ?></li>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </li>
    <li>
        <div class="row form-group">
            <label class="col-md-3 col-xs-12"><?php echo $words['DTL_0186']; ?>:</label>
            <!--   <?php if(form_error('phone_no')){?><span class="validation_error_msg"><?php echo form_error('phone_no'); ?></span><?php } ?> -->
            <div class="col-md-6 col-xs-12"><input class="form-control" type="text" id="phone_no" name="phone_no"
                                         value="<?php echo $userdata['phone_no']; ?>" tabindex="<?php echo $tabindex;
                $tabindex++; ?>">
                <?php if (form_error('phone_no')) { ?>
                    <ul class="parsley-error-list validation_error_msg" style="display: block;">
                        <li class="required" style="display: list-item;"><?php echo form_error('phone_no'); ?></li>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </li>
    <li>
        <div class="row form-group">
            <label class="col-md-3 col-xs-12"><?php echo $words['DTL_0105']; ?>:</label>

            <div class="col-md-6 col-xs-12">
                <input type="text" value="<?php echo $userdata['email_address']; ?>" class="form-control" disabled="">
            </div>
        </div>
    </li>
    <input type="hidden" name="email_address" id="email_address" value=<?php echo $userdata['email_address'] ?>>
    <?php if ($this->session->userdata('is_admin') != 1) { ?>
        <li>
            <div class="row form-group">
                <label class="col-md-3 col-xs-12">IBAN <?php echo $words['DTL_0169']; ?>:</label>

                <div class="col-md-6 col-xs-12">
					<input class="form-control" type="text" id="iban_number" name="iban_number"
                                             value="<?php echo $userdata['iban_number']; ?>"
                                             tabindex="<?php echo $tabindex;
                                             $tabindex++; ?>">
					<?php if (form_error('iban_number')) { ?>
						<ul class="parsley-error-list validation_error_msg" style="display: block;">
							<li class="required" style="display: list-item;"><?php echo form_error('iban_number'); ?></li>
						</ul>
					<?php } ?>
				</div>
            </div>
        </li>
        <li>
            <div class="row form-group">
                <label class="col-md-3 col-xs-12">BIC <?php echo $words['DTL_0169']; ?>:</label>

                <div class="col-md-6 col-xs-12"><input class="form-control" type="text" id="bic_number" name="bic_number"
                                             value="<?php echo $userdata['bic_number']; ?>"
                                             tabindex="<?php echo $tabindex;
                                             $tabindex++; ?>">
				<?php if (form_error('bic_number')) { ?>
					<ul class="parsley-error-list validation_error_msg" style="display: block;">
						<li class="required" style="display: list-item;"><?php echo form_error('bic_number'); ?></li>
					</ul>
				<?php } ?>
				</div>
            </div>
        </li>
    <?php } ?>
</ul>


<!--   <hr /> -->
<!--  <br/> -->
<?php if ($userdata['is_company'] == 0 && $userdata['user_type_id'] != 1) { ?>
    <h3 class="heading"><?php echo $words["DTL_0270"]; ?></h3>





    <ul class="icons-list form-horizontal">
        <li>
            <div class="row form-group">
                <label class="col-md-3 col-xs-12"><?php echo $words['DTL_0102']; ?>:</label>
                <!--  <?php if(form_error('education')){?><span class="validation_error_msg"><?php echo form_error('education'); ?></span><?php } ?> -->
                <div class="col-md-6 col-xs-12"><input class="form-control" type="text" id="education" name="education"
                                             value="<?php echo $trainerdata['education']; ?>"
                                             tabindex="<?php echo $tabindex;
                                             $tabindex++; ?>">
                    <?php if (form_error('education')) { ?>
                        <ul class="parsley-error-list validation_error_msg" style="display: block;">
                            <li class="required" style="display: list-item;"><?php echo form_error('education'); ?></li>
                        </ul>
                    <?php } ?></div>
            </div>
        </li>
        <li>
            <div class="row form-group">
                <label class="col-md-3 col-xs-12"><?php echo $words['DTL_0074']; ?>:</label>
                <!--   <?php if(form_error('cost_per_hour')){?><span class="validation_error_msg"><?php echo form_error('cost_per_hour'); ?></span><?php } ?> -->
                <div class="col-md-6 col-xs-12"><input class="form-control" type="text" id="cost_per_hour" name="cost_per_hour"
                                             value="<?php echo $trainerdata['cost_per_hour']; ?>"
                                             tabindex="<?php echo $tabindex;
                                             $tabindex++; ?>">
                    <?php if (form_error('cost_per_hour')) { ?>
                        <ul class="parsley-error-list validation_error_msg" style="display: block;">
                            <li class="required"
                                style="display: list-item;"><?php echo form_error('cost_per_hour'); ?></li>
                        </ul>
                    <?php } ?></div>
            </div>
        </li>
        <li>
            <div class="row form-group">
                <label class="col-md-3 col-xs-12"><?php echo $words['DTL_0293']; ?>:</label>
                <!--  <?php if(form_error('work_experience')){?><span class="validation_error_msg"><?php echo form_error('work_experience'); ?></span><?php } ?> -->
				<div class="col-md-6 col-xs-12"><input class="form-control" type="text" id="work_experience"
                                             name="work_experience"
                                             value="<?php echo $trainerdata['work_experience']; ?>"
                                             tabindex="<?php echo $tabindex;
                                             $tabindex++; ?>">
                    <?php if (form_error('work_experience')) { ?>
                        <ul class="parsley-error-list validation_error_msg" style="display: block;">
                            <li class="required"
                                style="display: list-item;"><?php echo form_error('work_experience'); ?></li>
                        </ul>
                    <?php } ?></div>
            </div>
        </li>
        <input type="hidden" id="is_company" name="is_company" value="0">

        <li><label> <?php echo $words['DTL_0248']; ?>:</label><br/>

            <div class="select2-container select2-container-multi form-control" id="s2id_s2_tokenization">

                <ul class="select2-choices" id="speciality_list">
                    <!--   <?php if(form_error('speciality_ids')){?><span class="validation_error_msg"><?php echo form_error('speciality_ids'); ?></span><?php } ?> -->
                    <?php if (isset($specialities)) { ?>
                        <?php foreach ($specialities as $speciality) { ?>
                            <li class="select2-search-choice">
                                <div><?php echo $speciality['speciality_name'] ?></div>
                                <span class="select2-search-choice-close" tabindex="-1"></span><input type="hidden"
                                                                                                      name="speciality_ids[]"
                                                                                                      value="<?php echo $speciality['speciality_id'] ?>">
                            </li>
                        <?php } ?>
                    <?php
                    } else {
                        ?>
                    <?php } ?>
                </ul>

            </div>

            <?php if (form_error('speciality_ids')) { ?>
                <ul class="parsley-error-list validation_error_msg" style="display: block;">
                    <li class="required" style="display: list-item;"><?php echo form_error('speciality_ids'); ?></li>
                </ul>
            <?php } ?>
        </li>
    </ul>
    <div class="form-group select_speciality_field">
        <img src="<?php echo base_url() ?>assets/img/loader.gif" style="display: none;" width="25" height="25"
             id="notification_loader_speciality">

        <label><?php echo $words['DTL_0240']; ?></label>
        <br/>

        <div class="row form-group">
			<div class="col-md-3 col-xs-12">
                <p><label><?php echo $words['DTL_0046']; ?></label></p>

                <div class="form-group">
                    <select multiple="multiple" id="json-one" class="form-control" tabindex="<?php echo $tabindex;
                    $tabindex++; ?>">
                        <?php foreach ($main_specialities as $speciality) { ?>
                            <option
                                value="<?php echo $speciality['id'] ?>"><?php echo $speciality['category_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
			<div class="col-md-3 col-xs-12">
                <p><label><?php echo $words['DTL_0254']; ?></label></p>

                <div class="form-group">
                    <select multiple="multiple" id="json-two" class="form-control" tabindex="<?php echo $tabindex;
                    $tabindex++; ?>">
                    </select>
                </div>
            </div>

			<div class="col-md-3 col-xs-12">
                <p><label><?php echo $words['DTL_0249']; ?></label></p>

                <div class="form-group">
                    <select multiple="multiple" id="json-three" class="form-control" tabindex="<?php echo $tabindex;
                    $tabindex++; ?>">
                    </select>
                </div>
            </div>
        </div>
    </div>

<?php
} elseif ($userdata['is_company'] == 1 && $userdata['user_type_id'] != 1) {
    ?>


    <h3 class="heading"><?php echo $words["DTL_0065"]; ?></h3>
    <div class="row">
		<div class="col-md-9 col-xs-12">
            <ul class="icons-list form-horizontal form-group">

                <li><?php echo $words['DTL_0147']; ?>: <br/>

                    <div class="select2-container select2-container-multi form-control" id="s2id_s2_tokenization">

                        <ul class="select2-choices" id="speciality_list">
                            <!--  <?php if(form_error('speciality_ids')){?><span class="validation_error_msg"><?php echo form_error('speciality_ids'); ?></span><?php } ?> -->
                            <?php if (isset($specialities)) { ?>
                                <?php foreach ($specialities as $speciality) { ?>
                                    <li class="select2-search-choice">
                                        <div><?php echo $speciality['speciality_name'] ?></div>
                                        <span class="select2-search-choice-close" tabindex="-1"></span><input
                                            class="form-control" type="hidden" name="speciality_ids[]"
                                            value="<?php echo $speciality['speciality_id'] ?>"></li>
                                <?php } ?>
                            <?php
                            } else {
                                ?>
                            <?php } ?>
                        </ul>
                    </div>

                    <?php if (form_error('speciality_ids')) { ?>
                        <ul class="parsley-error-list validation_error_msg" style="display: block;">
                            <li class="required"
                                style="display: list-item;"><?php echo form_error('speciality_ids'); ?></li>
                        </ul>
                    <?php } ?>

                </li>

                <input class="form-control" type="hidden" id="is_company" name="is_company" value="1">
            </ul>
        </div>
    </div>
    <div class="form-group select_speciality_field">
        <img src="<?php echo base_url() ?>assets/img/loader.gif" style="display: none;" width="25" height="25"
             id="notification_loader_speciality">

        <?php echo $words['DTL_0240']; ?>
        <br/>

        <div class="row form-group">
			<div class="col-md-3 col-xs-12">
                <p><?php echo $words['DTL_0046']; ?></p>

                <div class="form-group">
                    <select multiple="multiple" id="json-one" class="form-control" tabindex="<?php echo $tabindex;
                    $tabindex++; ?>">
                        <?php foreach ($main_specialities as $speciality) { ?>
                            <option
                                value="<?php echo $speciality['id'] ?>"><?php echo $speciality['category_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
			<div class="col-md-3 col-xs-12">
                <p><?php echo $words['DTL_0254']; ?></p>

                <div class="form-group">
                    <select multiple="multiple" id="json-two" class="form-control" tabindex="<?php echo $tabindex;
                    $tabindex++; ?>">
                    </select>
                </div>
            </div>

			<div class="col-md-3 col-xs-12">
                <p><?php echo $words['DTL_0249']; ?></p>

                <div class="form-group">
                    <select multiple="multiple" id="json-three" class="form-control" tabindex="<?php echo $tabindex;
                    $tabindex++; ?>">
                    </select>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<?php
if ($userdata['is_company'] == 0 && $userdata['user_type_id'] != 1) {
    ?>
    <input type="hidden" id="trainer_info_id" name="trainer_info_id" value="<?php echo $trainerdata['id']; ?>">
    <!--   <br /> -->



    <hr/>
    <br/>
    <h3 class="heading"><?php echo $words['DTL_0025']; ?></h3>
    <br/>
    <li class="visibilty-li">
        <div class="row form-group">
			<label class="col-md-3 col-xs-12">
                <?php echo $words['DTL_0289']; ?>:
            </label>

			<div class="col-md-6 col-xs-12">
                <select name="visibility" class="form-control" id="visibility" tabindex="<?php echo $tabindex;
                $tabindex++; ?>">
                    <option value="1" <?php if ($trainerdata['visibility_id'] == 1) {
                        echo 'selected';
                    } ?>><?php echo $words['DTL_0109']; ?></option>
                    <option value="2" <?php if ($trainerdata['visibility_id'] == 2) {
                        echo 'selected';
                    } ?>><?php echo $words['DTL_0164']; ?></option>
                    <option value="3" <?php if ($trainerdata['visibility_id'] == 3) {
                        echo 'selected';
                    } ?>><?php echo $words['DTL_0242']; ?></option>
                </select>
            </div>

        </div>
    </li>
    <br>
    <?php if (!empty($favorites)) {
        foreach ($favorites as $favorite) {
            if (!empty($favorite['userdata'])) {
                ?>

                <?php echo $words['DTL_0242'];
                break;?>
            <?php
            }
        }
    } ?>
    <ul>
        <?php if (!empty($favorites)) {
            foreach ($favorites as $favorite) {
                if (!empty($favorite['userdata'])) {
                    ?>
                    <li><?php echo $favorite['userdata']['name']; ?></li>
                <?php
                }
            }
        } ?>
    </ul>
    <ul id="company_list">
    </ul>

    <ul id="companies_here">

    </ul>
	<?php 
	if(!$this->session->userdata('is_admin')) { //show connect agendas if the user tries to edit the profile not admin
	?>
		<li class="connect-li">
			<div class="row form-group">
				<label class="col-md-3 col-xs-12">
					<?php echo $words['DTL_0061']; ?>:
				</label>

				<div class="col-md-6 col-xs-12">
					<div class="connect-icon">
						<a href="<?php echo site_url('google/googleapi'); ?>">
							<img src="<?php echo base_url() ?>assets/img/gmail.png">
						</a> 
						<img src="<?php echo base_url() ?>assets/img/outlook.png" id="outlook_image">
						<img src="<?php echo base_url() ?>assets/img/icloud.ico" class="login_to_icloud"> 
						<img src="<?php echo base_url() ?>assets/img/loader.gif" style="display: none;" width="25" height="25" id="notification_loader_edit_outlook">
					</div>
				</div>
			</div>
		</li>
		<br/>
	<?php  }?>
    <hr/>
    <br/>

    <div class="form-group edit-blocks">
        <h3 class="heading"><?php echo $words['DTL_0251']; ?></h3>


        <?php
        ?>
        <div class="row form-group">
            <div class="col-xs-12">
                <p><?php echo $words['DTL_0083']; ?></p>
            </div>

            <form class="form" action="<?php echo site_url('admin/day/save_day')?>" method="post">

                <?php
                $day_array = array();
                foreach($available_date as $key => $d){
                    $day_array[$key] = $d['day'];

                }
                $days = $day_array;
                $availability = explode(',' ,$standard_availability['from_new_day'] );



                $availability_time = explode(',' ,$standard_availability['from_new_time'] );



                ?>

				<div class="col-xs-12 form-inline three-col">
                    <?php if(in_array('sunday' , $days)){ ?>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="from_day[]" value='0' <?php if(in_array(0 , $availability)) echo "checked"?>>Sunday
                    </label>
                </div>


               <?php } ?>

                    <?php if(in_array('monday' , $days)){ ?>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="from_day[]" value='1' <?php if(in_array(1 , $availability)) echo "checked"?>>Monday
                        </label>
                    </div>

                    <?php } ?>


                    <?php if(in_array('tuesday' , $days)){ ?>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="from_day[]" value='2'<?php if(in_array(2 , $availability)) echo "checked"?>>Tuesday
                        </label>
                    </div>

                    <?php } ?>

                    <?php if(in_array('wednesday' , $days)){ ?>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="from_day[]" value='3'<?php if(in_array(3 , $availability)) echo "checked"?>>Wednesday
                        </label>
                    </div>
                    <?php } ?>

                    <?php if(in_array('thursday' , $days)){ ?>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="from_day[]" value='4'<?php if(in_array(4 , $availability)) echo "checked"?>>Thursday </label>
                    </div>
                    <?php } ?>


                    <?php if(in_array('friday' , $days)){ ?>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="from_day[]" value='5'<?php if(in_array(5 , $availability)) echo "checked"?>>Friday
                        </label>
                    </div>
                    <?php } ?>

                    <?php if(in_array('saturday' , $days)){ ?>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="from_day[]" value='6'<?php if(in_array(6 , $availability)) echo "checked"?>>Saturday</label>
                    </div>

                    <?php } ?>
        </div>

        
            <div class="col-xs-12">
                <p><?php echo $words['DTL_0263']; ?></p>
            </div>
            <div class="col-xs-12 form-inline three-col">
                <?php
                for($i = 0; $i <= 23; $i++){
                    foreach($available_time as $value){
                        if($i == $value['time']){ ?>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="choosen_time[]" value='<?php echo $i;?>' <?php if(in_array($i , $availability_time)) echo "checked"?>><?php echo $i;?>:00 -   <?php
                                        if($i == 23) {
                                            echo "0:00";
                                        }
                                        else {
                                            echo ($i+1).":00";
                                        }?>
                                    </label>
                                </div>
                <?php  } } } ?>


            </div>
<?php } ?>
<!-- For Dynamic Fields -->
<?php if (isset($dynamic_fields)) {
    foreach ($dynamic_fields as $dynamic_field) {
        ?>
        <?php if ($dynamic_field['type_id'] == 1) { ?>
				<div class="col-md-9 col-xs-12 m-b-14">
						<!--  <?php if(form_error('input_text['.$dynamic_field['id'].']')){?><span class="validation_error_msg"><?php echo form_error('input_text['.$dynamic_field['id'].']'); ?></span><?php } ?> -->
						<label for="signup-<?php echo $dynamic_field['name']; ?>"
							   class="placeholder-hidden"><?php echo $dynamic_field['name']; ?></label>
						<input class="form-control" type="text" class="form-control"
							   name="input_text[<?php echo $dynamic_field['id'] ?>]"
							   placeholder="<?php echo $dynamic_field['name']; ?>" value="<?php
						if (set_value('input_text[' . $dynamic_field['id'] . ']'))
							echo set_value('input_text[' . $dynamic_field['id'] . ']');
						elseif (!empty($dynamic_field['answers'])) {
							foreach ($dynamic_field['answers'] as $answer) {
								echo $answer['value'];
							}
						}?>" tabindex="<?php echo $tabindex;
						$tabindex++; ?>">

						<?php if (form_error('input_text[' . $dynamic_field['id'] . ']')) { ?>
							<ul class="parsley-error-list validation_error_msg" style="display: block;">
								<li class="required"
									style="display: list-item;"><?php echo form_error('input_text[' . $dynamic_field['id'] . ']'); ?></li>
							</ul>
						<?php } ?>
					</div>
        <?php
        } elseif ($dynamic_field['type_id'] == 2) {
            ?>
            <?php if (!empty($dynamic_field['options'])) { ?>
				<div class="col-md-9 col-xs-12 m-b-14">
							<!--   <?php if(form_error('option['.$dynamic_field['id'].']')){?><span class="validation_error_msg"><?php echo form_error('option['.$dynamic_field['id'].']'); ?></span><?php } ?> -->
							<label><?php echo $dynamic_field['name']; ?></label>

							<div class="form-group">
								<?php foreach ($dynamic_field['options'] as $option) { ?>
									<label class="checkbox-inline">
										<input type="checkbox" tabindex="<?php echo $tabindex;
										$tabindex++; ?>" name="option[<?php echo $dynamic_field['id'] ?>][]"
											   value="<?php echo $option['id'] ?>"
											<?php
											if (set_checkbox('option[' . $dynamic_field['id'] . '][]', $option['id']))
												echo set_checkbox('option[' . $dynamic_field['id'] . '][]', $option['id']);
											elseif (!empty($dynamic_field['answers'])) {
												foreach ($dynamic_field['answers'] as $answer) {
													if ($answer['option_id'] == $option['id']) {
														echo "checked";
													}
												}
											}?>> <?php echo $option['value']; ?>
									</label>
								<?php } ?>
							</div>
							<?php if (form_error('option[' . $dynamic_field['id'] . ']')) { ?>
								<ul class="parsley-error-list validation_error_msg" style="display: block;">
									<li class="required"
										style="display: list-item;"><?php echo form_error('option[' . $dynamic_field['id'] . ']'); ?></li>
								</ul>
							<?php } ?>
				</div>
            <?php } ?>
        <?php
        } elseif ($dynamic_field['type_id'] == 3) {
            ?>
            <?php if (!empty($dynamic_field['options'])) { ?>
				<div class="col-md-9 col-xs-12 m-b-14">

                        <strong><?php echo $dynamic_field['name']; ?></strong>
                        <!--    <?php if(form_error('select_option['.$dynamic_field['id'].']')){?><span class="validation_error_msg"><?php echo form_error('select_option['.$dynamic_field['id'].']'); ?></span><?php } ?> -->
                        <select id="" class="form-control" name="select_option[<?php echo $dynamic_field['id'] ?>]"
                                tabindex="<?php echo $tabindex;
                                $tabindex++; ?>">
                            <option value=""><?php echo $words['DTL_0197']; ?></option>
                            <?php foreach ($dynamic_field['options'] as $option) { ?>
                                <option value="<?php echo $option['id'] ?>"
                                    <?php
                                    if (set_select('select_option[' . $dynamic_field['id'] . ']', $option['id']))
                                        echo set_select('select_option[' . $dynamic_field['id'] . ']', $option['id']);
                                    elseif (!empty($dynamic_field['answers'])) {
                                        foreach ($dynamic_field['answers'] as $answer) {
                                            if ($answer['option_id'] == $option['id']) {
                                                echo "selected";
                                            }
                                        }
                                    }?> ><?php echo $option['value']; ?></option>
                            <?php } ?>
                        </select>

                        <?php if (form_error('select_option[' . $dynamic_field['id'] . ']')) { ?>
                            <ul class="parsley-error-list validation_error_msg" style="display: block;">
                                <li class="required"
                                    style="display: list-item;"><?php echo form_error('select_option[' . $dynamic_field['id'] . ']'); ?></li>
                            </ul>
                        <?php } ?>

                    </div>
                    <!-- /.col -->
            <?php } ?>

        <?php
        }
        ?>
    <?php
    }
} ?>
<!-- Dynamic Fields End -->
<?php if ($this->session->userdata('is_admin') != 1) { ?>

    <hr/>
    <br/>
				<div class="col-xs-12">
    <h3 class="heading"><?php echo $words['DTL_0051']; ?></h3>


    <ul class="icons-list form-horizontal form-group">
        <li>
            <div class="row form-group">
                <label class="col-md-3"><?php echo $words['DTL_0172']; ?>:</label>
                <div class="col-md-6"><input class="form-control" type="password" id="current_password" name="current_password" tabindex="<?php echo $tabindex;
                    $tabindex++; ?>">
					<?php echo form_error('current_password'); ?>
				</div>
            </div>
        </li>
        <li>
            <div class="row form-group">
                <label class="col-md-3"><?php echo $words['DTL_0158']; ?>:</label>

                <div class="col-md-6"><input class="form-control" type="password" id="new_password" name="new_password"
                                             tabindex="<?php echo $tabindex;
                                             $tabindex++; ?>"><?php echo form_error('new_password'); ?></div>
            </div>
        </li>
        <li>
            <div class="row form-group">
                <label class="col-md-3 col-xs-12"><?php echo $words['DTL_0066']; ?>:</label>

                <div class="col-md-6 col-xs-12"><input class="form-control" type="password" id="confirm_password"
                                             name="confirm_password" tabindex="<?php echo $tabindex;
                    $tabindex++; ?>"><?php echo form_error('confirm_password'); ?></div>
			</div>
        </li>
        <input type="hidden" name="user_login_id_for_password" id="user_login_id_for_password"
               value="<?php echo $userdata['user_login_id'] ?>">

    </ul>
				</div>

<?php } ?>

<hr/>
<div class="form-group">
    <div class="col-md-9 col-xs-12">
        <button type="submit" class="btn btn-primary" tabindex="<?php echo $tabindex;
        $tabindex++; ?>">
            <?php echo $words['DTL_0224'];?><!-- &nbsp; <i class="fa fa-play-circle"></i> -->
        </button>
    </div>
</div>

</form>
</div>
<!-- /.col -->

</div>
<!-- /.row -->

</div> <!-- /.col -->


<div class="col-md-3 col-md-6 col-sidebar-right">


</div> <!-- /.col -->


</div> <!-- /.row -->


</div> <!-- /.content-container -->

</div> <!-- /.content -->

</div> <!-- /.container -->

<div id="basicModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title"><?php echo $words['DTL_0128']; ?></h3>
            </div>
            <div class="modal-body">
                <form method="post" action="<?php echo site_url('member/profile/invite_company') ?>"
                      class="invite-company">
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <?php echo $words['DTL_0235']; ?>:
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <input class="form-control" type="text" id="select_company" name="select_company">
                        </div>
                    </div>

                    <ul id="selected_company" name="selected_company"></ul>

                    <h5><?php echo $words['DTL_0129']; ?></h5><br/>

                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <?php echo $words['DTL_0156']; ?>:
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <input class="form-control" type="text" name="company_name" id="company_name"><br/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <?php echo $words['DTL_0105']; ?>:
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <input class="form-control" type="text" name="company_email" id="company_email"><br/>
                        </div>
                    </div>
                    <?php echo $words['DTL_0151']; ?>:<br/>

                    <div class="row ">
                        <div class="col-xs-12">
                            <textarea name="company_message" id="company_message" rows="4"
                                      class="form-control"></textarea>
                            <input class="form-control" type="hidden" id="user_email" name="user_email"
                                   value="<?php echo $userdata['email_address'] ?>">
                            <input class="form-control" type="hidden" id="username" name="username"
                                   value="<?php echo $userdata['name']; ?>">
                            <input class="form-control" type="hidden" id="user_id" name="user_id"
                                   value="<?php echo $userdata['id'] ?>">
                            <input class="form-control" type="hidden" name="trainer_info_id"
                                   value="<?php echo $trainerdata['id']; ?>">
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal"><?php echo $words['DTL_0056']; ?></button>
                        <button type="submit" name="submit" id="submit"
                                class="btn btn-primary"><?php echo $words['DTL_0244']; ?></button>
                    </div>

                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

	<div class="modal fade" id="iCloud_login_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">iCloud Login</h4>
			</div>
			<form action="" id="icloud_login_form">
				<input type='hidden' name='flag_bit' class='flag_bit' value='1' />
				<div class="modal-body">
					<div class="row"><div class="col-xs-12 append_edit_btn pull-right"></div></div>
					<div class="row">
						<div class="col-xs-12">
							<label>
								<span>Apple ID:</span>
								<input type="text" name="apple_id" class="form-control" placeholder="Enter Apple Id" />
							</label>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<label>
								<span>Password:</span>
								<input type="password" name="password" class="form-control" placeholder="Enter Password" />
							</label>
						</div>
					</div>
					<div class="calendars_lists"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->