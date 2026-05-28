@extends('admin.partials.layout')
@section('title', 'Posts Table | Dashboard')
@section('content')

<div class="md:ml-64 ">
@include('admin.partials.header', [
       'linktext'        => 'Manage Posts', 
       'route'           => 'admin.posts.page', 
       'value'           => request('search'),
       'searchColor'     => 'bg-blueGray-200',
       'borderColor'     => 'border-blueGray-200',
       'backgroundColor' => 'bg-gray-400'
  ])
  {{-- filters  --}}
<div class="transform -translate-y-52">
  @include('admin.posts.partials.filters')
</div>
 <div class="bg-white shadow rounded-xl overflow-hidden w-7xl mx-4 transform -translate-y-40 ">
{{-- post table --}}
<x-tables.table id='' :headers="['#','Username','Image','Title','Body','Status','Hashtags','Categories','Reported','Reports Count','Featured','Likes','Comments','publishedAt','bannedAt','trashedAt','CreatedAt','Actions']" title="Posts Table" >
      @forelse ($posts as $post)
      <tr class="py-4">
        <td class="p-2"> {{ ($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration }}</td>
        <td class="p-2">{{$post->user->name}}</td>
        <td class="p-2"><img class="object-contain inline-block" src="{{$post->image_url}}" width="40px" height="40px"
        alt="{{$post->title}}">
        </td>
        <td class="p-2">{{Str::limit($post->slug, 20)}}</td>
        <td class="p-2"> {!! Str::limit(strip_tags($post->description), 40) !!}</td>
        <td class="p-2">
          <small>
            <i @class([
                "fas fa-circle  mr-2 text-xs",
                'text-green-600' => $post->status === \App\Enums\PostStatus::PUBLISHED,
                'text-orange-600' => $post->status === \App\Enums\PostStatus::DRAFT,
                'text-red-600' => $post->status === \App\Enums\PostStatus::BANNED,
                'text-yellow-500' => $post->status === \App\Enums\PostStatus::PENDING,
          ])></i>
          {{$post->status->label()}}
          </small>
        </td>
        <td class="p-2">
           @if($post->allHashtags->isNotEmpty())
           @foreach($post->allHashtags as $hashtag)
           <span 
             @class([
               "bg-gray-100 text-sm mr-2 px-2 py-1 rounded font-semibold",
               'text-green-600'  => $hashtag->status === \App\Enums\TagStatus::ACTIVE,
               'text-red-600'    => $hashtag->status === \App\Enums\TagStatus::DISABLED, 
             ])>
             {{ $hashtag->name }}
           </span>
         @endforeach
           @else
            <b>--</b>
           @endif
        </td>
        <td class="p-2">
           @if($post->categories->isNotEmpty())
           @foreach ($post->categories as $category)
           <span class="bg-blueGray-200 text-sm text-blueGray-500 font-semibold px-2 py-1 mr-1 rounded">
          {{$category->name}}
           </span>
           @endforeach
           @endif
        </td>
        <td class="p-2">
          @if($post->is_reported)
          <i class="fas fa-check text-green-500"></i>
          @else
          <i class="fas fa-times text-red-500"></i>
          @endif
        </td>
        <td class="p-2">
          @if($post->report_count > 0)
           {{$post->report_count}}
          @else
          {{$post->report_count}}
          @endif
        </td>
        <td class="p-2">
        <div class="flex justify-center">
        @if($post->is_featured)
        <i class="fas fa-check text-green-500"></i>
        @else
        <i class="fas fa-times text-red-600"></i>
        @endif
        </div>
        </td>
        <td class="p-2">{{$post->likes_count}}</td>
        <td class="p-2">{{$post->totalcomments_count}}</td>
        @foreach (['published_at', 'banned_at', 'trashed_at'] as $column)
        <td class="p-2">
         {{ $post->$column ? $post->$column->diffForHumans() : '--' }}
        </td>
        @endforeach
        <td class="p-2">{{$post->created_at->diffForHumans()}}</td>
        <td class=" text-white p-2 ">
        {{-- actions --}}
        <div class="flex items-center justify-center">
        @include('admin.posts.partials.actions', ['post' => $post])
        </div>
        </td>
      </tr>
      @empty
      <h4 class="text-center font-bold">Sorry, column not found</h4>
      @endforelse
    </x-tables.table>
    <div class="w-full overflow-auto">
       {!! $posts->links() !!}
     </div>
  </div>
</div>
  {{-- edit post model --}}
  @include('admin.posts.partials.edit-model')
  <!-- -->
@endsection
@push('scripts')
<script>
// open edit model + edit post
const open = document.getElementById('openModel');
const route = "{{ route('admin.posts.status', ':id') }}";
const buttons = document.querySelectorAll('.edit-btn');
const postTitle = document.getElementById('title');
const form = document.getElementById('editpost');
const close = document.getElementById('closeModel');

if (open && buttons && form && close) {
  const statusInput = form.querySelector('[name="status"]');
  
  const token = form.querySelector('input[name="_token"]').value;

  buttons.forEach(button => {
    button.addEventListener('click', () => {
      const postId = button.dataset.id;
      const postStatus = button.dataset.status;
      const postSlug = button.dataset.slug;
     
      form.action = route.replace(':id', postId);
      statusInput.value = postStatus;
      postTitle.textContent = postSlug;

      open.classList.remove('hidden');
    });
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    let options = {
      method: 'PUT',
      headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        status: statusInput.value,
      }),
    };

    fetch(form.action, options)
      .then(response => response.json())
      .then(data => {
        if (data.updated) {
          toastr.success(data.message);
          location.reload();
        } else {
          alert('An error occurred while updating the post status.');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the post status.');
      });
  });

  close.addEventListener('click', () => {
    open.classList.add('hidden');
  });
}
</script>
@endpush