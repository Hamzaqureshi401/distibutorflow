  <!-- Breadcrumbs-->

 <div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Edit Kit</h5>
            <p class="text-muted m-b-10 text-center">Update Kit Details</p>
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
          <div class="form-group">
            <input type="hidden" name="id" value="{{ $ItemKit->id }}">
                     <div class="ct">
                        <label>Select Category</label>
                        <select class="form-control" name="category_id">
                           @foreach($categories as $c)
                           <option value="{{ $c->id }}">{{ $c->name }}</option>
                           @endforeach
                        </select>
                     </div>
                    
                  <div class="form-group">
                     <label>Kit Name Name <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
                     <input class="form-control" type="text" placeholder="Enter Name" name="kitname" required="" value="{{ $ItemKit->name }}">
                  </div>
                  <div class="form-group">
                     <label>Trade Price</label>
                     <input type="number" class="form-control t-price calculate-price" placeholder="Enter Trade Price" name="price" value="{{ $ItemKit->price }}">
                  </div>
                 
                  <div class="form-group">
                     <label>Admin Benefit <span style="opacity: 0.5; font-style: italic;">(Auto Calculated)</span></label>
                     <input type="number" class="form-control a_ben"  placeholder="Enter Admin Benefit" name="a_ben" value="{{ $ItemKit->a_benefit }}" >
                  </div>
                  <div class="form-group">
                    <img 
                                       src="{{ asset($ItemKit->img) }}" 
                                       alt="Snow" 
                                       style="max-width:20%; max-height:20% ;"
                                       >
                  </div>
                  <div class="form-group">
                   <label style="font-size: 13px">Student Files <span style="color: red">*
                   jpeg,png,jpg,pdf</span>
                   </label>
                   <input type="file"  name="file" class="form-control">
                  </div>


                  <div class="card-block table-border-style">
                     <div class="table-responsive">
                        <table class="table table-bordered table-datatable table-custom-th table-hover" id="dataTable" width="100%" cellspacing="0">
                           <thead>
                              <tr>
                                 
                                 <th>Product</th>
                                 <th>Quantity</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                             @foreach($ItemKitDetails as $ItemKitDetail)
                            <tr>
                              <input type="hidden" name="product_idold[]" value="{{ $ItemKitDetail->product_id }}">
                              <td>{{ $ItemKitDetail->product->name }}</td>

                                <td> <input type="number" name="quantityold[]" class="form-control" placeholder="Ent quantity" value="{{ $ItemKitDetail->quantity }}"></td>
                                
                                  <td>
                                  <input 
                                  type="checkbox" 
                                  data-toggle="toggle" 
                                  data-onstyle="success" 
                                  data-size="xs" 
                                  name="statusold[]" 
                                  class="js-switch1" 
                                  value="{{ $loop->index }}" 
                                  checked 
                                  >
                                  </td>
                            </tr>
                            @endforeach
                            @php 
                            $cat_p = [];
                            @endphp 
                            

                            @foreach($products as $pr)
                            @if(!in_array($pr->category_id , $cat_p))
                              <tr>
                                
                                  <td class="bg-info">{{ $pr->category->name ?? '--' }}</td>
                                  
                              </tr>
                               @php 
                               $cat_p[] = $pr->category_id;
                                @endphp 

                              @endif
                              @if($pr->type == 'single_product')
                              <tr>
                                <input type="hidden" name="product_id[]" value="{{ $pr->id }}">
                                
                                <td>{{ $pr->name }}</td>
                                <td> <input type="number" name="quantity[]" class="form-control" placeholder="Ent quantity"></td>
                                <td>
                                <input 
                                  type="checkbox" 
                                  data-toggle="toggle" 
                                  data-onstyle="success" 
                                  data-size="xs" 
                                  name="status[]" 
                                  class="js-switch1" 
                                  value="{{ $loop->index }}" 
                                  ></td>
                            </tr>
                            @endif
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>

                  <button id="button" type="submit" class="btn btn-primary btn-block">Store Kit</button>
        </form>
      </div>
    </div>
  </div>
  </div>
      
 