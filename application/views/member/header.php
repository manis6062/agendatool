<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <title><?php echo $header;?></title>

  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,700italic,400,600,700">
  <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300,700">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery-ui.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/js/plugins/fullcalendar/fullcalendar.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/js/plugins/timepicker/bootstrap-timepicker.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/js/plugins/fileupload/bootstrap-fileupload.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-select.min.css">

  <link rel="stylesheet" href="<?php echo base_url()?>assets/js/plugins/select2/select2.css">	
  <link rel="stylesheet" href="<?php echo base_url()?>assets/js/plugins/datepicker/datepicker.css">	
  <link rel="stylesheet" href="<?php echo base_url()?>assets/js/plugins/icheck/skins/minimal/blue.css">	

  <!-- Plugin CSS -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/js/plugins/magnific/magnific-popup.css">
  <style>ul.ui-autocomplete{
		z-index: 9999999999999;
    }
	</style>
  <!-- App CSS -->




    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/target-admin.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/custom.css">
    <script src="<?php echo base_url()?>/assets/js/libs/jquery-1.10.1.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/libs/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/ajax.js"></script>
    <?php if($this->session->userdata('is_admin') == 1){?>
        <script src="<?php echo base_url()?>assets/js/admin.js"></script>
    <?php }?>
    <script src="<?php echo base_url()?>assets/js/jquery-ui.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/jquery-bootstrap-purr.js"></script>
    <script src="<?php echo base_url()?>assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="<?php echo base_url()?>assets/js/frontend.js"></script>


        <style type="text/css" media="screen">
            #map {
                width: 1080px;
                height: 460px;
            }
		.gm-style-iw {
      width: 200px !important;
    }
        </style>

  <script type="text/javascript">
      var site_url = '<?php echo site_url();?>';
	  var base_url = '<?php echo base_url();?>';
	  <?php if(isset($userdata)){?>
		var userid = '<?php echo $userdata["id"]?>';
	  <?php }?>
	  <?php if(!empty($userdata)){?>
	  	var userdata_id = "<?php echo $userdata['id']?>";
	  <?php }?>
	  
  </script>
  <?php if(isset($trainerdata)){?>
  <script>
		var trainerid = '<?php echo $trainerdata["id"]; ?>';
		</script>
	<?php } 
	else{?>
	<script>
		var trainerid = 1;
		</script>
	<?php } ?>
  <?php if($header!="Trainer Agenda"){?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/css/profile_page.css">
  <?php } 
  else{
    ?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/css/trainer_agenda_page.css">
  <?php } ?>

  <script src="<?php echo base_url()?>assets/js/header.js"></script>
 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
  
 <!-- <script src="//maps.googleapis.com/maps/api/js"></script> -->
  
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
  <![endif]-->
  
  <script type="text/javascript">
  var points_arr = new Array();
  $(document).ready(function(){
    $('#contact_trainer_button').on('click',function(){
        $('#contact_trainer_modal').modal('show');
   });
   $('#contact_company_button').on('click',function(){
        $('#contact_company_modal').modal('show');
   });
  });
</script>
	
	
</head>

