      <div class="row">

        <div class="col-md-12">

           <div class="content-header">
          <h2 class="content-header-title"><?php echo $words['DTL_0117'];?></h2>
              <ol class="breadcrumb">
              <li><a href="<?php echo site_url('admin/home') ?>"><?php echo $words['DTL_0122'];?></a></li>
              <li class="active"><?php echo $words['DTL_0117'];?></li>
              </ol>
          </div>

        
         <div class="heading-wrapper">
              <h3 class="heading">
               
                <?php echo $words['DTL_0033'];?>
              </h3>
			
             
                  <a href="<?php echo site_url('admin/field/add_question')?>"><button type="button" id="add_new_question" class="btn btn-secondary pull-right-md"><?php echo $words['DTL_0024'];?> &nbsp<i class="fa fa-plus-circle"></i></button></a>
              
					</div>


          <form class="form-group user-search filter_block" method="post" action="<?php echo site_url('admin/field')?>" >



        <div class="row">

              <div class="col-md-4">

                <div class="form-group">
                   <label><?php echo $words['DTL_0206'];?>: </label> <input type="text" id="" name="searchByQuestion" value="<?php //if($this->session->userdata('searchByName')!=''){ echo $this->session->userdata('searchByName');}?>" class="form-control">
                </div>

              </div>


               <div class="col-md-4">
                  <div class="form-group">
                 <label><?php echo $words['DTL_0276'];?></label>

                    <select id=""  name="searchByType" class="form-control">
                    <option value=''>All</option>
                    <?php foreach($field_types as $field_type){?>
                      <option value="<?php echo $field_type['id']?>" ><?php echo $field_type['type_name']?></option>
                    <?php }?>
                    </select>

              </div>
              </div>


              <div class="col-md-4">
                  <div class="form-group">
                 <label><?php echo $words['DTL_0234'];?></label>

                    <select id=""  name="searchBySection" class="form-control">
                    <option value=''>All</option>
                    <?php foreach($sections as $section){?>
                      <option value="<?php echo $section['id']?>" ><?php echo $section['section_name']?></option>
                    <?php }?>
                    </select>

              </div>
              </div>
               

            </div>



      <div class="notification_filter form-group">
      
           <div class="row">
               <div class="col-md-12">

               
                   <div class="row">
                 
                    <div class="col-md-4">
                        <div class="form-group">
                      <label><?php echo $words['DTL_0218'];?></label>

                    <select id=""  name="searchByRequired" class="form-control">
                      <option value=''>All</option>
                      <option value="1" ><?php echo $words['DTL_0295'];?></option>
                      <option value="0" ><?php echo $words['DTL_0160'];?></option>
                    </select>

                    </div>
                    </div>
                    
                     <div class="col-md-4">
      
                            <div class="form-group">
                      <label><?php echo $words['DTL_0140'];?></label>

                    <select id=""  name="per_page" class="form-control">
                      <option value="10" <?php if($this->session->userdata('per_page') == 10){ ?>selected = "selected"<?php } ?>>10</option>
                      <option value="20" <?php if($this->session->userdata('per_page') == 20){ ?>selected = "selected"<?php } ?>>20</option>
                      <option value="50" <?php if($this->session->userdata('per_page') == 50){ ?>selected = "selected"<?php } ?>>50</option>
                      <option value="100" <?php if($this->session->userdata('per_page') == 100){ ?>selected = "selected"<?php } ?>>100</option>
                      <option value="500" <?php if($this->session->userdata('per_page') == 500){ ?>selected = "selected"<?php } ?>>500</option>
                    </select>

                  </div>
                  </div>
            </div>
               
             
            </div>
            </div>
            </div>
     
     
      
    

               <input type="submit" name="submit" id="submit_btn" class="btn btn-primary">
     
      </form>
        

         
<?php if(!empty($questions)){?>
              <div class="table-responsive">

              <table 
                class="table table-striped table-bordered table-hover table-highlight table-checkable" 
                data-provide="datatable" 
                data-display-rows="10"
                data-info="true"
                data-search="true"
                data-length-change="true"
                data-paginate="true"
              >
                  <thead>
                    <tr>
                      <th data-filterable="false" data-sortable="true" data-direction="asc">#</th>
                      <th data-direction="asc" data-filterable="false" data-sortable="true"><?php echo $words['DTL_0206'];?></th>
                      <th data-filterable="false" data-sortable="true"><?php echo $words['DTL_0276'];?></th>
                      <th data-filterable="false" data-sortable="true"><?php echo $words['DTL_0234'];?></th>
                      <th data-filterable="false"><?php echo $words['DTL_0218'];?></th>
					  <th><?php echo $words['DTL_0002'];?></th>
                    </tr>
                  </thead>
                  <tbody>
					<?php $count = 1;
						foreach($questions as $question){?>
						<tr>
						  <td><?php echo $count;?></td>
						  <td>
						  <?php if($question['type_id']!=1){?>
						  <a href="<?php echo site_url('admin/field/option');?>/<?php echo $question['question_id'];?>">
						  <?php echo $question['name'];?>
						  </a>
						  <?php }
						  else{?>
						  <?php echo $question['name'];?>
						  <?php }?>
						  </td>
						  <td><?php echo $question['type_name'];?></td>
						  <td><?php echo $question['section_name'];?></td>
						  <td><?php if($question['is_required']==1){?>
								<?php echo $words['DTL_0295'];?>
							  <?php }
							  else{?>
								<?php echo $words['DTL_0160'];?>
							  <?php }?>
						  </td>
						  <td class="double_icons">
							  <a href="<?php echo site_url('admin/field/edit_question_page')?>/<?php echo $question['question_id']?>"><button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button></a>
							  &nbsp;
							  <a href="<?php echo site_url('admin/field/delete_question')?>/<?php echo $question['question_id']?>" onclick="return confirm('Are you sure you want to delete question');"><button class="btn btn-xs btn-primary"><i class="fa fa-trash-o"></i></button></a>
							</td>
						</tr>
					<?php $count++;
					}?>
                  </tbody>
                </table>

              </div> <!-- /.table-responsive -->
              
              <?php echo $pagination;?>
<?php }
else{?>

      <div class="alert alert-danger">
        <a class="close" data-dismiss="alert" href="#" aria-hidden="true">×</a>
        No Questions
      </div>
<!-- No Questions -->
<?php }?>
        

        </div> <!-- /.col -->

      </div> <!-- /.row -->