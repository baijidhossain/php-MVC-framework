<?php 
if ( "Add" == $this->data['action'] ) { ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">Add Tag</h4>
    </div>
    <form action="<?php APP_URL ?>/admin/Tag/Add" method="POST">
        <div class="modal-body row">
            <div class="col-md-10 col-md-offset-1">
                <div class="form-group">
                    <label for="name"><i class="fa fa-tag" style="margin-right: 2px;"></i> Tag Name</label>
                    <input type="text" class="form-control" name="name" id="" required>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="add" class="btn btn-primary">Save</button>
        </div>
    </form>

	<?php
} elseif ( "Edit" == $this->data['action'] ) { ?>


    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Update Tag</h4>
    </div>
    <form action="<?php APP_URL ?>/admin/Tag/Update" method="POST">
        <div class="modal-body row">
            <div class="col-md-10 col-md-offset-1">
                <div class="form-group">
                    <label for="name"><i class="fa fa-tag" style="margin-right: 2px;"></i> Tag Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $this->data['edit']['name'] ?>" id="" required>
                    <input type="hidden" name="id" value="<?php echo $this->data['edit']['id'] ?>">
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </div>
    </form>

	<?php
} 
?>
