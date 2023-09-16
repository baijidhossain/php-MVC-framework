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

  .line-clamp {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
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
                  <b>Invoice #<?= $data['sales']['bill_no'] ?></b><br>
                  <b>customer Status :<?= $data['customer']['status']  ?></b><br>

                </div>
                <!-- /.col -->
              </div>

              <div class="row">
                <div class="col-xs-12 table-responsive">
                  <table class="table table-striped table-bordered  table-condensed mb-0">
                    <thead class="bg-color ">
                      <tr>
                        <th style="width:10px ;">Si</th>
                        <th style="width:250px ;">Item Name</th>
                        <th style="width:100px ;">QTY</th>
                        <th style="width:170px ;">Sales Price</th>
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

                      foreach ($data['salesProducts'] as  $prodact) { ?>
                        <tr>

                          <td><?= $i; ?></td>
                          <td><?= $prodact['product_name'] ?? "" ?></td>
                          <td><?= $prodact['qty'] ?? "" ?></td>
                          <td><?= $prodact['sales_price'] ?? "" ?></td>
                          <td><?= $prodact['discount'] ?? "" ?></td>
                          <td><?= $prodact['discount_amount'] ?? "" ?></td>
                          <td><?= $prodact['tax'] ?? "" ?></td>
                          <td><?= $prodact['tax_amount'] ?? "" ?></td>
                          <td><?= $prodact['unit_cost'] ?? "" ?></td>
                          <td><?= $prodact['total_amount'] ?? "" ?></td>

                        </tr>

                      <?php

                        $i++;
                      }
                      ?>



                    </tbody>
                    <tfoot class="text-bold ">
                      <tr class="bg-gray">

                        <td colspan="2" class="text-right">Total = </td>
                        <td> <?= array_sum(array_column($data['salesProducts'], "qty")) ?></td>
                        <td><?= array_sum(array_column($data['salesProducts'], "sales_price")) ?></td>
                        <td></td>
                        <td><?= array_sum(array_column($data['salesProducts'], "discount_amount")) ?></td>
                        <td></td>
                        <td><?= array_sum(array_column($data['salesProducts'], "tax_amount")) ?></td>
                        <td><?= array_sum(array_column($data['salesProducts'], "unit_cost")) ?></td>
                        <td><?= array_sum(array_column($data['salesProducts'], "total_amount")) ?></td>
                      </tr>

                      <tr>
                        <td colspan="9" class="text-right">Subtotal : </td>
                        <td><?= $data['sales']['subtotal'] ?? "" ?></td>
                      </tr>
                      <tr>
                        <td colspan="9" class="text-right">Other Charges/Fixed : </td>
                        <td><?= $data['sales']['other_charges'] ?? "" ?></td>
                      </tr>
                      <tr>
                        <td colspan="9" class="text-right">Discount : </td>
                        <td><?= $data['sales']['discount_on_all'] ?? "" ?></td>
                      </tr>

                      <tr>
                        <td colspan="9" class="text-right">Grandtotal : </td>
                        <td><?= $data['sales']['grandtotal'] ?? "" ?></td>
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
                    <table class="table table-striped table-bordered  table-condensed mb-0" style="width:100%">
                      <thead>
                        <tr class="bg-color ">
                          <th style="width: 10px;">Si</th>
                          <th style="width: 200px;">Date</th>
                          <th style="width: 200px;">Payment Type</th>
                          <th style="width: 200px;">Payment Note</th>
                          <th style="width: 80px;">Payment</th>
                        </tr>
                      </thead>
                      <tbody>

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

                      </tbody>

                      <tfoot class="text-bold ">

                        <tr>
                          <td colspan="4" class="text-right">Total Paid : </td>
                          <td><?= $data['sales']['paid'] ?? "" ?></td>
                        </tr>

                        <tr>
                          <td colspan="4" class="text-right">Receivable Due : </td>
                          <td><?= $data['sales']['due'] ?? "" ?></td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>
      </div>
    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>