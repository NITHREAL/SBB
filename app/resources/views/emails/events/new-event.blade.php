@component('mail::message')
{{ $object->title }}

Thanks for creating a new event!

Thanks,<br>
{{ config('app.name') }}
@endcomponent
