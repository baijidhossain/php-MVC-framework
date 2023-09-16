     <!-- Add -->

     <?php
      if ($data['action'] == "add") { ?>

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
               <label for="exampleInputEmail1">Country Name</label>
               <input name="name" type="text" class="form-control" placeholder="Enter Country Name">
             </div>
           </div>
           <!-- /.box-body -->
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
           <button type="submit" name="addColor" class="btn bg-color">Save</button>
         </div>
       </form>

     <?php
      }
      ?>
     <!-- end add -->


     <!-- edit -->
     <?php
      if ($data['action'] == "edit") { ?>


       <div class="modal-header">
         <?php $this->getAlert(); ?>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title"><?php echo $data['modal_title']; ?></h4>
       </div>
       <form method="POST" action="<?= APP_URL ?>/admin/country/update/<?= $data['country']['id']; ?>">
         <div class="modal-body">

           <div class="box-body">

             <div class="form-group">
               <label for="name">Color Name</label>
               <input value="<?= $data['country']['name']; ?>" name="name" type="text" class="form-control" placeholder="Enter Name">
             </div>
           </div>
           <!-- /.box-body -->
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
           <button type="submit" name="updateColor" class="btn bg-color">Save</button>
         </div>
       </form>
     <?php
      }
      ?>
     <!-- end edit -->