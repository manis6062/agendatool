<div class="content-header">
	<h2 class="content-header-title"><?php echo $words['DTL_0351'];?></h2>
		<ol class="breadcrumb">
          <li><a href="<?php echo site_url('member/profile') ?>"><?php echo $words['DTL_0205'];?></a></li>
          <li class="active"><?php echo $words['DTL_0351'];?></li>
        </ol>
</div>


<form class="form-group user-search filter_block" method="post" action="<?php echo site_url('member/trainer_agenda/pending_agenda')?>/<?php echo $this->session->userdata('general_id')?>" >



        <div class="row">

              <div class="col-md-4">

                <div class="form-group">
                   <label><?php echo $words['DTL_0040'];?>: </label> <input type="text" id="" name="searchByAppointment" value="<?php if($this->session->userdata('searchByAppointment')!=''){ echo $this->session->userdata('searchByAppointment');}?>" class="form-control">
                </div>

              </div>


               <div class="col-md-4">

                <div class="form-group">
                 <label> <?php echo $words['DTL_0063'];?>: </label><input type="text" id="" name="searchByCompany" value="<?php if($this->session->userdata('searchByCompany')!=''){ echo $this->session->userdata('searchByCompany');}?>" class="form-control"> 
                </div>

              </div>



               <div class="col-md-4">

                <div class="form-group">
                  <label> <?php echo $words['DTL_0142'];?>: </label><input type="text" id="" name="searchByLocation" value="<?php if($this->session->userdata('searchByLocation')!=''){ echo $this->session->userdata('searchByLocation');}?>" class="form-control"> 
                </div>

              </div>

            </div>
             <div class="form-group double-input">
             <div class="row">
          
             

              <div class="col-sm-12">
              	  <label>Select Date:</label> 
                  <div class="row">
                      <div class="col-md-4">
                        <input class="form-control" type="text" placeholder="Start date" id="dpStart" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="from_date" value="<?php if($this->session->userdata('from_date')!=''){ echo $this->session->userdata('from_date');}?>">
                      </div>

                      <div class="col-md-4">
                        <input class="form-control" type="text" placeholder="End date" id="dpEnd" data-date-format="yyyy-mm-dd" data-date-autoclose="true" name="to_date" value="<?php if($this->session->userdata('to_date')!=''){ echo $this->session->userdata('to_date');}?>">
                      </div>
                  </div>
              </div> <!-- /.col -->
            </div>



        </div> <!-- /.row -->

      
      

