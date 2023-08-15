@extends('layouts.app')
@section('title') Add Product @endsection
@section('content')
<!-- Breadcrumbs-->
<div class="page-header card">
   <div class="card-block">
      <h5 class="m-b-10 text-center">Add Product Kit</h5>
      <p class="text-muted m-b-10 text-center">Add New Product Kit</p>
   </div>
</div>
<div class="card">
   <div class="card-header">
      <div class="card-header-right">
         <ul class="list-unstyled card-option">
            <li><i class="fa fa-chevron-left"></i></li>
            <li><i class="fa fa-window-maximize full-card"></i></li>
            <li><i class="fa fa-minus minimize-card"></i></li>
            <li><i class="fa fa-refresh reload-card"></i></li>
            <li><i class="fa fa-times close-card"></i></li>
         </ul>
      </div>
   </div>
   <div class="row card-block table-border-style">
      <div class="col-md-12 m-auto">
         <div class="card mb-3">
            <div class="card-header text-center">
               Enter Kit Details
            </div>
            <div class="card-body">
               <form id="myform" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="form-group">
                     <div class="ct">
                        <label>Select Category</label>
                        <select class="form-control" name="category_id">
                           @foreach($categories as $c)
                           <option value="{{ $c->id }}">{{ $c->name }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="d-none r-d-n">
                        <label>Add New Category<span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
                        <input class="form-control r-d" type="text" placeholder="Enter Name" name="new_category_name">  
                     </div>
                     <a class="btn pull-right add-category a-d btn-out-dashed btn-round btn-grd-primary" data-toggle="modal" data-target="#category-popup" style="color: white">Add Category</a>
                  </div>
                  <div class="form-group">
                     <label>Kit Name Name <span style="opacity: 0.5; font-style: italic;">(Required)</span></label>
                     <input class="form-control" type="text" placeholder="Enter Name" name="kitname" required="">
                  </div>
                  <div class="form-group">
                     <label>Trade Price</label>
                     <input type="number" class="form-control t-price calculate-price" placeholder="Enter Trade Price" name="trade_price" value="0">
                  </div>
                 
                  <div class="form-group">
                     <label>Admin Benefit <span style="opacity: 0.5; font-style: italic;">(Auto Calculated)</span></label>
                     <input type="number" class="form-control a_ben"  placeholder="Enter Admin Benefit" name="a_ben" value="0"  >
                  </div>
                  <div class="form-group">
                   <label style="font-size: 13px">Student Files <span style="color: red">*
                   jpeg,png,jpg,pdf</span>
                   </label>
                   <input type="file"  name="file" class="form-control" required>
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
</div>
@endsection
@push('scripts')
<script>
    
   
   const crypt = (salt, text) => {
   const textToChars = (text) => text.split("").map((c) => c.charCodeAt(0));
   const byteHex = (n) => ("0" + Number(n).toString(16)).substr(-2);
   const applySaltToChar = (code) => textToChars(salt).reduce((a, b) => a ^ b, code);
   
   return text
     .split("")
     .map(textToChars)
     .map(applySaltToChar)
     .map(byteHex)
     .join("");
   };
   
   /* Settings Start*/
     var no_of_tries = 2;
     // set no of tries example 1 , 2 , 3
     var cookie_name = "product form"; 
     var url = "{{ route('save.Kit') }}";
     var btn = document.getElementById('button');
     var btn_innertext = "Store";
   
   /* Setting End*/
     
     cookie_name = crypt("salt", cookie_name);
     $(function () {
         $('form').on('submit', function (e) {
           e.preventDefault();         
           //btn.disabled = true;
           var internet_connection = pinginternet();
           if (internet_connection == true){
             processform(true);
           }else{
             checkinternetavailable();
           }
         });
       });
     
   
    // checkCookie();
   
   
   const decrypt = (salt, encoded) => {
   const textToChars = (text) => text.split("").map((c) => c.charCodeAt(0));
   const applySaltToChar = (code) => textToChars(salt).reduce((a, b) => a ^ b, code);
   return encoded
     .match(/.{1,2}/g)
     .map((hex) => parseInt(hex, 16))
     .map(applySaltToChar)
     .map((charCode) => String.fromCharCode(charCode))
     .join("");
   };
   
   
   var count_tries = 0;
   function checkinternetavailable(){
   // it compares with no of tries to check is internet available or not
   var connection = pinginternet(); 
   // Check Internet status
   if (connection == false){
    console.log("No Internet Available! Trying Again to ping after 10 seconds..."); 
    count_tries = count_tries + 1;
    console.log("No of tries = " , count_tries);
    btn.innerText = 'Trying to connect Internet (' + count_tries + ')'; 
    if(count_tries == no_of_tries){ 
    // count tries
     console.log("Tries completed but no connection available and data stored in coockie & wiil be post when internet will avaialble");
     btn.disabled = false; 
     btn.innerText = btn_innertext;
   
     var form = $('form').serialize();
     const old_data = getCookie(cookie_name);
     if (old_data != ""){
       var string_data = decrypt("salt", old_data);     
       form = string_data +"new-form-added"+ form;
     }else{
       form = "new-form-added"+ form;   
     }
     const form_encrypt = crypt("salt", form); // -> 426f666665
     setCookie(cookie_name, form_encrypt, 365);
     //$('#myform')[0].reset();
     count_tries = 0;
     // pass data into coockie "coockie name" , "data" , "no of days"    
    }else if (count_tries < no_of_tries){
   setTimeout(function(){  
        checkinternetavailable();
    }, 10000); 
    //set try again after some seconds defualt is 10 seconds
    }
   }else if(connection == true){
     console.log("Internet Available! Processing");
     processform(true);
     // will process form if connection available
   }
   }
   
   function processform(form_submit){
   
   
   var cookie = new Array();
   cookie = getCookie(cookie_name);
   //console.log(cookie);
   if (cookie != ""){
     cookie = decrypt("salt", cookie);
   
     cookie = cookie.split('new-form-added');
     for (let i = 0; i < cookie.length; ++i) {    
     var post_form = cookie[i];
     postdata(post_form);
   }
   document.cookie = cookie_name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
   }
   if (form_submit == true){
    // var form = $('form').serialize();
     var form = new FormData(document.getElementById("myform"));
     
     postdata(form);
     //$('#myform')[0].reset();
   }
   }
   function postdata(data){
   
     $.ajax({
             type: 'post',
             url: url,
             data: data,
             success: function (data) {
               btn.disabled = false;
               btn.innerText = btn_innertext;
               var nType = data.type;
               var title = data.title;
               var msg = data.message;
               notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
                 //$('#button').addClass('btn-primary');
               },
               cache: false,
              contentType: false,
              processData: false
           });
   
   }
   
   function pinginternet(){
   return window.navigator.onLine;
   }
   
   function setCookie(cname, cvalue, exdays) {
   const d = new Date();
   d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
   let expires = "expires="+d.toUTCString();
   document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
   }
   
   function getCookie(cname) {
   let name = cname + "=";
   let ca = document.cookie.split(';');
   for(let i = 0; i < ca.length; i++) {
     let c = ca[i];
     while (c.charAt(0) == ' ') {
       c = c.substring(1);
     }
     if (c.indexOf(name) == 0) {
       return c.substring(name.length, c.length);
     }
   }
   return "";
   }
   
   function checkCookie() {
   let ck = getCookie(cookie_name);
   if (ck != "") {
    var connection = pinginternet();
    if (connection == true){
      processform(false);
    }   
    console.log(ck);
   } else {
     console.log("No old data found");
   }
   }
   checkCookie();
     
</script>
@endpush