<body>

  <div class="navbar">

  <div class="container">

    <div class="navbar-header">

      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <i class="fa fa-cogs"></i>
      </button>

      <a class="navbar-brand navbar-brand-image" href="<?php echo site_url('member/profile/view_profile').'/'.$this->session->userdata('general_id');?>">
        <img src="<?php echo base_url()?>assets/img/logo.png" alt="Site Logo" style="width:150px;height:auto; margin-top: 15px;">
      </a>

    </div> <!-- /.navbar-header -->

    <div class="navbar-collapse collapse">

	<?php if($this->session->userdata('general_id')!=null && $header!='Expired'){?>
	<ul class="nav navbar-nav navbar-right">
	<li class="sync_btn">
	<?php if($this->session->userdata('is_admin')!=1 && $this->session->userdata('is_company')!=1){?>
	<span class="sync_text" id="sync_text" style="display:none;"><?php echo $words['DTL_0261'];?></span><i class="fa fa-refresh" id="sync_btn"></i>
	<?php }?>
	</li>
	<?php if($this->session->userdata('is_admin')!=1){?>
	<li class="dropdown">
          <a href="./page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-envelope"></i>
            <span class="navbar-visible-collapsed">&nbsp;<?php echo $words['DTL_0152'];?>&nbsp;</span>
            <i class="fa fa-caret-down"></i>
          </a>
			
          <ul class="dropdown-menu noticebar-menu" role="menu">                
            <li class="nav-header">
              <div class="pull-left">
                <?php echo $words['DTL_0152'];?>
              </div>
            </li>



			<?php
            if(isset($messages)){
				$count = 0;
				foreach($messages as $message){?>
            <li>
              <a href="<?php echo site_url('member/profile//inbox_detail') .'/'. $message['id'];?>" class="noticebar-item">
                <span class="noticebar-item-body">
				  <?php if($message['email_by_id'] == $this->session->userdata('general_id')){?>
                  <strong class="noticebar-item-title"><?php echo $words['DTL_0245'];?>: <?php echo $message['sent_to']['name']?></strong>
				  <?php }
				  elseif($message['email_by_id'] != $this->session->userdata('general_id')){?>
				   <strong class="noticebar-item-title"><?php echo $words['DTL_0209'];?>: <?php echo $message['sent_by']['name']?></strong>
				  <?php }?>
                  <span class="noticebar-item-text"><?php 
				  $limited_msg= character_limiter($message['message'],40); 
							echo $limited_msg;?>
							</span>
                  <span class="noticebar-item-time"><i class="fa fa-clock-o"></i> <?php echo $message['created_date']?></span>
                </span>
              </a>
            </li>
				<?php 
				$count++;
				if($count == 3)
					break;
				} ?>

            <li class="noticebar-menu-view-all">
              <a href="<?php echo site_url('member/profile/inbox')?>"><?php echo $words['DTL_0298'];?></a>
            </li>
			<?php
			}
			else
			{?>
			<li class="noticebar-menu-view-all">
              <a href="<?php echo site_url('member/profile/inbox')?>"><?php echo $words['DTL_0163'];?></a>
            </li>
			<?php }?>
          </ul>
        </li>
	<?php }?>
		<li class="dropdown navbar-profile language-flag">
          <a href="./page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url()?>/uploads/flags/<?php echo $this->session->userdata('flag_icon')?>" class="navbar-profile-avatar">
            <i class="fa fa-caret-down"></i>
          </a>
			
          <ul class="dropdown-menu language-dropdown" role="menu">                
          <?php foreach($language as $l){?>
            <li>
              <a href="<?php echo site_url('auth/set_language')?>/<?php echo $l['id']?>">
                <img src="<?php echo base_url()?>/uploads/flags/<?php echo $l['flag']?>">
				&nbsp;&nbsp;<?php echo $l['lang_name']?>
				</a>
            </li>
		  <?php }?>
	     </ul>
        </li>

        <li class="dropdown navbar-profile">
          <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">
		  <?php
			if(!empty($userdata))
				if($userdata['photo_logo']==null){?>
            <img src="<?php echo base_url()?>uploads/userimage/default.jpg" class="navbar-profile-avatar" >
			<?php
			}
			elseif($userdata['linkedin_image_status'] == 0){
			?>
			<img src="<?php echo base_url()?>uploads/userimage/<?php echo $this->session->userdata('photo')?>" class="navbar-profile-avatar" alt="" style="width: 30px; height: 30px;">
			<?php }
			elseif($userdata['linkedin_image_status'] == 1){?>
			<img src="<?php echo $userdata['linkedin_image']?>" class="navbar-profile-avatar" alt="" style="width: 30px; height: 30px;">
			<?php }?>


			
            <span class="navbar-profile-label"><?php if(!empty($userdata)){ echo $userdata['email_address']; }?> &nbsp;</span>
            <i class="fa fa-caret-down"></i>
          </a>

          <ul class="dropdown-menu" role="menu">
			
            <li>
              <a href="<?php echo site_url('member/profile/view_profile').'/'.$this->session->userdata('general_id');?>">
                <i class="fa fa-user"></i> 
                &nbsp;&nbsp;<?php echo $words['DTL_0154'];?>
              </a>
            </li>

          <?php if($this->session->userdata('is_admin')==1)
		   { ?>
			
			<li>
              <a href="<?php echo site_url('admin/home/setting_page')?>">
                <i class="fa fa-cogs"></i> 
                &nbsp;&nbsp; <?php echo $words['DTL_0246'];?>
              </a>
            </li>

           
		   <?php }?>
            <li class="divider"></li>

            <li>
              <a href="<?php echo site_url('auth/logout');?>">
                <i class="fa fa-sign-out"></i> 
                &nbsp;&nbsp;<?php echo $words['DTL_0146'];?>
              </a>
            </li>

          </ul>

        </li>

      </ul>
	<?php }
	else {?>
	<ul class="nav navbar-nav navbar-right">   
	  <li class="dropdown navbar-profile language-flag">
			 
            <a href="./page-notifications.html" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url()?>/uploads/flags/<?php echo $this->session->userdata('flag_icon')?>" class="navbar-profile-avatar">
            <i class="fa fa-caret-down"></i>
          </a>
  
          <ul class="dropdown-menu language-dropdown" role="menu">                
          
            <?php foreach($language as $l){?>
            <li>
              <a href="<?php echo site_url('auth/set_language')?>/<?php echo $l['id']?>">
                <img src="<?php echo base_url()?>/uploads/flags/<?php echo $l['flag']?>">
				&nbsp;&nbsp;<?php echo $l['lang_name']?>
				</a>
            </li>
		  <?php }?>
	     </ul>
        </li>
		</ul>
	<?php }?>

    </div> <!--/.navbar-collapse -->

  </div> <!-- /.container -->

