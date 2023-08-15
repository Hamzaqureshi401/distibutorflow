var request = 0;

    function setprice(customer_id , product_id){

          product_id          = $('#p-'+product_id).data('pid');
           var p_name         = $('#p-'+product_id).data('pname');
           var price          = $('#set-'+product_id).val();//$('#p-'+product_id).data('pprice');
           var p_price        = $('#p-'+product_id).data('p_price');
           var psell_price    = $('#sell_price-'+product_id).val();
           var pa_benefit     = $('#a_benefit-'+product_id).val();
           var pc_benefit     = $('#c_benefit-'+product_id).val();

           $('#product-name').val(p_name + "  Purchase Price = " + p_price);
           $('#trade_price').val(price);
           $('#psell_price').val(psell_price);
           $('#pa_benefit').val(pa_benefit);
           $('#pc_benefit').val(pc_benefit);
           $('#customer_id_pr').val(customer_id);
           $('#pro_id').val(product_id);

           var new_price = price;
           var new_sell_price = psell_price;

           $("#trade_price").keyup(function () {
           $('#pa_benefit').val( ($(this).val()) - p_price);
           $('#pc_benefit').val( new_sell_price - ($(this).val()));
           new_price = $(this).val();
           });
           $("#psell_price").keyup(function () {
           $('#pc_benefit').val(($(this).val()) - new_price);
           new_sell_price = $(this).val();
           });

           $(document).on('click', '.saveprices', function(e){
             e.preventDefault();
             var price        = $('#trade_price').val();
             var sell_price   = $('#psell_price').val();
             var a_benefit    = $('#pa_benefit').val();
             var c_benefit    = $('#pc_benefit').val();
             var customer_id  = $('#customer_id_pr').val();   
             var pro_id       = $('#pro_id').val();   
             if (request == 0){
               request = 1;
             $.ajax({
                 type: "GET",
                 url: '{{ route("update.customer.product.prices") }}',
                 data: {'price': price , 'sell_price': sell_price , 'a_benefit': a_benefit , 'c_benefit': a_benefit , 'customer_id': customer_id , 'product_id': pro_id}, // serializes the form's elements.
                 success: function(data)
                 {
                   if (data.id != ""){
                     toastr.success("Product Updated!");
                     $('#product-update-popup .close').click();
                     $('#set-'+data.product_id).val(data.price);
                     $('#sell_price-'+data.product_id).val(data.sell_price);
                     $('#p_price-'+data.product_id).val(data.p_price);
                     $('#a_benefit-'+data.product_id).val(data.a_benefit);
                     $('#c_benefit-'+data.product_id).val(data.c_benefit);
                     $('#p-'+data.product_id).removeClass('btn-primary');
                     $('#p-'+data.product_id).addClass('btn-success');
                     $('#p-'+data.product_id).html('Product Updated');
                     request = 0;
                   }
                 }
             });
             }
             
         });

       }