  <!-- Breadcrumbs-->
 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Stock Products History</h5>
            <p class="text-muted m-b-10 text-center">Stock Add or Remove Details</p>
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
                        <th class="text-center">Old Stock</th>
                        <th class="text-center">Unit Added</th>
                        <th class="text-center">Current Stock</th>
                        
                     </tr>
                  </thead>
                  <tbody>

                     @foreach($data['transection'] as $pr)
                     <tr class="text-center">
                       <td>{{ $pr->product->name }}</td>
                       <td>{{ $pr->old_stock }}</td>
                       <td>{{ $pr->remaining_stock - $pr->old_stock }}</td>
                       <td>{{ $pr->remaining_stock }}</td>
                       
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
    </div>
  </div>
  </div>
      
 