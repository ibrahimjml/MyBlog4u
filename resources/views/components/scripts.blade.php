
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="{{asset('js/hiddenul.js')}}" defer></script>
<script src="{{asset('js/unsaveposts.js')}}" defer></script>
<script src="{{asset('js/fetchfollow.js')}}" defer></script>
@auth
@if(Route::is('single.post'))
<script src="{{asset('js/fetchlike.js')}}"></script>
<script src="{{asset('js/randomhearts.js')}}" ></script>
<script src="{{asset('js/comments.js')}}"></script>
<script src="{{asset('js/fetchsavedpost.js')}}"></script>
@endif
@endauth
@if(Route::is(['edit.post','createpage']))
<script src="{{asset('js/hashtagsUI.js')}}" defer></script>
@endif

@stack('scripts')