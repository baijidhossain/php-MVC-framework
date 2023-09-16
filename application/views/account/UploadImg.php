<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h4 class="modal-title">Upload Image</h4>
</div>
<form action="" method="post" autocomplete="off" enctype="multipart/form-data">
  <div class="modal-body row">
    <div class="col-md-10 col-md-offset-1">
      <div class="form-group">
        <label for="profile-img">Select an image</label>
        <input class="form-control" name="profile-img" type="file" id="profile-img"
          accept="image/x-png,image/gif,image/jpeg" required>
        <input type="hidden" name="id" value="" id="id" required>
      </div>
      <div id="sizeinfo" class="text-red"></div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="submit" class="btn btn-primary">Upload</button>
  </div>
</form>