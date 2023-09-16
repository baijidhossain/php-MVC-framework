<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">×</span></button>
  <h4 class="modal-title"><?= $this->data['modal_title']; ?></h4>
</div>


<form action="<?= APP_URL ?>/admin/comments/edit/<?= $this->data['comment']['id'] ?? "" ?>" method="post">

  <?= $this->request->verifier ?>

  <div class="modal-body">

    <div class="mb-3">

      <label for="comment" class="form-label">Comment</label>
      <textarea name="comment" class="form-control" rows="3"><?= $this->data['comment']['comment'] ?? "" ?></textarea>

    </div>

    <div class="mb-3">

      <label for="status" class="form-label">Status</label>
      <select name="status" class="form-control">
        <option <?= in_array($this->data['comment']['status'], [0, 1]) && $this->data['comment']['status'] == 0 ? 'selected' : '' ?> value="0">Unpublished</option>
        <option <?= in_array($this->data['comment']['status'], [0, 1]) && $this->data['comment']['status'] == 1 ? 'selected' : '' ?> value="1">Published</option>
      </select>

    </div>

  </div>

  <div class="modal-footer">
    <button type="button" class="btn  btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn  btn-primary" name="save">Update</button>
  </div>

</form>