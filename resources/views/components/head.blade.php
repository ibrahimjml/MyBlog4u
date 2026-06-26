<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" href="{{ $favicon_url }}" />
<link rel="apple-touch-icon" href="{{ url('/img/apple-touch-icon.png') }}" />
<link rel="canonical" href="{{ url()->current() }}">
<meta name="theme-color" content="#000000" />

{!! $header_scripts ?? '' !!}

{{-- SEO --}}
<meta name="description" content="{{ $meta_description ?? 'Myblog4u a social network that connect creators'}}" />
<meta name="keywords" content="{{ $meta_keywords  }}">
<meta name="author" content="{{ $author }}">
<meta content="BlogPost" property="og:site_name" />
{{-- meta og graph whatsapp/twitter --}}
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="{{ $og_type }}" />
<meta property="og:title" content="{{ $meta_title }}" />
<meta property="og:description" content="{{ $meta_description  }}" />
<meta property="og:image" content="{{ $og_image  }}" />
{{-- meta og twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $meta_title  }}" />
<meta name="twitter:description" content="{{ $meta_description  }}" />
<meta name="twitter:image" content="{{ $og_image }}">
<title>
  {{ $meta_title }}
</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="{{asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css')}}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link rel="stylesheet" href="{{asset('style.css')}}">
<link rel="stylesheet" href="{{asset('tinymce.css')}}">
@auth
<script>
     window.userId = {{ auth()->id() }};
</script>
@endauth
@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('styles')