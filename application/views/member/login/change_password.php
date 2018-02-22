

            <div class="tab-pane" id="password-tab">

              <h3 class=""><?php echo $words['DTL_0053'];?></h3>

              <br />

              <form action="<?php echo site_url('auth/change_password_function')?>" class="form-horizontal" method = "post">
			  
                <div class="form-group">

                  <label class="col-md-3"><?php echo $words['DTL_0158'];?></label>

                  <div class="col-md-7">
                    <input type="password" name="new_password" class="form-control" required />
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

                <div class="form-group">

                  <label class="col-md-3"><?php echo $words['DTL_0159'];?></label>

                  <div class="col-md-7">
                    <input type="password" name="confirm_password" class="form-control" required />
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

                <br />
				<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id?>" />

                <div class="form-group">

                  <div class="col-md-7 col-md-push-3">
                    <button type="submit" class="btn btn-primary"><?php echo $words['DTL_0224'];?></button>
                    &nbsp;
                    <button type="reset" class="btn btn-default"><?php echo $words['DTL_0045'];?></button>
                  </div> <!-- /.col -->

                </div> <!-- /.form-group -->

              </form>
            </div> <!-- /.tab-pane -->