      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $data['group_title'] ?></h4>
      </div>
      <form method="POST" action="<?php APP_URL ?>admin/contact_groups/update">
        <div class="box-body">
          <input type="text" hidden name="id" value="<?= $data['editdata'][0]['id'] ?>">
          <div class="form-group">
            <label for="exampleInputEmail1">Name</label>
            <input value="<?= $data['editdata'][0]['name'] ?>" name="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Group Name">
          </div>
        </div>
        <!-- /.box-body -->
        <div class="modal-footer">
          <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>