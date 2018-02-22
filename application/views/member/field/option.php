      <div class="row">

        <div class="col-md-12">

          <div class="content-header">
          <h2 class="content-header-title"><?php echo $words['DTL_0032'];?></h2>
              <ol class="breadcrumb">
              <li><a href="<?php echo site_url('admin/home') ?>">Home</a></li>
              <li><a href="<?php echo site_url('admin/field') ?>">Fields</a></li>
              <li class="active"><?php echo $words['DTL_0032'];?></li>
              </ol>
          </div>

       
            <div class="heading-wrapper">
              <h3 class="heading">
               
                <?php echo $words['DTL_0032'];?>: <?php echo $question_name['name'];?>
              </h3>
			
                  <a href="<?php echo site_url('admin/field/add_option')?>/<?php echo $question_id?>"><button type="button" id="add_new_question" class="btn btn-secondary pull-right-md"><?php echo $words['DTL_0016'];?>&nbsp<i class="fa fa-plus-circle"></i></button></a>
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
                      <th data-direction="asc" data-filterable="false" data-sortable="true"><?php echo $words['DTL_0176'];?></th>
					  <th><?php echo $words['DTL_0002'];?></th>
                    </tr>
                  </thead>
                  <tbody>
					<?php $count = 1;
						foreach($options as $option){?>
						<tr>
						  <td><?php echo $count;?></td>
						  <td><?php echo $option['value'];?></td>
						  <td>
							  <a href="<?php echo site_url('admin/field/edit_option_page')?>/<?php echo $option['id']?>"><button class="btn btn-xs btn-secondary"><i class="fa fa-pencil"></i></button></a>
							  &nbsp;
                <?php if($option_count != 1){?>
							  <a href="<?php echo site_url('admin/field/delete_option')?>/<?php echo $option['id']?>" onclick="return confirm('Are you sure you want to delete option');"><button class="btn btn-xs btn-primary"><i class="fa fa-trash-o"></i></button></a>
                <?php }?>
						   </td>
						</tr>
					<?php $count++;
					} ?>
                  </tbody>
                </table>
              </div> <!-- /.table-responsive -->
              


        

        </div> <!-- /.col -->

      </div> <!-- /.row -->