<?php include_once VIEW_PATH . '_common/header.php'; ?>
<style>
  .statistics_table td {
    padding: 18px 8px !important;
  }

  .statistics_table tr>td:nth-child(2) {
    text-align: right;
  }

  .statistics_table td>div {
    white-space: nowrap;
  }
</style>

<div class="wrapper">

  <?php include_once VIEW_PATH . '_common/admin_top.php'; ?>
  <?php include_once VIEW_PATH . '_common/navigation.php'; ?>

  <div class="content-wrapper">

    <section class="content-header">
      <h1><?= $data['page_title'] ?></h1>
      <ol class="breadcrumb">
        <li><a href="<?= APP_URL ?>/account/onAuthenticate"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?= $data['page_title'] ?></li>
      </ol>
    </section>

    <section class="content">

      <?php $this->getAlert(); ?>


      <form method="POST" enctype="multipart/form-data" action="<?php APP_URL ?>/admin/customers/">
        <div style="margin-top: 20px;" class="row">
          <div class="col-md-6">
            <div class="box box-widget">
              <div class="box-header with-border">
                <h3 class="box-title">Customer Info</h3>
              </div>

              <div class="box-body">
                <div class="row">

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input value="" name="name" type="text" class="form-control" placeholder="Enter Name">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input value="" name="email" type="email" class="form-control" placeholder="Enter Email">
                    </div>
                  </div>

                </div>

                <div class="form-group">
                  <label for="phone">Phone</label>
                  <input value="" name="phone" type="text" class="form-control" placeholder="Enter Phone">
                </div>

                <div class="form-group">
                  <label for="opening_balance">Opening Balance</label>
                  <input value="0" min="0" name="opening_balance" type="number" class="form-control" placeholder="Opening Balance">
                </div>

              </div>
              <!-- /.box-body -->
            </div>
          </div>

          <div class="col-md-6">
            <div class="box box-widget">
              <div class="box-header with-border">
                <h3 class="box-title">Address</h3>
              </div>

              <div class="box-body">
                <div class="row">

                  <div class="col-md-6">
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
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="state">State</label>
                      <select name="state" class="form-control select2 state" data-child-class="city" data-method-name="getCities">

                      </select>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="city">City</label>
                      <select name="city" class="form-control select2 city">

                      </select>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="zip">ZIP Code</label>
                      <input type="text" class="form-control" name="zip" placeholder="Zip">
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="address">Address</label>
                      <textarea name="address" class=" form-control" rows="1"></textarea>
                    </div>
                  </div>



                </div>
                <!-- /.box-body -->

              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <input type="submit" class="btn bg-color" value="Submit">
            </div>
          </div>

        </div>
      </form>





    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>

  <script>
    $('.select2').select2({

      placeholder: 'Select an option',
      "language": {
        "noResults": function() {
          return "First, select it's parent place"
        }
      },

    });

    $(document).ready(function() {

      $("select").change(function() {

        let place_id = $(this).find(":selected").val();

        let childClass = $(this).data().childClass;
        let methodName = $(this).data().methodName;

        if (childClass == "state") {
          $('.city').html(' <option value=""></option>');
        }

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

    })
  </script>