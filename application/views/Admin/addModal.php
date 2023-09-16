      <div class="modal-header">
        <?php $this->getAlert(); ?>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $data['modal_title']; ?></h4>
      </div>
      <form method="POST" action="">
        <div class="modal-body">

          <div class="box-body">

            <div class="form-group">
              <label for="exampleInputEmail1"><i class="fa fa-user"></i> Name</label>
              <input name="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1"><i class="fa fa-envelope"></i> Email</label>
              <input name="email" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Email">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1"><i class="fa fa-phone"></i> Phone</label>
              <input name="phone" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Phone">
            </div>

            <div class="form-group">
              <label for="exampleInputEmail1"><i class="fa fa-map-marker"></i> Address</label>
              <input name="address" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Address">
            </div>

            <div class="form-group">
              <label for="group"><i class="fa fa-users"></i> Group</label>
              <select class=" form-control select2" multiple="multiple" name="groups[]" id="" multiple>
                <option value="" disabled>-Select-</option>
                <?php
                foreach ($data['groups'] as $groups) { ?>
                  <option class="bg-primary" value="<?php echo $groups['id']; ?>"><?php echo $groups['name']; ?> </option>
                <?php
                }
                ?>
              </select>
            </div>

          </div>
          <!-- /.box-body -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
          <button type="submit" name="add" class="btn btn-primary">Save</button>
        </div>
      </form>



      <script>
        $(function() {

          $('.select2').select2({
            width: "100%",

          });


        });
      </script>