<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
            <img src="{{ asset('images/profit guru-01.jpg') }}" align="center" class="img-fluid logo" alt="Order Header Image" width="75px" height="75px"/>

@else
{{ $slot }}
@endif
</a>
</td>
</tr>