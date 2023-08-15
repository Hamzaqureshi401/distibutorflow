@extends('layouts.app')
@section('content')
@push('styles')
<style>
   .form-rounded {
   border-radius: 3em;
   border-color: #3498db;
   }
   .bg-bl th {
   height: 2px;
   line-height: 2px;
   }
   .bg-bl{
   background-color: #3498db;
   color: white;
   }
   .icon { 
   float: right;
   margin-right: 6px;
   margin-top: -20px;
   position: relative;
   z-index: 2;
   /*        color: red;*/
   }
   .sho-cat
   {
   /*   display: none;*/
   }
   .ds{
   display: inline;
   float: left;
   }
   .column:hover {
   box-shadow: 0 1px 2.94px 0.06px blue;
   background-color: #0479cc;
   }
   .main_img_div img{
   max-height: 92px !important;
   max-width: 92px !important;
   display:block;
   margin:auto;
   }
   .after-column{
   text-align: center;
   overflow: hidden;
   white-space: nowrap;
   text-overflow: ellipsis;
   max-width: 150px;
   font-size: 11px;
   margin: auto;
   background-color: #0479cc;
   font-weight: bold;
   color: #FFFFFF;
   padding: 2px;
   min-height: 15px;
   overflow:hidden;
   margin-top: 2px;
   }
   .toggle-full-row{
   text-align: center;
   overflow: hidden;
   background-color: #0479cc;
   font-weight: bold;
   color: #FFFFFF;
   }
   /* Clearfix (clear floats) */
   .row::after {
   content: "";
   clear: both;
   display: table;
   }
   .totalval{
   color: #6FD64B;
   font-size: 22px;
   }
   .due{
   color: #ff9e28;
   font-size: 22px;
   }
   .total{
   font-size: 22px;
   border: 0.5px dotted;
   }
   .paymenttotal{
   margin-top: 20px;
   margin-bottom: 20px;
   border-top: 0.5px solid;
   border-bottom: 0.5px dotted;
   border-left: 0.5px dotted;
   border-right: 0.5px dotted;
   }
   label {
   /* Other styling... */
   text-align: right;
   clear: both;
   float:left;
   margin-right:15px;
   }
   .inf{
   background-color: #3c8d;
   }
   .btn{
   background-color: #dee0df;
   }
   .col{
   border: 0.5px solid;
   border-radius: 5px;
   }
   .mr{
   margin-top: 20px;
   }
   .qnt{
   height: 20px;
   background-color: #eaf5da;
   border-top: 0.5px solid;
   border-bottom: 0.5px solid;
   }
   .subtottal{
   border-bottom: 0.5px dotted;
   }
   tr.spc th {
   padding-top: 2px; 
   padding-bottom:2px
   }
   .sl{
   border-top-right-radius: 10px;
   border-top-left-radius: 10px;
   background-color: #D5E4EC;
   margin-bottom: 20px;
   }
   .black-dashed{
   border: 1px dotted;
   }
   .mx-h{
   max-height: 300px;
   overflow-y: scroll;
   }
   @media(min-width: 355px) {
   .column {
   float: left;
   width: 47%;
   padding: 5px;
   overflow: hidden;
   display: flex;
   flex-wrap: wrap;
   margin: 2px;
   background-color: #fff;
   border: 0.5px solid;
   border-radius: 5px;
   text-align: center;
   }
   .toggle-full-row{
      font-size: 8px;
   }
}  
   @media(min-width: 720px) {
   .column {
   float: left;
   width: 23%;
   padding: 5px;
   overflow: hidden;
   display: flex;
   flex-wrap: wrap;
   margin: 2px;
   background-color: #fff;
   border: 0.5px solid;
   border-radius: 5px;
   text-align: center;
   }
   
   }
   .cncl-sspnd{
   float:left;
   width: 50%; 
   display: inline;"
   }
