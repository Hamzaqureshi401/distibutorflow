<script type="text/javascript">
	   var status = 0;
     $(".check-all").on('click',function(){
      $(this).html('Uncheck All Map points');
      if (status == 0 ){
        status = 1;
        $(this).addClass('btn-success');
         $(".checkbox").bootstrapToggle('on'); 
         $(".set-background").addClass('check-all');
      }else{
        status = 0;
        $(this).removeClass('btn-success');
        $(".checkbox").bootstrapToggle('off');
        $(this).html('Check All Map Points');
        $(".set-background").removeClass('check-all');
      }
      showmapbtn();
      
  });
     function UseStock (order_id){

        $('.use-stock').html('Remove Use Stock');

     $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('set.stock') }}',
            data: {order_id , order_id}, 
            success: function (data) {
            toastr.success(data.message);
            }
        });
    }
    function PickOrder (order_id){

     $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('pick.order') }}',
            data: {order_id , order_id}, 
            success: function (data) {
            toastr.success(data.message);
            }
        });
    }
    $('.confirm-btn').click(function (event) {
            event.preventDefault();
            console.log('a ', $("#seller_id_popup_confirm").val());
            if ($('#multiple-approve input:checkbox').length < 1) {
                toastr.error('Please Select Any Order First');
                $("#send-pending-modal .close").click();
                $("#order-unapprove-popup .close").click();
               
                return false;
            } else {
              if ($(this).hasClass('send-to-unapprove')){
                $('input[name="send_to_unapprove"]').val("1");

              }else{
                $('input[name="send_to_unapprove"]').val("");
              }
              $('#send-pending-modal .close').click();
              $('#order-unapprove-popup .close').click();
              $('#multiple-approve').submit();
            }
            

          });
     function clearOrder(order_id){
        //var order_id = $(this).data('id');
        var a = order_id;
    $.ajax({
            type: "GET",
            url: '{{ route('clear.order') }}',
            data: {order_id : order_id}, 
            
             success: function (data) {
                 if(data.success == true){
                          $(this).closest('tr').addClass('clear'); 
                          
                         toastr.success(data.message);
                         
                 } else {
                         toastr.error(data.message);
                 }
            },
        });
  }
  $(document).ready(function(){
    $(document).on('change', '.js-switch1', function () {
        let received_amount = $(this).prop('checked') === true ? 1 : 0;
         if ($(this).prop('checked') == 0) {
       $(this).closest('tr').addClass('cancel'); 
       $(this).attr("disabled", true);
    } else {
       $(this).closest('tr').removeClass('cancel'); 
       $(this).attr("disabled", true);
    }
        let userId = $(this).data('id');
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('equal.order') }}',
            data: {'received_amount': received_amount, 'user_id': userId},
            success: function (data) {
            console.log(data.message);
            toastr.success(data.message);
            }
        });
});
});

$(document).on('click', '.edit-receiving', function(event){
    event.preventDefault();

    var amount = $(this).data(amount);
    var subtotal = $(this).data(subtotal);
    var receivedamount = $(this).data(receivedamount);
    var amountleft = $(this).data(amountleft);
    $('.show-subtotal').html(amount.subtotal);
    $('.show-amount').html(amount.amount);
    $('.show-amount_left').html(amount.amountleft);
     
    console.log(amount.amount ,amount.subtotal , amount.receivedamount , amount.amountleft);
    var cTR = $(this).closest('tr');
    var c_name = cTR.find('td').eq(1).text();
    $('.old-category').val(c_name)
    $('.receiving-form').attr('action' , $(this).attr('href'));
    $('#receiving-popup .modal-title').html('Edit <b>' + c_name + '</b>');
    $('#receiving-popup .modal-footer button').text('Update Category');
});

 $(document).on('click', '.check-all-map-point', function(){
   
     var a = $('.add-coords').bootstrapToggle('on');
     console.log(a);
     $(".set-background").addClass('check-all-color');
     $(".check-all-map-point").addClass('uncheck-all-map-point');
     $(".check-all-map-point").removeClass('check-all-map-point');
     $(this).html('Uncheck All Map Points');  
   });
   $(document).on('click', '.uncheck-all-map-point', function(){
   
     var a = $('.add-coords').bootstrapToggle('off');
     console.log(a);
     $(".set-background").removeClass('check-all-color');
     $(".uncheck-all-map-point").addClass('check-all-map-point');
     $(".uncheck-all-map-point").removeClass('uncheck-all-map-point');
     $(this).html('Check All Map Points');
   
   });
</script>
<!--  edit product -->
  <script type="text/javascript">
       $('.view-order-details').click(function(){
    var param = $(this).attr('id');
    $.get('{{ route("edit.order") }}/' + param + '/' + "ajax", function(success){
      $("#edit-order-detail-popup .modal-body").show();
      $('#edit-order-detail-popup .modal-body').html(success);
      @include('orders.js.editorderjs');
    });
    });
  </script>