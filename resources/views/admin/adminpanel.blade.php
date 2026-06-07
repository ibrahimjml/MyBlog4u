@extends('admin.partials.layout')
@section('content')
  <div class="relative md:ml-64 bg-blueGray-50">
    <nav
    class="absolute top-0 left-0 w-full z-10 bg-transparent md:flex-row md:flex-nowrap md:justify-start flex items-center p-4">
    <div class="w-full mx-autp items-center flex justify-between md:flex-nowrap flex-wrap md:px-10 px-4">
      <a class="text-white text-sm uppercase hidden lg:inline-block font-semibold" href="./index.html">Dashboard</a>
      <form class="md:flex hidden flex-row flex-wrap items-center lg:ml-auto mr-3">
      <div class="relative flex w-full flex-wrap items-stretch">
        <span
        class="z-10 h-full leading-snug font-normal absolute text-center text-blueGray-300  bg-transparent rounded text-base items-center justify-center w-8 pl-3 py-3"><i
          class="fas fa-search"></i></span>
        <input type="text" placeholder="Search here..."
        class="border-0 px-3 py-3 placeholder-blueGray-300 text-blueGray-600 relative bg-white  rounded text-sm shadow outline-none focus:outline-none focus:ring w-full pl-10" />
      </div>
      </form>
      <ul class="flex-col md:flex-row list-none items-center hidden md:flex">
      <a class="text-blueGray-500 block" href="{{route('profile.home',auth()->user()->username)}}" >
        <div class="items-center flex">
        <span
          class="w-12 h-12 text-sm text-white bg-blueGray-200 inline-flex items-center justify-center rounded-full"><img
          alt="..." class="w-full rounded-full align-middle border-none shadow-lg"
          src="{{auth()->user()->avatar_url}}" /></span>
        </div>
      </a>
    
    </nav>
    <!-- Header -->
    <div class="relative bg-slate-400 md:pt-32 pb-32 pt-12">
      <!-- Card stats -->
@include('admin.partials.card-stats')
    {{-- filter by year --}}

    <div class="flex justify-end mt-8 mr-3">
      <form method="GET" action="{{ route('admin.panel') }}" class="mb-4">
      <label for="year" class="font-bold text-white">Filter By Year:</label>
      <select name="year" id="year" onchange="this.form.submit()"
        class="p-2 pl-3 pr-8 border rounded-md appearance-none bg-white text-gray-700">>
        @for ($i = now()->year; $i >= 2020; $i--)
      <option value="{{ $i }}" {{ request('year', now()->year) == $i ? 'selected' : '' }}>
      {{ $i }}
      </option>
      @endfor
      </select>
      </form>
    </div>
    </div>

{{-- chart container --}}
<div class="flex flex-col md:flex-row gap-4 px-4 md:px-10 mx-auto w-full -m-24">
  <div id="container1" class="rounded-lg w-full md:w-1/2"></div>
  <div id="container2" class="rounded-lg w-full md:w-1/2"></div>
</div>

@endsection

@push('scripts')
  <script>
    var datakeys = @json(array_keys($data['registeredusers']));
    var datavalues = @json(array_values($data['registeredusers']));
    var selectedYear = @json(request('year', now()->year));

    Highcharts.chart('container1', {
    chart: {
      type: 'column'
    },
    title: {
      text: 'Number of Registered Users ' + selectedYear
    },

    xAxis: {
      categories: datakeys,
      crosshair: true,
      accessibility: {
      description: 'Months'
      }
    },
    yAxis: {
      min: 0,
      title: {
      text: 'Users'
      }
    },
    tooltip: {
      valueSuffix: ' Users'
    },
    plotOptions: {
      column: {
      pointPadding: 0,
      borderWidth: 0,
      pointWidth: 50
      }
    },
    series: [
      {
      name: 'Users',
      data: datavalues,
      color: '#9e9e9e'
      }

    ]
    });
  </script>

  <script>
    var datakeys = @json(array_keys($data['numberofposts']));
    var datavalues = @json(array_values($data['numberofposts']));
    var selectedYear = @json(request('year', now()->year));

    Highcharts.chart('container2', {
    chart: {
      type: 'column'
    },
    title: {
      text: 'Number of Posts ' + selectedYear
    },

    xAxis: {
      categories: datakeys,
      crosshair: true,
      accessibility: {
      description: 'Months'
      }
    },
    yAxis: {
      min: 0,
      title: {
      text: 'Posts'
      }
    },
    tooltip: {
      valueSuffix: ' Posts'
    },
    plotOptions: {
      column: {
      pointPadding: 0,
      borderWidth: 0,
      pointWidth: 50
      }
    },
    series: [
      {
      name: 'Posts',
      data: datavalues,
      color: '#fcba03'
      }

    ]
    });
  </script>
@endpush