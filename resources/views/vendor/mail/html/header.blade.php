<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
	    <!--<img class="img-fluid logo" src="{{ asset('images/logo.png') }}" alt="Order Header Image" />-->

@else
{{ $slot }}
@endif
</a>
</td>
</tr>
