<?php


if ($data['action'] == "itemUpdate") { ?>

  <div class="modal-header">
    <?php $this->getAlert(); ?>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $data['modal_title']; ?></h4>
  </div>
  <form onsubmit method="POST" action="<?= APP_URL ?>/admin/purchase_return/invoice/itemUpdate">
    <div class="modal-body">

      <div class="box-body">

        <div class="row">
          <input type="text" hidden name="id" value="<?= $data['item']['id'] ?? '' ?>">

          <div class="col-md-6">
            <div class="form-group">
              <label for="purchase_price"><i class="fa fa-shopping-bbuilding"></i>Purchase Price</label>
              <input name="purchase_price" type="text" class="form-control purchase_price" value="<?= $data['item']['purchase_price'] ?? '' ?>" placeholder="Enter Seling Price">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="qty"><i class="fa fa-shopping-bbuilding"></i> Qty</label>
              <input name="qty" type="text" class="form-control qty" value="<?= $data['item']['qty'] ?? '' ?>" placeholder="Enter Qty">
            </div>
          </div>


          <div class="col-md-6">
            <div class="form-group">
              <label for="tax">Tax</label>

              <select class="form-control select2 tax" name="tax">
                <option value="0">None</option>
                <?php
                foreach ($data['taxs'] as $tax) { ?>
                  <option <?= $tax['tax'] == $data['item']['tax'] ? 'selected' : '' ?> value=" <?= $tax['tax']; ?>">
                    <?= $tax['name']; ?>(<?= $tax['tax']; ?>)
                  </option>
                <?php
                }
                ?>

              </select>
            </div>
          </div>


          <div class="col-md-6">
            <div class="form-group">
              <label for="tax_type">Tax Type </label>
              <select class="form-control select2 tax_type" name="tax_type">

                <option value="exclusive" <?= $data['item']['tax_type'] == 'exclusive' ? 'selected' : ''  ?>>Exclusive</option>
                <option value="inclusive" <?= $data['item']['tax_type'] == 'inclusive' ? 'selected' : ''  ?>>Inclusive</option>
              </select>
            </div>
          </div>


          <div class="col-md-6">
            <div class="form-group">
              <label for="discount">Discount </label>
              <input name="discount" class="form-control discount" value="<?= $data['item']['discount'] ?? '' ?>" type="text">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="discount_type">Discount Type </label>
              <select class="form-control select2 discount_type" name="discount_type">

                <option value="percent" <?= $data['item']['discount_type'] == 'percent' ? 'selected' : ''  ?>>Percentage</option>
                <option value="fixed" <?= $data['item']['discount_type'] == 'fixed' ? 'selected' :  ''  ?>>Fixed</option>
              </select>
            </div>
          </div>

        </div>

      </div>
      <!-- /.box-body -->
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
      <button type="submit" class="btn bg-color edititem">Save</button>
    </div>
  </form>

<?php } ?>


<?php
if ($data['action'] == "paynow") { ?>

  <div class="modal-header">
    <?php $this->getAlert(); ?>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $data['modal_title']; ?></h4>
  </div>

  <div class="modal-header">

    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        Supplier Information
        <address>
          Name: <?= $data['supplire']['name'] ?? '' ?>
          <br>
          Phone: <?= $data['supplire']['phone'] ?? '' ?>
          <br>
          Email: <?= $data['supplire']['email'] ?? '' ?>
          <br>
          Address: <?= $data['supplire']['address'] ?? '' ?>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        Purchase Information:
        <address>
          <b>Invoice #<?= $data['purchaseivoice']['bill_no'] ?? '' ?></b><br>
          <b>Date :<?= date_create($data['purchaseivoice']['purchase_date'] ?? "")->format('d M, Y')  ?></b><br>
          <b>Grand Total :<?= $data['purchaseivoice']['grandtotal'] ?? '' ?></b><br>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Paid Amount :<span><?= $data['purchaseivoice']['paid'] ?? '' ?></span></b><br>
        <b>Due Amount :<span id="due_amount_temp"><?= $data['purchaseivoice']['due'] ?? '' ?></span></b><br>

      </div>
      <!-- /.col -->
    </div>

  </div>

  <form onsubmit method="POST" action="<?= APP_URL ?>/admin/purchase_return/paynow/<?= $data['purchaseivoice']['id'] ?? '' ?>/<?= $data['supplire']['id'] ?? '' ?>">
    <div class="modal-body">

      <div class="box-body">

        <div class="row">
          <input type="text" hidden name="id" value="<?= $data['purchaseivoice']['id'] ?? '' ?>">


          <div class="col-md-4">

            <div class="form-group">
              <label for="date"><i class="fa fa-shopping-bbuilding"></i>Date</label>
              <input name="date" type="date" class="form-control dat" value="<?= date_create()->format('Y-m-d') ?>">
            </div>

          </div>

          <div class="col-md-4">

            <div class="form-group">
              <label for="paid_amount"><i class="fa fa-shopping-bbuilding"></i>Amount</label>
              <input name="paid_amount" type="text" class="form-control paid_amount" value="<?= $data['purchaseivoice']['due'] ?? '' ?>" placeholder="Amount">
            </div>

          </div>

          <div class="col-md-4">

            <div class="form-group">
              <label for="payment_type"><i class="fa fa-shopping-bbuilding"></i> Payment Type</label>
              <select name="payment_type" class="form-control">
                <option value="1">Cash</option>
                <option value="2">Bank</option>
              </select>
            </div>

          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="payment_note"><i class="fa fa-shopping-bbuilding"></i>Payment Note</label>

              <textarea name="payment_note" rows="2" class="form-control"></textarea>
            </div>
          </div>


        </div>

      </div>
      <!-- /.box-body -->
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
      <button type="submit" class="btn bg-purple edititem">Save</button>
    </div>
  </form>


<?php } ?>

