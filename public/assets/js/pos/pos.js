  $(document).on('click', '.cancel', function() {
        $.each(allPr, function(index, find) {
            if ($('#clone-' + find).length) {
                var row = $('#clone-' + find);
                remove(row);
            }
            total = 0;
            balance = 0;
            $('#sale_total').html("PKRs: " + Number(total + balance).toFixed(2));
            $('#amountReceived').val(0);
        });
    });
var toggle = 1;
$(document).on('click', '.show-category', function() {
    $('.sho-cat').toggle();
    if (toggle == 1) {
        $(this).html('Show Category');
        toggle = 2;
    } else {
        $(this).html('Hide Category');
        toggle = 1;
    }

});
var sel = "";

$(document).on('click', '.filter-category', function() {

    if (sel != "") {
        sel.removeClass('btn-primary');
    }
    sel = $(this);

    $('.show-category').removeClass('btn-primary')

    $(this).addClass('btn-primary')
    $('.show-all').removeClass('btn-primary')

    const cat = $(this).data('id');
    var catArray = @json($catArray);
    var allcat = [];
    $.each(catArray, function(index, val) {
        allcat = allcat.concat(val);
    });

    $.each(allcat, function(index, val) {
        $('#cat-filter-' + val).addClass('d-none');

    });

    $.each(catArray[cat], function(index, val) {
        $('#cat-filter-' + val).removeClass('d-none');
    });
    return false;

});
$(document).on('click', '.show-all', function() {

    $(this).addClass('btn-primary')
    if (sel != "") {
        sel.removeClass('btn-primary');

    }

    var catArray = @json($catArray);
    var allcat = [];
    $.each(catArray, function(index, val) {
        allcat = allcat.concat(val);
    });

    $.each(allcat, function(index, val) {
        $('#cat-filter-' + val).removeClass('d-none');

    });
    return false;
});
var oldID = "";
$(document).on('click', '.column', function() {


    var id = $(this).data('id');
    if (id != oldID) {
        $('#ful-d0-' + id).addClass('d-none');
        $('#ful-d1-' + id).addClass('d-none');
        $('#ful-d-' + id).removeClass('d-none');

        $('#ful-d0-' + oldID).removeClass('d-none');
        $('#ful-d1-' + oldID).removeClass('d-none');
        $('#ful-d-' + oldID).addClass('d-none');

        oldID = $(this).data('id');
    } else {
        $('#ful-d0-' + oldID).removeClass('d-none');
        $('#ful-d1-' + oldID).removeClass('d-none');
        $('#ful-d-' + oldID).addClass('d-none');
    }
});

// tr handle 
var click = "";
var oldclick = "";
var click_count = "";
var productArray = new Array();
var price = '';
var name = '';
var $tr = '';
// upper cart
var qty_of = 0;
var subtotal = '';
var total = 0;
var balance = 0;
var receivedamount = '';
var sum_qt_val = 0;

