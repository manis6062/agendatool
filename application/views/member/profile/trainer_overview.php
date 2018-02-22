<div class="content-header">
	<h2 class="content-header-title"><?php echo $words['DTL_0269'];?></h2>

	<ol class="breadcrumb">
	    <li><a href="<?php echo site_url('member/profile') ?>"><?php echo $words['DTL_0205'];?></a></li>
	    <li class="active"><?php echo $words['DTL_0269'];?></li>
	  </ol>

		
		
</div>
<form class="form-group user-search filter_block" method="post" action="<?php echo site_url('member/trainer_overview/view_agendas')?>/<?php echo $this->session->userdata('general_id');?>" >

		<div class="notification_filter form-group filter_block">

	 		<div class="row">

              <div class="col-md-4">

                <div class="form-group">
                   <label><?php echo $words['DTL_0268'];?>: </label> <input type="text" id="" name="searchByTrainerName" value="<?php //if($this->session->userdata('searchTrainerName')!=''){ echo $this->session->userdata('searchByName');}?>" class="form-control">
                </div>

              </div>


               <div class="col-md-4">

                <div class="form-group">
                 <label> <?php echo $words['DTL_0040'];?>: </label><input type="text" id="" name="searchByAppointmentName" value="<?php //if($this->session->userdata('searchByAmount')!=''){ echo $this->session->userdata('searchByAmount');}?>" class="form-control"> 
                </div>

              </div>



               <div class="col-md-4">

                <div class="form-group">
                  <label> <?php echo $words['DTL_0038'];?>: </label><input type="text" id="dp-ex-3" data-date-format="yyyy-mm-dd" name="searchByAppointmentDate" value="<?php //if($this->session->userdata('searchByStartDate')!=''){ echo $this->session->userdata('searchByStartDate');}?>" class="form-control" data-auto-close="true" data-date-autoclose="true">
                </div>

              </div>

            </div>
               
          <div class="row">
              <div class="col-md-4">

