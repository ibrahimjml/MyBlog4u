<x-layout>
@push('styles')
<style>
  html,body{
    overflow-y: hidden !important;
  }

</style>
@endpush
  <main class="h-[80%] grid grid-cols-1 lg:grid-cols-4 gap-6 p-6 ">

    <aside id="mobileSidebar" class=" col-span-1 flex flex-col justify-center items-center bg-white p-6 rounded-lg shadow h-full lg:h-auto fixed top-0 left-0 w-80 z-30 lg:static lg:w-auto lg:z-auto transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0">
      <p class="text-xl font-semibold mb-4">User Settings</p>
      <div class="flex flex-col w-fit  gap-2">
        <a href="{{route('info')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('info') ? 'bg-gray-200':''}}">
          <i class="fas fa-user"></i>
          Profile Details
        </a>
        <a href="{{route('account')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('account') ? 'bg-gray-200':''}}">
          <i class="fas fa-cog"></i>
          Account Management
        </a>
        <a href="{{route('account.privacy')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('account.privacy') ? 'bg-gray-200':''}}">
          <i class="fas fa-lock"></i>
          Account Privacy
        </a>
        <a href="{{route('two.factor.view')}}" class="py-2 px-4 text-gray-500  rounded-xl flex items-center gap-4 {{request()->routeIs('two.factor.view') ? 'bg-gray-200':''}}">
          <i class="fas fa-shield-alt"></i>
          Two-Factor Auth
        </a>
      </div>
      <div class="mt-auto py-4 border-t border-gray-300 w-full">
        <button 
        onclick="if(history.length >1 ){
        history.back();
        }else{
        window.location.href='{{route('profile.home',$user->username)}}'
        };"
        class="block py-2 px-4 text-gray-500 rounded-xl w-full hover:bg-gray-200">
          <b>&leftarrow;</b> Back
        </button>
      </div>
    </aside>

    <section class="col-span-1 lg:col-span-3 bg-white px-6  rounded-lg shadow h-[500px] overflow-y-auto">
      <div class="flex items-center gap-5 mb-6 lg:hidden sticky h-20 top-0 bg-white z-10">
        <button id="toggleSidebar" class="text-lg text-gray-400 hover:text-gray-600 transition cursor-pointer">
          <i class="fas fa-bars"></i>
        </button>
        <p class="text-2xl font-bold text-gray-900">User Settings</p>
      </div>
      @switch($section)

      @case('profile-info')
      @include('profile-settings.profile-info')
      @break

     @case('profile-account')
       @include('profile-settings.account-management')
       @break

     @case('profile-privacy')
       @include('profile-settings.account-privacy')
       @break

     @case('security')
       @include('profile-settings.security2fa',['user'=>$user])
       @break
       @endswitch
    </section>

  </main>
@push('scripts')
<script>
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.getElementById('mobileSidebar');

toggleBtn.addEventListener('click', () => {
  sidebar.classList.toggle('-translate-x-full');
});
 document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
      if (!sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('show-sidebar');
      }
    }
  });
</script>
@endpush
</x-layout>
