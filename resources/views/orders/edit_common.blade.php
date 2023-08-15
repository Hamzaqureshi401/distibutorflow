 <?php $cat_p = []; ?>
                           @foreach($products as $product)
                           @php
                           $acutual_price = $product->getProductrecord($order->customer_id , $product->id)->price;
                           $details = $product->GetOrderData($order->id , $product->id);
                           if(!empty($details)){
                           $unit    = $details->unit;
                           $amount  = $details->amount;
                           $price   = $amount / $unit;
                           if($price != $acutual_price){
                           $price = $price;
                           }else{
                           $price = $acutual_price;
                           }
                           }else{
                           $price = $acutual_price;
                           $unit  = '';
                           $amount = '';
                           }
                           $customer_p = $order->customer->final_allowed_products;
                           $productsarray = explode('|', $customer_p);

                           @endphp
                           @if(!in_array($product->category_id , $cat_p))
                           <tr>
                              <td class="bg-primary">{{ $product->category->name }}</td>
                           </tr>
                           <?php $cat_p[] = $product->category_id ?>
                           @endif
                          
                           <tr class="product-row" style="width: 100%">
                              <td class="bg-info">{{ $product->name }}
                                @if (Auth::user()->role < 3)
                                <br>
                                <span style="color:red;">
                                     Purchase = {{ $product->p_price }}
                                </span>
                                @endif
                                 @if(!in_array($product->id , $productsarray))
                                 <br>
                                 <span style="color:red;">
                                     (defualt)
                                </span>
                                @endif
     
                              </td>
                              <td class="p-price"><input style="width: 70px;" type="text" value="{{ $price }}" name="price[]" class="form-control" {{ Auth::user()->role < 3 ? '' : 'readonly' }} /></td>
                              <td>
                                  <div class="d-none">
                           <input type="number" class="form-control p-id" name="product_id[]" value="{{ $product->id }}"> 
                           </div>
                                 <input type="number" style="width: 70px;" class="form-control p-units" step="any" value="{{ $unit }}" name="unit[]">
                              </td>
                              <td><input type="number" class="row-amount" value="{{ $amount }}" disabled=""></td>
                              
                           </tr>
                           @endforeach