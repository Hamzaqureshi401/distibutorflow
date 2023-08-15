<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Seller;
class SellerStatusCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::user()->role == 3){
            $seller = 1;
            if ($seller == 1){
            return redirect()->back()->with('error' , 'Invalid Request');
        }
        return $next($request);
    }
}
}