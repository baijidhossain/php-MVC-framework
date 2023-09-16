      <!-- Add category -->
      <?php
      if ($data['action'] == "add") { ?>

        <div class="modal-header">
          <?php $this->getAlert(); ?>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">
            <?php echo $data['modal_title'] ?></h4>
        </div>
        <form method="POST" action="<?= APP_URL?>/admin/category/add">
          <div class="modal-body">

            <div class="box-body">
              <div class="form-group">
                <label for="name"><i class="fa fa-shopping-bbuilding"></i> Category Name</label>
                <input name="name" type="text" class="form-control" id="name" placeholder="Enter Name">
              </div>
            </div>

            <!-- /.box-body -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
            <button type="submit" name="addCategory" class="btn btn-primary">Save</button>
          </div>
        </form>
      <?php
      }
      ?>
      <!-- end add category -->





      <!-- edit category -->
      <?php
      if ($data['action'] == "edit") { ?>

        <div class="modal-header">
          <?php $this->getAlert(); ?>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">
            <?= $data['modal_title'] ?></h4>
        </div>
        <form method="POST" action="<?= APP_URL?>/admin/category/update">
          <div class="modal-body">
            <div class="box-body">
              <input type="text" hidden name="id" value="<?= $data['category']['id'] ?>">
              <div class="form-group">
                <label for="name"><i class="fa fa-shopping-bbuilding"></i> Category Name</label>
                <input value="<?php echo $data['category']['name'] ?>" name="name" type="text" class="form-control" id="name" placeholder="Enter Name">
              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
            <button type="submit" name="addCategory" class="btn bg-color">Save</button>
          </div>
        </form>

      <?php
      }
      ?>
      <!-- end edit category -->