</div> <!-- /.navbar -->
<?php if($header != 'Login' && $header != 'Register Company' && $header != 'Register Trainer' && $header != 'Expired' && $header != 'Forgot Password'){?>
  <div class="mainbar">

  <div class="container">
	
    <button type="button" class="btn mainbar-toggle" data-toggle="collapse" data-target=".mainbar-collapse">
      <i class="fa fa-bars"></i>
    </button>
	
    <div class="mainbar-collapse collapse" style="height: 0px;">

      <ul class="nav navbar-nav mainbar-nav">
		<?php if($this->session->userdata('is_admin')==1 && $this->session->userdata('general_id')!=null){?>   
			<li  <?php if($header == 'Admin Home'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('admin/home')?>">
					<i class="fa fa-home"></i>
					<?php echo $words['DTL_0122'];?>
				</a>
			</li>  
			<li <?php if($header == 'Users'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('admin/user')?>">
					<i class="fa fa-users"></i>
					<?php echo $words['DTL_0286'];?>
				</a>
			</li>  
			<li <?php if($header == 'Payment'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('admin/payment')?>">
					<i class="fa fa-money"></i>
					<?php echo $words['DTL_0182'];?>
				</a>
			</li>
			<li <?php if($header == 'Fields'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('admin/field')?>">
					<i class="fa fa-minus"></i>
					<?php echo $words['DTL_0117'];?>
				</a>
			</li>
			<li <?php if($header == 'Specialities'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('admin/speciality')?>">
					<i class="fa fa-wrench"></i>
					<?php echo $words['DTL_0248'];?>
				</a>
			</li>
			<li <?php if($header == 'Emails'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('admin/email')?>">
					<i class="fa fa-envelope"></i>
					<?php echo $words['DTL_0108'];?>
				</a>
			</li>
			<li <?php if($header == 'Languages'){?>class="active"<?php }?>>
			<a href="<?php echo site_url('admin/language')?>">
				<i class="fa fa-font"></i>
			<?php echo $words['DTL_0137'];?>
			</a>
			</li>
			<?php }
			elseif($this->session->userdata('is_company')==1  && $this->session->userdata('general_id')!=null){
              
            ?>
            <!--
			<li>
				<a href="javascript:;">
					<i class="fa fa-home"></i>
					<?php echo $words['DTL_0122'];?>
				</a>
			</li> 
			-->
			<li <?php if($header == 'Profile'){?>class="active"<?php }?>>
			  <a href="<?php echo site_url('member/profile/view_profile').'/'.$this->session->userdata('general_id');?>">
				<i class="fa fa-user"></i>
				<?php echo $words['DTL_0205'];?>
			  </a>
			</li>
			<li <?php if($header == 'Search'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('member/search');?>">
					<i class="fa fa-search"></i>
					<?php echo $words['DTL_0233'];?>
				</a>
			</li>
			<li <?php if($header == 'View Agendas'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('member/trainer_overview/view_agendas').'/'.$this->session->userdata('general_id');?>">
					<i class="fa fa-globe"></i>
					<?php echo $words['DTL_0030'];?>
				</a>
			</li>
			<li <?php if($header == 'Your Appointments'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('member/trainer_overview/your_appointments').'/'.$this->session->userdata('general_id');?>">
					<i class="fa fa-globe"></i>
					Your Appointments
				</a>
			</li>
			<li class="<?php if($header == 'Notifications'){?> active <?php }?>">
				<a href="<?php echo site_url('member/notification');?>">
					<i class="fa fa-bell"></i>
					<?php echo $words['DTL_0168'];?>
				</a>
			</li>
        <?php
          }
        elseif($this->session->userdata('is_company')==0  && $this->session->userdata('general_id')!=null){
			?>
			<!--
			<li>
				<a href="javascript:;">
					<i class="fa fa-home"></i>
					<?php echo $words['DTL_0122'];?>
				</a>
			</li> 
			-->
			<li class="<?php if($header == 'Profile'){?> active <?php }?>">
				<a href="<?php echo site_url('member/profile/view_profile').'/'.$this->session->userdata('general_id');?>" >
					<i class="fa fa-user"></i>
					<?php echo $words['DTL_0205'];?>
				</a>


			</li>  
			<li class="<?php if($header == 'Trainer Agenda'){?> active <?php }?>">
				<a href="<?php echo site_url('member/trainer_agenda/get_trainer_agenda').'/'.$this->session->userdata('general_id');?>">
					<i class="fa fa-calendar"></i> 
					<?php echo $words['DTL_0027'];?>
				</a>
			</li>
			<li class="<?php if($header == 'Agenda Notifications'){?> active <?php }?>">
				<a href="<?php echo site_url('member/trainer_agenda/pending_agenda').'/'.$this->session->userdata('general_id');?>">
					<i class="fa fa-check-square-o"></i>
					<?php echo $words['DTL_0030'];?>
				</a>
			</li>
			<li <?php if($header == 'Your Appointments'){?>class="active"<?php }?>>
				<a href="<?php echo site_url('member/trainer_agenda/your_appointments').'/'.$this->session->userdata('general_id');?>">
					<i class="fa fa-globe"></i>
					Your Appointments
				</a>
			</li>
			<li class="<?php if($header == 'Notifications'){?> active <?php }?>">
				<a href="<?php echo site_url('member/notification');?>">
					<i class="fa fa-bell"></i>
					<?php echo $words['DTL_0168'];?>
				</a>
			</li>
			</ul>

			
			<!--
			<li>
			  <a href="javascript:;">
				<i class="fa fa-cogs"></i>
				<?php echo $words['DTL_0030'];?>
			  </a>
			</li>	
			-->
			
			<?php 
        }
        ?>

      </ul>

    </div>
 

  </div> <!-- /.container --> 

</div> <!-- /.mainbar -->
<?php }?>


<div class="container">

  <div class="content">

    <div class="content-container">