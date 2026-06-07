@extends('admin.partials.layout')
@section('title', 'Profile Reports | Dashboard')
@section('content')

<div class="md:ml-64 ">
@include('admin.partials.header', [
         'linktext'     => 'Manage Profile Reports',
         'route'        => 'admin.reports.profiles.index', 
         'value'         => request('search'),
          'searchColor'     => 'bg-blueGray-200',
          'borderColor'     => 'border-blueGray-200',
         'backgroundColor' => 'bg-gray-400'
   ])
<div class="w-fit transform -translate-y-48 ml-4">
@include('admin.reports.partials.filter')
</div>
 <div class="bg-white shadow rounded-xl overflow-hidden max-w-7xl mx-4 transform -translate-y-40 ">
<x-tables.table id="tableprofilereports" :headers="['#','Profile','Profile Owner','Reporter','Reason','Other','Status','CreatedAt','Actions']" title="Profile Reports Table">
    @forelse ($reports as $report)
        <tr>
      <td>{{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration }}</td>
      <td>
        <div class="flex items-start gap-2">
        <img class="object-contain inline-block" src="{{$report->profile->avatar_url}}" width="40px" height="40px"
      alt="{{$report->profile->username}}">
        </div>
      </td>
      <td>
        <span class="px-2">
          {{$report->profile->name}}
        </span>
      </td>
      <td>
        <span class="px-2 py-2">
        {!! $report->user->name .'<b class="text-blue-400">'.' @'.$report->user->username.'</b>' !!}
       </span>
      </td>
      <td>{{$report->reason->value}}</td>
      <td>
        @if(isset($report->other))
        {{$report->other}}
        @else
        <b>--</b>
        @endif
      </td>
      <td>
          <i @class([
            "fas fa-circle  mr-2 text-xs",
            'text-orange-400' => $report->status === \App\Enums\ReportStatus::Pending,
            'text-green-600' => $report->status === \App\Enums\ReportStatus::Reviewed,
            'text-red-600' => $report->status === \App\Enums\ReportStatus::Rejected,
          ])></i>  {{$report->status->label()}}

      </td>
      <td>{{$report->created_at->format('y/m/j')}}</td>
      <td>
        <div class="flex items-center justify-start gap-2">
      @can('profilereport.delete')
          <form action='{{route('admin.reports.profiles.delete',$report->id)}}' method="POST"
         onsubmit="return confirm('Are you sure you want to delete this report?');">
         @csrf
         @method('delete')
         <button type="submit"
         class="rounded-lg text-red-600 p-2  hover:text-red-300 transition-colors duration-100">
         <i class="fas fa-trash"></i></button>
       </form>
        @endcan
       <a href="{{route('profile.home',$report->profile->username)}}"><i class="fas fa-eye "></i></a>
        {{-- action report status menu --}}
        @can('profilereport.status')
        <div class="relative inline-block">
        <button  class="toggle-menu w-6 h-6 rounded-[50%] bg-slate-50 hover:bg-opacity-65 transition-bg-opacity duration-100">
         <i class="fas fa-ellipsis-v"></i>
        </button>
              @include('admin.reports.partials.action-report-status-menu',['type'=>'profile','report'=>$report])
        </div>
        @endcan
      </div>
      </td>
     </tr>
    @empty
        <h4 class="text-center font-bold">No Reports yet</h4>
    @endforelse
     
</x-tables.table>
<div class="relative md:ml-64 md:w-[80%] md:left-4">
  {!! $reports->links() !!}
</div>
</div>
</div>
@endsection