<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Invoices Details</title>
      <!-- Bootstrap core CSS-->
    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
      <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

	<!-- Latest compiled and minified JavaScript -->

	<style type="text/css">
	.total{
			border: 1px solid gray;
			padding: 1px;
			font-weight: 600;
		}
	
		</style>
</head>
<body>
	<p class="text-center" align="1">{{ date('d/m/Y h:i:sa') }}</p>
	<div id="p">
		<img class="img-fluid" src="{{ asset('images/PRINTER DESIGN.jpg') }}" alt="Invoice Header Image" width="470px" height="200px"/>
        
	    
	    
  <table class="table table-bordered table-custom-th" width="100%" cellspacing="0">
                    <?php $name1 = App\Models\User::where('id' , $name[0])->pluck('name')->first(); ?>
                    <?php $name2 = App\Models\User::where('id' , $name[1])->pluck('name')->first(); ?>
	                <p>Date: {{ date('d/m/Y h:i:sa' , strtotime($invoice->created_at)) }} / Bill No: {{ $bill_no }}</p>
	              
	                <p>Stock Added by {{ $name1 }} to {{ $name2 }} Account!</p>
	                <p class="text-center">*******************************</p>
	                
            @foreach($idetails as $d)
			    
			    <tr><td><p class="text-center" align="1">         --------------</p></td></tr>
			    <tr><td class="text-center" align="1"><?php echo $d->product->name;?></td></tr>
                <tr><td><p class="text-center" align="1">         --------------</p></td></tr>
        <tr>
        <td>Stock Added  :{{ $d->unit }}</td>
        </tr>
        
        
 
				@endforeach
			
	
  </table>
            <p class="text-center">*******************************</p>
		<table>
	        <tbody>
	            </tbody>
	    </table>
		<center><img class="img-fluid"  align="1" src="{{asset('images/qr.bmp') }}" alt="Invoice Header Image" width="auto" height="auto"/></center>
           
	<!--<p style="text-align:center;"><img class="aligncenter" src="{{ asset('images/qr.bmp') }}" align="1" alt="Invoice Header Image" width="auto" height="auto" align="middle"></p>-->
	    <p class="text-center" align="1">--------Thank You--------</p>
		
</div>
@section('scripts')
<script type="text/javascript">
$("tr.statuscheck input, tr.statuscheck select, tr.statuscheck textarea").prop('disabled', true);
</script>
	  
</body>
</html>