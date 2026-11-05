<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <x-head />
  @vite('resources/css/custom/auth-animate.css')
</head>

<body class="min-h-screen bg-background text-foreground antialiased">
  <div class="flex min-h-screen flex-col">
    <div class="area flex flex-1 items-center justify-center px-4 py-10 sm:py-14">
      <ul class="circles" aria-hidden="true"> <!-- animate circles --> 
        @for($i = 0; $i <= 9; $i++)
          <li></li>
        @endfor
      </ul>

      <div class="relative z-10 w-full">
        @yield('content')
      </div>
    </div>

    <footer class="relative z-10 border-t border-border/70 bg-background/70 py-5 text-center font-mono text-[11px] text-muted-foreground backdrop-blur">
      &copy; {{ date('Y') }} MyBlog4U
    </footer>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  @stack('scripts')
</body>

</html>
