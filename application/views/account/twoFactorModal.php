<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4 class="modal-title">Enter your password</h4>
</div>
<form action="" method="post" autocomplete="off">
    <div class="modal-body row">
        <div class="col-md-10 col-md-offset-1">
            <input type="hidden" name="2FA" value="<?= $data['type'] ?>">
            <div class="form-group">
                <label>Password:</label>
                <input type="password" pattern=".{8,}" class="form-control" name="password"
                       title="8 characters minimum" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
