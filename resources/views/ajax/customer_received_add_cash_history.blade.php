   <table>
      <tbody>
         <tr><td><p class="text-center" align="1">         --------------</p></tr></td>
         <tr><td><p class="text-center" align="1">Customer Cash Add Received History</p></tr></td>
         <tr><td><p class="text-center" align="1">         --------------</p></tr></td>
            @foreach($receivings as $re)
               <tr><td><p class="text-center" align="1">Old Paid Remaining </p></tr></td>
               <tr><td><p class="text-center" align="1">{{ $re->old_cash_remaining }} {{ $re->cash_paid_added }} = {{ $re->current_cash_remaining }}</p></tr></td>
            @endforeach
            <tr><td><p class="text-center" align="1">         --------------</p></tr></td>
               <tr><td><p class="text-center" align="1">Stock Purchase & Sale History & Remaining</p></tr></td>
            <tr><td><p class="text-center" align="1">         --------------</p></tr></td>
            <tr><td><p class="text-center" align="1">
                {{ $stock_purchase }} {{ $allReceivings->sum('cash_paid_added') }} = {{ $stock_purchase +  ($allReceivings->sum('cash_paid_added')) }}
                </p></tr></td>      
        </tbody>
</table>