
$(document).ready(function() {

  $('.select2').select2({
    width: "100%",

  });

  $('.product').change(function() {

    var product = $(this).children("option:selected").val();
    var othercharges = $('.othercharges').val();
    var discount_on_all = $('.discount_on_all').val();
    var url = "/admin/purchase/Invoice/add";
    autoload(url, discount_on_all, othercharges, product)

  })

  $('input').on('focusout', function() {

    var othercharges = $('.othercharges').val();

    var discount_on_all = $('.discount_on_all').val();

    url = "/admin/purchase/Invoice/calculation";

    autoload(url, discount_on_all, othercharges)

  })


  $('.clear').click(function() {
    var url = "/admin/purchase/Invoice/delete";
    autoload(url, discount_on_all = "", othercharges = "", product = "", id = "");
  })


});

// Extra function function....

function removeItem(id) {

  var url = "/admin/purchase/Invoice/removeitem";

  autoload(url, discount_on_all = "", othercharges = "", product = "", id);
}

function autoload(url = "", discount_on_all = "", othercharges = "", product = "", id = "") {

  $.ajax({
    url: url,
    type: "POST",
    data: {
      id: id,
      product: product,
      discount_on_all: discount_on_all,
      othercharges: othercharges
    },
    success: function(data) {

      if ($('.newrow').length > 0) {
        $('.newrow').remove();
      }

      var productlist = JSON.parse(data);

      if (typeof productlist === 'object' && productlist !== null && !Array.isArray(productlist)) {

        for (const [key, product] of Object.entries(productlist.product)) {
          field = `
                  <tr class="newrow">
                    <td class="text-center">${product.name}</td>
                    <td class="text-center">${product.seling_price} </td>

                    <td class="text-center">
                    ${product.qty}<br>
                    ${product.unit}
                    </td>

                    <td class="text-center">
                    ${product.total}
                    </td>

                    <td class="text-center">
                    ${product.tax}%
                    <br>
                    ${product.tax_type}
                    </td>

                    <td class="text-center">
                    ${product.discount}
                    ${product.discount_type}
                    </td>

                    <td class="text-center">
                    ${product.final_seling_price}
                    </td>
                    <td class="text-center">
                   <a data-toggle="modal" data-target="#myModal" href="<?= APP_URL ?>/admin/purchase/invoice/edit/${key}"> <i class="fa fa-pencil text-primary"> </i> </a>
                   &nbsp;&nbsp;
                   <a href="#" onclick ="removeItem(${key})"> <i class="fa fa-trash text-danger "> </i> </a>
                    </td>

                  </tr>
                  
                  `;

          $('.rowappend').append(field);
        }

      } else {

        alert(productlist);
        autoload(url = "/admin/purchase/Invoice/calculation", discount_on_all = "", othercharges = "", product = "", id = "");

      }

      $('.subtotal').val(productlist.calculation.subtotal);
      $('.othercharges').val(productlist.calculation.othercharges);
      $('.discount_on_all').val(productlist.calculation.discount_on_all);
      $('.grandtotal').val(productlist.calculation.grandtotal);

    }
  });
}
autoload(url = "/admin/purchase/Invoice/calculation", discount_on_all = "", othercharges = "", product = "", id = "");
