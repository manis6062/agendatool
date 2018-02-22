<style>
.red{
	background:red;
}
.green {
	background:green;
}
</style>
<div class="content-header">
	<h2 class="content-header-title"><?php echo $words['DTL_0366'];?></h2>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('member/profile') ?>"><?php echo $words['DTL_0205'];?></a></li>
			<li class="active"><?php echo $words['DTL_0366'];?></li>
        </ol>
</div>
<div class="row">
	<div class="col-xs-12">
	<!--Loop starts here-->
	<?php 
	if(!empty($agendas)) {
	foreach($agendas as $agenda) {
		$appointment_time = strtotime($agenda['appoint_date']." ".$agenda['end_event_time']);
		$current_time = time();
		$user_specialities_arr = getUserSpecialities($agenda['trainer_info_id']);
		$user_specialites = "";
		foreach($user_specialities_arr as $us) {
			$user_specialites .= $us->category_name.",";
		}
		$user_specialites = rtrim($user_specialites,",");
		$user_profile_image = getProfileImage($agenda['trainer_detail']);
		if($current_time > $appointment_time) {
			$class = "red";
		}
		else {
			$class = "green";
		}
	?>
		<div class="search_result">
			<div class="info_section">
				<div class="userpic">
					<a href="#" class="img-responsive">
						<?php echo $user_profile_image;?>
					</a>
				</div>
				<div class="userinfo">
					<p class="name"><a href="<?php echo base_url()?>member/profile/view_profile/<?php echo $agenda['general_id']?>"><?php echo $agenda['trainer_detail']['name']?></a></p>
					<p class="cost"><label>Specialities:</label><span><?php echo $user_specialites ?></span></p>
					<p class="<?php echo $class;?>"><label>Appointment Date:</label><span><?php echo $agenda['appoint_date'] ?> <?php echo $agenda['start_event_time']?> to <?php echo $agenda['end_event_time']?></span></p>
				</div>
			</div>
			<div class="option_section clearfix">
				<div class="col-xs-12 option_section_wrapper">
					<p>
						<a class="contact_trainer_search_button" href="" id="contact" result="<?php echo $agenda['general_id'];?>" result_mail="<?php echo @$agenda['trainer_detail']['email_address'];?>"><i class="fa fa-comments"></i> Contact</a></p>
					<p><a href="<?php echo base_url()?>member/profile/view_profile/<?php echo $agenda['general_id']?>" class="btn">Visit Profile</a></p>
				</div>
			</div>
		</div>
	<?php } 
	echo $pagination;
	}
	else {?>
		<div class="alert alert-danger">
			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
			<?php echo $words['DTL_0161'];?>!
		</div>
	<?php }?>
	<!--Loop ends here-->
	</div>
</div>