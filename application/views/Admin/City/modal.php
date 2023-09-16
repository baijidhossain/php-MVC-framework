     <!-- Add -->

     <?php
      if ($data['action'] == "add") { ?>

       <div class="modal-header">
         <?php $this->getAlert(); ?>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title"><?= $data['modal_title']; ?></h4>
       </div>
       <form method="POST" action="">
         <div class="modal-body">

           <div class="box-body">




             <div class="form-group">
               <label for="country">Country</label>
               <select name="country" class="form-control select2 country" data-child-class="state" data-method-name="getStates">
                 <option value=""></option>
                 <?php

                  foreach ($data['countries'] as $country) { ?>

                   <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>

                 <?php    }

                  ?>
               </select>
             </div>


             <div class="form-group">
               <label for="state">State</label>
               <select name="state" class="form-control select2 state" data-child-class="city" data-method-name="getCities">

               </select>
             </div>



             <div class="form-group">
               <label for="name">City Name</label>
               <input name="name" type="text" class="form-control" placeholder="Enter City Name">
             </div>
           </div>
           <!-- /.box-body -->
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
           <button type="submit" class="btn bg-color">Save</button>
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
         <h4 class="modal-title"><?= $data['modal_title']; ?></h4>
       </div>
       <form method="POST" action="<?= APP_URL ?>/admin/city/update/<?= $data['city']['id']; ?>">
         <div class="modal-body">

           <div class="box-body">



             <div class="form-group">
               <label for="country">Country</label>
               <select name="country" class="form-control select2 country" data-child-class="state" data-method-name="getStates">
                 <option value=""></option>
                 <?php

                  foreach ($data['countries'] as $country) { ?>

                   <option value="<?= $country['id'] ?>"><?= $country['name'] ?></option>

                 <?php    }

                  ?>
               </select>
             </div>


             <div class="form-group">
               <label for="state">State</label>
               <select name="state" class="form-control select2 state" data-child-class="city" data-method-name="getCities">

               </select>
             </div>


             <div class="form-group">
               <label for="name"> Color Name</label>
               <input value="<?= $data['city']['name']; ?>" name="name" type="text" class="form-control" placeholder="Enter City Name">
             </div>
           </div>
           <!-- /.box-body -->
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
           <button type="submit" class="btn bg-color">Save</button>
         </div>
       </form>
     <?php
      }
      ?>
     <!-- end edit -->


     <script>
       $('.select2').select2({
         width: "100%",
       })




       $("select").change(function() {

         let place_id = $(this).find(":selected").val();

         let childClass = $(this).data().childClass;
         let methodName = $(this).data().methodName;

         console.log(methodName);

         $.ajax({
           type: "POST",
           url: "<?= APP_URL ?>/admin/customers/getPlace",
           data: {
             place_id: place_id,
             methodName: methodName
           },
           success: function(result) {
             console.log(result);
             $(`.${childClass}`).html(result);
           }
         });


       });
     </script>