$(document).on('click', '.product_tr_clone', function() {


    var plus_val = $(this).data('plus') - 1;
    //    console.log(plus_val , );
    if (plus_val == $(this).data('id')) {
        click = $(this).data('id');
        click_count = 1;
    }

    $('#clone-' + oldclick).removeClass('trcolr');
    $('#clone-' + $(this).data('id')).addClass('trcolr');
    setThisval(this);
    if (click == "") {
        click = $(this).data('id');

        click_count = 1;
    } else if (click == $(this).data('id') && click_count == 1) {
        //pushProduct(click);
        var findProduct = inArrayProduct(click);
        if (findProduct == 'notExist') {
            addNewRow(click);
            assignId(click);
            setNewValues(click);

        } else {
            setOldRowValues(click);
        }
        calculateupperCart();

        click = "";
        click_count = "";

    } else if (click != $(this).data('id')) {
        click = $(this).data('id');
        click_count = 1;
        oldclick = click;
    }

    function setThisval(ths) {
        price = $(ths).data('price');
        console.log(price);
        console.log($('[data-id="23"]').data('price'));
        name = $(ths).data('name');

    }

    function setNewValues(click) {
        var qty = $('#quantity-' + click).val(1);
        var disc = $('#discount-' + click).val(0);
        var pro = $('#product_name-' + click).html(name);
        var prc = $('#price-' + click).html('Price:' + price);
        var prc = $('#total-' + click).html('Total:' + (price));
        $('#notify-badge-' + click).removeClass('d-none');
        var notify = $('#notify-badge-' + click).html(1);
        oldclick = click;
    }

    function setOldRowValues(click) {

        var qty = $('#quantity-' + click).val();
        qty = ++qty;
        $('#quantity-' + click).val(qty);
        var prc = $('#total-' + click).html('Total:' + (price * qty));
        var notify = $('#notify-badge-' + click).html(qty);
        oldclick = click;
        $('#clone-' + click).prependTo('#tb');

    }

    function addNewRow(click) {
        pushProduct(click);
        $tr = $('#dummyTr');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $tr.after($clone);
        $tr.prop('id', 'clone-' + click);
        $tr.find('input').attr('data-id', click);
        $('#clone-' + click).addClass('trcolr');
        $tr.removeClass('d-none');
        qty_of = ++qty_of;
        $('#qty_of').html(qty_of);
        $('#cat-filter-' + click).addClass('selectedDiv');
        $('#clone-' + click).prependTo('#tb');
        $('.trmx-h').removeClass('d-none');



    }




    function assignId(click) {


        var quantity = $($tr).find($('input[name="unit[]"]'));
        var discount = $($tr).find($('input[name="discount[]"]'));
        var product_name = $($tr).find($('span[data-pname="product-name"]'));
        var price = $($tr).find($('b[data-pprice="price"]'));
        var total = $($tr).find($('div[data-ptotal="total"]'));

        quantity.eq(0).attr('id', 'quantity-' + click);
        discount.eq(0).attr('id', 'discount-' + click);
        product_name.eq(0).attr('id', 'product_name-' + click);
        price.eq(0).attr('id', 'price-' + click);
        total.eq(0).attr('id', 'total-' + click);

    }

    function pushProduct(click) {
        productArray.push(parseInt(click));
        productArray = productArray.filter(onlyUnique);

    }

    function inArrayProduct(click) {
        if (jQuery.inArray(click, productArray) != -1) {
            return "exist";
        } else {
            return 'notExist';
        }
    }

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }
    fittext();

});

function runtimeCalculate(input) {
    click = input.getAttribute('data-id');
    $('#clone-' + oldclick).removeClass('trcolr');
    $('#clone-' + click).addClass('trcolr');
    var qty = input.value;
    var prc = $('#price-' + click).html();
    price = prc.split('Price:')[1];
    var prc = $('#total-' + click).html('Total:' + (price * qty));
    var notify = $('#notify-badge-' + click).html(qty);
    oldclick = click;



    calculateupperCart();

    //    var objDiv = document.getElementById("div");
    // objDiv.scrollTop = objDiv.scrollHeight;
}

function calculateupperCart() {
    $.each(productArray, function(index, click) {
        sum_qt_val = parseInt(sum_qt_val) + parseInt($('#quantity-' + click).val());
        var a = $('#total-' + click).html();
        a = a.split('Total:')[1];
        total = parseInt(total) + parseInt(a);

    });
    $('#sum_qt_val').html(sum_qt_val);
    $('#subtottal').html(Number(total).toFixed(2));
    $('#dummyTotal').html(Number(total + balance).toFixed(2));

    $('#sale_total').html("PKRs: " + Number(total + balance).toFixed(2));
    sum_qt_val = 0;
    total = 0;
    fittext();

}


$(document).on("keyup", calculateTotalUpperCart);

function calculateTotalUpperCart() {
    var sum = 0;
    $("#amountReceived").each(function() {
        val = +$(this).val();

    });
    var sale_total = $('#dummyTotal').html();
    $("#paymenttotal").html(Number(val).toFixed(2));
    $('#sale_amount_due').html("PKRs: " + Number(sale_total - val).toFixed(2));
    fittext();
}


