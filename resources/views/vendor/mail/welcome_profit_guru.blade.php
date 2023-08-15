
<body>
    <div class="layout one-col fixed-width">
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
    
<p>      Hi, Dear Customer {{ $data['name'] }}, My Name is Profit Guru and I will be your Personal Virtual Assistant 
& I am assigned to you from Distributor Flow (Order Processing Department). I am glad to work with you my activity 
is to outline your earnings on month-to-month foundation as you're Subscribed earnings calculation 
that is why I am assigned to you.</p>


Thanks,<br>
<b>From : Qureshi Sons</b>


@component('mail::panel')
<b>Note: </b> We have Enabled Profit Guru & Product Return notification system. 
If you want to disable this feature please contact us at customercare@scoopscreamery.pk!
@endcomponent
