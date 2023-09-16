      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $data['group_title']; ?></h4>
      </div>
      <form method="POST" action="">
        <div class="modal-body">

          <div class="box-body">

            <div class="form-group">
              <label for="exampleInputEmail1"><i class="fa fa-users"></i> Name</label>
              <input name="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
            </div>

          </div>
          <!-- /.box-body -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
          <button type="submit" name="submitGroup" class="btn btn-primary">Save</button>
        </div>
      </form>