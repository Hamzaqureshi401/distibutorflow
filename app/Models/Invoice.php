<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = array('a_benefit', 'c_benefit');

    public function invoicedetail(){
        return $this->hasMany('App\Models\InvoiceDetail');
    }

    public function customer(){
    	return $this->belongsTo('App\Models\Customer');
    }
    public function totalProductPrice(){
    return $this->invoiceDetail()->with('product')->get()->sum(function ($invoiceDetail) {
        return $invoiceDetail->product->p_price * $invoiceDetail->unit;
    });
    }
}
