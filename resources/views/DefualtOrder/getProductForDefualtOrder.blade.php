@extends('layouts.app')

@section('title') Edit Order Taker @endsection

@section('content')
<!-- Breadcrumbs-->
<div class="page-header card">
         <div class="card-block">
            <h5 class="m-b-10 text-center">Set Defualt Order</h5>
            <p class="text-muted m-b-10 text-center">Set Unit For Defualt Order</p>
         </div>
      </div>
<div class="row">
  <div class="col-md-9 m-auto">
    <div class="card mb-9">
      <div class="card-body">
      
        <form method="post" action="{{ route('setDefualt.order') }}" enctype="multipart/form-data">
          {{ csrf_field() }}
                  <h3 class="text-center">Set Default Order Values</h3>
          <div class="table-responsive">
            <table class="table table-bordered table-custom-th table table-hover table-bordered results"  width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody>
                  
                @foreach($product as $key => $pr)
                <tr>
                  <input type="hidden" name="product_id[]" value="{{ $pr->id }}">
                  <td class="name">{{ $pr->name }}</td>
                  <td>
                    <input type="number" name="unit[]" value="{{ $pr->DefualtOrder->unit ?? 0 }}">          
                  </td>
                </tr>

                @endforeach
                
               
              </tbody>
            </table>
            <button type="submit" class="btn btn-block btn-primary">Submit</button>
          </div>
          
         <!--ordertaker end -->
        </form>
      </div>   
        </div>
        
      </div>
    </div>
  <
@endsection
@push('scripts')
<script type="text/javascript">
</script>

@endpush