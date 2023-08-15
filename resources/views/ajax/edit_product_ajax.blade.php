
<!DOCTYPE html>
<html>
<head>
  <title>Print Order</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style type="text/css">
  .total{
      border: 1px solid gray;
      padding: 1px;
      font-weight: 600;
    }
    </style>
</head>
<body><!-- Breadcrumbs-->
<div class="row">
  <div class="col-md-12 m-auto">
    <div class="card mb-3">
      <div class="card-header text-center">
        Product Details
      </div>
      <div class="card-body">
        <form>
          {{ csrf_field() }}
          <div class="form-group">
            <label>Select Category</label>
            <select class="form-control" name="category_id">
              @foreach($categories as $c)
              <option value="{{ $c->id }}" @if($product->category_id == $c->id) selected @endif>{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Name</label>
            <input class="form-control" type="text" placeholder="Enter Name" name="name" value="{{ $product->name }}" required="">
          </div>
          <div class="form-group">
            <label>Trade Price</label>
            <input class="form-control t-price" placeholder="Enter Trade Price" name="price" value="{{ $product->price }}" required="">
          </div>
          <div class="form-group">
            <label>Sell Price</label>
            <input class="form-control sell-price" placeholder="Enter Customer Benefit" name="sell_price" value="{{ $product->sell_price }}" required="">
          </div>
          <div class="form-group">
            <label>Purchase Price</label>
            <input class="form-control p-price" placeholder="Enter Purchase Price" name="p_price" value="{{ $product->p_price }}" required="">
          </div>
          <div class="form-group">
            <label>Customer Benefit</label>
            <input class="form-control c-ben" placeholder="Enter Customer Benefit" name="c_benefit" value="{{ $product->c_benefit }}" required="">
          </div>
          
          
          <div class="form-group">
            <label>Order Taker Benefit</label>
            <input class="form-control" placeholder="Enter Order Taker Benefit" name="ot_benefit" value="{{ $product->ot_benefit }}" required="">
          </div>
           <div class="form-group">
            <label>Admin Benefit <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input class="form-control a_ben" placeholder="Enter Admin Benefit" name="a_benefit" value="{{ $product->a_benefit }}" required="">
          </div>
          <button id="button" type="submit" class="btn btn-primary btn-block">Update Product</button>
        </form>
      </div>
    </div>
  </div>
 
 @section('scripts')

  <script type="text/javascript">
 function disableButton() {
        var btn = document.getElementById('button');
        btn.disabled = true;
        btn.innerText = 'Updating Wait'

        var originalText = $("#button").text(),
    i  = 0;
setInterval(function() {

    $("#button").append(".");
    i++;

    if(i == 4)
    {
        $("#button").html(originalText);
        i = 0;
    }

}, 500);
    }
    
$('.t-price').keyup(function(){
    var t_price = $('.t-price').val();
    var p_price = $('.p-price').val();
    var sell_price = $('.sell-price').val();
    var result = t_price - p_price;
    var p_price = $('.a_ben').val(result);
    var result = sell_price - t_price;
    var c_ben = $('.c-ben').val(result);
   
  });
 $('.p-price').keyup(function(){
    var t_price = $('.t-price').val();
    var p_price = $('.p-price').val();
    var sell_price = $('.sell-price').val();
    var result = t_price - p_price;
    var p_price = $('.a_ben').val(result);
    var result = sell_price - t_price;
    var c_ben = $('.c-ben').val(result);
    
  });
  $('.sell-price').keyup(function(){
    var t_price = $('.t-price').val();
    var sell_price = $('.sell-price').val();
    var result = sell_price - t_price;
    var c_ben = $('.c-ben').val(result);
  });

  $(function () {
        $('form').on('submit', function (e) {
          e.preventDefault();
          var btn = document.getElementById('button');
          btn.disabled = true;
          btn.innerText = 'Product Saving Wait..';
          $('#button').addClass('btn-success');
          $.ajax({
            type: 'post',
            url: '{{ route('update.product') }}'+'/' + {{ $product->id }},
            data: $('form').serialize(),
            success: function (data) {
              var nType = "success";
              var title = "Success ";
              var msg = data.message;
              notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
               var btn = document.getElementById('button');
                btn.disabled = false;
                total = total + 1;
                btn.innerText = 'Again Save New Product (Totoal Saved '+total+')';
                //$('#button').addClass('btn-primary');
              }
          });

        });

      });

    </script>
@endsection
 </body>
</html>