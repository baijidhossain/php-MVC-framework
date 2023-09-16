<?php


if ($data['action'] == "itemUpdate") { ?>

  <div class="modal-header">
    <?php $this->getAlert(); ?>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $data['modal_title']; ?></h4>
  </div>
  <form onsubmit method="POST" action="<?= APP_URL ?>/admin/purchase/invoice/itemUpdate">
    <div class="modal-body">

      <div class="box-body">

        <div class="row">
          <input type="text" hidden name="id" value="<?= $data['item']['id'] ?? '' ?>">

          <div class="col-md-6">
            <div class="form-group">
              <label for="purchase_price">Purchase Price</label>
              <input name="purchase_price" type="text" class="form-control purchase_price" value="<?= $data['item']['purchase_price'] ?? '' ?>" placeholder="Enter Seling Price">
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label for="qty"> Qty</label>
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
      <button type="submit" class="btn bg-color">Save</button>
    </div>
  </form>

<?php } ?>


<?php
if ($data['action'] == "pay_now") { ?>



  <div class="modal-header">
    <?php $this->getAlert(); ?>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?php echo $data['modal_title']; ?></h4>
  </div>

  <div class="modal-header">

    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        Customer Information
        <address>
          Name: <?= $data['customer']['name'] ?? '' ?>
          <br>
          Phone: <?= $data['customer']['phone'] ?? '' ?>
          <br>
          Email: <?= $data['customer']['email'] ?? '' ?>
          <br>
          Address: <?= $data['customer']['address'] ?? '' ?>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        Sales Information:
        <address>
          <b>Date :<?= date_create($data['saleInvoice']['purchase_date'] ?? "")->format('d M, Y')  ?></b><br>
          <b>Invoice #<?= $data['saleInvoice']['bill_no'] ?? '' ?></b><br>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Grand Total :<?= $data['saleInvoice']['grandtotal'] ?? '' ?></b><br>
        <b>Paid Amount :<span><?= $data['saleInvoice']['paid'] ?? '' ?></span></b><br>
        <b>Due Amount :<span id="due_amount_temp"><?= $data['saleInvoice']['due'] ?? '' ?></span></b><br>

      </div>
      <!-- /.col -->
    </div>

  </div>

  <form onsubmit method="POST" action="<?= APP_URL ?>/admin/sales/pay_now/<?= $data['saleInvoice']['id'] ?? '' ?>/<?= $data['customer']['id'] ?? '' ?>">
    <div class="modal-body">

      <div class="box-body">

        <div class="row">

          <div class="col-md-4">

            <div class="form-group">
              <label for="date">Date</label>
              <input name="date" type="date" class="form-control dat" value="<?= date_create()->format('Y-m-d') ?>">
            </div>

          </div>

          <div class="col-md-4">

            <div class="form-group">
              <label for="paid_amount">Payable Amount</label>
              <input name="paid_amount" type="number" class="form-control paid_amount" value="<?= $data['saleInvoice']['due'] ?? '' ?>" placeholder="Amount">
            </div>

          </div>

          <div class="col-md-4">

            <div class="form-group">
              <label for="payment_type"> Payment Type</label>
              <select name="payment_type" class="form-control">
                <option value="Cash">Cash</option>
                <option value="Bank">Bank</option>
              </select>
            </div>

          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label for="payment_note">Payment Note</label>

              <textarea name="payment_note" rows="2" class="form-control"></textarea>
            </div>
          </div>


        </div>

      </div>
      <!-- /.box-body -->
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default " data-dismiss="modal"> Close</button>
      <button type="submit" class="btn bg-color ">Save</button>
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
        Customer Information
        <address>
          Name: <?= $data['customer']['name'] ?? '' ?>
          <br>
          Phone: <?= $data['customer']['phone'] ?? '' ?>
          <br>
          Email: <?= $data['customer']['email'] ?? '' ?>
          <br>
          Address: <?= $data['customer']['address'] ?? '' ?>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        Sales Information:
        <address>
          <b>Invoice #<?= $data['saleInvoice']['bill_no'] ?? '' ?></b><br>
          <b>Date :<?= date_create($data['saleInvoice']['purchase_date'] ?? "")->format('d M, Y')  ?></b><br>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Grand Total :<?= $data['saleInvoice']['grandtotal'] ?? '' ?></b><br>
        <b>Paid Amount :<span><?= $data['saleInvoice']['paid'] ?? '' ?></span></b><br>
        <b>Due Amount :<span id="due_amount_temp"><?= $data['saleInvoice']['due'] ?? '' ?></span></b><br>

      </div>
      <!-- /.col -->
    </div>

  </div>

  <div class="modal-body">
    <p>Payment Information:</p>
    <div class="row">
      <div class="col-md-12">
        <div class=" table-responsive">
          <table class="table table-striped table-bordered  table-condensed mb-0">
            <thead>
              <tr class=" bg-color">
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
              if (empty($data['payments'])) {
                echo '<tr> <td colspan="10" class="text-center" >No Recorded Found</td> </tr>';
              } else {
                foreach ($data['payments'] as $key => $payment) { ?>

                  <tr>
                    <td class=" text-center"><?= $i ?></td>
                    <td class=" text-center"><?= date_create($payment['created'])->format('d M, Y') ?></td>

                    <td class=" text-center"><?= $payment['user'] ?></td>
                    <td class=" text-center"><?= $payment['payment_type'] == 1 ? 'Cash' : 'Bank' ?></td>
                    <td class=" text-center"><?= $payment['payment_note'] ?></td>
                    <td class=" text-center"><?= $payment['payment_amount'] ?></td>
                    <td class=" text-center"><a href="javascript:deletePayment(<?= $payment['id'] ?>)"><i class="fa fa-trash text-danger"></i> </a></td>

                  </tr>

              <?php $i++;
                  $total += $payment['payment_amount'] ?? 0;
                }
              }
              ?>

            </tbody>

            <tfoot class="bg-gray">
              <tr>
                <td colspan="5" class="text-right text-bold">Total Amount</td>
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