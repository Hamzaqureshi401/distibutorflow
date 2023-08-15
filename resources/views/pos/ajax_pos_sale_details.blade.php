  <!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Pos Sell Details</h5>
            <p class="text-muted m-b-10 text-center">Sell Data</p>
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
      <div class="card-block table-border-style">
            <div class="table-responsive">
               <table class="table table-hover table-datatable">
                  <thead>
                     <tr>
                        <th class="text-center">Product Name</th> 
                        <th class="text-center">Price</th> 
                        <th class="text-center">Unit Sold</th>
                        <th class="text-center">Amount</th>
                     </tr>
                  </thead>
                  <tbody>

                     @foreach($saledetails as $pr)
                     <tr class="text-center">
                       <td>{{ $pr->product->name }}</td>
                       @if ($pr->subtotal != 0)
                       <td>{{ $pr->subtotal/$pr->unit }}</td>
                       @else
                       <td>0</td>
                       @endif
                       <td>{{ $pr->unit }}</td>
                       <td>{{ $pr->subtotal }}</td>
                       
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
    </div>
  </div>
  </div>
      
 