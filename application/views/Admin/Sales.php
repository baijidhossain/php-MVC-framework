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


      <div style="margin-top: 20px;" class="row">
        <div class="col-md-2">
          <div class="box box-primary">

            <div class="box-body">
              <h4>Categories</h4>

              <?php

              if (!empty($data["getAllcategory"])) {
                foreach ($data["getAllcategory"] as  $category) { ?>
                  <h5>
                    <a style="cursor:pointer" onclick="category(<?php echo $category['id']; ?>)"><?php echo $category['name']; ?></a>
                  </h5>
              <?php

                }
              }

              ?>
            </div>
          </div>
        </div>

        <div class="col-md-4 ">
          <div class="box" style="overflow:scroll; height:80vh;">
            <div class="box-body">
              <div class="row  product">
              </div>
            </div>
          </div>
        </div>


        <div class="col-md-6">

          <div class="box mb-4 shadow-sm box-primary">
            <div class="box-header ">

              <div class="pull-left">
                Product List
              </div>
              <div class="pull-right">
                <button class="btn btn-outline-warning mr-2 " onclick="cartLS.destroy()">Destroy</button>
              </div>

            </div>
            <div class="box-body">
              <form action="" method="get">
                <table class="table">
                  <thead>
                    <tr>
                      <td>Name</td>
                      <td></td>
                      <td class="text-center">Quantity</td>
                      <td></td>

                      <td class="text-right">Price</td>
                      <td class="text-right text-red"> <i class="fa fa-times"></i> </td>
                    </tr>
                  </thead>

                  <tbody class="cart">
                  </tbody>
                  <tfoot>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td class="text-right">Total: <strong class="total"></strong></td>
                    <td class="text-right">
                      <button class="btn btn-sm">Checkout </button>
                    </td>
                  </tfoot>
                </table>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include_once VIEW_PATH . '_common/footer.php'; ?>



  <script>
    // product sales code
    function category(id) {

      // card remove function
      var card = $('.pcard');
      if (card.length > 0) {
        $('.pcard').remove();
      }
      // card remove function

      $(document).ready(function() {



        $.ajax({

          url: "/admin/Sales/views/" + id,
          type: "GET",
          data: {},
          dataType: 'json',
          success: function(result) {

            for (let i = 0; i < result.length; i++) {

              var image = result[i]['image'];
              var name = result[i]['name'];
              var price = result[i]['price'];
              var qty = result[i]['qty'];
              var id = result[i]['id'];

              var data = '<div class="col-lg-6 col-md-12 pcard">' +
                '<div class="box">' +
                '<div class="box-body">' +
                '<h4>' + name + '</h4>' +
                '<span>' + 'Price : ' + price + '</span>' +
                '<button type="button" class="btn btn-block " onClick="cartLS.add({id: ' + id + ' , price: ' + price + ', quantity: 2})">' + 'Add to Cart' + '</button>' +
                '</div>' +
                '</div>' +
                '</div>';

              $(".product").append(data);

            }
          },
        })

      });
    }
    // product sales code
  </script>



  <script>
    function renderCart(items) {
      const $cart = document.querySelector(".cart")
      const $total = document.querySelector(".total")

      $cart.innerHTML = items.map((item) => `
					<tr>
						<td>${item.name}</td>
						<td style="width: 40px;">	
							<button type="button" class="btn btn-block btn-sm btn-outline-primary"
								onClick="cartLS.quantity(${item.id},1)">+</button>
						</td>
          <td class="text-right"><input name="qty[]"  type="text" style=" border: none; background-color:transparent;" class="text-center" disabled value="${item.quantity}"> </td>
						<td style="width: 40px;">	
							<button type="button" class="btn btn-block btn-sm btn-outline-primary"
								onClick="cartLS.quantity(${item.id},-1)">-</button>
						</td>
						<td class="text-right"><input name="price[]" type="text" style=" border: none; background-color:transparent;" class="text-right" disabled value="${item.price}"> </td>
						<td style="cursor: pointer;" class="text-right text-red "  onClick="cartLS.remove(${item.id})"><i class="fa fa-times"></i></td>
					</tr>`).join("")

      $total.innerHTML = "Tk " + cartLS.total()
    }
    renderCart(cartLS.list())
    cartLS.onChange(renderCart)
  </script>