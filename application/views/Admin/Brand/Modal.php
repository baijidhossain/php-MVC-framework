      <!-- add brand  -->
      <?php
      if (isset($data) && $data['action'] == "add") { ?>


        <div class="modal-header">
          <?php $this->getAlert(); ?>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">
            <?= $data['modal_title'] ?? "" ?>
          </h4>
        </div>
        <form method="POST" action="<?= APP_URL ?>/admin/brands/add">
          <div class="modal-body">

            <div class="box-body">

              <div class="form-group">
                <label for="name"><i class="fa fa-shopping-bbuilding"></i> Brand Name</label>
                <input name="name" type="text" class="form-control" id="name" placeholder="Enter Name">
              </div>

              <div class="form-group">
                <label for="address"><i class="fa fa-shopping-bbuilding"></i>Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
              </div>

            </div>
            <!-- /.box-body -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
            <button type="submit" name="addbrand" class="btn bg-color">Save</button>
          </div>
        </form>

      <?php
      }
      ?>
      <!-- end add brand -->



      <!-- edit brand -->
      <?php
      if (isset($data) && $data['action'] == "edit") { ?>

        <div class="modal-header">

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">
            <?= $data['modal_title'] ?? "" ?>
          </h4>
        </div>
        <form method="POST" action="<?= APP_URL ?>/admin/brands/update">
          <div class="modal-body">

            <input hidden value="<?= $data['brand']['id'] ?>" type="text" name="id" id="">

            <div class="box-body">
              <div class="form-group">
                <label for="name"><i class="fa fa-shopping-bbuilding"></i> Brand Name</label>
                <input value="<?php echo $data['brand']['name']; ?>" name="name" type="text" class="form-control" id="name" placeholder="Enter Name">
              </div>

              <div class="form-group">
                <label for="address"><i class="fa fa-shopping-bbuilding"></i>Address</label>
                <textarea name="address" class="form-control" rows="2"><?= $data['brand']['address']; ?></textarea>

              </div>

            </div>
            <!-- /.box-body -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
            <button type="submit" name="updatebrand" class="btn bg-color">Save</button>
          </div>
        </form>

      <?php
      }
      ?>
      <!-- end edit brand -->