function remove(row) {
    row.closest('tr').remove();
    var id = row.closest('tr').attr('id');
    var p_id = id.split('-')[1];
    var newArray = new Array();
    $.each(productArray, function(index, click) {
        if (click != p_id) {
            newArray.push(parseInt(click));
        }
    });
    productArray = newArray;
    calculateupperCart();
    $('#notify-badge-' + p_id).addClass('d-none');
    $('#cat-filter-' + p_id).removeClass('selectedDiv');
    qty_of = --qty_of;
    $('#qty_of').html(qty_of);
}

function fittext() {


    var maxW = 100,
        maxH = 33,
        maxSize = 20;
    var c = document.getElementsByClassName("fitin");
    var e = document.getElementsByClassName("fitinsubtotal");

    var d = document.createElement("span");
    var len = c.length;
    //alert(window.screen.width , window.screen.width);
    if (window.screen.width <= 360 && len < 11) {
        d.style.fontSize = maxSize + "px";


        for (var i = 0; i < c.length; i++) {
            d.innerHTML = c[i].innerHTML;
            document.body.appendChild(d);
            var w = d.offsetWidth;
            var h = d.offsetHeight;
            document.body.removeChild(d);
            var x = w > maxW ? maxW / w : 1;
            var y = h > maxH ? maxH / h : 1;
            var r = Math.min(x, y) * maxSize;
            c[i].style.fontSize = r + "px";
        }

        $('.totalval').css({
            fontSize: "10px"
        });
    }
    fitSubtotal();
}

function fitSubtotal() {

    var maxW = 80,
        maxH = 33,
        maxSize = 20;
    var c = document.getElementsByClassName("fitinsubtotal");

    var d = document.createElement("span");
    var len = c.length;
    //alert(window.screen.width , window.screen.width);
    if (window.screen.width <= 360 && len < 12) {
        d.style.fontSize = maxSize + "px";


        for (var i = 0; i < c.length; i++) {
            d.innerHTML = c[i].innerHTML;
            document.body.appendChild(d);
            var w = d.offsetWidth;
            var h = d.offsetHeight;
            document.body.removeChild(d);
            var x = w > maxW ? maxW / w : 1;
            var y = h > maxH ? maxH / h : 1;
            var r = Math.min(x, y) * maxSize;
            c[i].style.fontSize = r + "px";
        }
    }
    fitQtyOfItem();
}

function fitQtyOfItem() {

    var maxW = 30,
        maxH = 33,
        maxSize = 10;
    var c = document.getElementsByClassName("fitQtyOfItem");

    var d = document.createElement("span");
    var len = c.length;
    //alert(window.screen.width , window.screen.width);
    if (window.screen.width <= 360 && len < 5) {
        d.style.fontSize = maxSize + "px";



        for (var i = 0; i < c.length; i++) {
            d.innerHTML = c[i].innerHTML;
            document.body.appendChild(d);
            var w = d.offsetWidth;
            var h = d.offsetHeight;
            document.body.removeChild(d);
            var x = w > maxW ? maxW / w : 1;
            var y = h > maxH ? maxH / h : 1;
            var r = Math.min(x, y) * maxSize;
            c[i].style.fontSize = r + "px";
        }
    } else if (len < 8) {
        var maxW = 60,
            maxH = 33,
            maxSize = 10;
        d.style.fontSize = maxSize + "px";



        for (var i = 0; i < c.length; i++) {
            d.innerHTML = c[i].innerHTML;
            document.body.appendChild(d);
            var w = d.offsetWidth;
            var h = d.offsetHeight;
            document.body.removeChild(d);
            var x = w > maxW ? maxW / w : 1;
            var y = h > maxH ? maxH / h : 1;
            var r = Math.min(x, y) * maxSize;
            c[i].style.fontSize = r + "px";
        }

    }
    fitPaymentTotal()
}

