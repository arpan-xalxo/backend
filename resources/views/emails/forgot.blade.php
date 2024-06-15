@component('mail::message')
<p>Hello {{ $user->email }}</p>


@php
    $url = url('http://localhost:1776/reset-password/' . $user->remember_token . '?email=' . $user->email);
@endphp



@component('mail::button', ['url' => $url])
Reset Password Link
@endcomponent

In case you have issues, please contact.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