<!-- View Payment -->
<?php
if ($data['action'] == "payment_view") { ?>


  <div class="modal-header">
    <?php $this->getAlert(); ?>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $data['modal_title']; ?></h4>
  </div>

  <div class="modal-header">

    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        Supplier Information
        <address>
          Name: <?= $data['supplire']['name'] ?? '' ?>
          <br>
          Phone: <?= $data['supplire']['phone'] ?? '' ?>
          <br>
          Email: <?= $data['supplire']['email'] ?? '' ?>
          <br>
          Address: <?= $data['supplire']['address'] ?? '' ?>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        Purchase Information:
        <address>
          <b>Invoice #<?= $data['purchaseinvoice']['bill_no'] ?? '' ?></b><br>
          <b>Date :<?= date_create($data['purchaseinvoice']['purchase_date'] ?? "")->format('d M, Y')  ?></b><br>
          <b>Grand Total :<?= $data['purchaseinvoice']['grandtotal'] ?? '' ?></b><br>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Paid Amount :<span><?= $data['purchaseinvoice']['paid'] ?? '' ?></span></b><br>
        <b>Due Amount :<span id="due_amount_temp"><?= $data['purchaseinvoice']['due'] ?? '' ?></span></b><br>

      </div>
      <!-- /.col -->
    </div>

  </div>

  <div class="modal-body">
    <p>Payment Information:</p>
    <div class="row">
      <div class="col-md-12">
        <div class=" table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr class=" ">
                <th>#</th>
                <th>Payment Date</th>
                <th>Created by</th>
                <th>Payment Type</th>
                <th>Payment Note</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

              <?php

              $i = 1;
              $total = 0;
              if (empty($data['paymentinfo'])) {
                echo '<tr> <td colspan="10" class="text-center" >No Recorded Found</td> </tr>';
              } else {
                foreach ($data['paymentinfo'] as $key => $payinfo) { ?>

                  <tr>
                    <td class=" text-center"><?= $i ?></td>
                    <td class=" text-center"><?= date_create($payinfo['created'])->format('d M, Y') ?></td>

                    <td class=" text-center"><?= $payinfo['user'] ?></td>
                    <td class=" text-center"><?= $payinfo['payment_type'] == 1 ? 'Cash' : 'Bank' ?></td>
                    <td class=" text-center"><?= $payinfo['payment_note'] ?></td>
                    <td class=" text-center"><?= $payinfo['payment_amount'] ?></td>
                    <td class=" text-center"><a href="javascript:paymentDelete(<?= $payinfo['id'] ?>)"><i class="fa fa-trash text-danger"></i> </a></td>

                  </tr>

              <?php $i++;
                  $total += $payinfo['payment_amount'] ?? 0;
                }
              }
              ?>

            </tbody>

            <tfoot class="bg-gray">
              <tr>
                <td colspan="5" class="text-right text-bold">Total</td>
                <td><?= $total ?></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="clearfix"></div>
    </div>

  </div>



<?php } ?>


<script>
  $(document).ready(function() {

    $('.select2').select2({
      width: "100%",
      placeholder: "Please select an option",

    });
  })
</script>