<div class="notification_filter form-group ">

		
	 
                   <div class="row">
                  <div class="col-md-4">
                  	<div class="form-group">
		
		  <label> <?php echo $words['DTL_0178'];?> </label>
		
		  <select id=""  name="order" class="form-control">
				<option value="DESC" <?php if($order == 'DESC'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0139'];?></option>
				<option value="ASC" <?php if($order == 'ASC'){ ?>selected = "selected"<?php } ?>><?php echo $words['DTL_0173'];?></option>
		  </select>
		
 </div>
 </div>
                     <div class="col-md-4">
                     	<div class="form-group">
		 <label>  <?php echo $words['DTL_0140'];?> </label>

		  <select id=""  name="per_page" class="form-control" >
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

		        <input type="submit" name="submit" id="submit_btn" class="btn btn-primary">
		 
      </form>
<?php if(!empty($agendas)){?>
	<div class="table-responsive">
		<table class="table table-highlight table-bordered table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th><?php echo $words['DTL_0040'];?>:</th>
					<th><?php echo $words['DTL_0063'];?>:</th>
					<th><?php echo $words['DTL_0002'];?></th>
					<th width="200"><?php echo $words['DTL_0253'];?></th>
				</tr>
			</thead>
		    <tbody>
			<?php
				$count = 1;
				foreach($agendas as $agenda){
					if($agenda['appoint_date'] < date('Y-m-d')){
				?>



					<tr>
						<td><?php echo $count;?></td>
						<td><?php echo $agenda['appointment_name'];?></td>
						<td><?php echo $agenda['company_detail']['name'];?></td>
						<td class="double_icons">
							<button class="agdBtn btn btn-xs btn-info" data-toggle="modal" data-target="#notificationModal" agenda_id="<?php echo $agenda['agenda_id']?>"><i class="fa fa-eye"></i></button>
							<?php if($agenda['is_appointed']!=0 && $agenda['is_confirm']!=1){?>
								<!--				<a href="--><?php //echo site_url('member/trainer_agenda/edit_agenda').'/'.$userdata['id'].'/'.$agenda['agenda_id'];?><!--"><button class="agdBtn btn btn-xs btn-secondary" ><i class="fa fa-pencil"></i></button></a>-->

								<a href="<?php echo site_url('member/trainer_agenda/get_trainer_agenda').'/'.$userdata['id'];?>"><button class="agdBtn btn btn-xs btn-secondary" ><i class="fa fa-pencil"></i></button></a>
							<?php }?>
						</td>
						<td class="double_status">
							<?php if($agenda['is_appointed']==0){?>
								<span class="label label-primary"><?php echo $words['DTL_0215'];?></span>
							<?php }
							else {
								if($agenda['is_confirm']==1){
							?>
								<span class="label label-success"><?php echo $words['DTL_0001'];?></span>
							<?php }
							else{?>
								<span class="label label-default"><?php echo $words['DTL_0167'];?></span>
							<?php
							}
							}?>

						</td>

					</tr>
			<?php }
				else {?>
					<tr>
							<td><?php echo $count;?></td>
							<td><?php echo $agenda['appointment_name'];?></td>
							<td><?php echo $agenda['company_detail']['name'];?></td>
							<td class="double_icons"><button class="agdBtn btn btn-xs btn-info" data-toggle="modal" data-target="#notificationModal" agenda_id="<?php echo $agenda['agenda_id']?>"><i class="fa fa-eye"></i></button>
							<?php if(($agenda['is_appointed']!=0 && $agenda['is_confirm']!=1)){?>
			<!--				<a href="--><?php //echo site_url('member/trainer_agenda/edit_agenda').'/'.$userdata['id'].'/'.$agenda['agenda_id'];?><!--"><button class="agdBtn btn btn-xs btn-secondary" ><i class="fa fa-pencil"></i></button></a>-->
							<a href="<?php echo site_url('member/trainer_agenda/get_trainer_agenda').'/'.$userdata['id'];?>"><button class="agdBtn btn btn-xs btn-secondary" ><i class="fa fa-pencil"></i></button></a>
							<?php }?>
							</td>
							<td class="double_icons">
								<?php if($agenda['is_appointed']==0){?>
									<span class="label label-danger"><?php echo $words['DTL_0215'];?></span>
								<?php }
								else {
									if($agenda['is_confirm']==1){?>
										<span class="label label-success"><?php echo $words['DTL_0001'];?></span>
									<?php }
									else{?>
										<a href="<?php echo site_url('member/trainer_agenda/accept')?>/<?php echo $agenda['agenda_id'];?>" class="btn btn-xs btn-success notificationBtns confirm_agenda" onclick="return confirm('Are you sure you want to accept this agenda?');"><?php echo $words['DTL_0355'];?></a>
									
										<a href="<?php echo site_url('member/trainer_agenda/reject')?>/<?php echo $agenda['agenda_id'];?>" class="btn btn-xs btn-danger notificationBtns reject_agenda" onclick="return confirm('Are you sure you want to reject this agenda?');"><?php echo $words['DTL_0214'];?></a>
									<?php
									}
								}?>
							</td>
					</tr>
			<?php
					}
					$count++;
				}?>
		</tbody>
    </table>
	<?php
	echo $pagination;
	}
	else {?>
		<div class="alert alert-danger">
			<a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
			<?php echo $words['DTL_0161'];?>!
		</div>
	<?php }?>
	</div>