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
        <form id="myForm" action="{{ $route ?? '' }}" >
          {{ csrf_field() }}
          <input type="hidden" name="id" value="{{ $product->id }}">
                    
          <div class="form-group">
            <label>Select Category</label>
            <select class="form-control c-id" name="category_id">
              @foreach($categories as $c)
              <option value="{{ $c->id }}"  @if($product->category_id == $c->id) selected @endif>{{ $c->name }}</option>
              
              @endforeach
            </select>
          </div>
          <input type="hidden" value="{{ $product->category->name }}" class="form-control" type="text" id="c-name">
          <input type="hidden" class="form-control id" type="text" name="id" value="{{ $product->id }}">
          <div class="form-group">
            <label>Name</label>
            <input class="form-control name" type="text" placeholder="Enter Name" name="name" value="{{ $product->name }}" required="">
          </div>
          <div class="form-group">
            <label>Trade Price</label>
            <input type="number" step="0.01" class="form-control t-price" placeholder="Enter Trade Price" name="price" value="{{ $product->price }}" required="">
          </div>
          <div class="form-group">
            <label>Sell Price</label>
            <input type="number" step="0.01" class="form-control sell-price" placeholder="Enter Customer Benefit" name="sell_price" value="{{ $product->sell_price }}" required="">
          </div>
          <div class="form-group">
            <label>Purchase Price</label>
            <input type="number" step="0.01" class="form-control p-price" placeholder="Enter Purchase Price" name="p_price" value="{{ $product->p_price }}" required="">
          </div>
          <div class="form-group">
            <label>Customer Benefit</label>
            <input type="number" step="0.01" class="form-control c-ben" placeholder="Enter Customer Benefit" name="c_benefit" value="{{ $product->c_benefit }}" required="">
          </div>
          
          
          <div class="form-group">
            <label>Order Taker Benefit</label>
            <input type="number" step="0.01" class="form-control ot-ben" placeholder="Enter Order Taker Benefit" name="ot_benefit" value="{{ $product->ot_benefit }}" required="">
          </div>
           <div class="form-group">
            <label>Admin Benefit <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
            <input type="number" step="0.01" class="form-control a_ben" placeholder="Enter Admin Benefit" name="a_benefit" value="{{ $product->a_benefit }}" required="">
          </div>
          <div class="form-group">
         <label style="font-size: 13px">Student Files <span style="color: red">*
         jpeg,png,jpg,pdf</span>
         </label>
         <input type="file"  name="file" class="form-control">
         
         
      </div>
           <button id="button" style="color: white;" class="btn btn-primary btn-block submit-form">{{ $button }}</button>
        </form>
      </div>
    </div>
  </div>
  </div>
      
 