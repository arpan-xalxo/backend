@component('mail::message')
<p>Hello {{$user->name}}</p>

@component('mail::button' , ['url' => url('api/verify/'.$user->remember_token)])
Email Verification
@endcomponent

<p>In case you have issues please contact </p>
Thanks <br/>
{{config('app.name')}}
@endcomponent


