<!DOCTYPE html>
<html>
<head>
	<title>Print Invoice</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>.
	<style type="text/css">
	    @font-face { font-family: myFirstFont; src: url('{{ asset("css/urdu_font.eot") }}'); }
	    body{
	        width: 100%;
	        font-family: myFirstFont;
	    }
		.wrapper{
			width: 100%;
			padding: 10px 0px;
			border-top: .5px solid #C3C3C3;
			border-bottom: .5px solid #C3C3C3;
			font-size: 14px;
		}
		p{
			margin: 0px;
		}
		.header{
			width: 100%;
		}
		.header-img{
			width: 100%;
			height: auto;
			display: block;
			margin-bottom: 10px;
		}
		.footer-img{
			width: 100%;
			height: auto;
			display: block;
			margin-top: 10px;
			height: 150px;
		}
		table{
		    width: 100%;
		}
		.invoice{
			margin: 7px 0px;
		}
		.invoice th , .invoice td{
			padding: 2px;
			border: .5px solid #C3C3C3;
		}
		.total{
			border: 1px solid gray;
			padding: 1px;
			font-weight: 600;
		}
		.text-center{
		    text-align: center !important;
		}
		.text-right{
		    text-align: right !important;
		}
	</style>
</head>
<body>
    <?php error_reporting(0); ?>
	<div class="wrapper">
		<div class="header">
		    <p>Street 25 dars road Baghbanpora Lahore<br />
		       Email: scoopscreamery@gmail.com<br />
		       www.scoopscreamery.com<br />
		       Phone: +92331-4266560
		    </p>
		    <h1>SCOOPS ICE CREAM</h1>
		    <h3>SALES TEX INVOICE</h3>
			<!--<img class="header-img" src="{{ asset('images/printer-design.bmp') }}" alt="Invoice Header Image">-->
		</div>
		<table class="header">
	        <tbody>
	            <tr>
	                <td width="100%"><p>Customer Name: {{ strtoupper($invoice->customer->user->name) }}</p></td>
	            </tr>
	            <tr>
	                <td width="100%"><p>Address: {{ $invoice->customer->address }}</p></td>
	            </tr>
	            <tr>
	                <td width="50%"><p>Date: {{ date('d/m/Y' , strtotime($invoice->created_at)) }}</p></td>
	                <td width="50%" class="text-right"><p>Bill No: {{ $bill_no }}</p></td>
	            </tr>
	        </tbody>
	    </table>
	    <p class="text-center">**********************</p>
		<table class="invoice">
			<tbody>
			    @foreach($invoice->invoicedetail as $idetail)
			    <tr><td width="100%"><p class="text-center">--------------</p></td></tr>
			    <tr><td width="100%"><p class="text-center"><b>{{ $idetail->product->name }}</b></p></td></tr>
			    <tr><td width="100%"><p class="text-center">--------------</p></td></tr>
				<tr> 
					<td width="100%">Price: {{ $idetail->product->price }}</td>
				</tr>
				<tr>
				    <td width="100%">Units: {{ $idetail->unit }}</td>
				</tr>
				<tr>
				    <td width="100%">Amount: {{ $idetail->amount }}</td>
				</tr>
				<tr>
				    <td width="100%">Benefit: {{ $idetail->product->c_benefit*$idetail->unit }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<p class="text-center">**********************</p>
		<table class="totals">
	        <tbody>
	            <tr>
	                <td><p>Sub Total: {{ $invoice->subtotal }}</p></td>
	            </tr>
	            
	            <tr>
	                <td><p>Balance: {{ $prev_invoice->amount_left }}</p></td>
	            </tr>
	            <tr>
	                <td><p>Benefit: {{ $invoice->c_benefit }}</p></td>
	            </tr>
	            <tr>
	                <td><p>Total: <span class="total">{{ $invoice->amount }}</span></p></td>
	            </tr>
	            <tr>
	                <td class="text-center"><p><b>Previous Bill History {{date('d.m.Y',strtotime( $prev_invoice->created_at))}}</b></p></td>
	            </tr>
	            <tr>
	                <td class="text-center"><p>Total: {{ $prev_invoice->amount }}</p></td>
	            </tr>
	            <tr>
	                <td class="text-center"><p>Received: {{ $prev_invoice->received_amount }}</p></td>
	            </tr>
	            <tr>
	                <td class="text-center"><p>Remaining Balance: {{ $prev_invoice->amount_left }}</p></td>
	            </tr>
	           
	        </tbody>
	    </table>
		<p class="text-center">**Thank You**</p>
	</div>
</body>
</html>