</style>
@endpush
<div class="page-header card">
<div class="card-block">
   <h5 class="m-b-10 text-center">Point Of Sale</h5>
   <p class="text-muted m-b-10 text-center">Create Sale</p>
   <div class="row">
      <div class="col-lg-5">
         <div class="card author-box col">
            <table class="mr">
               <tbody>
                  <tr class="qnt spc">
                     <th style="width: 55%;">Quantity of 2 Items</th>
                     <th style="width: 45%; text-align: right;">4</th>
                  </tr>
                  <tr class="subtottal">
                     <th style="width: 55%;">Subtotal</th>
                     <th style="width: 45%; text-align: right;">PKRs&nbsp;900.00</th>
                  </tr>
                  <tr class="total">
                     <th style="width: 55%;">Total</th>
                     <th style="width: 45%; text-align: right;" class="totalval"><span id="sale_total">PKRs&nbsp;900.00</span></th>
                  </tr>
               </tbody>
            </table>
            <table class="paymenttotal" id="payment_totals">
               <tbody>
                  <tr class="spc paymenttotal">
                     <th style="width: 55%;">Payments Total</th>
                     <th style="width: 45%; text-align: right;">PKRs&nbsp;0.00</th>
                  </tr>
                  <tr class="total paymenttotal">
                     <th style="width: 55%;">Amount Due</th>
                     <th class="due" style="width: 45%; text-align: right;"><span id="sale_amount_due">PKRs&nbsp;900.00</span></th>
                  </tr>
               </tbody>
            </table>
            <div class="sl">
               <table>
                  <tbody>
                     <tr class="">
                        <th style="width: 100%">Payment Type</th>
                        <th style="width: 100%" class="form-group">
                           <div>
                              <select class="form-rounded">
                                 <option value="Daily">Daily</option>
                                 <option value="Weekly">Weekly</option>
                                 <option value="Monthly">Monthly</option>
                              </select>
                           </div>
                        </th>
                     </tr>
                     <tr class="">
                        <th style="width: 50%">Amount Tendered</th>
                        <th style="width: 50%" class="form-group float-left">
                           <input type="number" class="form-rounded" style="width: 200%;" name="">
                        </th>
                     </tr>
                  </tbody>
               </table>
               <div style="margin-top : 20px; margin-bottom : 10px;">
                  <a href="" class="btn-sm form-rounded form-control btn-success text-center">Add Payment</a>
               </div>
               <div class="cncl-sspnd">
                  <a href="" class="btn-sm form-rounded form-control btn-warning text-center" >Suspend</a>
               </div>
               <div class="cncl-sspnd">
                  <a href="" class="btn-sm form-rounded form-control btn-danger text-center">Cancel</a>
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-7">
         <div class="page-header card col">
            <div class="card-block">
               <div class="form-group ">
                  <div class="">
                     <div class="row">
                        <div class="input-group col-md-8">
                           <span class="input-group-addon form-rounded" id="name"><i class="ti-dropbox"></i></span>
                           <input type="text" class="form-control input-sm form-rounded" placeholder="Start typing Item Name or scan Barcode...">
                        </div>
                        <div class="form-group col-md-4">
                           <a class="btn btn-sm pull-right btn-out-dashed btn-round btn-grd-primary show-category" style="color: white;">Show Category</a>
                        </div>
                     </div>
                  </div>
                  <div class="">
                     <div class="">
                        <div class=" sho-cat">
                           <div>
                              @foreach($categories as $cat)
                              @php
                              $catArray[$cat->id] = $cat->product->where('category_id' , $cat->id)->pluck('id')->toArray();
                              @endphp
                              <a class="btn btn-sm black-dashed btn-round filter-category"  data-id="{{ $cat->id }}" rel="Ice cream " href="" >{{ $cat->name }} </a>
                              @endforeach
                              <a class="btn btn-sm black-dashed btn-round show-all" href="" data-id="{{ 'show-all' }}" rel="Ice cream " >{{ "Show All" }} </a>
                           </div>
                        </div>
                     </div>
                  </div>
                  <br>
                  <div class="row mx-h">
                     <!-- ->where('category_id' , 10) -->
                     @foreach($categories as $cat)
                     <!-- <div id="cat-filter-{{ $cat->id }}" class="float-left" style="display:inline;"> -->
                     @foreach($cat->product as $product)
                     @if(!empty($product->img))
                     <div 
                        class="column card main_img_div text-center justify-content-center" 
                        data-id="{{ $product->id }}" 
                        id="cat-filter-{{ $product->id }}"
                        >
                        <img 
                           src="{{ asset($product->img) }}" 
                           alt="Snow" 
                           style="width:100%"
                           >
                        @else
                        <div 
                           class="column card main_img_div text-center justify-content-center" 
                           data-id="{{ $product->id }}" 
                           id="cat-filter-{{ $product->id }}"
                            
                           >
                            <img 
                           src="{{ asset('product/product.png') }}" 
                           alt="Snow" 
                           style="width:100%"
                           >
                        
                           @endif
                           <div 
                           class="after-column form-rounded form-control" 
                           id="ful-d0-{{ $product->id }}"
                           style="background-color: #ff163d;" 
                           >
                              <span>{{ $product->name }}</span>
                           </div>
                           <div 
                           class="after-column form-rounded form-control "  
                           id="ful-d1-{{ $product->id }}"
                           style="background-color: #6FD64B" 
                           >
                              <span>Rs:{{ $product->price }}</span>
                           </div>
                           <div class="toggle-full-row form-rounded form-control d-none" id="ful-d-{{ $product->id }}" style="background-color: red">
                              <span>{{ $product->name }} | Rs:{{ $product->price }}</span>
                           </div>
                        </div>
                        @endforeach
                        <!-- </div> -->
                        @endforeach
                     </div>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="card col">
                     <div class="padding-20">
                        <div class="section-body ">
                        </div>
                        <div class="tab-content tab-bordered" id="myTab3Content">
                           <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2">
                              <div class="row mx-h">
                                 <div class="col-12">
                                    <div class="card">
                                       <div class="card-body p-0">
                                          <div class="table-responsive">
                                             <table class="table table-hover">
                                                <thead class="bg-bl">
                                                  <!--  <tr>
                                                      <th>Item #</th>
                                                      <th>Item Name</th>
                                                      <th>Price</th>
                                                      <th>Quantity</th>
                                                      <th>Disc</th>
                                                      <th>Total</th>
                                                      <th>Action</th>
                                                   </tr> -->
                                                </thead>
                                                <tbody>
                                                    @foreach($categories as $cat)
                     <!-- <div id="cat-filter-{{ $cat->id }}" class="float-left" style="display:inline;"> -->
                     @foreach($cat->product as $product)
                     
                                                   <tr>
                                                   
                                                      <td class="main_img_div float-left"> 
                                                         @if(!empty($product->img))
                                                         <img 
                                                   src="{{ asset($product->img) }}" 
                                                   alt="Snow" 
                                                   style="width:100%"
                                                   >
                                                   @else
                                                    <img 
                                                   src="{{ asset('product/product.png') }}" 
                                                   alt="Snow" 
                                                   style="width:100%"
                                                   >
                                                   @endif

                                                 
                                                </td>
                                                <td>
                                                     <div 
                           class="after-column form-rounded form-control" 
                           id="ful-d0-{{ $product->id }}"
                           style="background-color: #ff163d;" 
                           >
                              <span>{{ $product->name }}</span>
                           </div>
                           <div 
                           class="after-column form-rounded form-control "  
                           id="ful-d1-{{ $product->id }}"
                           style="background-color: #6FD64B" 
                           >
                              <span>Rs:{{ $product->price }}</span>
                           </div>
                                                </td>
                                                   
                                                   </tr>
                                                   
                                                   @endforeach
                                                   @endforeach
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                              <div class="row">
               <div class="col-12">
                  <div class="card col">
                     <div class="padding-20">
                        <div class="section-body ">
                        </div>
                        <div class="tab-content tab-bordered" id="myTab3Content">
                           <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="home-tab2">
                              <div class="row">
                                 <div class="col-12">
                                    <div class="card">
                                       <div class="card-body p-0">
                                          <div class="table-responsive">
                                             <table class="table table-hover">
                                                <thead class="bg-bl">
                                                   <tr>
                                                      <th>Item #</th>
                                                      <th>Item Name</th>
                                                      <th>Price</th>
                                                      <th>Quantity</th>
                                                      <th>Disc</th>
                                                      <th>Total</th>
                                                      <th>Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   <tr>
                                                      <td>1</td>
                                                      <td>Caramal</td>
                                                      <td ><input type="number" style="max-width: 50px;" name="" class="form-rounded" value="100">
                                                      </td>
                                                      <td><input style="max-width: 50px;" type="number" name="" class="form-rounded" value="100">
                                                      </td>
                                                      <td><input style="max-width: 50px;" type="number" name="" class="form-rounded" value="100">
                                                      </td>
                                                      <td><input style="max-width: 50px;" type="number" name="" class="form-rounded" value="100">
                                                      </td>
                                                      <td><a href="" class="btn btn-sm pull-right btn-out-dashed btn-round btn-grd-primary ">Update</a></td>
                                                   </tr>
                                                    <tr>
                                                      <td>1</td>
                                                      <td>Caramal</td>
                                                      <td ><input type="number" style="max-width: 50px;" name="" class="form-rounded" value="100">
                                                      </td>
                                                      <td><input style="max-width: 50px;" type="number" name="" class="form-rounded" value="100">
                                                      </td>
                                                      <td><input style="max-width: 50px;" type="number" name="" class="form-rounded" value="100">
                                                      </td>
                                                      <td><input style="max-width: 50px;" type="number" name="" class="form-rounded" value="100">
                                                      </td>
                                                      <td><a href="" class="btn btn-sm pull-right btn-out-dashed btn-round btn-grd-primary ">Update</a></td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
                  </div>

               </div>
            </div> 

         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
