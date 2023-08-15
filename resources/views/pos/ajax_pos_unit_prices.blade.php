<?php $cat_p = []; ?>
@foreach($stock_products as $p)
@if(!in_array($p->category_id , $cat_p))
<tr>
    <td class="bg-info">{{ $p->category->name }}</td>
</tr>
<?php $cat_p[] = $p->category_id ?>
@endif
<tr>
  @php

   $AvlStock = $p->GetProductStockRecord($customer_user_id , $p->id)->remaining_stock ?? '0'; 
   $SoldUnits = $p->GetSoldData($customer_id , $p->id)->sum('unit') ?? '0';
   $IncomingUnits = ($p->GetIncomingData($customer_id , $p->id)->sum('unit') ?? '0') + ($p->GetIncomingDataInv($customer_id , $p->id)->sum('unit') ?? '0');

   $RemainingStock = $AvlStock + $IncomingUnits - $SoldUnits;

   @endphp
  <input type="hidden" class="form-control p-id" name="" value="{{ $p->id }}">
    <td>{{ $p->name }}</td>
    <!--<td class="p-price"><input style="width: 70px;"  onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp' type="number" disabled="" value="{{ $p->price }}" class="form-control" {{ Auth::user()->role < 3 ? '' : 'readonly' }} /></td>-->
    
   @if($AvlStock > 0)
        <td style="background-color : #e7ffdb;">
      @else
      <td>
        @endif
       <input type="number" class="form-control" style="width: 70px;" disabled="" value="{{ $AvlStock }}" id="available-{{ $p->id }}">
       </td>
   <td><input type="number" class="form-control available_stock" id="price-{{ $p->id }}" style="width: 70px;" disabled="" value="{{ $p->getProductrecord($customer_id , $p->id)->sell_price }}"></td>


      @if ( $SoldUnits != 0)
     <td style="background-color : #ffebeb;">
      @else
      <td>
        @endif
      <input type="number" style="width: 70px;" class="form-control" value="{{ $SoldUnits }}"  id="old-sold-{{ $p->id }}" name="" disabled="">
    </td>
    @if ( $IncomingUnits != 0)
     <td style="background-color : #faf5e6;">
      @else
      <td>
        @endif
      <input type="number" style="width: 70px;" class="form-control" value="{{ $IncomingUnits }}" id="incoming-{{ $p->id }}" name="" disabled="">
    </td>
    <td>
      <input type="number" style="width: 70px;" data-id="{{ $p->id }}" class="form-control p-units" id="sold-{{ $p->id }}" name="">
    </td>
    <td>
      <input type="number" style="width: 70px;" data-id="{{ $p->id }}" class="form-control r-units" id="remain-{{ $p->id }}" value="{{ $RemainingStock }}" name="" >
      <input type="hidden" style="width: 70px;" class="form-control" id="hidden-remain-{{ $p->id }}" value="{{ $RemainingStock }}" name="" >
    </td>
   <!--<td><input  type="number" class="form-control new-stock" disabled=""></td>-->
    <td><input type="number" class="form-control sum-amount" id="rmty-{{ $p->id }}" style="width: 70px;" value="0" disabled=""></td>
   
</tr>
@endforeach
