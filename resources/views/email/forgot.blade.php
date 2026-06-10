@component('mail::message')

<h2>Hey,{{$user->name}}</h2>
<p>Someone requested a link to change your password within email {{ $user->email }}. Click the button below to proceed.</p>
@component('mail::button',['url'=>url('reset/'.$token)])
Reset your Password
@endcomponent
<p>If you didn't request this, please ignore the email. Your password will stay safe and won't be changed</p>
Thanks<br>
{{config('app.name')}}
@endcomponent