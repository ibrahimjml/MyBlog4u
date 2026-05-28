@extends('admin.partials.layout')
@section('title', 'Post Moderation | Dashboard')
@section('content')
  <!-- Header -->
  @include('admin.partials.header', ['linktext' => 'Post Moderation', 'route' => 'admin.posts.moderation.index', 'value' => request('search')])

  <div class="md:ml-64 w-full mx-auto transform -translate-y-48">
    <div class="flex flex-wrap">
      <div class="w-full lg:w-8/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
          <div class="rounded-t bg-white mb-0 px-6 py-6">
            <div class="text-center flex justify-between">
              <h6 class="text-blueGray-700 text-xl font-bold">
                Post Moderation
              </h6>

            </div>
          </div>
          {{-- user registration rules --}}
          <form id="actionForm" action="{{ route('admin.posts.moderation.update.rules') }}" method="POST"
            class="flex-auto px-4  lg:px-10 py-10 pt-0 ">
            @csrf
            @method('PUT')
            <h6 class="text-blueGray-400 text-sm mt-3 mb-2 font-bold uppercase">
              Post Approval Rules
            </h6>
            <p class="text-sm text-blueGray-400">Manage submission rules and review pending articles.</p>
            <div class="rounded-lg p-5 w-full mt-3 flex justify-between items-center border-2 border-gray-300">
              <div class="">
                <p class="text-lg font-bold">Allow User Post Submission</p>
                <p class="text-xs text-blueGray-400 mt-1">If disabled, standard users will not be able to write or submit new posts. Only Administrators will be able to publish content.</p>
              </div>
              <div class="">
                <input type="hidden" name="enable_post_submission" value="0">
                <x-toggle name="enable_post_submission" value="1" :checked="$rules->enable_post_submission"/>
              </div>
            </div>
            <div class="rounded-lg p-5 w-full mt-3 flex justify-between items-center border-2 border-gray-300">
              <div class="">
                <p class="text-lg font-bold">Auto Approve User Posts</p>
                <p class="text-xs text-blueGray-400 mt-1">If enabled, posts submitted by standard users will be automatically approved. If disabled, all posts will require admin approval.</p>
              </div>
              <div class="">
                <input type="hidden" name="enable_auto_approve" value="0">
                <x-toggle name="enable_auto_approve" value="1" :checked="$rules->enable_auto_approve" />
              </div>
            </div>
            
            <hr class="my-4">
    
            @can('postmoderation.update')
            <button
              class="block bg-green-500 ml-auto mt-2 w-fit text-white active:bg-gray-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
              type="submit">
              <i class="fas fa-save mr-2"></i>
              Save rules
            </button>  
            @endcan
          </form>
        </div>
      </div>
@endsection
