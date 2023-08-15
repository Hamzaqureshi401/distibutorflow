function myFunction() { 
  var x = document.getElementById("mySelect").value;
  document.getElementById("demo").value = x;
}

function disableButton() {

        var btn = document.getElementById('button');
        btn.disabled = true;
        btn.innerText = 'Order Updating Wait'
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

//   $('.update-type input:radio').on('change' , function(){

//     if(this.checked){
//       if($(this).hasClass('full-edit')){
//         $('#invoice-form').attr('action' , $('#amount-left-r-form').attr('action')).show();
//         $('#amount-left-r-form').hide();
//       }
//       else{
//         $('#amount-left-r-form').attr('action' , $('#invoice-form').attr('action')).show();
//         $('#invoice-form').hide(); 
//       }
//     }
//   });
$(document).ready(function(){
    $('.class1').on('change', function(){        
        if($('.class1:checked').length){
            $('.class3').prop('disabled', true);
            $('.class3').prop('checked', false);
            return;
        }
        
        $('.class3').prop('disabled', false);
    });
    
    $('.class2').on('change', function(){
        if(!$(this).prop('checked')){
            $('.class2').prop('disabled', false);
            return;
        }
        $('.class2').prop('disabled', true);
        $(this).prop('disabled', false);
        
        !$('.class1:checked').length ? $('.class1').click() : '';
    });
})
// show all product
$(document).on('click', '.sl-toggler', function(){
        $('.not-in-sl').toggle();
    });
    function UseStock (){
        
    var order_id = @json($order->id); 
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
    var cookie_name = "Edit Order Form"; 
    var form_type = "GET";
    var url = "{{ route('update.order') }}" + '/' + param;
    console.log("Order id is" + @json($order->id));
    var submit_data = 0;
    var btn = document.getElementById('button');
    var btn_innertext = "Store";

/* Setting End*/
    
    cookie_name = crypt("salt", cookie_name);
    $(function () {
        $('form').on('submit', function (e) {
          e.preventDefault();         
          e.stopImmediatePropagation();
          btn.disabled = true;
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
    $('#invoice-form')[0].reset();
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
    var form = $('form').serialize();
    postdata(form);
    $('#invoice-form')[0].reset();
  }
 }

 function postdata(data){
      
      if (submit_data == 0){
        submit_data = 1;
    $.ajax({
            type: form_type,
            url: url,
            data: data,
            success: function (data) {
              btn.disabled = false;
              btn.innerText = btn_innertext;
              var nType = "success";
              var title = "Success ";
              var msg = data.message;
              notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
                //$('#button').addClass('btn-primary');
                $("#edit-order-detail-popup .close").click();
             // console.log(data.data);
              console.log(data.data.amount);
              var id = '#order-id-' + param;
              console.log(id);
              $('#order-unit-' + param).html(data.data.unit);
              $('#order-amount-' + param).html(data.data.amount);
              $('#order-subtotal-' + param).html(data.data.subtotal);
              $('#order-received_amount-' + param).html(data.data.received_amount);
              $('#order-amount_left-' + param).html(data.data.amount_left);
              }
          });

 }
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