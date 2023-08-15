<?php $cat_p = []; ?>
<input type="hidden" id="old_balance" value="{{ $old_balance ?? '0' }}" name="old_balance">
<input type="hidden" id="old_inv" value=" {{ $old_date ?? 'No Record Found'}} " name="old_date">
@if($stock_type == 1)
@foreach($stock_products as $p)
@if(!in_array($p->category_id , $cat_p))
<tr>
    <td class="bg-info">{{ $p->category->name }}</td>
</tr>
<?php $cat_p[] = $p->category_id ?>
@endif
<tr>
  <input type="hidden" class="form-control p-id" name="" value="{{ $p->id }}">
    <td>{{ $p->name }}</td>
    <!--<td class="p-price"><input style="width: 70px;"  onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp' type="number" disabled="" value="{{ $p->price }}" class="form-control" {{ Auth::user()->role < 3 ? '' : 'readonly' }} /></td>-->
    
   <td><input type="number" class="form-control available_stock" style="width: 70px;" disabled="" value="{{ $p->remaining_stock }}"></td>
   <td>
      <input type="number" style="width: 70px;" class="form-control p-units" name="">
    </td>
   <!--<td><input  type="number" class="form-control new-stock" disabled=""></td>-->
    <td><input type="hidden" class="form-control row-amount" style="width: 70px;" disabled=""></td>
   
</tr>
@endforeach
@else
@foreach($products as $p)
@if(!in_array($p->category_id , $cat_p))
<tr>
    <td class="bg-info">{{ $p->category->name }}</td>
</tr>
<?php $cat_p[] = $p->category_id ?>
@endif
<tr @if(!in_array($p->id, $shortListed)) class="not-in-sl" @endif>
  <input type="hidden" class="form-control p-id" name="" value="{{ $p->id }}">
    <td>{{ $p->name }}</td>
    <td class="p-price"><input style="width: 70px;"  onfocus='tmp=this.value;this.value=""' onblur='if(this.value=="")this.value=tmp' type="number" value="{{ $p->price }}" class="form-control" {{ Auth::user()->role < 3 ? '' : 'readonly' }} /></td>
    <td>
      <input type="number" style="width: 70px;" class="form-control p-units" name="">
    </td>
   <td><input type="number" class="form-control row-amount" style="width: 50px;" disabled=""></td>
   <td class="show-row-ben">0</td>
    <input class="c-ben" type="hidden" value="{{ $p->c_benefit }}" disabled="">
</tr>
@endforeach
@endif