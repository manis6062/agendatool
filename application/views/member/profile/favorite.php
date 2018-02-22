         

			<div class="content-header">
				<h2 class="content-header-title"><?php echo $words['DTL_0115'];?></h2>
				<ol class="breadcrumb">
					<li><a href="<?php echo site_url('member/profile') ?>"><?php echo $words['DTL_0205'];?></a></li>
                    <li class="active"><?php echo $words['DTL_0115'];?></li>
                </ol>
			</div>

			<?php if(isset($favorites)){
				foreach($favorites as $favorite){ 
					$user_specialities_arr = getUserSpecialities($favorite['general_id']);
					$user_specialites = "";
					foreach($user_specialities_arr as $us) {
						$user_specialites .= $us->category_name.",";
					}
					$user_specialites = rtrim($user_specialites,",");
				?>
					<div class="search_result">
						<div class="info_section">
							<div class="userpic">
								<a href="<?php echo site_url('member/profile/view_profile')?>/<?php echo $favorite['id']?>" class="img-responsive">
									<?php 
										$user_profile_image =  '';
										if ($favorite['photo_logo'] == null) {
											$user_profile_image = '<img class="img-responsive" src="'.base_url().'assets/img/default.jpg" alt="Profile Picture">';
										}
										elseif ($favorite['linkedin_image_status'] == 0) {
											$user_profile_image = '<img class="img-responsive" src="'.base_url().'uploads/userimage/'.$favorite['photo_logo'].'" alt="Profile Picture">';
										}
										elseif ($favorite['linkedin_image_status'] == 1) {
											$user_profile_image = '<img class="img-responsive" src="'.$favorite['linkedin_image'].'" alt="Profile Picture">';
										}
										echo $user_profile_image;
									?>
								</a>
							</div>
							<div class="userinfo">
								<p class="name"><a href="#"><?php echo $favorite['name'];?></a></p>
								<p class="location"><label>Specialities:</label><span><?php echo $user_specialites?></span></p>
							</div>
						</div>
						<div class="option_section clearfix">
							<div class="col-xs-12 option_section_wrapper">
								<p><a class="contact_trainer_search_button" href="" id="contact" result="<?php echo $favorite["general_id"];?>" result_mail="<?php echo $favorite['email_address'];?>"><i class="fa fa-comments"></i> Contact</a></p>
								<p><a href="<?php echo site_url('member/profile/view_profile')?>/<?php echo $favorite['id']?>" class="btn">Visit Profile</a></p>
							</div>
						</div>
					</div>
				<?php }	?>
			<?php }
			else{
			echo "No Favorites!!!";
		}
		?>

    </div> <!-- /.row -->

    </div> <!-- /.content-container -->
      
  </div> <!-- /.content -->

</div> <!-- /.container -->