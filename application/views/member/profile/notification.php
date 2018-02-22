<div class="content-header">
        <h2 class="content-header-title"><?php echo $words['DTL_0168'];?></h2>
        <ol class="breadcrumb">
          <li><a href="<?php echo site_url('member/profile') ?>"><?php echo $words['DTL_0205'];?></a></li>
          <li class="active"><?php echo $words['DTL_0168'];?></li>
        </ol>
      </div> <!-- /.content-header -->
<?php if(!empty($today_events) || !empty($yesterday_events) || !empty($older_events) ){ ?>
<?php if(!empty($today_events)){?>
      <h5 class="heading"><?php echo $words['DTL_0342']?></h5>

<ul class="icons-list notifications-list">
<?php foreach($today_events as $today_event){?>
<?php if($today_event['notification_type']==1){?>
  <li>
    <i class="icon-li fa fa-envelope-o"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $today_event['other_user']['general_id']?>"><?php echo $today_event['other_user']['name']?> </a><?php echo $words['DTL_0344']?>
  </li>
  <?php }
  elseif($today_event['notification_type']==2){
    ?>
    <li>
    <i class="icon-li fa fa-calendar-o"></i>
    <?php if($this->session->userdata('is_company')==1){?>
      <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $today_event['other_user']['general_id']?>"><?php echo $today_event['other_user']['name']?></a> <?php echo $words['DTL_0346']?> <a href="<?php echo site_url('member/trainer_overview/view_agendas')?>/<?php echo $this->session->userdata('general_id');?>"><?php echo $words['DTL_0347']?></a>
    <?php }
    else {?>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $today_event['other_user']['general_id']?>"><?php echo $today_event['other_user']['name']?></a> <?php echo $words['DTL_0346']?> <a href="<?php echo site_url('member/trainer_agenda/pending_agenda')?>/<?php echo $this->session->userdata('general_id');?>"><?php echo $words['DTL_0347']?></a>
    <?php }?>
  </li>
    <?php
  }
  elseif($today_event['notification_type']==3){
    ?>
    <li>
    <i class="icon-li fa fa-star-o"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $today_event['other_user']['general_id']?>"><?php echo $today_event['other_user']['name']?></a> <?php echo $words['DTL_0345']?>
  </li>
    <?php
  }
  elseif($today_event['notification_type']==4){
  ?>
  <li>
    <i class="icon-li fa fa-check-circle text-success"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $today_event['other_user']['general_id']?>"><?php echo $today_event['other_user']['name']?></a> <?php echo $words['DTL_0348']?>
  </li>
  <?php }
  elseif($today_event['notification_type']==5){
    ?>
    <li>
    <i class="icon-li fa fa-ban text-danger"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $today_event['other_user']['general_id']?>"><?php echo $today_event['other_user']['name']?></a> <?php echo $words['DTL_0349']?>
  </li>
    <?php
  }
  elseif($today_event['notification_type']==6){
    ?>
    <li>
    <i class="icon-li fa fa-pencil"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $today_event['other_user']['general_id']?>"><?php echo $today_event['other_user']['name']?></a> <?php echo $words['DTL_0350']?><a href="<?php echo site_url('member/trainer_overview/view_agendas')?>/<?php echo $this->session->userdata('general_id');?>"><?php echo $words['DTL_0347']?></a>
  </li>
    <?php
  }
  }?>
</ul>
<br>
<?php }?>

<?php if(!empty($yesterday_events)){?>
<h5 class="heading"><?php echo $words['DTL_0343']?></h5>

<ul class="icons-list notifications-list">
<?php foreach($yesterday_events as $yesterday_event){?>
<?php if($yesterday_event['notification_type']==1){?>
  <li>
    <i class="icon-li fa fa-envelope-o"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $yesterday_event['other_user']['general_id']?>"><?php echo $yesterday_event['other_user']['name']?></a> <?php echo $words['DTL_0344']?>
  </li>
  <?php }
  elseif($yesterday_event['notification_type']==2){
    ?>
    <li>
    <i class="icon-li fa fa-calendar"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $yesterday_event['other_user']['general_id']?>"><?php echo $yesterday_event['other_user']['name']?></a> <?php echo $words['DTL_0346']?> <a href="<?php echo site_url('member/trainer_agenda/pending_agenda')?>/<?php echo $this->session->userdata('general_id');?>"><?php echo $words['DTL_0347']?></a>
  </li>
    <?php
  }
  elseif($yesterday_event['notification_type']==3){
    ?>
    <li>
    <i class="icon-li fa fa-star-o"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $yesterday_event['other_user']['general_id']?>"><?php echo $yesterday_event['other_user']['name']?></a> <?php echo $words['DTL_0345']?>
  </li>
    <?php
  }
  elseif($yesterday_event['notification_type']==4){
  ?>
  <li>
    <i class="icon-li fa fa-check-cricle text-success"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $yesterday_event['other_user']['general_id']?>"><?php echo $yesterday_event['other_user']['name']?></a> <?php echo $words['DTL_0348']?>
  </li>
  <?php }
  elseif($yesterday_event['notification_type']==5){
    ?>
    <li>
    <i class="icon-li fa fa-ban text-danger"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $yesterday_event['other_user']['general_id']?>"><?php echo $yesterday_event['other_user']['name']?></a> <?php echo $words['DTL_0349']?>
  </li>
    <?php
  }
  elseif($yesterday_event['notification_type']==6){
    ?>
    <li>
    <i class="icon-li fa fa-pencil"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $yesterday_event['other_user']['general_id']?>"><?php echo $yesterday_event['other_user']['name']?></a> <?php echo $words['DTL_0350']?><a href="<?php echo site_url('member/trainer_overview/view_agendas')?>/<?php echo $this->session->userdata('general_id');?>"><?php echo $words['DTL_0347']?></a>
  </li>
    <?php
  }
  }?>
</ul>
<br>
<?php }?>

<?php if(!empty($older_events)){
  foreach($older_events as $older_event_date=>$older_event){
      echo "<h5 class='heading'>".$older_event_date."</h5>";
      echo "<ul class='icons-list notifications-list'>";
      foreach($older_event as $old) {
  ?>
  <?php if($old['notification_type']==1){?>
  <li>
    <i class="icon-li fa fa-envelope-o"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $old['other_user']['general_id']?>"><?php echo $old['other_user']['name']?></a> <?php echo $words['DTL_0344']?>
  </li>
  <?php }
  elseif($old['notification_type']==2){
    ?>
    <li>
    <i class="icon-li fa fa-calendar-o"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $old['other_user']['general_id']?>"><?php echo $old['other_user']['name']?></a> <?php echo $words['DTL_0346']?> <a href="<?php echo site_url('member/trainer_agenda/pending_agenda')?>/<?php echo $this->session->userdata('general_id');?>"><?php echo $words['DTL_0347']?></a>
  </li>
    <?php
  }
  elseif($old['notification_type']==3){
    ?>
    <li>
    <i class="icon-li fa fa-star-o"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $old['other_user']['general_id']?>"><?php echo $old['other_user']['name']?></a> <?php echo $words['DTL_0345']?>
  </li>
    <?php
  }
  elseif($old['notification_type']==4){
  ?>
  <li>
    <i class="icon-li fa fa-check-circle text-success"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $old['other_user']['general_id']?>"><?php echo $old['other_user']['name']?></a> <?php echo $words['DTL_0348']?>
  </li>
  <?php }
  elseif($old['notification_type']==5){
    ?>
    <li>
    <i class="icon-li fa fa-ban text-danger"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $old['other_user']['general_id']?>"><?php echo $old['other_user']['name']?></a> <?php echo $words['DTL_0349']?>
  </li>
  
    <?php
  }
  elseif($old['notification_type']==6){
    ?>
    <li>
    <i class="icon-li fa fa-pencil"></i>
    <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $old['other_user']['general_id']?>"><?php echo $old['other_user']['name']?></a> <?php echo $words['DTL_0350']?><a href="<?php echo site_url('member/trainer_overview/view_agendas')?>/<?php echo $this->session->userdata('general_id');?>"><?php echo $words['DTL_0347']?></a>
  </li>
    <?php
  }
}
      echo "</ul>";
      echo "<br>";

  }?>

<?php } }
else{?>
 <div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
            <?php 
            echo $words['DTL_0357']; ?>
            </div>
<?php }?>