      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $data['modal_title']; ?></h4>
      </div>
      <form method="POST" action="<?php APP_URL ?>/admin/contacts/update" enctype="multipart/form-data">
        <div class="box-body">
          <input hidden value="<?= (isset($data)) ? $data['editdata']['id'] : ''; ?>" type="text" name="id">
          <div class="form-group">
            <label for="exampleInputEmail1">Name</label>
            <input value="<?= $data['editdata']['name']; ?>" name="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Name">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Email</label>
            <input value="<?= $data['editdata']['email']; ?>" name="email" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Email">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Phone</label>
            <input value="<?= $data['editdata']['phone']; ?>" name="phone" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Phone">
          </div>

          <div class="form-group">
            <label for="group">Group</label>
            <select class="form-control select2" name="groups[]" id="" multiple>
              <option disabled value=""> -select- </option>
              <?php
              foreach ($data['group'] as $group) {
                $selected = "";
                foreach ($data['relation'] as $relations) {
                  if ($relations['group_id'] == $group['id']) {
                    $selected = "selected";
                  }
                }
              ?>
                <option <?= $selected; ?> value="<?php echo $group['id'] ?>"><?php echo $group['name'] ?></option>
              <?php
              }
              ?>
            </select>

          </div>


          <div class="form-group">
            <label for="exampleInputEmail1">Photo</label>
            <input value="<?= $data['editdata']['photo']; ?>" name="photo" type="file" class="form-control" id="photo" placeholder="Enter photo">
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>