<!-- <<<<<<< .mine
	
		 <label> <?php echo $words['DTL_0028'];?> </label>
		  
		  <select id=""  name="filter" class="form-control">
				<option value="All" <?php if($filter == 'All'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0031'];?></option>
				<option value="Confirmed" <?php if($filter == 'Confirmed'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0060'];?></option>
				<option value="Rejected" <?php if($filter == 'Rejected'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0216'];?></option>
				<option value="Reserved" <?php if($filter == 'Reserved'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0220'];?></option>
		  </select>
	  </div>
                     <div class="col-md-4">
	
		<label>Order</label>
		
		  <select id=""  name="order" class="form-control" >
				<option value="DESC" <?php if($order == 'DESC'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0139'];?></option>
				<option value="ASC" <?php if($order == 'ASC'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0173'];?></option>
		  </select>
======= -->
				<div class="form-group">
				 <label> <?php echo $words['DTL_0028'];?> </label>
				  
				  <select id="filter_select"  name="filter" class="form-control">
						<option value="All" <?php if($filter == 'All'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0031'];?></option>
						<option value="Confirmed" <?php if($filter == 'Confirmed'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0060'];?></option>
						<option value="Rejected" <?php if($filter == 'Rejected'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0216'];?></option>
						<option value="Reserved" <?php if($filter == 'Reserved'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0220'];?></option>
				  </select>
			</div>
		  </div>
           <div class="col-md-4">
			<div class="form-group">
				<label>Order</label>
				
				  <select id="order_select"  name="order" class="form-control" >
						<option value="DESC" <?php if($order == 'DESC'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0139'];?></option>
						<option value="ASC" <?php if($order == 'ASC'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0173'];?></option>
				  </select>


		    </div>
		    </div>
              <div class="col-md-4">
				<div class="form-group">
					<label>Limit</label>

		<!--   <select id=""  name="per_page" class="form-control" >
				<option value="5" <?php if($per_page == 5){ ?>selected = "selected"<?php } ?>>5</option>
				<option value="10" <?php if($per_page == 10){ ?>selected = "selected"<?php } ?>>10</option>
				<option value="20" <?php if($per_page == 20){ ?>selected = "selected"<?php } ?>>20</option>
				<option value="50" <?php if($per_page == 50){ ?>selected = "selected"<?php } ?>>50</option>
				<option value="100" <?php if($per_page == 100){ ?>selected = "selected"<?php } ?>>100</option>
				<option value="500" <?php if($per_page == 500){ ?>selected = "selected"<?php } ?>>500</option>
		  </select>
======= -->
				  <select id="list_select"  name="per_page" class="form-control" >
						<option value="5" <?php if($per_page == 5){ ?>selected = "selected"<?php } ?>>5</option>
						<option value="10" <?php if($per_page == 10){ ?>selected = "selected"<?php } ?>>10</option>
						<option value="20" <?php if($per_page == 20){ ?>selected = "selected"<?php } ?>>20</option>
						<option value="50" <?php if($per_page == 50){ ?>selected = "selected"<?php } ?>>50</option>
						<option value="100" <?php if($per_page == 100){ ?>selected = "selected"<?php } ?>>100</option>
						<option value="500" <?php if($per_page == 500){ ?>selected = "selected"<?php } ?>>500</option>
				  </select>


           	 </div>
            </div>
                  </div>



                   <img src="<?php echo base_url()?>assets/img/loader.gif" style="display: none;" width="25" height="25" id="notification_loader">
               
             
            </div>
         
		 <input type="submit" name="submit" id="submit_btn" class="btn btn-primary" value="<?php echo $words['DTL_0259']?>">
     
      </form>
		


	<div class="table-responsive">
		        <table class="table table-bordered table-highlight">
		            <thead>
		                <tr>
		                   <!--  <th style="width: 150px"><?php echo $words['DTL_0123'];?></th> -->
							<th>#</th>
		                    <!--<th><?php echo $words['DTL_0248'];?></th>-->
		                    <th><?php echo $words['DTL_0040'];?></th>
		                    <th><?php echo $words['DTL_0268'];?></th>
							<!--<th><?php echo $words['DTL_0038'];?></th>-->
		                    <th><?php echo $words['DTL_0002'];?></th>
		                    <th width="200"><?php echo $words['DTL_0253'];?></th>
		                </tr>
		            </thead>
		            
		            <tbody>

				<?php if(!empty($agendas)){
					$count = 1;
				foreach($agendas as $agenda){
					if($agenda['appoint_date'] < date('Y-m-d')){
				?>

		              <tr class="expired">
		                <!-- <td>
						<div class="thumbnail">
			                  <div class="thumbnail-view">
							  <a class="thumbnail-view-hover ui-lightbox" href='<?php echo base_url()?>uploads/userimage/<?php echo $agenda["trainer_detail"]["photo_logo"]?>'>
							  </a>

							  <img src='<?php echo base_url()?>uploads/userimage/<?php echo $agenda["trainer_detail"]["photo_logo"]?>' width="125"/>
							</div>
						</div>
		                </td> -->
		                <td><?php echo $count;?></td>
		                <!--<td class="file-info">
							<?php foreach($agenda['trainer_detail']['specialities'] as $speciality){?>
							<span><?php echo $speciality['category_name']?></span> <br>
							<?php }?>
						</td>-->
		                <td>
		                  <?php echo $agenda['appointment_name'];?>
		                </td>
		                <td><p><?php echo $agenda['trainer_detail']['name'];?></p></td>
						<!--<td>
		                  <?php echo $agenda['appoint_date'];?>
		                </td>-->
		                <td class="double_icons">
		                  <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $agenda['trainer_detail']['general_info_id']?>"><button class="btn btn-xs btn-info "><i class="fa fa-eye"></i></button></a>
		                 
		                  <button class="btn btn-xs btn-tertiary contact_trainer_search_button"><i class="fa fa-comments-o"></i></button>
						<?php 
						if($agenda['is_appointed'] == 0 && $agenda['is_confirm']==0){}//do nothing means donot show edit button
						else {//else show edit button?>
							<a href="<?php echo site_url('member/trainer_agenda/get_trainer_agenda').'/'.$agenda['trainer_detail']['general_info_id'];?>"><button class="agdBtn btn btn-xs btn-secondary" ><i class="fa fa-pencil"></i></button></a>
						<?php } ?>
		                </td>
						<td class="double_status">
							<?php if($agenda['is_edited']==1){?>
									<!-- <a href="<?php echo site_url('member/trainer_agenda/get_trainer_agenda').'/'.$agenda['trainer_detail']['general_info_id'] ;?>"><button class="agdBtn btn btn-xs btn-success" ><i class="fa fa-pencil-square-o"></i> <?php echo $words['DTL_0092'];?></button></a> -->
										<a href="<?php echo site_url('member/trainer_agenda/company_accept')?>/<?php echo $agenda['agenda_id'];?>" class="btn btn-xs btn-success notificationBtns  confirm_agenda" onclick="return confirm('Are you sure you want to accept this agenda?');"><?php echo $words['DTL_0355'];?></a>
										<a href="<?php echo site_url('member/trainer_agenda/company_reject')?>/<?php echo $agenda['agenda_id'];?>" class="btn btn-xs btn-primary notificationBtns reject_agenda" onclick="return confirm('Are you sure you want to reject this agenda?');"><?php echo $words['DTL_0214'];?></a>
								<?php }
								elseif($agenda['is_appointed'] == 0 && $agenda['is_confirm'] == 0) {?>
									<span class="label label-primary"><?php echo $words['DTL_0215'];?></span>
								<?php }
								elseif($agenda['is_confirm']==1 && $agenda['is_appointed']==1){?>
									<span class="label label-success"><?php echo $words['DTL_0001'];?></span>
								<?php }
								elseif($agenda['is_confirm'] == 0 && $agenda['is_appointed'] == 1){?>
										<span class="label label-default"><?php echo $words['DTL_0167'];?></span>
								<?php }?>
						</td>
		              </tr>
<?php 
		}
		else{
		?>
				<tr>
		                <!-- <td>
						<div class="thumbnail">
			                  <div class="thumbnail-view">
							  <a class="thumbnail-view-hover ui-lightbox" href='<?php echo base_url()?>uploads/userimage/<?php echo $agenda["trainer_detail"]["photo_logo"]?>'>
							  </a>

							  <img src='<?php echo base_url()?>uploads/userimage/<?php echo $agenda["trainer_detail"]["photo_logo"]?>' width="125"/>
							</div>
						</div>
		                </td> -->
		                <td><?php echo $count;?></td>
		                <!--<td class="file-info">
							<?php foreach($agenda['trainer_detail']['specialities'] as $speciality){?>
							<span><?php echo $speciality['category_name']?></span> <br>
							<?php }?>
						  </td>-->
		                <td>
		                  <?php echo $agenda['appointment_name'];?>
		                </td>
		                <td><p><?php echo $agenda['trainer_detail']['name'];?></p></td>
						<!--<td>
		                  <?php echo $agenda['appoint_date'];?>
		                </td>-->
		                <td class="double_icons">
		                   <a href="<?php echo site_url('member/profile/other_profile')?>/<?php echo $agenda['trainer_detail']['general_info_id']?>"><button class="btn btn-xs btn-info "><i class="fa fa-eye"></i></button></a>
		                 
		                  <button class="btn btn-xs btn-tertiary contact_trainer_search_button"><i class="fa fa-comments-o"></i></button>
							
						<?php 
						if($agenda['is_appointed'] == 0 && $agenda['is_confirm']==0){}//do nothing means donot show edit button
						else {//else show edit button?>
							<a href="<?php echo site_url('member/trainer_agenda/get_trainer_agenda').'/'.$agenda['trainer_detail']['general_info_id'];?>"><button class="agdBtn btn btn-xs btn-secondary" ><i class="fa fa-pencil"></i></button></a>
						<?php  } ?>
		                </td>
						<td class="double_status">
							<?php 
								if($agenda['is_edited']==1) {?>
									<!-- <a href="<?php echo site_url('member/trainer_agenda/get_trainer_agenda').'/'.$agenda['trainer_detail']['general_info_id'];?>"><button class="agdBtn btn btn-xs btn-success" ><i class="fa fa-pencil-square-o"></i> <?php echo $words['DTL_0092'];?></button></a> -->
										<a href="<?php echo site_url('member/trainer_agenda/company_accept')?>/<?php echo $agenda['agenda_id'];?>" class="btn btn-xs btn-success notificationBtns confirm_agenda" onclick="return confirm('Are you sure you want to accept this agenda?');"></i><?php echo $words['DTL_0355'];?></a>
										<a href="<?php echo site_url('member/trainer_agenda/company_reject')?>/<?php echo $agenda['agenda_id'];?>" class="btn btn-xs btn-primary notificationBtns reject_agenda" onclick="return confirm('Are you sure you want to reject this agenda?');"><?php echo $words['DTL_0214'];?></a>
								<?php }
								elseif($agenda['is_appointed'] == 0 && $agenda['is_confirm']==0){ ?>
									<span class="label label-primary"><?php echo $words['DTL_0215'];?></span>
								<?php }
								elseif($agenda['is_confirm']==1 && $agenda['is_appointed']==1){
								?>
									<span class="label label-success"><?php echo $words['DTL_0001'];?></span>
								<?php }
									elseif($agenda['is_confirm'] == 0 && $agenda['is_appointed'] == 1)
									{?>
										<span class="label label-default"><?php echo $words['DTL_0167'];?></span>
									<?php
									}
								?>
						</td>
		              </tr>
<?php
		}
		$count++;
	}
	
} 
else {
?>
<div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
       <?php echo $words['DTL_0161'];?>
      </div>

<?php }?>

    </tbody>
		          </table>
<?php echo $pagination;?>
		          </div> <!-- /.table-responsive -->

