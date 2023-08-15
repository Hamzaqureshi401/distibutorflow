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

    .bg-bl {
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

    .sho-cat {
        /*   display: none;*/
    }

    .ds {
        display: inline;
        float: left;
    }

    .column:hover {
        box-shadow: 0 1px 2.94px 0.06px blue;
        background-color: #0479cc;
    }

    .main_img_div img {
        max-height: 92px !important;
        max-width: 92px !important;
        display: block;
        margin: auto;
    }

    .after-column {
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
        overflow: hidden;
        margin-top: 2px;
    }

    .toggle-full-row {
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

    .totalval {
        color: #ff0068;
        font-size: 22px;
    }

    .due {
        color: #ff9e28;
        font-size: 22px;
    }

    .total {
        font-size: 22px;
        border: 0.5px dotted;
    }

    .selectedDiv {
        background-color: #f70707;
    }

    .paymenttotal {
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
        float: left;
        margin-right: 15px;
    }

    .inf {
        background-color: #ff0068;
    }

    /*.btn{
   background-color: #dee0df;
   }*/
    .col {
        border: 0.5px solid;
        border-radius: 15px;
    }

    .mr {
        margin-top: 20px;
    }

    .qnt {
        height: 20px;
        background-color: #eaf5da;
        border-top: 0.5px solid;
        border-bottom: 0.5px solid;
    }

    .subtottal {
        border-bottom: 0.5px dotted;
    }

    tr.spc th {
        padding-top: 2px;
        padding-bottom: 2px
    }

    .sl {
        border-top-right-radius: 10px;
        border-top-left-radius: 10px;
        background-color: #D5E4EC;
        margin-bottom: 20px;
    }

    .black-dashed {
        border: 1px dotted;
    }

    .mx-h {
        max-height: 300px;
        overflow-y: scroll;
    }

    .trmx-h {
        max-height: 260px;
        overflow-y: scroll;
    }

    .cncl-sspnd {
        float: left;
        width: 50%;
        display: inline;
        

    }

    .trcolr {
        background-color: #e9e4c3;
    }

    .amtdue {
        font-size: 17px;
    }

    .notify-badge {
        position: absolute;
        left: 1px;
        top: 1px;
        background: red;
        text-align: center;
        border-radius: 30px 30px 30px 30px;
        color: white;
        padding: 5px 5px;
        font-size: 10px;
    }

    table.fixed {
        table-layout: fixed;
        width: 100%;
    }

    table.fixed td {
        overflow: hidden;
    }

    .errspan {
        float: right;
        top: calc(50% - 0.5em);
        right: 15px;
        position: relative;
        z-index: 2;
        color: red;
    }

    @media(min-width: 355px) {
        .column {
            float: left;
            width: 30%;
            padding: 5px;
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
            margin: 2px;
            /*   background-color: #fff;*/
            border: 0.5px solid;
            border-radius: 5px;
            text-align: center;
        }

        .smtotal {
            font-size: 10px;
        }

        .toggle-full-row {
            font-size: 8px;
        }
    }

    @media(min-width: 720px) {
        .column {
            float: left;
            width: 15%;
            padding: 5px;
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
            margin: 2px;
            /*   background-color: #fff;*/
            border: 0.5px solid;
            border-radius: 5px;
            text-align: center;
        }
    }

    ol {
        list-style-type: none;
        padding: 0;
        width: 600px;
    }

    /*input {
   width: 600px;
   padding: 12px;
   border: 1px solid #ccc;
   color: #999;
   }*/
    /*li {
   display: none;
   }*/
    li img {
        margin-right: 5px;
    }

    li a {
        display: block;
        text-decoration: none;
        color: #666;
        font: 16px tahoma;
        padding: 4px;
    }

    li a:hover {
        background: #fffff0;
        color: #333;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.min.css') }}">
@endpush
<form id="myform" enctype="multipart/form-data">
    <div class="page-header card">
        <div class="card-block">
            <!-- <h5 class="m-b-10 text-center">Point Of Sale</h5>
         <p class="text-muted m-b-10 text-center">Create Sale</p>
          -->
            <div class="row">
                <div class="col-lg-8 " id="tog">

                    {{ csrf_field() }}
                    <div class="page-header card col">
                        <div class="card-block">
                            <div class="form-group ">
                                <div class="">
                                    <div class="row">
                                        <div class="input-group col-md-8">
                                            <span class="input-group-addon form-rounded" id="name"><i class="ti-dropbox"></i></span>
                                            <input type="text" class="form-control input-sm form-rounded" id="srehberText" placeholder="Start typing Item Name or scan Barcode...">
                                            <span class="fa fa-question-circle errspan" id="myBlinkingDiv"></span>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <a class="btn btn-sm pull-right btn-out-dashed btn-round btn-grd-primary show-category" style="color: white;">Hide Category</a>
                                        </div>
                                        <div class="form-group col-md-8">
                                            <ol class="commentlist">
                                                @foreach($categories as $cat)
                                                @foreach($cat->product as $product)
                                                <li style="display: none;">
                                                    <div class="product_tr_clone" data-id="{{ $product->id }}" data-price="{{ $product->price }}" data-name="{{ $product->name }}" data-plus="{{ $product->id+1 }}" id="cat-filter-plus-{{ $product->id }}">
                                                        <a href="#">
                                                            @if(!empty($product->img))
                                                            <img src="{{ asset($product->img) }}" alt="Snow" style="max-width:5%; max-height:5% ;">
                                                            @else
                                                            <img src="{{ asset('product/product.png') }}" alt="Snow" style="max-width:5%; max-height:5% ;">
                                                            @endif
                                                            {{ $product->name }}
                                                        </a>
                                                    </div>
                                                </li>
                                                @endforeach
                                                @endforeach
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="sho-cat">
                                    <div>
                                        <a class="btn btn-sm black-dashed btn-round btn-primary show-all" href="" data-id="{{ 'show-all' }}" rel="Ice cream ">{{ "Show All" }} </a>

                                        @foreach($categories as $cat)
                                        @php
                                        $catArray[$cat->id] = $cat->product->where('category_id' , $cat->id)->pluck('id')->toArray();
                                        @endphp
                                        <a class="btn btn-sm black-dashed btn-round filter-category" data-id="{{ $cat->id }}" rel="Ice cream " href="">{{ $cat->name }} </a>
                                        @endforeach
                                    </div>
                                </div>

                                <br>
                                <div class="row mx-h">
                                    <!-- ->where('category_id' , 10) -->
                                    @foreach($categories as $cat)
                                    <!-- <div id="cat-filter-{{ $cat->id }}" class="float-left" style="display:inline;"> -->
                                    @foreach($cat->product as $product)
                                    <div class="column card main_img_div text-center justify-content-center product_tr_clone" data-id="{{ $product->id }}" data-price="{{ $product->price }}" data-name="{{ $product->name }}" id="cat-filter-{{ $product->id }}" data-plus="{{ $product->id }}">
                                        <span class="notify-badge d-none" id="notify-badge-{{ $product->id }}"></span>
                                        @if(!empty($product->img))
                                        <img src="{{ asset($product->img) }}" alt="Snow" style="width:100%">
                                        @else
                                        <img src="{{ asset('product/product.png') }}" alt="Snow" style="width:100%">
                                        @endif
                                        <div class="after-column form-rounded form-control" id="ful-d0-{{ $product->id }}" style="background-color: #ff163d;">
                                            <span>{{ $product->name }}</span>
                                        </div>
                                        <div class="after-column form-rounded form-control " id="ful-d1-{{ $product->id }}" style="background-color: #000000">
                                            <span>Rs:{{ $product->price }}</span>
                                        </div>
                                        <div class="toggle-full-row form-rounded form-control d-none" id="ful-d-{{ $product->id }}" style="background-color: red">
                                            <span>{{ $product->name }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                    <!-- </div> -->
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive trmx-h d-none" id="div">
                            <table class="table table-hover fixed">
                                <thead class="bg-bl">
                                    <tr>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Disc</th>
                                    </tr>
                                </thead>
                                <tbody id="tb">
                                    <tr class="text-center d-none" id="dummyTr">
                                        <td>
                                            <a class="form-rounded text-left" style="font-size: 20px" onclick="remove($(this));" style="margin-top: 5px"> <i class="fa fa-trash"></i></a>
                                            <br>
                                            <span data-pname="product-name">Caramal</span> <br>
                                            <b style="color: #004cff" data-pprice="price">Price: 100</b><br>
                                            <div class="text-center font-weight-bold totalval fitin" data-ptotal="total">
                                                <span>Total: 0.00</span>
                                            </div>
                                        </td>
                                        <td><input type="number" style="max-width: 50px;" name="unit[]" class="form-rounded" value="" onkeyup="runtimeCalculate(this);"><br><br>
                                        </td>
                                        <td><input style="max-width: 50px;" type="number" name="discounts[]" class="form-rounded" value="">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 div1">
                    <div class="card author-box col">
                        <div class="mr">
                            <div class="form-group">
                                <select class="form-control select2" name="customer_id" id="customer-id" required="" data-placeholder="Choose a customer...">
                                    <!-- <option value="defualt" selected>Defualt Customer</option> -->
                                    <option value="" selected>Chose Customer</option>
                                    @foreach($data['cst'] as $key => $customer)
                                    <option value="{{ $customer->id }}-{{ $key }}">{{ $customer->user->name ?? '--' }} / {{ $customer->id ?? '--' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row qnt spc">
                                <div class="col-9 font-weight-bold" style="font-size: 12px">
                                    Quantity of <span id="qty_of"></span> Items
                                </div>
                                <div class="col-3 text-right font-weight-bold fitQtyOfItem">
                                    <span id="sum_qt_val">0</span>
                                </div>
                            </div>
                            <div class="row subtottal">
                                <div class="col-4 font-weight-bold">
                                    Subtotal
                                </div>
                                <div class="col-8 text-right font-weight-bold fitinsubtotal">
                                    PKRs <span id="subtottal">0.00</span>
                                </div>
                            </div>
                            <div class="row total">
                                <div class="col-4 font-weight-bold">
                                    Total
                                </div>
                                <div class="col-8 text-right font-weight-bold totalval">
                                    <div class="d-none"> <span id="dummyTotal"></span> </div>
                                    <span class="fitin" id="sale_total">PKRs: 0.00</span>
                                </div>
                            </div>
                            <div class="row paymenttotal">
                                <div class="col-6 font-weight-bold ">
                                    Payments Total
                                </div>
                                <div class="col-6 text-right fitPaymentTotal">
                                    PKRs: <span id="paymenttotal">0.00</span>
                                </div>
                            </div>
                            <div class="row total paymenttotal">
                                <div class="col-6 font-weight-bold amtdue">
                                    Amount Due
                                </div>
                                <div class="col-6 text-right due amtdue">
                                    <div class="d-none"> <span id="dummyAmountDue"></span> </div>
                                    <span class="fitin" id="sale_amount_due">PKRs: 0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="sl">
                            <table>
                                <tbody>
                                    <tr class="">
                                        <th style="width: 100%">Payment Type</th>
                                        <th style="width: 100%" class="form-group">
                                            <div>
                                                <select class="form-rounded">
                                                    <option value="Daily">Cash</option>
                                                    <option value="Weekly">Card</option>
                                                </select>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="">
                                        <th style="width: 50%">Amount Tendered</th>
                                        <th style="width: 50%" class="form-group float-left">
                                            <input type="number" class="form-rounded" id="amountReceived" style="width: 200%;" name="received_amount" value="0" required='This is Required!'>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                            <div style="margin-top : 20px; margin-bottom : 10px;">
                                <button id="button" type="submit" class="btn-sm form-rounded form-control btn-success text-center">Add Sale</button>
                            </div>
                            <!--  <div class="cncl-sspnd">
         <a href="" class="btn-sm form-rounded form-control btn-warning text-center" >Suspend</a>
         </div> -->
                            <div class="">
                                <a class="btn-sm form-rounded form-control btn-danger text-center cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</form>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('assets/js/select2.min.js') }}"></script>
<script>
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
            $("#paymenttotal").html(Number(0).toFixed(2));
            $('#sale_amount_due').html("PKRs: " + Number(0).toFixed(2));
    
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
    $('#amountReceived').val(total);
    calculateTotalUpperCart(total);
    sum_qt_val = 0;
    total = 0;
    fittext();

}


$(document).on("keyup", calculateTotalUpperCart);

function calculateTotalUpperCart(val) {
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

    $('.sho-cat').toggle();
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
</script>
@endpush