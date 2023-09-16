<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Purchase Invoice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap -->
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

</head>

<body>

  <div class="container">


    <div style="margin-top: 20px;" class="row">
      <div class="col-md-12">
        <div class="box box-widget">
          <div class="box-header with-border ">
            <h3 class="box-title"><?= $data['page_title'] ?></h3>

          </div>

          <div class="box-body">
            <div class="row invoice-info">

              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                <i>Customer Details<br></i>
                <address>
                  <strong><?= $data['customer']['name'] ?></strong><br>
                  Mobile: <?= $data['customer']['phone'] ?><br>
                  Email: <?= $data['customer']['email'] ?><br>
                  Address: <?= $data['customer']['address'] ?><br>

                </address>
              </div>
              <!-- /.col -->
              <div class="col-sm-4 invoice-col">
                <b>Invoice #<?= $data['saleInvoice']['bill_no'] ?></b><br>
                <b>Sales Status :<?php


                                  if ($data['saleInvoice']['status']  == 'Final') {
                                    echo 'Final';
                                  } elseif ($data['saleInvoice']['status']  == 'Quotation') {
                                    echo 'Quotation';
                                  }

                                  ?></b><br>

              </div>
              <!-- /.col -->
            </div>

            <div class="row">
              <div class="col-xs-12 table-responsive">
                <table class="table table-striped  table-bordered table-condensed ">
                  <thead class="bg-purple ">
                    <tr>
                      <th style="width:10px ;">Si</th>
                      <th style="width:250px ;">Item Name</th>
                      <th style="width:100px ;">QTY</th>
                      <th style="width:170px ;">Purchase Price</th>
                      <th style="width:170px ;">Discount</th>
                      <th style="width:200px ;">Discount Amount</th>
                      <th style="width:170px ;">Tax%</th>
                      <th style="width:170px ;">Tax Amount</th>
                      <th style="width:170px ;">Unit Cost</th>
                      <th style="width:170px ;">Total Amount</th>

                    </tr>
                  </thead>
                  <tbody>

                    <?php

                    $i = 1;
                    $qty = 0;
                    $PurchasePrice = 0;
                    $DiscountAmount = 0;
                    $TaxAmount = 0;
                    $UnitCost = 0;
                    $TotalAmount = 0;

                    foreach ($data['saleProducts'] as  $product) { ?>
                      <tr>

                        <td><?= $i; ?></td>
                        <td><?= $product['product_name'] ?? "" ?></td>
                        <td><?= $product['qty'] ?? "" ?></td>
                        <td><?= $product['purchase_price'] ?? "" ?></td>
                        <td><?= $product['discount'] ?? "" ?></td>
                        <td><?= $product['discount_amount'] ?? "" ?></td>
                        <td><?= $product['tax'] ?? "" ?></td>
                        <td><?= $product['tax_amount'] ?? "" ?></td>
                        <td><?= $product['unit_cost'] ?? "" ?></td>
                        <td><?= $product['total_amount'] ?? "" ?></td>

                      </tr>


                    <?php
                      $i++;
                      $qty += $product['qty'] ?? 0;
                      $PurchasePrice += $product['purchase_price'] ?? 0;
                      $DiscountAmount += $product['discount_amount'] ?? 0;
                      $TaxAmount += $product['tax_amount'] ?? 0;
                      $UnitCost += $product['unit_cost'] ?? 0;
                      $TotalAmount += $product['total_amount'] ?? 0;
                    } ?>



                  </tbody>
                  <tfoot class="text-bold ">
                    <tr class="bg-gray">

                      <td colspan="2" class="text-center">Total</td>
                      <td> <?= $qty ?></td>
                      <td><?= $PurchasePrice ?? 00 ?></td>
                      <td></td>
                      <td><?= $DiscountAmount ?? 00 ?></td>
                      <td></td>
                      <td><?= $TaxAmount ?? 00 ?></td>
                      <td><?= $UnitCost ?? 00 ?></td>
                      <td><?= $TotalAmount ?? 00 ?></td>
                    </tr>

                    <tr>
                      <td colspan="9" class="text-right">Subtotal </td>
                      <td><?= $data['saleInvoice']['subtotal'] ?? "" ?></td>
                    </tr>
                    <tr>
                      <td colspan="9" class="text-right">Other Cherges/Fixed </td>
                      <td><?= $data['saleInvoice']['other_charges'] ?? "" ?></td>
                    </tr>
                    <tr>
                      <td colspan="9" class="text-right">Discount </td>
                      <td><?= $data['saleInvoice']['discount_on_all'] ?? "" ?></td>
                    </tr>

                    <tr>
                      <td colspan="9" class="text-right">Grandtotal </td>
                      <td><?= $data['saleInvoice']['grandtotal'] ?? "" ?></td>
                    </tr>

                  </tfoot>
                </table>


              </div>
              <!-- /.col -->


            </div>



            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <h4 class="box-title text-info">Payments Information : </h4>
                  <table class="table table-hover table-bordered table-condensed" style="width:100%" id="">

                    <tbody>
                      <tr class="bg-purple ">
                        <th style="width: 50px;">Si</th>
                        <th style="width: 200px;">Date</th>
                        <th style="width: 200px;">Payment Type</th>
                        <th style="width: 200px;">Payment Note</th>
                        <th style="width: 80px;">Payment</th>
                      </tr>
                      <?php

                      $index = 1;
                      $total_amount = 0;
                      if (empty($data['payment'])) {
                        echo '<tr><td colspan="10" class="text-center">No Payment Record Found</td></tr>';
                      } else {

                        foreach ($data['payment'] as  $payments) { ?>
                          <tr class=" text-bold">
                            <td><?= $index ?? "" ?></td>
                            <td><?= date_create($payments['created'])->format('d M, Y') ?></td>
                            <td><?= $payments['payment_type'] == 1 ? "Cash" : "Bank" ?></td>
                            <td><?= $payments['payment_note'] ?? "" ?></td>
                            <td><?= $payments['payment_amount'] ?? "" ?></td>
                          </tr>

                      <?php
                          $index++;
                          $total_amount += $payments['payment_amount'] ?? 0;
                        }
                      }
                      ?>

                      <tr class="bg-gray">
                        <td colspan="4" class="text-right ">Total Payment </td>
                        <td><?= $total_amount ?? 00 ?></td>
                      </tr>

                      <tr>
                        <td colspan="4" class="text-right">Total Paid </td>
                        <td><?= $data['saleInvoice']['paid'] ?? "" ?></td>
                      </tr>

                      <tr>
                        <td colspan="4" class="text-right">Receivable Due </td>
                        <td><?= $data['saleInvoice']['due'] ?? "" ?></td>
                      </tr>

                    </tbody>


                  </table>
                </div>
              </div>
            </div>

          </div>

        </div>

      </div>
    </div>

  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script>
    window.print();
  </script>
</body>

</html>