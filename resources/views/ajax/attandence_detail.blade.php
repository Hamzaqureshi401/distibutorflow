<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Invoices Details</title>
      <!-- Bootstrap core CSS-->
    <!--<link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">-->
      <!-- Custom fonts for this template-->
    <!--<link href="{{ asset('assets/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">-->

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
	    @foreach($images as $img)
		@php
		if(!empty($point)){
		$path = 'pointss/'.$point.'/'.$img.'.png';
		}else{
		$path = 'pointss/'.Auth::id().'/'.$img.'.png';
		}
		@endphp
		<img class="img-fluid" src="{{ asset($path) }}" alt="{{ $path }}" width="470px" height="200px"/>	
		@endforeach
</div>
@section('scripts')
<script type="text/javascript">
$("tr.statuscheck input, tr.statuscheck select, tr.statuscheck textarea").prop('disabled', true);
</script>
	  
</body>
</html>