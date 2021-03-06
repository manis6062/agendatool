	<div class="content-header">
		<h2 class="content-header-title"><?php echo $words['DTL_0283']; ?></h2>
	</div>
	<script>
		$(document).ready(function() {
			var edit_bit = 0;
			var flag_bit = 1;
			$(".login_to_icloud").click(function(e) {
				edit_bit = 0;
				$(".append_edit_btn").html("");
				getUserIcloudDetails();
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
					append_list = "<div class='row'><div class='col-md-12'>No Calendars</div></div>";
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
					append_list = "<div class='row'><div class='col-md-12'>Select Your Default Calendars</div></div>"+append_list
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
						$("#iCloud_login_modal input[name='apple_id']").val(result.apple_id);
						$("#iCloud_login_modal input[name='password']").val(result.password);
						if(result != "null") {
							flag_bit = 2;
							$("#iCloud_login_modal input[name='apple_id']").attr("readonly","readonly");
							$("#iCloud_login_modal input[name='password']").attr("readonly","readonly");
							$(".append_edit_btn").html("<input type=button class='btn btn-primary pull-right edit_icloud_login' value='Edit iCloud'/>");
							bindEditLogin();
						}
						getCalenders(result);
					},
					error: function(jqXHR,textStatus,errorThrown){
						alert(errorThrown);
					}
				});
			}
			$("#icloud_login_form").submit(function(e) {
				e.preventDefault();
				alert(flag_bit);
				url_data = $("#icloud_login_form").serialize() + '&' + $.param({edit_bit: edit_bit,flag_bit:flag_bit});
				$.ajax({
					url: site_url+'member/icloud_controller/save_iCloud_Credentials',
					data:url_data,
					dataType: "JSON",
					type:"POST",
					success: function(result) {
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
	<div class="row">

	<div class="col-md-8 col-xs-12">

	<div class="row">

	<div class="col-md-4 col-sm-5 col-xs-12">

		<div class="thumbnail profile-pic">
			<?php if ($userdata['photo_logo'] == null) {
				?>
				<img src="<?php echo base_url() ?>assets/img/default.jpg" alt="Profile Picture">
			<?php
			} elseif ($userdata['linkedin_image_status'] == 0) {
				?>
				<img src="<?php echo base_url() ?>uploads/userimage/<?php echo $userdata['photo_logo']; ?>"
					 alt="Profile Picture">
			<?php
			} elseif ($userdata['linkedin_image_status'] == 1) {
				?>
				<img src="<?php echo $userdata['linkedin_image']; ?>" alt="Profile Picture">
			<?php } ?>
		</div>
		<!-- /.thumbnail -->

		<br/>
		<?php if ($same_user == 0) { ?>

			<div class="profile-list list-group">

				<a href="javascript:;" class="list-group-item" id="add_to_favorite">
					<?php if ($is_favorite != null) { ?>
						<i class="fa fa-star"></i> &nbsp;&nbsp;<?php echo $words['DTL_0114']; ?> <i
							class="fa fa-chevron-right list-group-chevron"></i>
					<?php
					} else {
						?>
						<i class="fa fa-star-o"></i> &nbsp;&nbsp;<?php echo $words['DTL_0023']; ?><i
							class="fa fa-chevron-right list-group-chevron"></i>
					<?php } ?>
					<img src="<?php echo base_url() ?>assets/img/loader.gif" style="display: none;" width="25"
						 height="25" id="notification_loader_favorite">
				</a>
				<?php if ($userdata['is_company'] == 0 && $this->session->userdata('is_admin') != 1) { ?>

					<a href="javascript:;" class="list-group-item" id="contact_trainer_button">
						<i class="fa fa-comments-o"></i> &nbsp;&nbsp;<?php echo $words['DTL_0067']; ?> <i
							class="fa fa-chevron-right list-group-chevron"></i>
					</a>


					<?php
					if (!empty($videos))
						foreach ($videos as $video) {
							?>

							<div class="video_wrapper"><?php echo $video['video_link'] ?></div>
						<?php
						}
				} elseif ($this->session->userdata('is_admin') != 1) {
					?>
					<a href="javascript:;" class="list-group-item" id="contact_company_button">
						<i class="fa fa-comments-o"></i> &nbsp;&nbsp;<?php echo $words['DTL_0068']; ?><i
							class="fa fa-chevron-right list-group-chevron"></i>
					</a>
				<?php } ?>
			</div> <!-- /.list-group -->
		<?php
		} else {
			if ($userdata['is_company'] == 0 && $userdata['user_type_id'] != 1) {
				?>
				<div class="profile-list list-group">

					<a href="<?php echo site_url('member/favorite'); ?>" class="list-group-item">
						<i class="fa fa-bookmark"></i>&nbsp;&nbsp;
						<?php echo $words['DTL_0115']; ?><i class="fa fa-chevron-right list-group-chevron"></i>
					</a>
					<?php if ($this->session->userdata('is_admin') != 1) { ?>
						<a href="<?php echo site_url('member/profile/inbox'); ?>" class="list-group-item">
							<i class="fa fa-comments-o"></i>&nbsp;&nbsp;
							<?php echo $words['DTL_0152']; ?><i class="fa fa-chevron-right list-group-chevron"></i>
						</a>
                   <?php } ?>
				</div>
				<?php
				if ($videos == null) {
					?>
					<div class="profile-list list-group">
						<span class="list-group-item" style="color: red;">
							<?php echo $words['DTL_0166']; ?> !
						</span>
					</div>

				<?php
				} else {
					foreach ($videos as $video) {

						?>

						<!--<button id="delete_video" class="delete_video" video_id="<?php echo $video['id']?>">Delete this video</button>-->
						<div class="video_wrapper">
							<?php echo $video['video_link'] ?>
						</div>
					<?php
					}
				}
			} elseif ($this->session->userdata('is_admin') != 1) {
				?>
				<div class="profile-list list-group">

					<a href="<?php echo site_url('member/favorite'); ?>" class="list-group-item">
						<i class="fa fa-bookmark"></i>&nbsp;&nbsp;
						<?php echo $words['DTL_0115']; ?><i class="fa fa-chevron-right list-group-chevron"></i>
					</a>
					<a href="<?php echo site_url('member/profile/inbox'); ?>" class="list-group-item">
						<i class="fa fa-comments-o"></i>&nbsp;&nbsp;
						<?php echo $words['DTL_0152']; ?><i class="fa fa-chevron-right list-group-chevron"></i>
					</a>
				</div>
			<?php } ?>


		<?php
		}
		?>

		<img src="<?php echo base_url() ?>assets/img/loader.gif" style="display: none;" width="25" height="25"
			 id="notification_loader_video_delete">
	</div>
	<!-- /.col -->


	<div class="col-md-8 col-sm-7 col-xs-12 profile-title ">


		<h2><?php echo $userdata['name']; ?></h2>
		<?php if ($same_user == 1 || $this->session->userdata('is_admin') == 1) { ?>
			<a href="<?php echo site_url('member/profile/edit_profile') . "/" . $userdata['id']; ?>"
			   class="pull-right btn btn-primary"><?php echo $words['DTL_0090']; ?></a>
		<?php } ?>


		<hr/>


		<ul class="icons-list">

			<li><i class="icon-li fa fa-map-marker"></i> <?php echo $userdata['address']; ?></li>
			<li><i class="icon-li fa fa-map-marker"></i> <?php echo $userdata['zip_code']; ?></li>
			<li><i class="icon-li fa fa-phone"></i> <?php echo $userdata['phone_no']; ?></li>
			<li><i class="icon-li fa fa-envelope"></i> <?php echo $userdata['email_address']; ?></li>
		</ul>


		<hr/>
		<br/>
		<?php if ($userdata['is_company'] == 0 && $userdata['user_type_id'] != 1) { ?>
			<h3><?php echo $words['DTL_0270']; ?></h3>

			<hr/>



			<ul class="icons-list">
				<li><label> <?php echo $words['DTL_0102']; ?>: </label> <?php echo $trainerdata['education']; ?></li>
				<li><label> <?php echo $words['DTL_0074']; ?>: </label> <?php echo $trainerdata['cost_per_hour']; ?>
				</li>
				<?php if (isset($specialities)) { ?>
					<li><label> <?php echo $words['DTL_0248']; ?>: </label>
						<ul>
							<?php
							foreach ($specialities as $speciality) {
								echo "<li>" . $speciality . "</li>";
							}
							?>
						</ul>
					</li>
				<?php } ?>
			</ul>
		<?php
		}
		elseif ($userdata['is_company'] == 1 && $userdata['user_type_id'] != 1) {?>
			<h4><u><?php echo $words['DTL_0065']; ?></u></h4>

			<hr/>
			<ul class="icons-list">
				<?php if (isset($specialities)) { ?>
					<li><?php echo $words['DTL_0147']; ?>:
						<ul>
							<?php

							foreach ($specialities as $speciality) {
								echo "<li>" . $speciality . "</li>";
							}

							?>
						</ul>
					</li>
				<?php } ?>
			</ul>
		<?php
		}
		?>


	</div>
	<!-- /.col -->

	</div>
	<!-- /.row -->

	</div>
	<!-- /.col -->


	<div class="col-sm-12 col-md-4 col-xs-12 col-sidebar-right">

	<?php if ($userdata['is_company'] == 0 && $userdata['user_type_id'] != 1) { ?>
		<div class="">

			<div class="portlet widget profile-calendar">



<!--                <a class=" btn btn-success" href="--><?php //echo base_url('member/trainer_agenda/get_trainer_agenda/' . $this->uri->segment(4));?><!--">Check Appointments On Calendar</a>-->
				<div class="agd-widget">
					<span class="year prev-year"></span>
					<span class="year next-year"></span>

					<div id="widget-calender"></div>
					<!-- /#full-calendar -->
				</div>


			</div>
			<!-- /.portlet -->

		</div> <!-- /.col-md-8 -->
		<ul class="calender_note">
			<li><span class="color_spot available"></span><?php echo $words['DTL_0042']; ?></li>
			<li><span class="color_spot notConfirmed"></span><?php echo $words['DTL_0167']; ?></li>
			<li><span class="color_spot unavailable"></span><?php echo $words['DTL_0277']; ?></li>
		</ul>
	<?php } ?>

	</div>
	<!-- /.col -->


	</div> <!-- /.row -->


	</div> <!-- /.content-container -->

	</div> <!-- /.content -->

	</div> <!-- /.container -->


	<div id="contact_modal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title"><?php echo $words['DTL_0128']; ?></h3>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo site_url('member/profile/invite_company') ?>">
					<?php echo $words['DTL_0235']; ?>: <input type="text" id="select_company" name="select_company">
					<img src="<?php echo base_url() ?>assets/img/loader.gif" style="display: none;" width="25"
						 height="25" id="notification_loader_select_company">
					<ul id="selected_company" name="selected_company"></ul>
					<h5><?php echo $words['DTL_0129']; ?></h5><br/>
					<?php echo $words['DTL_0156']; ?>: <input type="text" name="company_name" id="company_name"><br/>
					<?php echo $words['DTL_0105']; ?>: <input type="text" name="company_email" id="company_email"><br/>
					<?php echo $words['DTL_0151']; ?>:<br/>
					<textarea name="company_message" id="company_message"></textarea>
					<input type="hidden" id="user_email" name="user_email"
						   value="<?php echo $userdata['email_address'] ?>">
					<input type="hidden" id="username" name="username" value="<?php echo $userdata['name']; ?>">
					<input type="hidden" id="user_id" name="user_id" value="<?php echo $userdata['id'] ?>">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"
						data-dismiss="modal"><?php echo $words['DTL_0056']; ?></button>
				<button type="submit" name="submit" id="submit"
						class="btn btn-primary"><?php echo $words['DTL_0244']; ?>!
				</button>
			</div>

			</form>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
	</div><!-- /.modal -->