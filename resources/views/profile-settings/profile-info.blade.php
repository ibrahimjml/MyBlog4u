@push('styles')
<style>
  .edit-group:hover .label-expand {
    max-width: 150px;
  }
</style>
@endpush
<main class=" relative lg:w-4/6 w-full h-full lg:mx-auto mx-0 mt-5">
        <p class="text-xl font-medium text-black">Profile</p>
        <p class="text-md font-medium text-gray-400">Manage Your Profile</p>
        <hr class="mt-6">
        <div>
          <form id="phone-form" action="{{route('update.info')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mt-2 flex flex-col">
             <p>Profile Image</p>
             <div 
             class="relative p-2 group rounded border border-gray-300 flex items-center gap-4 mt-3"
             style="background: url('{{ $user->cover }}') center center / cover no-repeat;
             "
             >
             <!-- delete cover on Hover -->
           @if($user->cover_photo !== 'sunset.jpg')
           <button 
           type="button"
           onclick="if(confirm('Are you sure you want to delete the cover?')) document.getElementById('delete-cover-form').submit();"
           class="absolute bottom-1 right-1  w-10 h-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-red-500 text-white rounded-full  hover:bg-red-600"
           title="Delete cover">
           <i class="fas fa-trash"></i>
           </button>
           @endif
              <!-- cover -->
              <div class="absolute top-1 right-1 edit-group  w-fit p-2 rounded-xl text-white bg-blue-600 cursor-pointer ">
                <i class="fas fa-image"></i>
                <label for="cover"  class="label-expand align-middle inline-block max-w-0 overflow-hidden  transition-all duration-300 whitespace-nowrap text-sm cursor-pointer">
                 Change cover
                </label>
                <input name="cover" id="cover" class="hidden" type="file">
              </div>
             <div class=" w-[100px] h-[100px] group relative  ml-10">
             <img  src="{{$user->avatar_url}}" alt=""  class="relative w-full h-full object-cover flex justify-center items-center border-2 border-blue-500 shrink-0 grow-0 rounded-full ">
            <!-- delete avatar on Hover -->
            @if($user->avatar !== 'default.jpg')
            <button 
            type="button"
            onclick="if(confirm('Are you sure you want to delete the avatar?')) document.getElementById('delete-avatar-form').submit();"
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2  w-10 h-10 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-red-500 text-white rounded-full  hover:bg-red-600"
            title="Delete Avatar">
            <i class="fas fa-trash"></i>
            </button>
            @endif
            </div>
               <div class="flex flex-col gap-2">
               <!-- avatar -->
                <label for="avatar" class="w-fit p-2 text-sm rounded-xl text-white bg-blue-600 cursor-pointer flex items-center gap-2">
              <i class="fas fa-camera"></i>
               Change avatar
              </label>
              <input id="avatar" name="avatar" type="file" class="hidden">
                <p class="text-sm text-white">PNG, JPG, JPEG: 300 x 300 px</p>
               </div>
             </div>
             <!-- name -->
             <label for="name" class="mt-5">Name</label>
             <input type="text" name="name" value="{{ old('name', $user->name) }}" class="rounded-xl p-2 mt-2 font-bold border border-gray-300 @error("name") border-2 border-red-500 @enderror">
              @error('name')
             <p class="text-red-500 text-xs italic mt-4">
             {{ $message }}
             </p>
              @enderror
             <!-- phone -->
             <label for="phone" class="mt-5">Phone</label>
              <input 
                id="phone" 
                type="tel"
                class="rounded-xl p-2 mt-2 font-semibold border border-gray-300 @error("phone") border-2 border-red-500 @enderror" 
                name="phone"
                value="{{ old('phone', $user->phone ?? '') }}"  autocomplete="tel">
      
              @error('phone')
              <p class="text-red-500 text-xs italic mt-4">
              {{ $message }}
              </p>
              @enderror
             <p class="text-xl font-medium text-black mt-4">About You</p>
             <hr class="mt-4">
              <!-- bio -->
             <label for="bio" class="mt-5">Bio <small class="text-gray-400 ml-3"> (optional)</small></label>
             <input type="text" name="bio" value="{{ old('bio', $user->bio) }}" class="rounded-xl p-2 mt-2 font-bold border border-gray-300 @error("bio") border-2 border-red-500 @enderror">
            @error('bio')
             <p class="text-red-500 text-xs italic mt-4">
             {{ $message }}
             </p>
            @enderror
             <!-- about me -->
             <label for="aboutme" class="mt-5">About Me <small class="text-gray-400 ml-3"> (optional)</small></label>
             <textarea id="textarea" name="aboutme" rows="10"  placeholder="Description about your self" class="rounded-xl p-2 mt-2 font-bold border border-gray-300 hover:bg-gray-200 @error("aboutme") border-2 border-red-500 @enderror">
              {{ old('aboutme', $user->aboutme) }}
             </textarea>
             @error('aboutme')
             <p class="text-red-500 text-xs italic mt-4">
             {{ $message }}
             </p>
             @enderror
             <!-- social links -->
             <p class="text-xl font-medium text-black mt-4">Social profile</p>
             <p class="text-md font-medium text-gray-400">The social links you add here will show up on your profile.</p>
             <hr class="mt-4">
              <!-- github -->
             <label for="github" class="mt-5">Github <small class="text-gray-400 ml-3"> (optional)</small></label>
             <input type="text" name="github" value="{{old('github',$user->github)}}" placeholder="https://github.com/username" class="rounded-xl p-2 mt-2 font-bold placeholder:font-light placeholder:text-sm border border-gray-300 @error("github") border-2 border-red-500 @enderror">
             @error('github')
             <p class="text-red-500 text-xs italic mt-4">
             {{ $message }}
            </p>
            @enderror
             <!-- linkedin -->
             <label for="linkedin" class="mt-5">Linkedin <small class="text-gray-400 ml-3"> (optional)</small></label>
             <input type="text" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}" placeholder="https://linkedin.com/in/username" class="rounded-xl p-2 mt-2 font-bold placeholder:font-light placeholder:text-sm border border-gray-300 @error("linkedin") border-2 border-red-500 @enderror">
               @error('linkedin')
             <p class="text-red-500 text-xs italic mt-4">
             {{ $message }}
            </p>
            @enderror
             <!-- twitter -->
             <label for="twitter" class="mt-5">Twitter <small class="text-gray-400 ml-3"> (optional)</small></label>
             <input type="text" name="twitter" value="{{ old('twitter', $user->twitter) }}" placeholder="https://twitter.com/username" class="rounded-xl p-2 mt-2 font-bold placeholder:font-light placeholder:text-sm border border-gray-300 @error("twitter") border-2 border-red-500 @enderror">
               @error('twitter')
             <p class="text-red-500 text-xs italic mt-4">
             {{ $message }}
            </p>
            @enderror
             <!-- custom links -->
             <p class="text-xl font-medium text-black mt-4">custom links profile</p>
             <p class="text-md font-medium text-gray-400">Add your custom links like platform: portfolio, link: https://myportfolio.com.</p>
            <hr class="mt-4">
            <label for="socail-links" class="block text-gray-700 text-sm font-bold mt-2 mb-2 sm:mb-4">Custom Links</label>
            <div id="custom-links">
             @foreach(old('social_links', $user->socialLinks ?? []) as $i => $link)
              @php
               $platform = is_array($link) ? $link['platform'] ?? '' : $link->platform ?? '';
               $url = is_array($link) ? $link['url'] ?? '' : $link->url ?? '';
               $linkId = is_object($link) ? $link->id : null;
              @endphp
       
             <div class="flex gap-2 mb-2" data-link-id="{{ $linkId }}">
               <input type="text" name="social_links[{{ $i }}][platform]" placeholder="Platform" value="{{ $platform }}" class="rounded border p-2 @error("social_links.{$i}.platform") border-2 border-red-500 @enderror">
               <input type="url" name="social_links[{{ $i }}][url]" placeholder="URL" value="{{ $url }}" class="rounded border p-2 @error("social_links.{$i}.url") border-2 border-red-500 @enderror">
       
               @can('deleteSocial', is_object($link) ? $link : null)
                   <button type="button" class="delete-link-btn text-red-600 hover:text-red-800"
                           data-id="{{ $linkId }}">
                       <i class="fas fa-trash"></i>
                   </button>
               @endcan
             </div>
             @error("social_links.{$i}.url")
             <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
              @enderror
              @error("social_links.{$i}.platform")
              <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
              @enderror
          @endforeach
                </div>
            </div>
           <div class="sticky inset-0 z-10 flex justify-end border-t border-t-gray-400 bg-white py-4 ">
            <button id="addCustomBtn" type="button" onclick="addCustomLink()" class="hidden rounded-xl text-white font-bold bg-blue-600  p-2 mr-4">Add custom link</button>
            <button type="submit" class="rounded-xl text-white font-bold bg-blue-600  p-2">update</button>
           </div>
          </form>
          <!-- delete avatar form -->
          <form id="delete-avatar-form" action="{{ route('avatar.destroy') }}" method="POST" class="hidden">
          @csrf
          @method('DELETE')
          </form>
          <!-- delete cover form -->
          <form id="delete-cover-form" action="{{ route('cover.destroy') }}" method="POST" class="hidden">
          @csrf
          @method('DELETE')
          </form>
        </div>
      </main>
        @push('scripts')
  <!-- intl-tel-input with auto detect country flag  -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const phoneInputField = document.querySelector("#phone");

      // Initialize intl-tel-input
      const phoneInput = window.intlTelInput(phoneInputField, {
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
      });


      fetch('https://get.geojs.io/v1/ip/geo.json')
      .then(response => response.json())
      .then(data => {
        const country = data.country_code;
        phoneInput.setCountry(country);  
      })
      .catch(error => console.error('Error fetching location:', error));
      const form = document.querySelector('#phone-form');
      form.addEventListener('submit', function (eo) {
      eo.preventDefault();


      const countryCode = phoneInput.getSelectedCountryData().dialCode;
      const phoneNumber = phoneInput.getNumber();


      const countryCodeInput = document.createElement('input');
      countryCodeInput.type = 'hidden';
      countryCodeInput.name = 'country_code';
      countryCodeInput.value = countryCode;
      form.appendChild(countryCodeInput);

      phoneInputField.value = phoneNumber;

      form.submit();
      });
    });   
    </script>

