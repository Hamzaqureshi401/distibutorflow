<!DOCTYPE html>
<html>
<head>
	<title>Print Order</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style type="text/css">
	.total{
			border: 1px solid gray;
			padding: 1px;
			font-weight: 600;
		}
		</style>
</head>
<body>
	<div id="p">
        <table class="table table-bordered table-custom-th" width="100%" cellspacing="0">
	                <p>Customer Name: {{ strtoupper($customer->user->name) }}</p>
	                <a href="tel:{{ $order->customers->phone }}"><p>Phone   : {{ $customer->phone }}</p></a>
	                <p class="d-none">Phone   : {{ $customer->phone }}</p>
	                <p>Address: {{ $customer->address }}</p>
        </table>
	   </div>
</body>
</html>