<script>
   $(document).on('click', '.show-category', function(){
           $('.sho-cat').toggle();
       });
   
   $(document).on('click', '.filter-category', function(){
   
           const cat =  $(this).data('id');
           var catArray = @json($catArray);
           var allcat =[];
           $.each(catArray , function(index, val) { 
               allcat = allcat.concat(val);
              });
          
           $.each(allcat , function(index, val) { 
            $('#cat-filter-' + val).addClass('d-none');
            
           });
   
            $.each(catArray[cat] , function(index, val) { 
            $('#cat-filter-' + val).removeClass('d-none');
           });
           return false;
          
       });
   $(document).on('click', '.show-all', function(){
   
              var catArray = @json($catArray);
           var allcat =[];
           $.each(catArray , function(index, val) { 
               allcat = allcat.concat(val);
              });
          
           $.each(allcat , function(index, val) { 
            $('#cat-filter-' + val).removeClass('d-none');
            
           });
   
            
           return false;
   
          
          
       });
   var oldID = "";
   $(document).on('click', '.column', function(){
   
              
              var id = $(this).data('id');
   
              if (id != oldID){
                 $('#ful-d0-'+id).addClass('d-none');
                 $('#ful-d1-'+id).addClass('d-none');
                 $('#ful-d-'+id).removeClass('d-none');
   
                 $('#ful-d0-'+oldID).removeClass('d-none');
                $('#ful-d1-'+oldID).removeClass('d-none');
                $('#ful-d-'+oldID).addClass('d-none');
   
                 oldID = $(this).data('id');
              }else{
                $('#ful-d0-'+oldID).removeClass('d-none');
                $('#ful-d1-'+oldID).removeClass('d-none');
                $('#ful-d-'+oldID).addClass('d-none');
              }
              console.log(oldID);
   
              
              
          
          
       });
   
</script>
@endpush