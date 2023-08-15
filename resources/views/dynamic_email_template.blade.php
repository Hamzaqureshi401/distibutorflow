@component('mail::message')
<!--# Order Shipped-->

<body>
    <div class="layout one-col fixed-width" style="max-width: 600px;min-width: 320px;">
        <div class="layout__inner">
            <!-- Content Body -->
            <!--<div style="margin-bottom:15px;">-->
            <!--    Here is some example content. This is where you want your main content to be.-->
            <!--</div>-->
            <!-- /Content Body -->
            <div style="width:200px;margin:auto;">
                <img alt="Partnerships" src="{{ asset('images/profit guru-01.jpg') }}" style="width: 100%;" />
            </div>
        </div>
    </div>
</body>
    
Your order has been shipped!
<p>Hi, This is {{ $data['name'] }}</p>
<p>I have some query like {{ $data['name'] }}.</p>
<p>It would be appriciative, if you gone through this feedback {{ $data['message'] }}.</p>

<p>Hi, My Name is Profit Guru (Virtual Assistant) i will be your persional assistant 
& i am assigned to you from Distributor Flow (Order Processigns Department) 
i am happy to work with you my job is to define your profit on mothly basis 
as you are Subscribe profit calculation thats why i am assigned to you.</p>
@component('mail::button', ['url' => $url])
View Order
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@component('mail::panel')
This is the panel content.
@endcomponent

@component('mail::table')
|P.id       | Unit         | Subtotal  |
| ---------- |:-------------:| --------:|
@foreach($order as $detail)
|  {{$detail->product_id}} | {{$detail->unit}} | {{$detail->amount}}
@endforeach
@endcomponent


