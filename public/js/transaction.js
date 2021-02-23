
  $(function () {
    var checkout_id = 0;
    const client = ShopifyBuy.buildClient({
        accessToken: '8af401c542645b1749b6d157afcc17c6',
        domain: 'neacmed.myshopify.com',

    });


    client.checkout.create().then((checkout) => {
        checkout_id = checkout.id;
        const shippingAddress = {
          address1: '561 Kaingin St. Balasing',
          address2: 'Apartment 2',
          city: 'Sta Maria',
          company: null,
          country: 'Philippines',
          firstName: 'NEac',
          lastName: 'MedExam',
          phone: '09559160218',
          province: 'Bulacan',
          zip: '3022'
        };
       client.checkout.updateShippingAddress(checkout_id, shippingAddress).then(checkout => {
         // Do something with the updated checkout
       });
    });

    
    let productList;
    client.product.fetchAll(26).then((products) => {
       productList = products;
       get_product_data(products);
    });

    $('#product_search').keyup(function(){
      $('#services_info .services_info_content').html('<p class="mt-2">Loading...</p>');
      client.product.fetchQuery({query: $(this).val(), limit: 26}).then((products) => {
        productList = products;
        get_product_data(products);
      });
    });

    function get_product_data(products) {
      
      var image_html = '';
      var html = '<div class="row">'; 
      for(var x = 0; x < products.length; x++) {
          if(products[x].images.length > 0) {
            image_html = `<img src="${ products[x].images[0].src }" width="50">`;
          }
         html += `
             <div class="col-md-6" style="border-bottom: 1px solid #ccc; margin-bottom: 10px">
                ${image_html}
                <h5 style="margin: 20px 10px 5px; display: inline-block;"><a href="${products[x].onlineStoreUrl}"><strong>${products[x].title}</strong></a></h5>
             `;
              for(var y = 0; y < products[x].variants.length; y++) {

               html += `
                 <table>
                   <tr>
                       <td style="width: 100px;">
                        <input class="service-checkbox" type="checkbox" name="services[]" id="service-${ products[x].variants[y].id }" value="${ products[x].variants[y].id }"></td>
                       <td>
                       <label class="font-weight-normal" for="service-${ products[x].variants[y].id }">${ products[x].variants[y].title }</label>&nbsp;&nbsp;
                       [ <small>USD</small><strong> ${ products[x].variants[y].price }</strong> ]
                       </td>
                   </tr>
                 </table>
               `;

              }             
         html += `
             </div>
         `;
      }
      html += '</div>';
      $('#services_info .services_info_content').html(html);

      if(products.length < 25) {
        $('.next_product').hide();
      }
    }

    

    $('body').on('click', '.next_product' ,function(e){
      e.preventDefault();
        $(this).html('Next <i class="fas fa-spinner fa-spin"></i>');
        try {
          client.fetchNextPage(productList).then((nextPageOfProducts) => {
          var products = nextPageOfProducts.model;
          productList = products;
            var image_html = '';
            var html = '<div class="row">'; 
            for(var x = 0; x < products.length; x++) {
              if(products[x].images.length > 0) {
                image_html = `<img src="${ products[x].images[0].src }" width="50">`;
              }
              html += `
                  <div class="col-md-6" style="border-bottom: 1px solid #ccc; margin-bottom: 10px">
                  ${image_html}
                  <h5 style="margin: 20px 10px 5px;  display: inline-block;"><a href="${products[x].onlineStoreUrl}"><strong>${products[x].title}</strong></a></h5>
                  `;
                    for(var y = 0; y < products[x].variants.length; y++) {
    
                    html += `
                      <table>
                        <tr>
                            <td style="width: 100px;"><input class="service-checkbox" type="checkbox" name="services[]" id="service-${ products[x].variants[y].id }" value="${ products[x].variants[y].id }" data></td>
                            <td>
                            <label class="font-weight-normal" for="service-${ products[x].variants[y].id }">${ products[x].variants[y].title }</label>&nbsp;&nbsp;
                            [ <small>USD</small><strong> ${ products[x].variants[y].price }</strong> ]
                            </td>
                        </tr>
                      </table>
                    `;
    
                    }             
              html += `
                  </div>
              `;
            }
            html += '</div>';
          $(this).html('Next');
          $('#services_info .services_info_content').append(html);
          if(products.length < 25) {
            $('.next_product').hide();
          }
        });
        } catch(err) {
          $(this).html('Next');
          alert('No products to show');
        }
    });

    $('body').on('click', '.service-checkbox', function(){
        $('#transaction_history table').html('<tr><td>Loading...</td></tr>');

        if(!$(this).is(':checked')) {
          var removeItem = [$(this).attr('remove-item')];
      
          client.checkout.removeLineItems(checkout_id, removeItem).then((checkout) => {
            var mode = 'remove';
            generate_placeorder(checkout, mode);
          });
        } else {
          var formData = {
            'variantId' : $(this).val(),
            'quantity': 1
          };
          client.checkout.addLineItems(checkout_id, formData).then((checkout) => {
            var mode = 'add';
            generate_placeorder(checkout, mode);
          });
        }

    });

    $('#place_order').submit(function(e){
      e.preventDefault();
      if(confirm('Do you really want to pay via offline this cart?')) {
        client.checkout.fetch(checkout_id).then((checkout) => {
          const data = {
            checkout,
            data: $(this).serialize()
          };
          $.ajax({
              type: 'POST',
              url: '/transactions/placeorder',
              data,
              success: function(res){
                var success_html = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Success!</strong> The data is saved! Please reload to see changes in table.
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>`;
                $('#payment_form').append(success_html);
              }
          });
        });
      }
    });

    function generate_placeorder(checkout, mode) {
      console.log(checkout);
      var html = `
      <table class="table table-striped table-hover">
          <thead>
              <tr>
                  <th>Service</th>
                  <th style="width: 100px;">Price</th>
              </tr>
          </thead>
          <tbody> 
    `;           
        for(var x = 0; x< checkout.lineItems.length; x++) { 
          if(mode == 'add') {
            $('.service-checkbox').each(function(){
              if($(this).val() == checkout.lineItems[x].variableValues.lineItems.variantId) {
                $(this).attr('remove-item', checkout.lineItems[x].id);
              }
            });
          }

            html += `
              <tr>
                  <td>${checkout.lineItems[x].title}<br><small><strong>Variation:</strong>${checkout.lineItems[x].variant.title}</small></td>
                  <td>${ '<small>'+checkout.currencyCode+'</small> '+checkout.lineItems[x].variant.price}</td>
              </tr>
            `; 
          }
        html += ` 
          </tbody>
          <tfoot>
            <tr>
              <th>Sub Total</th>
              <td>${ '<small>'+checkout.currencyCode+'</small> '+checkout.subtotalPrice }</td>
            </tr>
            <tr>
              <th style="border: none">Total Tax</th>
              <td style="border: none">${ '<small>'+checkout.currencyCode+'</small> '+checkout.totalTax }</td>
            </tr>
            <tr>
              <th>Total Price</th>
              <td>${ '<small>'+checkout.currencyCode+'</small> '+checkout.totalPrice }</td>
            </tr>
          </tfoot>
      </table>
      `;
      $('#transaction_history table').html(html);
    }
});