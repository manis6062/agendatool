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
						  <a href="<?php echo site_url('admin/field/option');?>/<?php echo $question['id'];?>">
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
						  <td>
							  <a href="<?php echo site_url('admin/field/edit_question_page')?>/<?php echo $question['id']?>"><button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button></a>
							  &nbsp;
							  <a href="<?php echo site_url('admin/field/delete_question')?>/<?php echo $question['id']?>" onclick="return confirm('Are you sure you want to delete question');"><button class="btn btn-xs btn-primary"><i class="fa fa-trash-o"></i></button></a>
							</td>
						</tr>
					<?php $count++;
					}?>
                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->
              


        

        </div> <!-- /.col -->

      </div> <!-- /.row -->