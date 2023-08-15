  <!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Edit Products</h5>
            <p class="text-muted m-b-10 text-center">Update Product Details</p>
           <!--  <ul class="breadcrumb-title b-t-default p-t-10">
               <li class="breadcrumb-item">
                  <a href="index.html"> <i class="fa fa-home"></i> </a>
               </li>
               <li class="breadcrumb-item"><a href="#!">All Products</a>
               </li>
                <li class="breadcrumb-item"><a href="#!">All Categories</a>
                  </li>
            </ul>
            <div class="card-header">
               <i class="fa fa-table"></i> Products List
               <a class="btn pull-right add-category btn-out-dashed btn-round btn-grd-primary" data-toggle="modal" data-target="#category-popup" style="color: white">Add Product</a>
            </div> -->
         </div>
      </div>
<div class="row">
  <div class="col-md-12 m-auto">
    <div class="card mb-3">
      <div class="card-header text-center">
        Product Details
      </div>
      <div class="card-body">
        <form id="p">
          {{ csrf_field() }}
          <div class="form-group">
            <label>Select Category</label>
            <select class="form-control c-id" name="category_id" {{ $product->user_id == Auth::id() ? '' : 'disabled' }}>
              @foreach($categories as $c)
              <option value="{{ $c->id }}"  @if($product->category_id == $c->id) selected @endif>{{ $c->name }}</option>
              
              @endforeach
            </select>
          </div>
          <input type="hidden" value="{{ $product->category->name }}" class="form-control" type="text" id="c-name">
          <input type="hidden" class="form-control id" type="text" name="id" value="{{ $product->id }}">
          <div class="form-group">
            <label>Name</label>
            <input class="form-control name" type="text" placeholder="Enter Name" name="name" value="{{ $product->name }}" required="" {{ $product->user_id == Auth::id() ? '' : 'readonly' }}>
          </div>
          <div class="form-group">
            <label>Sell Price</label>
            <input type="number" class="form-control sell-price" placeholder="Enter Customer Benefit" name="sell_price" value="{{ $product->getProductrecord(Auth::user()->customer_id , $product->id)->sell_price }}" required="">
          </div>
          
          <div class="form-group">
            <label>Purchase Price</label>
            <input type="number" class="form-control t-price" placeholder="Enter Purchase Price" name="price" value="{{ $product->getProductrecord(Auth::user()->customer_id , $product->id)->price }}" required="" {{ $product->user_id == Auth::id() ? '' : 'readonly' }} >
          </div>
                   

          <div class="form-group">
            <label>Customer Benefit</label>
            <input type="number" class="form-control c-ben" placeholder="Enter Customer Benefit" name="c_benefit" value="{{ $product->getProductrecord(Auth::user()->customer_id , $product->id)->c_benefit }}" required="">
          </div>
          <a class="btn btn-primary btn-block update-product" style="color:white;">Update Product</a>
        </form>
      </div>
    </div>
  </div>
  </div>
      
 