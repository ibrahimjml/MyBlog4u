@component('mail::message')

<div style="text-align: center;">
    <img src="{{ url('img/logo2.png') }}"
         alt="MyBlog4u"
         style="max-width:100%; height:auto; border-radius:8px;">
</div>

# Hey, {{ $user->name }}!

Your account has been approved at {{ $user->activation?->completed_at->format('d M Y') }}.

You can now access your dashboard, follow people , and share good articles.

Thanks,<br>
{{ config('app.name') }}

@endcomponent