function fitPaymentTotal() {

    var maxW = 55,
        maxH = 33,
        maxSize = 10;
    var c = document.getElementsByClassName("fitPaymentTotal");

    var d = document.createElement("span");
    var len = c.length;
    //alert(window.screen.width , window.screen.width);
    if (window.screen.width <= 360 && len < 5) {
        d.style.fontSize = maxSize + "px";



        for (var i = 0; i < c.length; i++) {
            d.innerHTML = c[i].innerHTML;
            document.body.appendChild(d);
            var w = d.offsetWidth;
            var h = d.offsetHeight;
            document.body.removeChild(d);
            var x = w > maxW ? maxW / w : 1;
            var y = h > maxH ? maxH / h : 1;
            var r = Math.min(x, y) * maxSize;
            c[i].style.fontSize = r + "px";
        }
    }
}

var blink_speed = 300; // every 1000 == 1 second, adjust to suit
var t = setInterval(function() {
    var ele = document.getElementById('myBlinkingDiv');
    ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
}, blink_speed);


var allPr = @json($product->pluck('id')->toArray());
// live search
$(document).ready(function() {
    $("#srehberText").keyup(function() {

        // Retrieve the input field text and reset the count to zero
        var filter = $(this).val();

        count = 0;
        if (!filter) {
            $(".commentlist li").fadeOut();
            return;
        }

        var regex = new RegExp(filter, "i");
        // Loop through the comment list
        $(".commentlist li").each(function() {

            // If the list item does not contain the text phrase fade it out
            if ($(this).text().search(regex) < 0) {
                $(this).hide();

                // Show the list item if the phrase matches and increase the count by 1
            } else {
                $(this).fadeIn();
                count++;
            }
        });


    });
    $(".commentlist li a").click(function() {
        var val = $(this).text();
        $("#srehberText").val(val.trim());
        $('.commentlist li').fadeOut();
        $('#srehberText').val('');
        //console.log(this);
    });
});

var allPr = @json($product->pluck('id')->toArray());

$("#srehberText").keyup(function(event) {

    if (event.keyCode == 13) {

        var newVal = $(this).val();
        $.each(allPr, function(index, find) {

            if (find == newVal) {

                $('#cat-filter-' + newVal).click();
                $('#cat-filter-' + newVal).click();

                $('#srehberText').val('');



            }
        });
    }
});


// var keys = "";
//     $(function () {
//       $(document).keyup(function (e) {



//          if (keys == ""){
//           keys = e.key;
//          }

//          else{
//             keys = keys+event.key;
//          }

//          console.log(keys , e.keyCode);
//          $("#srehberText").val(keys);
//          if (event.keyCode == 13) {

//       var newVal = $(this);
//       console.log(newVal);
//          $.each(allPr, function(index, find) {

//           if (find == newVal){

//                $('#cat-filter-'+ newVal).click();
//                $('#cat-filter-'+ newVal).click();

//                $('#srehberText').val('');



//           }
//        }); 
//     }

//       });
//     });

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
var url = "{{ route('add.PosSale') }}";
var btn = document.getElementById('button');
var btn_innertext = "Store";

/* Setting End*/

