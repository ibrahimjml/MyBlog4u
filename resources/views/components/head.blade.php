<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" href="{{url('/img/icon.png')}}" type="image/png" />
<link rel="apple-touch-icon" href="{{url('/img/apple-touch-icon.png')}}" />
<meta name="theme-color" content="#000000" />
{{-- SEO --}}
<meta name="description" content="{{ $meta_description ?? config('app.name') . '- The platform for creating, interacting, and sharing content on  ' }}" />
<meta name="keywords" content="{{ $meta_keywords ?? 'laravel, blogpost, myblog, links, link, cv, portfolio, aggregation, platform, social, media, profile, bio, tree' }}">
<meta name="author" content="{{ $author ?? config('app.name') }}">
<meta content="BlogPost" property="og:site_name" />
{{-- meta og graph whatsapp/twitter --}}
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="{{ $og_type ?? 'website' }}" />
<meta property="og:title" content="{{ $meta_title ?? (config('app.name') . '- Myblog platform .') }}" />
<meta property="og:description" content="{{ $meta_description ?? (config('app.name') . '- The platform for creating, interacting, and sharing content on  .') }}" />
<meta property="og:image" content="{{ $og_image ?? url('/img/logo.png') }}" />
<meta property="og:image:width" content="200" />
<meta property="og:image:height" content="200" />
{{-- meta og twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $meta_title ?? (config('app.name') . '- Myblog platform .') }}" />
<meta name="twitter:description" content="{{ $meta_description ?? (config('app.name') . '- The platform for creating, interacting, and sharing content on .') }}" />
<meta name="twitter:image" content="{{ $og_image ?? url('/img/logo.png') }}">
<title>
  {{ $meta_title ?? (config('app.name') . '- Myblog platform .') }}
</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link rel="stylesheet" href="{{asset('style.css')}}">
<link rel="stylesheet" href="{{asset('tinymce.css')}}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('styles')