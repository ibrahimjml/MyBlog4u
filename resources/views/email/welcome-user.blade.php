@component('mail::message')

<div style="text-align: center;">
    <img src="{{ url('img/logo.png') }}"
         alt="MyBlog4u"
         style="max-width:100%; height:auto; border-radius:8px;">
</div>

# Welcome, {{ $user->name }}!

You registered a new account on **{{ config('app.name') }}** at {{ $user->created_at->format('d M Y') }}.

Your account is currently awaiting approval.We'll send you another email as soon as your account is activated.

Thanks,<br>
{{ config('app.name') }}

@endcomponent