<!-- function add custom links  -->
    <script>
function addCustomLink() {
    const container = document.getElementById('custom-links');
    const index = container.children.length;
    const div = document.createElement('div');
    div.classList.add('flex', 'gap-2', 'mb-2');
    div.innerHTML = `
        <input type="text" name="social_links[${index}][platform]" placeholder="Platform" class="rounded border p-2 ">
        <input type="url" name="social_links[${index}][url]" placeholder="URL" class="rounded border p-2 ">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
          <i class="fas fa-trash"></i>
        </button>
        `;
    container.appendChild(div);
      }
    </script>

    <!-- observe custom link  -->
    <script>
  document.addEventListener('DOMContentLoaded', () => {
    const customLinks = document.getElementById('custom-links');
    const addButton = document.getElementById('addCustomBtn');

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          addButton.classList.remove('hidden');
        } else {
          addButton.classList.add('hidden');
        }
      });
    }, {
      root: null, 
      threshold: 0.1
    });

    if (customLinks) {
      observer.observe(customLinks);
    }
  });
</script>
<!-- delete custom link -->
  <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-link-btn').forEach(button => {
        button.addEventListener('click', function () {
            const linkId = this.dataset.id;
            const row = this.closest('[data-link-id]');

            if (!confirm('Are you sure you want to delete this link?')) return;

            fetch(`/profile/delete/custom-link/${linkId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            })
            .then(res => {
                if (res.ok) {
                    row.remove();
                    toastr.success('Link deleted successfully');
                } else {
                    toastr.error('Failed to delete link');
                }
            })
            .catch(err => {
                console.error(err);
                toastr.error('Error deleting link');
            });
        });
    });
});
    </script>
@endpush