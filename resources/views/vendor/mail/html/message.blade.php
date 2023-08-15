@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<!-- {{ config('app.name') }} -->
<img src="{{ asset('images/distributor logo.png') }}" style="max-width: 200px;" alt="{{ config('app.name') }} Logo">
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} Qureshi Sons. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