cookie_name = crypt("salt", cookie_name);
$(function() {
    $('form').on('submit', function(e) {
        e.preventDefault();
        //btn.disabled = true;
        var internet_connection = pinginternet();
        if (internet_connection == true) {
            processform(true);
        } else {
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

function checkinternetavailable() {
    // it compares with no of tries to check is internet available or not
    var connection = pinginternet();
    // Check Internet status
    if (connection == false) {
        console.log("No Internet Available! Trying Again to ping after 10 seconds...");
        count_tries = count_tries + 1;
        console.log("No of tries = ", count_tries);
        btn.innerText = 'Trying to connect Internet (' + count_tries + ')';
        if (count_tries == no_of_tries) {
            // count tries
            console.log("Tries completed but no connection available and data stored in coockie & wiil be post when internet will avaialble");
            btn.disabled = false;
            btn.innerText = btn_innertext;

            var form = $('form').serialize();
            const old_data = getCookie(cookie_name);
            if (old_data != "") {
                var string_data = decrypt("salt", old_data);
                form = string_data + "new-form-added" + form;
            } else {
                form = "new-form-added" + form;
            }
            const form_encrypt = crypt("salt", form); // ->426f666665
            setCookie(cookie_name, form_encrypt, 365);
            //$('#myform')[0].reset();
            count_tries = 0;
            // pass data into coockie "coockie name" , "data" , "no of days"    
        } else if (count_tries < no_of_tries) {
            setTimeout(function() {
                checkinternetavailable();
            }, 10000);
            //set try again after some seconds defualt is 10 seconds
        }
    } else if (connection == true) {
        console.log("Internet Available! Processing");
        processform(true);
        // will process form if connection available
    }
}

function processform(form_submit) {


    var cookie = new Array();
    cookie = getCookie(cookie_name);
    //console.log(cookie);
    if (cookie != "") {
        cookie = decrypt("salt", cookie);

        cookie = cookie.split('new-form-added');
        for (let i = 0; i < cookie.length; ++i) {
            var post_form = cookie[i];
            postdata(post_form);
        }
        document.cookie = cookie_name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }
    if (form_submit == true) {
        // var form = $('form').serialize();
        var form = new FormData(document.getElementById("myform"));
        form.append('products_id', productArray);

        postdata(form);
        //$('#myform')[0].reset();
    }
}

function postdata(data) {

    console.log(productArray);

    $.ajax({
        type: 'post',
        url: url,
        data: data,
        success: function(data) {
            btn.disabled = false;
            btn.innerText = btn_innertext;
            var nType = data.type;
            var title = data.title;
            var msg = data.message;
            if (data.message == "At Least 1 Product is Required to Store Sale!") {
                toastr.error(data.message);
            } else {
                toastr.success(data.message);
                $('.cancel').click();
            }
            // notify(nFrom, nAlign, nIcons, nType, nAnimIn, nAnimOut ,title , msg);
            //   //$('#button').addClass('btn-primary');
            // },
        },
        cache: false,
        contentType: false,
        processData: false
    });

}

function pinginternet() {
    return window.navigator.onLine;
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
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
        if (connection == true) {
            processform(false);
        }
        console.log(ck);
    } else {
        console.log("No old data found");
    }
}
checkCookie();

$(".select2").select2({
    placeholder: "Select a Name",
    allowClear: true,
    theme: "classic"
});
var cproductarr = new Array();
var new_all_product = new Array();
var all_Product = @json(App\Models\Product::pluck('id')->toArray());
var all_Price = @json(App\Models\Product::pluck('price')->toArray());


function showAllProduct() {

    $('.sho-cat').removeClass('d-none');
    $.each(all_Product, function(index, val) {
        $('#cat-filter-' + val).removeClass('d-none');
        $('#cat-filter-plus-' + val).removeClass('d-none');
        $('#ful-d1-' + val).html('Rs:' + all_Price[index]);
        $('[data-id=' + val + ']').data('price', all_Price[index]);
        $('#price-' + val).html('Price:' + all_Price[index]);

    });
}

function showCustomeCustomerProduct(customer) {

    $('.sho-cat').addClass('d-none');
    var cdetails = $(customer).val().split('-');
    var v = cdetails[1];
    var dataProduct = @json(($data['product']));
    var old_balance = @json(($data['old_balance']));

    var cproduct = dataProduct[v];
    //balance = old_balance[v]; // if u will enable it then it will get previous balance of a customert
    balance = 0;

    console.log(balance);

    $.each(cproduct, function(index, val) {
        cproductarr.push(parseInt(val.id));
        $('#ful-d1-' + val.id).html('Rs:' + val.price);
        $('[data-id=' + val.id + ']').data('price', val.price);

    });
    var dif1 = all_Product.diff(cproductarr);
    cproductarr = new Array();

    $.each(dif1, function(index, val) {
        $('#cat-filter-' + val).addClass('d-none');
        $('#cat-filter-plus-' + val).addClass('d-none');
    });

}

function setCartBalance(balance) {

    $('#sale_total').html("PKRs: " + Number(total + balance).toFixed(2));
}
$('#customer-id').on('change', function() {
    $('#tog').removeClass('d-none');
    $('.cancel').click();
    balance = 0;
    showAllProduct();
    if ($(this).val() == 'defualt') {

    } else {
        showCustomeCustomerProduct(this);
    }

});
Array.prototype.diff = function(a) {
    return this.filter(function(i) {
        return a.indexOf(i) < 0;
    });
}; 