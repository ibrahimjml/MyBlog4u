@extends('admin.partials.layout')
@section('title', 'manage mails | Dashboard')
@section('content')
  <!-- Header -->
  @include('admin.partials.header', ['linktext' => 'Admin Settings', 'route' => 'admin.settings.smtp', 'value' => request('search')])

  <div class="md:ml-64 w-full mx-auto transform -translate-y-48">
    <div class="flex flex-wrap">
      <div class="w-full lg:w-8/12 px-4">
        <div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg bg-blueGray-100 border-0">
          <div class="rounded-t bg-white mb-0 px-6 py-6">
            <div class="text-center flex justify-between">
              <h6 class="text-blueGray-700 text-xl font-bold">
                Manage mails
              </h6>

            </div>
          </div>
          <div class="flex-auto px-4 lg:px-10 py-10 pt-0 ">

            <h6 class="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
              smtp mail config
            </h6>
            <form action="{{ route('admin.settings.smtp.config') }}" method="POST">
              @csrf
              <div class="flex flex-wrap">
                <div class="w-full lg:w-6/12 px-4">
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="mail_transport">
                      Mail transport
                    </label>
                    <input type="text" name="mail_transport"
                      class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" 
                      value="{{ $smtp->mail_transport }}"/>
                      
                      @error('mail_transport')
                      <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                <div class="w-full lg:w-6/12 px-4">
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="mail_host">
                      Mail host
                    </label>
                    <input type="text" name="mail_host"
                      class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" 
                      value="{{ $smtp->mail_host }}"/>
                    @error('mail_host')
                      <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                <div class="w-full lg:w-6/12 px-4">
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="mail_port">
                      Mail port
                    </label>
                    <input type="text" name="mail_port"
                      class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"
                      value="{{ $smtp->mail_port }}"/>
                    @error('mail_port')
                      <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                <div class="w-full lg:w-6/12 px-4">
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="mail_username">
                      Mail username
                    </label>
                    <input type="text" name="mail_username"
                      class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150 {{ $errors->has('phone') ? 'border-red-500' : '' }}"
                      name="mail_username" value="{{ $smtp->mail_username }}"/>
                    @error('mail_username')
                      <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                <div class="w-full lg:w-6/12 px-4">
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="mail_password">
                      Mail password
                    </label>
                    <input type="text" name="mail_password"
                      class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" 
                      value="{{ $smtp->mail_password }}"/>
                    @error('mail_password')
                      <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                <div class="w-full lg:w-6/12 px-4">
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="mail_encryption">
                      Mail encryption
                    </label>
                    <input type="text" name="mail_encryption"
                      class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" 
                      value="{{ $smtp->mail_encryption }}"/>
                    @error('mail_encryption')
                      <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
                <div class="w-full lg:w-6/12 px-4">
                  <div class="relative w-full mb-3">
                    <label class="block uppercase text-blueGray-600 text-xs font-bold mb-2" for="mail_from">
                      Mail from
                    </label>
                    <input type="text" name="mail_from"
                      class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" 
                      value="{{ $smtp->mail_from }}"/>
                    @error('mail_from')
                      <p class="text-red-500 text-xs italic mt-4">
                        {{ $message }}
                      </p>
                    @enderror
                  </div>
                </div>
              </div>
              @can('smtp.update')
              <button
                class="bg-gray-500 ml-5 text-white active:bg-gray-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
                type="submit">
                Save
              </button>
              @endcan
            </form>
            @can('smtp.update')
            <form action="{{ route('admin.settings.smtp.test') }}" method="POST">
              @csrf
              <button
                class="bg-green-500 ml-5 mt-3 text-white  font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 ease-linear transition-all duration-150"
                type="submit">
                Send test mail
              </button>
            </form>
            @endcan
          </div>

        </div>

      </div>
@endsection